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
}
