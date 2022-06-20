<?php

namespace App\Service;

use Exception;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ImageUploadService
{

    /**
     * @throws Exception
     */
    public function process(UploadedFile $file, int $width = 800, bool $isWatermarked = false): string
    {

        $manager = new ImageManager(['driver' => 'imagick']);
        $image = $manager->make($file->getRealPath());

        $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        if ($isWatermarked) {
            $watermarkImage = __DIR__. '/../../public/images/watermark.png';
            $watermark = $manager->make($watermarkImage)->opacity(20);
            $watermark->resize(null, $image->height(), function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->insert($watermark, 'center');
        }

        return $image->encode()->encode('data-url');
    }

}
