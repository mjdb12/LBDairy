<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ServiceWorkerCommand extends Command
{
    protected $signature = 'sw:generate {--force : Force regeneration of service worker}';
    protected $description = 'Generate or update service worker for PWA functionality';

    public function handle()
    {
        $this->info('Generating Service Worker...');

        // Generate service worker content
        $swContent = $this->generateServiceWorkerContent();
        
        // Write to public directory
        $swPath = public_path('sw.js');
        
        if (File::exists($swPath) && !$this->option('force')) {
            if (!$this->confirm('Service worker already exists. Overwrite?')) {
                $this->info('Operation cancelled.');
                return;
            }
        }

        File::put($swPath, $swContent);
        
        $this->info('Service worker generated successfully at: ' . $swPath);
        
        // Generate manifest if it doesn't exist
        $manifestPath = public_path('manifest.json');
        if (!File::exists($manifestPath)) {
            $this->generateManifest();
            $this->info('Web app manifest generated successfully.');
        }
    }

    private function generateServiceWorkerContent()
    {
        $cacheName = 'lb-dairy-v' . time();
        
        return <<<SW
const CACHE_NAME = '{$cacheName}';
const urlsToCache = [
  '/',
  '/build/assets/app.css',
  '/build/assets/app.js',
  '/offline.html',
  '/manifest.json'
];

// Install event - cache resources
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Fetch event - serve from cache when offline
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Return cached version or fetch from network
        return response || fetch(event.request);
      })
      .catch(() => {
        // Return offline page if both cache and network fail
        if (event.request.mode === 'navigate') {
          return caches.match('/offline.html');
        }
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});
SW;
    }

    private function generateManifest()
    {
        $manifest = [
            'name' => 'LB Dairy Management System',
            'short_name' => 'LB Dairy',
            'description' => 'Livestock and dairy management system',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#ffffff',
            'orientation' => 'portrait-primary',
            'icons' => [
                [
                    'src' => '/img/icon-192x192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'maskable any'
                ],
                [
                    'src' => '/img/icon-512x512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'maskable any'
                ]
            ]
        ];

        File::put(public_path('manifest.json'), json_encode($manifest, JSON_PRETTY_PRINT));
    }
}
