<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Service untuk mengompres dan meresize gambar sebelum disimpan.
 * Mendukung JPG, PNG, WEBP, GIF.
 * Output selalu berupa JPEG untuk efisiensi ukuran file.
 */
class ImageProcessingService
{
    /**
     * Proses dan simpan gambar langsung ke public/uploads/{subfolder}/
     *
     * @param  \Illuminate\Http\UploadedFile $file      File gambar dari request
     * @param  string                        $subfolder Subfolder di dalam public/uploads/ (default: '')
     * @param  int                           $maxWidth  Lebar maksimum output (default: 1200px)
     * @param  int                           $quality   Kualitas JPEG 0-100 (default: 82)
     * @return string  Path relatif dari public/ — contoh: 'uploads/thumbnail/xxx.jpg'
     */
    public function process(
        $file,
        string $subfolder = '',
        int $maxWidth = 1200,
        int $quality = 82
    ): string {
        $ext      = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid() . '.jpg'; // Selalu simpan sebagai JPEG

        // Tentukan folder tujuan
        $subPath = $subfolder ? trim($subfolder, '/') . '/' : '';
        $destDir = public_path('uploads/' . $subPath);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0775, true);
        }
        $destPath = $destDir . $filename;

        // Buat resource GD dari file upload
        $srcPath = $file->getRealPath();
        $image   = match ($ext) {
            'jpg', 'jpeg' => imagecreatefromjpeg($srcPath),
            'png'         => $this->pngToJpeg($srcPath),
            'webp'        => imagecreatefromwebp($srcPath),
            'gif'         => imagecreatefromgif($srcPath),
            default       => imagecreatefromjpeg($srcPath),
        };

        if (!$image) {
            // Jika GD gagal, simpan file apa adanya
            $file->move($destDir, $filename);
            return 'uploads/' . $subPath . $filename;
        }

        // Resize jika lebih lebar dari maxWidth
        [$origW, $origH] = [imagesx($image), imagesy($image)];
        if ($origW > $maxWidth) {
            $newH   = (int) round($origH * $maxWidth / $origW);
            $resized = imagecreatetruecolor($maxWidth, $newH);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newH, $origW, $origH);
            imagedestroy($image);
            $image = $resized;
        }

        // Simpan sebagai JPEG
        imagejpeg($image, $destPath, $quality);
        imagedestroy($image);

        return 'uploads/' . $subPath . $filename;
    }

    /**
     * Konversi PNG (termasuk transparan) ke resource GD dengan background putih.
     */
    private function pngToJpeg(string $srcPath)
    {
        $png = imagecreatefrompng($srcPath);
        if (!$png) return false;

        $w = imagesx($png);
        $h = imagesy($png);

        $bg = imagecreatetruecolor($w, $h);
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagecopy($bg, $png, 0, 0, 0, 0, $w, $h);
        imagedestroy($png);

        return $bg;
    }
}
