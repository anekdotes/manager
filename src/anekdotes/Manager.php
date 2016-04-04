<?php

namespace Anekdotes\Manager;

use Anekdotes\File\File;
use Closure;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class Manager
{
    const IMAGES = ['jpg', 'jpeg', 'png'];
    const DOCUMENTS = ['pdf', 'doc', 'docx'];
    protected $exts = ['jpg', 'png', 'jpeg'];
    protected $path = 'uploads/';
    protected $weight = 3000000;
    protected $size = null;
    protected $prefix = 'public/';
    protected $quality = 70;
    public $success = false;

  /**
   * overwrite default manager's properties.
   *
   * @param array $options array of properties
   */
  public function __construct($options = [])
  {
      if (isset($options['exts'])) {
          $this->exts = $options['exts'];
      }
      if (isset($options['path'])) {
          $this->path = $options['path'];
      }
      if (isset($options['weight'])) {
          $this->weight = $options['weight'];
      }
      if (isset($options['size'])) {
          $this->size = $options['size'];
      }
      if (isset($options['prefix'])) {
          $this->prefix = $options['prefix'];
      }
  }

  /**
   * set custom manager's property.
   *
   * @param string $option property name
   * @param mixed $value  new property value
   */
  public function set($option, $value)
  {
      if (property_exists($this, $option)) {
          $this->$option = $value;
      }
  }

  /**
   * Manage the whole process (validation + upload).
   *
   * @param  string $fileInfo uploaded file information the same as $_FILE['input name'] + pathinfo['file name']
   * @param  closure $uploadCallback closure function to execute before dealking with the upload
   *
   * @return bool Return true at the end of the process
   */
  public function manage($fileInfo, Closure $uploadCallback = null)
  {
      if (gettype($fileInfo) != 'array') {
          throw new \Exception('First parameter must be an `array` and not a `'.gettype($fileInfo).'`');
      }

      if (is_null($fileInfo['error']) || $fileInfo['error'] > 0) {
          throw new \Exception('Invalid file');
      }

      $fileInfo = array_merge($fileInfo, pathinfo($fileInfo['name']));
      $fileInfo['extension'] = strtolower($fileInfo['extension']);
      $filename = date('YmdHis', time()).'.jpg';

      if ($fileInfo['size'] / 1000000 > $this->weight) {
          throw new \Exception('File is too big');
      }

      if (!in_array($fileInfo['extension'], $this->exts)) {
          throw new \Exception('File extension is not supported');
      }

    //there's a callback, execute the callback and return its value as new filename
    if ($uploadCallback) {
        $filename = $uploadCallback($fileInfo);
    }

      if (in_array($fileInfo['extension'], $this::IMAGES)) {
          $this->handleImage($fileInfo, $filename);
      } elseif (in_array($fileInfo['extension'], $this::DOCUMENTS)) {
          $this->handleDocument($fileInfo, $filename);
      } else {
          $this->handleOther($fileInfo, $filename);
      }

      return true;
  }

  /**
   * creates folder recursively if there's none
   * must have a prefix, by default /public.
   *
   * @param  string $path Path to be created
   */
  public function directorize($path)
  {
      $folders = explode('/', $path);
      $pathbuild = $this->prefix;
      foreach ($folders as $folder) {
          $pathbuild .= "{$folder}/";
          $cond = File::exists($pathbuild) && File::isDirectory($pathbuild);
          if (!$cond) {
              File::makeDirectory($pathbuild);
              File::put("{$pathbuild}.gitkeep", 'Generated by `anekdotes/manager`');
          }
      }
  }

  /**
   * Handle all IMAGES file formats.
   *
   * @param  string $fileInfo uploaded file information the same as $_FILE['input name'] + pathinfo['file name']
   * @param  string $filename filename to save the new file
   */
  private function handleImage($fileInfo, $filename)
  {
      if ($this->size && is_array($this->size)) {
          foreach ($this->size as $size => $config) {
              $newPath = $this->path.$size.'/';
              self::directorize($newPath);
              $newPath = $this->prefix.$newPath.$filename;
              $quality = isset($config['quality']) ? $config['quality'] : $this->quality;
              $file = Image::open($fileInfo['tmp_name']);
        //
        $size = $file->getSize();
              switch ($config['resize']) {
          case 'widen':
            $file = $file->resize($size->widen($config['width']));
            break;
          case 'heighten':
            $file = $file->resize($size->heighten($config['height']));
            break;
          case 'max':
            if ($size->getWidth() > $config['width']) {
                $file = $file->resize($size->widen($config['width']));
            }
            if ($size->getHeight() > $config['height']) {
                $file = $file->resize($size->heighten($config['height']));
            }
          break;
        }
              if ($config['crop']) {
                  $size = new Box($config['width'], $config['height']);
                  $mode = ImageInterface::THUMBNAIL_OUTBOUND;
                  $file = $file->thumbnail($size, $mode);
              }
        //
        $tmpPath = $fileInfo['tmp_name'].'.'.$fileInfo['extension'];
              $file->save($tmpPath, [
            'quality' => $quality,
        ]);

              File::move($tmpPath, $newPath);
          }
      }
  }

  /**
   * Handle all DOCUMENTS file formats.
   *
   * @param  string $fileInfo uploaded file information the same as $_FILE['input name'] + pathinfo['file name']
   * @param  string $filename filename to save the new file
   */
  private function handleDocument($fileInfo, $filename)
  {
      self::directorize($this->path);
      $newPath = $this->prefix.$this->path.$filename;
      File::move($fileInfo['tmp_name'], $newPath);
  }

  /**
   * Handle all other file format other than IMAGES and DOCUMENTS array.
   *
   * @param  string $fileInfo uploaded file information the same as $_FILE['input name'] + pathinfo['file name']
   * @param  string $filename filename to save the new file
   */
  private function handleOther($fileInfo, $filename)
  {
      self::directorize($this->path);
      $newPath = $this->prefix.$this->path.$filename;
      File::move($fileInfo['tmp_name'], $newPath);
  }
}
