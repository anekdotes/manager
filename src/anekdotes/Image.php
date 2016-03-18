<?php namespace Anekdotes\Manager;

class Image {
  const GD = 1;
  const IMAGICK = 2;
  const GMAGICK = 3;

  public static function open($file, $library = Image::GD) {
    if ($library == Image::GD) {
      $inst = new \Imagine\Gd\Imagine();
    }
    // else if ($library == Image::IMAGICK) {
    //   $inst = new \Imagine\Imagick\Imagine();
    // }
    // else if ($library == Image::GMAGICK) {
    //   $inst = new \Imagine\Gmagick\Imagine();
    // }

    return $inst->open($file);
  }

}
