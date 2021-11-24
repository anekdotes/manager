<?php

namespace Tests;

use Anekdotes\Manager\Image;
use PHPUnit\Framework\TestCase;

final class ImageTest extends TestCase
{
    public function testIfImageGDWorks()
    {
        $this->assertNotEmpty(Image::open(__DIR__.'/dummy/dummy.jpg'));
    }
}
