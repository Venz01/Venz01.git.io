<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DiagnoseCloudinary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudinary:diagnose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose Cloudinary configuration and connectivity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Cloudinary Diagnostics');
        $this->newLine();

        // 1. Check configuration
        $this->line('📋 Configuration Check:');
        $this->line('─────────────────────────────────');
        
        $cloudName = config('cloudinary.cloud_name') ?? config('services.cloudinary.cloud_name') ?? env('CLOUDINARY_CLOUD_NAME');
        $apiKey = config('cloudinary.api_key') ?? config('services.cloudinary.api_key') ?? env('CLOUDINARY_API_KEY');
        $apiSecret = config('cloudinary.api_secret') ?? config('services.cloudinary.api_secret') ?? env('CLOUDINARY_API_SECRET');

        $this->checkValue('Cloud Name', $cloudName);
        $this->checkValue('API Key', $apiKey);
        $this->checkValue('API Secret', $apiSecret, true);
        $this->newLine();

        // 2. Check CA bundle
        $this->line('🔒 SSL Certificate Bundle:');
        $this->line('─────────────────────────────────');
        
        $iniPath = ini_get('curl.cainfo');
        if ($iniPath && file_exists($iniPath)) {
            $this->info("✓ php.ini curl.cainfo: {$iniPath}");
        } else {
            $this->warn("✗ php.ini curl.cainfo not set or file missing");
        }

        $bundled = storage_path('cacert.pem');
        if (file_exists($bundled)) {
            $this->info("✓ Project CA bundle: {$bundled}");
        } else {
            $this->warn("✗ Project CA bundle not found: {$bundled}");
            $this->line("  Run: curl -o storage/cacert.pem https://curl.se/ca/cacert.pem");
        }

        $commonPaths = [
            '/etc/ssl/certs/ca-certificates.crt',
            '/etc/pki/tls/certs/ca-bundle.crt',
            '/etc/ssl/ca-bundle.pem',
            '/etc/ssl/cert.pem',
        ];

        $systemBundleFound = false;
        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                $this->info("✓ System CA bundle: {$path}");
                $systemBundleFound = true;
                break;
            }
        }

        if (!$systemBundleFound && !file_exists($bundled) && (!$iniPath || !file_exists($iniPath))) {
            $this->error("⚠ No CA bundle found! SSL verification will likely fail.");
        }
        $this->newLine();

        // 3. Test connectivity
        if ($cloudName && $apiKey && $apiSecret) {
            $this->line('🌐 Connectivity Test:');
            $this->line('─────────────────────────────────');
            
            $testResult = $this->testCloudinaryConnection($cloudName, $apiKey, $apiSecret);
            
            if ($testResult['success']) {
                $this->info("✓ Successfully connected to Cloudinary!");
                $this->line("  Response: {$testResult['message']}");
            } else {
                $this->error("✗ Failed to connect to Cloudinary");
                $this->line("  Error: {$testResult['error']}");
                
                if (isset($testResult['curl_error'])) {
                    $this->line("  cURL Error: {$testResult['curl_error']}");
                }
                
                if (isset($testResult['http_code'])) {
                    $this->line("  HTTP Code: {$testResult['http_code']}");
                }
            }
            $this->newLine();
        }

        // 4. Check storage disk
        $this->line('💾 Local Storage Check:');
        $this->line('─────────────────────────────────');
        
        try {
            $publicPath = storage_path('app/public');
            if (is_dir($publicPath)) {
                $this->info("✓ Public storage directory exists: {$publicPath}");
                
                if (is_writable($publicPath)) {
                    $this->info("✓ Public storage is writable");
                } else {
                    $this->error("✗ Public storage is not writable");
                }
            } else {
                $this->error("✗ Public storage directory not found: {$publicPath}");
                $this->line("  Run: php artisan storage:link");
            }

            $linkPath = public_path('storage');
            if (is_link($linkPath) || is_dir($linkPath)) {
                $this->info("✓ Storage link exists: {$linkPath}");
            } else {
                $this->warn("✗ Storage link not found: {$linkPath}");
                $this->line("  Run: php artisan storage:link");
            }
        } catch (\Exception $e) {
            $this->error("Error checking storage: " . $e->getMessage());
        }
        $this->newLine();

        // 5. Summary
        $this->line('📊 Summary:');
        $this->line('─────────────────────────────────');
        
        if ($cloudName && $apiKey && $apiSecret) {
            $this->info('✓ Cloudinary is configured');
            
            if (isset($testResult) && $testResult['success']) {
                $this->info('✓ Cloudinary connection successful');
                $this->info('→ Images will upload to Cloudinary');
            } else {
                $this->warn('⚠ Cloudinary configured but connection failed');
                $this->warn('→ Images will fall back to local storage');
            }
        } else {
            $this->warn('⚠ Cloudinary not configured');
            $this->warn('→ Images will use local storage');
        }

        return 0;
    }

    private function checkValue($label, $value, $secret = false)
    {
        if (!empty($value)) {
            $display = $secret ? str_repeat('*', min(strlen($value), 20)) : $value;
            $this->info("✓ {$label}: {$display}");
        } else {
            $this->error("✗ {$label}: Not set");
        }
    }

    private function testCloudinaryConnection($cloudName, $apiKey, $apiSecret)
    {
        // Test using a simple API call to check credentials
        $timestamp = time();
        $signature = sha1("timestamp={$timestamp}{$apiSecret}");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.cloudinary.com/v1_1/{$cloudName}/resources/image",
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic " . base64_encode("{$apiKey}:{$apiSecret}")
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        // Try to use CA bundle if available
        $iniPath = ini_get('curl.cainfo');
        if ($iniPath && file_exists($iniPath)) {
            curl_setopt($ch, CURLOPT_CAINFO, $iniPath);
        } else {
            $bundled = storage_path('cacert.pem');
            if (file_exists($bundled)) {
                curl_setopt($ch, CURLOPT_CAINFO, $bundled);
            }
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return [
                'success' => false,
                'error' => 'Connection failed',
                'curl_error' => $curlError,
            ];
        }

        if ($httpCode === 200) {
            return [
                'success' => true,
                'message' => 'API credentials valid',
                'http_code' => $httpCode,
            ];
        } else {
            $decoded = json_decode($response, true);
            return [
                'success' => false,
                'error' => $decoded['error']['message'] ?? 'Unknown error',
                'http_code' => $httpCode,
                'response' => $response,
            ];
        }
    }
}