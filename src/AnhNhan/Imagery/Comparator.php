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
    private static function rgbColor(Image $img, $x, $y)
    {
        $colorIndex = imagecolorat($img->getImageData(), $x, $y);
        $rgb = imagecolorsforindex($img->getImageData(), $colorIndex);

        return [
            'r' => $rgb['red'],
            'g' => $rgb['green'],
            'b' => $rgb['blue'],
            'a' => $rgb['alpha'],
        ];
    }

    private static function rgbCompare(array $color1, array $color2, $deviation = 3)
    {
        foreach ($color1 as $key => $value) {
            $delta = $color1[$key] - $color2[$key];
            if ($delta < 0) {
                $delta *= -1;
            }

            if ($value - $delta < $value - $deviation || $value + $delta > $value + $deviation) {
                return false;
            }
        }

        return true;
    }

    public static function compareByPixels(Image $img1, Image $img2, $deviation = 3)
    {
        $img1Width = $img1->getImageWidth();
        $img1Height = $img1->getImageHeight();

        $img2Width = $img2->getImageWidth();
        $img2Height = $img2->getImageHeight();

        if ($img1Width != $img2Width || $img1Height != $img2Height) {
            throw new \Exception('Sizes do not match!');
        }

        for ($i_y = 0; $i_y < $img1Height; $i_y++) {
            for ($i_x = 0; $i_x < $img1Width; $i_x++) {
                $img1Color = self::rgbColor($img1, $i_x, $i_y);
                $img2Color = self::rgbColor($img2, $i_x, $i_y);

                if (!self::rgbCompare($img1Color, $img2Color, $deviation)) {
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
