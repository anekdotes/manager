<?php namespace Tests;
use PHPUnit_Framework_TestCase; use Anekdotes\Manager\Image;

class ImageTest extends PHPUnit_Framework_TestCase
{

    public function testIfImageGDWorks()
    {
        $this->assertNotEmpty(Image::open(__DIR__ . "/dummy/dummy.jpg"));
    }

}
