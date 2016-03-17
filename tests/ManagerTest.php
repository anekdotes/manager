<?php namespace Tests;
use PHPUnit_Framework_TestCase; use Anekdotes\Manager\Manager;

class ManagerTest extends PHPUnit_Framework_TestCase
{

    public function testIfManagerCanBeCreated()
    {
        $a = new Manager();
        $this->assertNotEmpty($a, "Manager couldn't be created");
    }

    public function testIfManagerCanAcceptParams()
    {
        $a = new Manager(array(
          'prefix' => 'public/',
          'path' => 'uploads/test/',
          'exts' => array('jpg', 'jpeg', 'png', 'gif'),
          'size' => array(
            'square' => array(
              'resize' => 'heighten',
              'crop' => true,
              'width' => 200,
              'height' => 200
            )
          )
        ));
        $this->assertNotEmpty($a, "Manager couldn't be created");
    }

}
