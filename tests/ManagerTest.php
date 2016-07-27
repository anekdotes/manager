<?php

namespace Tests;

use Anekdotes\File\File;
use Anekdotes\Manager\Manager;
use PHPUnit_Framework_TestCase;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    public function testManager1()
    {
        $m = new Manager();
        $this->assertInstanceOf(Manager::class, $m);
    }

    public function testManager2()
    {
        $config = [
          'exts'   => ['jpg', 'jpeg', 'png', 'gif'],
        ];
        $m = new Manager($config);
        $reflection = new \ReflectionClass($m);
        $reflectionProperty = $reflection->getProperty('exts');
        $reflectionProperty->setAccessible(true);
        $expected = ['jpg', 'jpeg', 'png', 'gif'];
        $this->assertEquals($expected, $reflectionProperty->getValue($m));
    }

    public function testManager3()
    {
        $m = new Manager();
        $m->set('weight', 3000000);
        $reflection = new \ReflectionClass($m);
        $reflectionProperty = $reflection->getProperty('weight');
        $reflectionProperty->setAccessible(true);
        $expected = 3000000;
        $this->assertEquals($expected, $reflectionProperty->getValue($m));
    }

    public function testManager4()
    {
        $m = new Manager();
        $m->set('misc', true);
        try {
            $reflection = new \ReflectionClass($m);
            $reflectionProperty = $reflection->getProperty('misc');
        } catch (\Exception $e) {
            $this->assertInstanceOf(Manager::class, $m);
        }
    }

    public function testManager5()
    {
        $configs = [
            'prefix' => '',
            'exts'   => ['jpg', 'jpeg', 'png', 'gif'],
            'weight'   => 3000000,
            'path'   => 'tests/cache/',
            'size'   => [
                'square' => [
                    'resize' => 'heighten',
                    'crop'   => true,
                    'width'  => 200,
                    'height' => 200,
                ],
            ],
        ];
        $m = new Manager($configs);
        $reflection = new \ReflectionClass($m);
        $reflectionProperty = $reflection->getProperty('size');
        $reflectionProperty->setAccessible(true);
        $expected = $configs['size'];
        $this->assertEquals($expected, $reflectionProperty->getValue($m));
    }

    public function testManager6()
    {
        $m = new Manager();
        $m->manage('');
        $this->assertFalse($m->success);
    }

    public function testManager7()
    {
        $m = new Manager();
        $m->manage(1);
        $this->assertFalse($m->success);
    }

    public function testManager8()
    {
        $m = new Manager();
        $m->manage((object) []);
        $this->assertFalse($m->success);
    }

    public function testManager9()
    {
        $m = new Manager();
        $m->manage('');
        $this->assertTrue(count($m->errors) > 0);
    }

    public function testManager10()
    {
        $m = new Manager();
        $m->manage([]);
        $this->assertFalse($m->success);
    }

    public function testManager11()
    {
        $m = new Manager();
        $gibberish = [
            'foo' => 'bar',
            'poo' => 'bar',
        ];
        $m->manage($gibberish);
        $this->assertFalse($m->success);
    }

    public function testManager12()
    {
      $m = new Manager();
      $dummies = [
          'name'     => 'foo',
          'type'     => 'foo',
          'tmp_name' => 'foo',
          'error'    => null,
          'size'     => null,
      ];
      $m->manage($dummies);
      $this->assertFalse($m->success);
    }

    public function testManager13()
    {
        $m = new Manager();
        $dummies = [
            'name'     => 'foo',
            'type'     => 'foo',
            'tmp_name' => 'foo',
            'error'    => 4,
            'size'     => 1,
        ];
        $m->manage($dummies);
        $this->assertFalse($m->success);
    }

    public function testManager14()
    {
        $configs = [
            'weight'   => 0.02,
        ];
        $path = __dir__.'/dummy/dummy.jpg';
        $m = new Manager($configs);
        $dummies = [
            'name'     => $path,
            'type'     => mime_content_type($path),
            'tmp_name' => $path,
            'error'    => 0,
            'size'     => File::size($path),
        ];
        $m->manage($dummies);
        $this->assertFalse($m->success);
    }

    public function testManager15()
    {
        $configs = [
            'weight'   => 3000000,
            'exts'   => ['jpg', 'jpeg', 'png', 'gif'],
        ];
        $path = __dir__.'/dummy/dummy.swf';
        $m = new Manager($configs);
        $dummies = [
            'name'     => $path,
            'type'     => mime_content_type($path),
            'tmp_name' => $path,
            'error'    => 0,
            'size'     => File::size($path),
        ];
        $m->manage($dummies);
        $this->assertFalse($m->success);
    }

    public function testManager16()
    {
        $configs = [
            'weight'   => 3000000,
            'exts'   => ['jpg', 'jpeg', 'png', 'gif'],
        ];
        $m = new Manager($configs);
        $dummies = [
            'name'     => 'foo',
            'type'     => 'foo',
            'tmp_name' => 'foo',
            'error'    => 0,
            'size'     => 100,
        ];
        $m->manage($dummies);
        $this->assertFalse($m->success);
    }

    public function testManager17()
    {
        $configs = [
            'weight'   => 3000000,
            'exts'   => ['jpg', 'jpeg', 'png', 'gif'],
        ];
        $m = new Manager($configs);
        $dummies = [
            'name'     => 'foo',
            'type'     => 'foo',
            'tmp_name' => 'foo',
            'error'    => 0,
            'size'     => 100,
        ];
        $m->manage($dummies);
        $this->assertFalse($m->success);
    }

    public function testManager18()
    {
        $configs = [
            'prefix' => '',
            'exts'   => ['jpg', 'jpeg', 'png', 'gif'],
            'weight'   => 3000000,
            'path'   => 'tests/cache/',
            'size'   => [
                'square' => [
                    'resize' => 'heighten',
                    'crop'   => true,
                    'width'  => 200,
                    'height' => 200,
                ],
            ],
        ];
        $path = __dir__.'/dummy/dummy.jpg';
        $dummies = [
            'name'     => $path,
            'type'     => mime_content_type($path),
            'tmp_name' => $path,
            'error'    => 0,
            'size'     => File::size($path),
        ];

        $m = new Manager($configs);
        $m->manage($dummies);
        $this->assertTrue($m->success);
    }

    public function testManager19()
    {
        $configs = [
            'prefix' => '',
            'exts'   => ['jpg', 'jpeg', 'png', 'gif'],
            'weight'   => 3000000,
            'path'   => 'tests/cache/',
            'size'   => [
                'square' => [
                    'resize' => 'heighten',
                    'crop'   => true,
                    'width'  => 200,
                    'height' => 200,
                ],
            ],
        ];
        $path = __dir__.'/dummy/dummy.jpg';
        $dummies = [
            'name'     => $path,
            'type'     => mime_content_type($path),
            'tmp_name' => $path,
            'error'    => 0,
            'size'     => File::size($path),
        ];

        $m = new Manager($configs);
        $m->manage($dummies, function () {
            return mt_rand(1000, 100000).'.jpg';
        });
        $this->assertTrue($m->success);
    }

    public function testManager20()
    {
        $configs = [
            'prefix' => '',
            'exts'   => ['doc', 'docx', 'pdf'],
            'weight'   => 3000000,
            'path'   => 'tests/cache/',
            'size'   => [],
        ];
        $path = __dir__.'/dummy/dummy.doc';
        $dummies = [
            'name'     => $path,
            'type'     => mime_content_type($path),
            'tmp_name' => $path,
            'error'    => 0,
            'size'     => File::size($path),
        ];

        $m = new Manager($configs);
        $m->manage($dummies);
        $this->assertTrue($m->success);
    }

    public function testManager21()
    {
        $configs = [
            'prefix' => '',
            'exts'   => ['swf', 'fla'],
            'weight'   => 3000000,
            'path'   => 'tests/cache/',
            'size'   => [],
        ];
        $path = __dir__.'/dummy/dummy.swf';
        $dummies = [
            'name'     => $path,
            'type'     => mime_content_type($path),
            'tmp_name' => $path,
            'error'    => 0,
            'size'     => File::size($path),
        ];

        $m = new Manager($configs);
        $m->manage($dummies);
        $this->assertFalse($m->success);
    }

    public function testManager22()
    {
      $configs = [
          'prefix' => '',
          'exts'   => ['jpg', 'jpeg', 'png', 'gif'],
          'weight'   => 3000000,
          'path'   => 'tests/cache/',
          'size'   => [
              'square' => [
                  'resize' => 'widen',
                  'crop'   => true,
                  'width'  => 200,
                  'height' => 200,
              ],
          ],
      ];
      $path = __dir__.'/dummy/dummy.jpg';
      $dummies = [
          'name'     => $path,
          'type'     => mime_content_type($path),
          'tmp_name' => $path,
          'error'    => 0,
          'size'     => File::size($path),
      ];

      $m = new Manager($configs);
      $m->manage($dummies, function () {
          return mt_rand(1000, 100000).'.jpg';
      });
      $this->assertTrue($m->success);
    }

    public function testManager23()
    {
        $configs = [
            'prefix' => '',
            'exts'   => ['jpg', 'jpeg', 'png', 'gif'],
            'weight'   => 3000000,
            'path'   => 'tests/cache/',
            'size'   => [
                'square' => [
                    'resize' => 'max',
                    'crop'   => true,
                    'width'  => 200,
                    'height' => 200,
                ],
            ],
        ];
        $path = __dir__.'/dummy/dummy.jpg';
        $dummies = [
            'name'     => $path,
            'type'     => mime_content_type($path),
            'tmp_name' => $path,
            'error'    => 0,
            'size'     => File::size($path),
        ];

        $m = new Manager($configs);
        $m->manage($dummies, function () {
            return mt_rand(1000, 100000).'.jpg';
        });
        $this->assertTrue($m->success);
    }
}
