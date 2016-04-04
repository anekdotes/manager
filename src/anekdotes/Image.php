<?php

namespace Anekdotes\Manager;

use Imagine\Gd\Imagine;

class Image
{
    /**
   * Open the file for modification using imagine class.
   *
   * @param  string $file file path
   *
   * @return instance of Imagine\Gd\Imagine
   */
  public static function open($file)
  {
      $inst = new Imagine();

      return $inst->open($file);
  }
}
