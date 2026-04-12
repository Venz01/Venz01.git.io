<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Drop this trait into any controller that uploads images.
 * Tries Cloudinary first; falls back to public/storage on local dev.
 *
 * Usage:
 *   use App\Http\Controllers\Concerns\HandlesImageUploads;
 *   class MyController extends Controller {
 *       use HandlesImageUploads;
 *       $url = $this->handleImageUpload($request->file('image'), 'packages');
 *       $this->deleteImage($oldUrl);
 *   }
 *
 * SSL on Windows / local dev
 * ──────────────────────────
 * XAMPP, Laragon, and Herd do not ship a CA certificate bundle, so cURL
 * fails with "SSL certificate problem: unable to get local issuer certificate".
 *
 * One-time fix — run once in your terminal, then it works forever:
 *   curl -o storage/cacert.pem https://curl.se/ca/cacert.pem
 *   (or download https://curl.se/ca/cacert.pem and save as storage/cacert.pem)
 *
 * This trait checks for that file automatically. On production Linux the
 * system bundle is used instead; no file is needed there.
 *
 * Why we never download the bundle at runtime:
 * Downloading cacert.pem via file_get_contents() or cURL requires the very
 * SSL stack that is broken — a circular dependency. The file must exist on
 * disk before the application runs.
 */
trait HandlesImageUploads
{
    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * Upload an image. Returns a publicly accessible URL for DB storage.
     * Uses Cloudinary when configured; falls back to local public disk.
     */
    protected function handleImageUpload($file, string $folder): string
    {
        if ($this->cloudinaryConfigured()) {
            try {
                $url = $this->uploadToCloudinary($file, $folder);
                Log::info('Cloudinary upload successful', [
                    'folder' => $folder,
                    'url' => $url,
                ]);
                return $url;
            } catch (\Exception $e) {
                Log::error('Cloudinary upload failed, falling back to local storage.', [
                    'folder' => $folder,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        } else {
            Log::info('Cloudinary not configured, using local storage', [
                'folder' => $folder,
            ]);
        }

        return $this->uploadToLocal($file, $folder);
    }

    /**
     * Delete an image. Handles Cloudinary URLs and local storage paths/URLs.
     */
    protected function deleteImage(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_contains($path, 'cloudinary.com')) {
            try {
                $this->deleteFromCloudinary($path);
                Log::info('Cloudinary delete successful', ['path' => $path]);
            } catch (\Exception $e) {
                Log::warning('Cloudinary delete failed.', [
                    'path' => $path,
                    'error' => $e->getMessage(),
                ]);
            }
            return;
        }

        // Normalise full URL (http://localhost/storage/x.jpg) → relative path (x.jpg)
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $urlPath = parse_url($path, PHP_URL_PATH) ?: '';
            if (str_starts_with($urlPath, '/storage/')) {
                $path = ltrim(substr($urlPath, strlen('/storage/')), '/');
            }
        } elseif (str_starts_with($path, '/storage/')) {
            $path = ltrim(substr($path, strlen('/storage/')), '/');
        }

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            Log::info('Local storage delete successful', ['path' => $path]);
        }
    }

    // ── Local storage ─────────────────────────────────────────────────────────

    private function cloudinaryConfigured(): bool
    {
        // Check both config locations: config/cloudinary.php and config/services.php
        $cloudName = config('cloudinary.cloud_name') 
                  ?? config('services.cloudinary.cloud_name')
                  ?? env('CLOUDINARY_CLOUD_NAME');
                  
        $apiKey = config('cloudinary.api_key')
               ?? config('services.cloudinary.api_key')
               ?? env('CLOUDINARY_API_KEY');
               
        $apiSecret = config('cloudinary.api_secret')
                  ?? config('services.cloudinary.api_secret')
                  ?? env('CLOUDINARY_API_SECRET');

        $configured = !empty($cloudName) && !empty($apiKey) && !empty($apiSecret);

        if (!$configured) {
            Log::debug('Cloudinary configuration check failed', [
                'cloud_name_set' => !empty($cloudName),
                'api_key_set' => !empty($apiKey),
                'api_secret_set' => !empty($apiSecret),
            ]);
        }

        return $configured;
    }

    private function uploadToLocal($file, string $folder): string
    {
        $storedPath = $file->store($folder, 'public');
        $url = '/storage/' . ltrim($storedPath, '/');
        
        Log::info('Local upload successful', [
            'folder' => $folder,
            'path' => $storedPath,
            'url' => $url,
        ]);
        
        return $url;
    }

    // ── Cloudinary upload ─────────────────────────────────────────────────────

    /**
     * Upload to Cloudinary via direct cURL.
     *
     * Cloudinary signature rules:
     *  - Only sign params sent in the request (not file/api_key/resource_type).
     *  - Sort them alphabetically: folder, public_id, timestamp.
     *  - Build "key=value&key=value" with NO trailing & before the secret.
     *  - Append the API secret directly, then SHA-1 the whole string.
     *
     * public_id must be a bare ID with no folder prefix.
     * If public_id = "folder/id" AND folder is also sent, Cloudinary stores the
     * file at "folder/folder/id" and the computed signature mismatches → 403.
     */
    private function uploadToCloudinary($file, string $folder): string
    {
        $cloudName = config('cloudinary.cloud_name')
                  ?? config('services.cloudinary.cloud_name')
                  ?? env('CLOUDINARY_CLOUD_NAME');
                  
        $apiKey = config('cloudinary.api_key')
               ?? config('services.cloudinary.api_key')
               ?? env('CLOUDINARY_API_KEY');
               
        $apiSecret = config('cloudinary.api_secret')
                  ?? config('services.cloudinary.api_secret')
                  ?? env('CLOUDINARY_API_SECRET');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            throw new \RuntimeException('Cloudinary credentials not configured');
        }

        $timestamp = time();
        $publicId  = uniqid(); // bare ID — folder param handles placement

        // Sorted: folder, public_id, timestamp. Secret appended directly (no &).
        $signature = sha1("folder={$folder}&public_id={$publicId}&timestamp={$timestamp}{$apiSecret}");

        Log::debug('Cloudinary upload attempt', [
            'cloud_name' => $cloudName,
            'folder' => $folder,
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL        => "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload",
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => [
                'file'      => new \CURLFile(
                    $file->getRealPath(),
                    $file->getMimeType(),           // explicit MIME — avoids blank type on Windows
                    $file->getClientOriginalName()
                ),
                'api_key'   => $apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'folder'    => $folder,
                'public_id' => $publicId,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO         => $this->resolvedCaBundlePath(),
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        $curlInfo  = curl_getinfo($ch);
        curl_close($ch);

        if ($curlError) {
            Log::error('Cloudinary cURL error', [
                'error' => $curlError,
                'curl_info' => $curlInfo,
                'ca_bundle' => $this->resolvedCaBundlePath(),
            ]);
            throw new \RuntimeException('Cloudinary connection error: ' . $curlError);
        }

        if ($httpCode !== 200) {
            $decoded = json_decode($response, true);
            $message = $decoded['error']['message'] ?? $response;
            
            Log::error('Cloudinary HTTP error', [
                'http_code' => $httpCode,
                'response' => $response,
                'decoded' => $decoded,
            ]);
            
            throw new \RuntimeException("Cloudinary upload failed (HTTP {$httpCode}): " . $message);
        }

        $result = json_decode($response, true);

        if (empty($result['secure_url'])) {
            Log::error('Cloudinary response missing secure_url', [
                'response' => $response,
                'result' => $result,
            ]);
            throw new \RuntimeException('Cloudinary upload failed: no URL returned.');
        }

        return $result['secure_url'];
    }

    // ── Cloudinary delete ─────────────────────────────────────────────────────

    /**
     * Delete a Cloudinary asset by its secure_url.
     * Signature for destroy: "public_id={id}&timestamp={ts}{secret}".
     * Post fields must use snake_case keys ('public_id', not 'publicId').
     */
    private function deleteFromCloudinary(string $url): void
    {
        $cloudName = config('cloudinary.cloud_name')
                  ?? config('services.cloudinary.cloud_name')
                  ?? env('CLOUDINARY_CLOUD_NAME');
                  
        $apiKey = config('cloudinary.api_key')
               ?? config('services.cloudinary.api_key')
               ?? env('CLOUDINARY_API_KEY');
               
        $apiSecret = config('cloudinary.api_secret')
                  ?? config('services.cloudinary.api_secret')
                  ?? env('CLOUDINARY_API_SECRET');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            Log::warning('Cloudinary delete skipped - credentials not configured');
            return;
        }

        $publicId = $this->extractCloudinaryPublicId($url);
        if (!$publicId) {
            Log::warning('Failed to extract public_id from Cloudinary URL', ['url' => $url]);
            return;
        }

        $timestamp = time();
        $signature = sha1("public_id={$publicId}&timestamp={$timestamp}{$apiSecret}");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL        => "https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy",
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => [
                'public_id' => $publicId,
                'api_key'   => $apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO         => $this->resolvedCaBundlePath(),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            Log::warning('Cloudinary delete failed', [
                'http_code' => $httpCode,
                'response' => $response,
                'public_id' => $publicId,
            ]);
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Extract the Cloudinary public_id from a secure_url.
     *
     * Handles versioned and non-versioned formats:
     *   .../upload/v1234567890/folder/id.jpg  →  folder/id
     *   .../upload/folder/id.jpg              →  folder/id
     */
    private function extractCloudinaryPublicId(string $url): ?string
    {
        if (!str_contains($url, 'cloudinary.com')) {
            return null;
        }

        $parts = explode('/upload/', $url);
        if (count($parts) < 2) {
            return null;
        }

        $path = $parts[1];
        $path = preg_replace('#^v\d+/#', '', $path);     // strip optional version
        $path = preg_replace('/\.[^.\/]+$/', '', $path);  // strip file extension

        return $path ?: null;
    }

    /**
     * Resolve the CA bundle path for cURL SSL verification.
     *
     * Priority:
     *   1. php.ini curl.cainfo  — system-wide config, trust it.
     *   2. storage/cacert.pem   — project-bundled file (see class docblock).
     *   3. /etc/ssl/certs/ca-certificates.crt — Debian/Ubuntu default
     *   4. /etc/pki/tls/certs/ca-bundle.crt — RedHat/CentOS default
     *   5. null — cURL uses its compiled-in default (fallback)
     */
    private function resolvedCaBundlePath(): ?string
    {
        // 1. Check php.ini setting
        $iniPath = ini_get('curl.cainfo');
        if ($iniPath && file_exists($iniPath)) {
            Log::debug('Using CA bundle from php.ini', ['path' => $iniPath]);
            return $iniPath;
        }

        // 2. Check project storage directory
        $bundled = storage_path('cacert.pem');
        if (file_exists($bundled)) {
            Log::debug('Using CA bundle from storage/', ['path' => $bundled]);
            return $bundled;
        }

        // 3. Check common Linux locations
        $commonPaths = [
            '/etc/ssl/certs/ca-certificates.crt',  // Debian/Ubuntu/Alpine
            '/etc/pki/tls/certs/ca-bundle.crt',     // RedHat/CentOS
            '/etc/ssl/ca-bundle.pem',               // OpenSUSE
            '/etc/ssl/cert.pem',                    // OpenBSD
        ];

        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                Log::debug('Using system CA bundle', ['path' => $path]);
                return $path;
            }
        }

        // 4. Fallback to cURL's default
        Log::debug('No CA bundle found, using cURL default');
        return null;
    }
}