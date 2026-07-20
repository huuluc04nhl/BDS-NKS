<?php

// Reconfigure Laravel storage path to Vercel's writable /tmp directory at runtime
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    @mkdir($storagePath, 0755, true);
    @mkdir($storagePath . '/framework', 0755, true);
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
}

// Override environment variables to force Laravel to use /tmp for compilation
putenv("VIEW_COMPILED_PATH=/tmp/storage/framework/views");

// Forward Vercel Serverless requests to Laravel public bootstrap index
require __DIR__ . '/../public/index.php';
