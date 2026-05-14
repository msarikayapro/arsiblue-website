<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    /** Spec § 11 — preset boyutları */
    public const SIZES = [
        'hero' => [1920, 1080],
        'card' => [800, 600],
        'thumb' => [300, 200],
    ];

    /**
     * Upload edilen dosyayı:
     *  - Verilen klasöre kaydet (orijinal + WebP versiyonları)
     *  - Hero/Card/Thumb boyutlarında resize'lı kopyaları üret
     *
     * @return array{original:string,thumb:string,webp:string} filename'ler
     */
    public function uploadAndProcess(
        UploadedFile $file,
        string $subPath,
        ?string $prefix = null,
        bool $generateThumb = true,
        bool $generateWebp = true
    ): array {
        $dir = public_path('storage/uploads/'.trim($subPath, '/'));
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $base = ($prefix ? Str::slug($prefix).'-' : '').time().'-'.Str::random(6);
        $ext = $file->extension() ?: 'jpg';
        $original = $base.'.'.$ext;
        $file->move($dir, $original);

        $result = ['original' => $original, 'thumb' => $original, 'webp' => null];

        try {
            $img = Image::read($dir.'/'.$original);

            if ($generateThumb) {
                $thumb = $base.'-thumb.'.$ext;
                $clone = clone $img;
                $clone->cover(self::SIZES['thumb'][0], self::SIZES['thumb'][1])->save($dir.'/'.$thumb);
                $result['thumb'] = $thumb;
            }

            if ($generateWebp) {
                $webp = $base.'.webp';
                $img->toWebp(quality: 80)->save($dir.'/'.$webp);
                $result['webp'] = $webp;
            }
        } catch (\Throwable $e) {
            // Resize/WebP hatası olsa bile orijinal kalsın
            logger()->warning('Image processing failed', ['file' => $original, 'error' => $e->getMessage()]);
        }

        return $result;
    }
}
