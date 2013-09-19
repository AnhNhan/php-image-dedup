<?php
namespace AnhNhan\Imagery;

/**
 * Compares images
 *
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package ImageDeduplicator
 */
class Comparator
{
    public static function compareByPixels(Image $img1, Image $img2, $deviation = 0.015)
    {
        $img1Width = $img1->getImageWidth();
        $img1Height = $img1->getImageHeight();
        $img1Data = $img1->getImageData();

        $img2Width = $img2->getImageWidth();
        $img2Height = $img2->getImageHeight();
        $img2Data = $img2->getImageData();

        if ($img1Width != $img2Width || $img1Height != $img2Height) {
            throw new \Exception('Sizes do not match!');
        }

        for ($i_y = 0; $i_y < $img1Height; $i_y++) {
            for ($i_x = 0; $i_x < $img1Width; $i_x++) {
                $img1Color = imagecolorat($img1Data, $i_x, $i_y);
                $img2Color = imagecolorat($img2Data, $i_x, $i_y);

                if ($img1Color != $img2Color) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function compareByHashsum(Image $img1, Image $img2)
    {
        $img1Data = $img1->getImageData();
        $img2Data = $img2->getImageData();

        ob_start();
        imagepng($img1Data);
        $img1_img = ob_get_clean();
        $img1Hash = sha1($img1_img);

        ob_start();
        imagepng($img2Data);
        $img2_img = ob_get_clean();
        $img2Hash = sha1($img2_img);

        return $img1Hash == $img2Hash;
    }
}
