<?php

namespace Tests;

use Anekdotes\Manager\Image;
use PHPUnit_Framework_TestCase;

class ImageTest extends PHPUnit_Framework_TestCase
{
    public function testIfImageGDWorks()
    {
        $this->assertNotEmpty(Image::open(__DIR__.'/dummy/dummy.jpg'));
    }
}
