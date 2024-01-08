<?php 

if (!function_exists('is_vite')) {
    function useVite()
    {
        return file_exists(public_path('build/manifest.json'));

    }
}