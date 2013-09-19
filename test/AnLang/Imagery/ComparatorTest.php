<?php
namespace AnhNhan\Imagery;

/**
 * @author Anh Nhan Nguyen <anhnhan@outlook.com>
 * @package ImageDeduplicator
 */
class ComparatorTest extends \PHPUnit_Framework_TestCase
{
    public static $imgFolder;

    public static function setUpBeforeClass()
    {
        self::$imgFolder = getSuperRoot() . 'test/images/';
    }

    public function testComparisonByPixels()
    {
        // Same images
        $img1 = Image::createFromFile(self::$imgFolder . 'small/beach_1.jpg');
        $img2 = Image::createFromFile(self::$imgFolder . 'small/beach_1.jpg');

        self::assertTrue(Comparator::compareByPixels($img1, $img2));

        // Two different images
        $img1 = Image::createFromFile(self::$imgFolder . 'small/beach_1.jpg');
        $img2 = Image::createFromFile(self::$imgFolder . 'small/beach_2.jpg');

        self::assertFalse(Comparator::compareByPixels($img1, $img2));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Sizes do not match!
     */
    public function testCantCompareWeirdlySizedImages()
    {
        // Same images, different resolution
        $img1 = Image::createFromFile(self::$imgFolder . 'src/beach_1.jpg');
        $img2 = Image::createFromFile(self::$imgFolder . 'small/beach_1.jpg');

        self::assertTrue(Comparator::compareByPixels($img1, $img2));
    }

    public function testComparisonByHashsums()
    {
        // Same images
        $img1 = Image::createFromFile(self::$imgFolder . 'small/beach_1.jpg');
        $img2 = Image::createFromFile(self::$imgFolder . 'small/beach_1.jpg');

        self::assertTrue(Comparator::compareByHashsum($img1, $img2));

        // Two different images
        $img1 = Image::createFromFile(self::$imgFolder . 'small/beach_1.jpg');
        $img2 = Image::createFromFile(self::$imgFolder . 'small/beach_2.jpg');

        self::assertFalse(Comparator::compareByHashsum($img1, $img2));

        // Same images, different resolution
        $img1 = Image::createFromFile(self::$imgFolder . 'src/beach_1.jpg');
        $img2 = Image::createFromFile(self::$imgFolder . 'small/beach_1.jpg');

        self::assertFalse(Comparator::compareByHashsum($img1, $img2));
    }
}
