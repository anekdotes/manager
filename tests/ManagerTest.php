<?php

namespace Tests;

use Anekdotes\File\File;
use Anekdotes\Manager\Manager;
use PHPUnit_Framework_TestCase;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    public function testManagerCreation()
    {
        $m = new Manager();
        $this->assertNotEmpty($m);
    }

    public function testManagerAcceptsConfig()
    {
        require __DIR__.'/testConfig.php';
        $m = new Manager($configs);
        $this->assertNotEmpty($m);
    }

    public function testManagerUploadDummyFile()
    {
        ini_set('memory_limit', '-1');
        require __DIR__.'/testConfig.php';
        $path = __dir__.'/dummy/dummy.jpg';
        $dumInfo = [
          'name'     => $path,
          'type'     => mime_content_type($path),
          'tmp_name' => $path,
          'error'    => 0,
          'size'     => File::size($path),
        ];

        $m = new Manager($configs);
        $this->assertTrue($m->manage($dumInfo));
    }

    public function testManagerUploadDummyFileWClosure()
    {
        ini_set('memory_limit', '-1');
        require __DIR__.'/testConfig.php';
        $path = __dir__.'/dummy/dummy.jpg';
        $dumInfo = [
          'name'     => $path,
          'type'     => mime_content_type($path),
          'tmp_name' => $path,
          'error'    => 0,
          'size'     => File::size($path),
        ];

        $m = new Manager($configs);
        $this->assertTrue($m->manage($dumInfo, function () {
          return mt_rand(1000, 100000).'.jpg';
        }));
    }
}
