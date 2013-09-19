<?php
namespace AnhNhan\Imagery\Deduplication;

use AnhNhan\Imagery;

/**
 * Analyzes an image, generating an image hash
 *
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package ImageDeduplicator
 */
class ImageHasher
{
    public static function hashImage(
        Imagery\Image $image,
        $hashSampleSizeWidth = 128,
        $hashSampleSizeHeight = 128
    ) {
        $origWidth = $image->getImageWidth();
        $origHeight = $image->getImageHeight();

        $hashImage = imagecreatetruecolor($hashSampleSizeWidth, $hashSampleSizeHeight);
        imagecopyresampled(
            $hashImage,
            $image->getImageData(),
            0,
            0,
            0,
            0,
            $hashSampleSizeWidth,
            $hashSampleSizeHeight,
            $origWidth,
            $origHeight
        );

        return new Imagery\Image($hashImage);
    }
}
