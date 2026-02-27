<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Drop this trait into any controller that uploads images.
 * It tries Cloudinary first; falls back to public/storage on localhost.
 *
 * Usage inside a controller:
 *
 *   use App\Http\Controllers\Concerns\HandlesImageUploads;
 *   class MyController extends Controller {
 *       use HandlesImageUploads;
 *       ...
 *       $url = $this->handleImageUpload($request->file('image'), 'packages');
 *       $this->deleteImage($oldUrl);
 *   }
 */
trait HandlesImageUploads
{
    /**
     * Upload an image file.
     * Returns a publicly accessible URL string suitable for storing in the DB.
     */
    protected function handleImageUpload($file, string $folder): string
    {
        if ($this->cloudinaryConfigured()) {
            try {
                return $this->uploadToCloudinary($file, $folder);
            } catch (\Exception $e) {
                \Log::warning('Cloudinary upload failed, falling back to local storage.', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->uploadToLocal($file, $folder);
    }

    /**
     * Delete an image — handles both Cloudinary URLs and local storage paths.
     */
    protected function deleteImage(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_contains($path, 'cloudinary.com')) {
            try {
                $this->deleteFromCloudinary($path);
            } catch (\Exception $e) {
                \Log::warning('Cloudinary delete failed.', ['error' => $e->getMessage()]);
            }
            return;
        }

        // Local: path may be a full URL (http://localhost/storage/packages/x.jpg)
        // or a relative path (packages/x.jpg). Handle both.
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            // Convert URL → relative storage path
            $relativePath = str_replace(
                Storage::disk('public')->url(''),
                '',
                $path
            );
            $path = ltrim($relativePath, '/');
        }

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function cloudinaryConfigured(): bool
    {
        return !empty(env('CLOUDINARY_CLOUD_NAME'))
            && !empty(env('CLOUDINARY_API_KEY'))
            && !empty(env('CLOUDINARY_API_SECRET'));
    }

    private function uploadToLocal($file, string $folder): string
    {
        $storedPath = $file->store($folder, 'public');
        return Storage::disk('public')->url($storedPath);
    }

    private function uploadToCloudinary($file, string $folder): string
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey    = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        $timestamp = time();
        $publicId  = $folder . '/' . uniqid();
        $signature = sha1("folder={$folder}&public_id={$publicId}&timestamp={$timestamp}{$apiSecret}");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL        => "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload",
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => [
                'file'      => new \CURLFile($file->getRealPath()),
                'api_key'   => $apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'folder'    => $folder,
                'public_id' => $publicId,
            ],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \RuntimeException('Cloudinary upload failed: ' . $response);
        }

        return json_decode($response, true)['secure_url'];
    }

    private function deleteFromCloudinary(string $url): void
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey    = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            return;
        }

        $publicId = $this->extractCloudinaryPublicId($url);
        if (!$publicId) {
            return;
        }

        $timestamp = time();
        $signature = sha1("public_id={$publicId}&timestamp={$timestamp}{$apiSecret}");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL        => "https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy",
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => compact('publicId', 'apiKey', 'timestamp', 'signature') + [
                'public_id' => $publicId,
                'api_key'   => $apiKey,
            ],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        curl_exec($ch);
        curl_close($ch);
    }

    private function extractCloudinaryPublicId(string $url): ?string
    {
        if (!str_contains($url, 'cloudinary.com')) {
            return null;
        }

        $parts = explode('/upload/', $url);
        if (count($parts) < 2) {
            return null;
        }

        $pathParts = explode('/', $parts[1]);
        array_shift($pathParts); // strip version (v1234567)

        return preg_replace('/\.[^.]+$/', '', implode('/', $pathParts));
    }
}