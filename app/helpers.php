<?php

use Illuminate\Support\Facades\File;

if (!function_exists('svg')) {
    function svg(string $name, string $class = '', bool $solid = false): string
    {
        $file = $solid ? "{$name}_solid.svg" : "{$name}.svg";
        $path = resource_path("icon/{$file}");

        if (!File::exists($path) && $solid) {
            $path = resource_path("icon/{$name}.svg");
        }

        if (!File::exists($path)) return '';

        $svg = File::get($path);

        if ($class) {
            if (preg_match('/<svg\b[^>]*\bclass="/i', $svg)) {
                $svg = preg_replace('/(<svg\b[^>]*\bclass=")([^"]*)(")/i', '$1$2 ' . $class . '$3', $svg, 1);
            } else {
                $svg = preg_replace('/<svg\b/i', '<svg class="' . $class . '"', $svg, 1);
            }
        }

        $svg = preg_replace('/\sstroke="(?!none)[^"]*"/i', ' stroke="currentColor"', $svg);
        $svg = preg_replace('/\sfill="(?!none)[^"]*"/i', ' fill="currentColor"', $svg);
        return $svg;
    }
}
