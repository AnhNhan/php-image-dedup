<?php
namespace AnhNhan\Imagery;

/**
 * Represents an image object
 *
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package ImageDeduplicator
 */
class Image
{
    private $_image;
    private $_meta = [];

    public static function createFromFile($path)
    {
        if (preg_match('/(jpg|jpeg)$/',$path)) {
            $src_img = @imagecreatefromjpeg($path);
        } elseif (preg_match('/png$/',$path)) {
            $src_img = @imagecreatefrompng($path);
        } elseif (preg_match('/gif/',$path)) {
            $src_img = @imagecreatefromgif($path);
        }

        if (!isset($src_img) || !$src_img) {
            return null;
        }

        return new Image($src_img);
    }

    /**
     * Creates a new Image object
     *
     * @param resource $image A GD2 image resource
     * @throws \InvalidArgumentException
     */
    public function __construct($image)
    {
        if (!is_resource($image) && $image !== null) {
            throw new \InvalidArgumentException('Invalid image supplied!');
        } elseif ($image === null) {
            $image = imagecreatetruecolor(64, 64);
        }
        $this->_image = $image;

        $this->_meta['size_w'] = imageSX($this->_image);
        $this->_meta['size_h'] = imageSY($this->_image);
    }

    /**
     * Disposing image data
     */
    public function __destruct()
    {
        imagedestroy($this->_image);
    }

    public function &getImageData()
    {
        return $this->_image;
    }

    public function getMetaInfo($name)
    {
        return key_exists($name, $this->_meta) ? $this->_meta[$name] : null;
    }

    public function getImageWidth()
    {
        return $this->getMetaInfo('size_w');
    }

    public function getImageHeight()
    {
        return $this->getMetaInfo('size_h');
    }

    public function colorAt($w, $h)
    {
        return imagecolorat($this->_image, $w, $h);
    }

    /**
     * Get an eagerly resized version of this image without changing the original.
     *
     * @return Image
     */
    public function resizeTo($new_x, $new_y)
    {
        $new_img = imagecreatetruecolor($new_x, $new_y);
        imagecopyresampled($new_img, $this->_image, 0, 0, 0, 0, $new_x, $new_y, $this->getImageWidth(), $this->getImageHeight());
        return new Image($new_img);
    }

    /**
     * Get an eagerly created greyscale version of this image without changing it.
     *
     * @return Image
     */
    public function getGreyscale()
    {
        $new_img = imagecreatetruecolor($this->getImageWidth(), $this->getImageHeight());

        imagecopy($new_img, $this->_image, 0, 0, 0, 0, $this->getImageWidth(), $this->getImageHeight());
        imagefilter($new_img, IMG_FILTER_GRAYSCALE);

        return new Image($new_img);
    }

    public $dhash_cmp_w = 9;
    public $dhash_cmp_h = 8;

    public function getDHash()
    {
        assert(imageistruecolor($this->_image));
        $greyscale = $this->getGreyscale();
        $small_img = $greyscale->resizeTo($this->dhash_cmp_w, $this->dhash_cmp_h);
        $hash_field = [];
        $colo_field = [];
        foreach (range(0, $this->dhash_cmp_w - 1) as $w)
        {
            foreach (range(0, $this->dhash_cmp_h - 1) as $h)
            {
                $pos = $w * $this->dhash_cmp_h + $h;
                $pixel = $small_img->colorAt($w, $h);
                $c = $pixel & 0xFF;
                $colo_field[$pos] = $c;
                if ($w != 0)
                {
                    $hash_field[] = $colo_field[$pos] > $colo_field[$pos - 1];
                }
            }
        }
        unset($greyscale);
        unset($small_img);
        return $hash_field;
    }
}
