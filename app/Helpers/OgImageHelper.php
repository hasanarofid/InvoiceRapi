<?php

namespace App\Helpers;

class OgImageHelper
{
    /**
     * Given an image path or URL, generate an optimized 1200x630 OpenGraph JPEG (< 150KB)
     * saved directly in public/images/og-cache/ so Apache/Nginx serves it as a static file
     * with exact Content-Length and zero PHP session cookies (essential for WhatsApp crawler).
     *
     * @param string|null $rawImage
     * @return string Absolute URL to static image
     */
    public static function getOptimizedOgUrl(?string $rawImage): string
    {
        if (empty($rawImage)) {
            $rawImage = 'images/posts/Synbarra_Post_01.png';
        }

        try {
            $cacheDir = public_path('images/og-cache');
            if (!file_exists($cacheDir)) {
                @mkdir($cacheDir, 0755, true);
            }

            $isRemote = str_starts_with($rawImage, 'http://') || str_starts_with($rawImage, 'https://');

            if ($isRemote) {
                $hash = 'remote_' . md5($rawImage);
                $cacheFile = $cacheDir . '/' . $hash . '.jpg';

                if (!file_exists($cacheFile)) {
                    $ctx = stream_context_create(['http' => ['timeout' => 5]]);
                    $content = @file_get_contents($rawImage, false, $ctx);
                    if ($content) {
                        $tempRemote = $cacheDir . '/temp_' . md5($rawImage);
                        file_put_contents($tempRemote, $content);
                        self::createJpeg($tempRemote, $cacheFile);
                        @unlink($tempRemote);
                    } else {
                        return self::getFallbackUrl();
                    }
                }
                return url('images/og-cache/' . $hash . '.jpg');
            }

            // Local file
            $cleanPath = ltrim(parse_url($rawImage, PHP_URL_PATH) ?? $rawImage, '/');
            $fullPath = public_path($cleanPath);

            if (!file_exists($fullPath)) {
                $fullPath = public_path('images/posts/Synbarra_Post_01.png');
            }

            if (!file_exists($fullPath)) {
                return self::getFallbackUrl();
            }

            $hash = 'og_' . md5($cleanPath);
            $cacheFile = $cacheDir . '/' . $hash . '.jpg';

            if (!file_exists($cacheFile)) {
                self::createJpeg($fullPath, $cacheFile);
            }

            if (file_exists($cacheFile)) {
                return url('images/og-cache/' . $hash . '.jpg');
            }

            return url(ltrim($cleanPath, '/'));
        } catch (\Throwable $e) {
            return self::getFallbackUrl();
        }
    }

    private static function getFallbackUrl(): string
    {
        if (file_exists(public_path('images/posts/Synbarra_Post_01.png'))) {
            return url('images/posts/Synbarra_Post_01.png');
        }
        return url('favicon.png');
    }

    private static function createJpeg(string $sourcePath, string $targetPath): bool
    {
        $info = @getimagesize($sourcePath);
        if (!$info) {
            return false;
        }

        $mime = $info['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $src = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $src = @imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $src = @imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
                $src = @imagecreatefromgif($sourcePath);
                break;
            default:
                $src = false;
        }

        if (!$src) {
            return false;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        $targetW = 1200;
        $targetH = 630;

        $dst = imagecreatetruecolor($targetW, $targetH);

        // Synbarra brand deep teal background #011E26
        $bg = imagecolorallocate($dst, 1, 30, 38);
        imagefill($dst, 0, 0, $bg);

        // Aspect fit centered
        $scale = min($targetW / $origW, $targetH / $origH);
        $newW = (int) round($origW * $scale);
        $newH = (int) round($origH * $scale);
        $offX = (int) round(($targetW - $newW) / 2);
        $offY = (int) round(($targetH - $newH) / 2);

        imagecopyresampled($dst, $src, $offX, $offY, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        // Save as JPEG with 85% quality (< 150KB, crisp for WhatsApp preview)
        imagejpeg($dst, $targetPath, 85);
        imagedestroy($dst);

        return true;
    }
}
