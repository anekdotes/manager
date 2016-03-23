<?php namespace Anekdotes\Manager;
use Closure; use Imagine\Image\ImageInterface; use Imagine\Image\Box; use Anekdotes\File\File; use Anekdotes\Manager\Image;

class Manager {

  protected $exts = array('jpg', 'png', 'jpeg');
  protected $path = 'uploads/';
  protected $weight = 3000000;
  protected $size = null;
  protected $prefix = 'public/';
  public $success = false;

  /**
   * overwrite default manager's properties
   * @param array $options array of properties
   */
  public function __construct($options = array()) {
    if (isset($options['exts']))
      $this->exts = $options['exts'];
    if (isset($options['path']))
      $this->path = $options['path'];
    if (isset($options['weight']))
      $this->weight = $options['weight'];
    if (isset($options['size']))
      $this->size = $options['size'];
    if (isset($options['prefix']))
      $this->prefix = $options['prefix'];
  }

  /**
   * set custom manager's property
   * @param string $option property name
   * @param mixed $value  new property value
   */
  public function set($option, $value){
    if (property_exists($this, $option))
      $this->$option = $value;
  }

  /**
   * Manage the whole process (validation + upload)
   * @param  string $fileInfo       [description]
   * @param  closure $uploadCallback [description]
   * @return boolean Return true at the end of the process
   */
  public function manage($fileInfo, Closure $uploadCallback = null) {
    if (gettype($fileInfo) != "array"){
      throw new \Exception('First parameter must be an `array` and not a `' . gettype($fileInfo) . '`');
    }

    if (is_null($fileInfo['error']) || $fileInfo['error'] > 0){
      throw new \Exception("Invalid file");
    }

    $fileInfo = array_merge($fileInfo, pathinfo($fileInfo['name']));
    $ext = strtolower($fileInfo['extension']);
    $tmpPath = $fileInfo['tmp_name'];

    //basic validation
    if ($fileInfo['size'] / 1000000 > $this->weight){
      throw new \Exception("File is too big");
    }
    if (!in_array($ext, $this->exts)){
      throw new \Exception("File extension is not supported");
    }

    $filename = date('YmdHis', time()) . ".jpg";
    $quality = 70;

    //there's a callback, execute the callback and return its value as new filename
    if ($uploadCallback){
      $filename = $uploadCallback($fileInfo);
    }

    self::directorize($this->path);
    $newPath = $this->prefix . $this->path . $filename;

    if ($this->size && is_array($this->size)) {
      foreach ($this->size as $size => $config) {
        $newPath = $this->path . $size . '/';
        self::directorize($newPath);
        $newPath = $this->prefix . $newPath . $filename;
        $quality = isset($config['quality']) ? $config['quality'] : 75;
        self::upload($tmpPath, $newPath, $ext, $quality, function($picture) use ($config) {
          $size = $picture->getSize();
          switch ($config['resize']) {
            case 'widen':
              $picture = $picture->resize($size->widen($config['width']));
              break;
            case 'heighten':
              $picture = $picture->resize($size->heighten($config['height']));
              break;
            case 'max':
              if ($size->getWidth() > $config['width']) {
                $picture = $picture->resize($size->widen($config['width']));
              }
              if ($size->getHeight() > $config['height']) {
                $picture = $picture->resize($size->heighten($config['height']));
              }
              break;
          }
          if ($config['crop']) {
            $size = new Box($config['width'], $config['height']);
            $mode = ImageInterface::THUMBNAIL_OUTBOUND;
            $picture = $picture->thumbnail($size, $mode);
          }
          return $picture;
        });
      };
    }
    else{
      self::upload($tmpPath, $newPath, $ext, $quality);
    }
    return true;
  }

  /**
   * [upload description]
   * @param  string $tmpPath       temporary uploaded file path
   * @param  string $newPath       new file path
   * @param  string $ext           extension of the uploaded file
   * @param  integer $quality       quality of the uploaded file (useful if image)
   * @param  closure $imageCallback function to execute during the upload
   */
  public function upload($tmpPath, $newPath, $ext, $quality, Closure $imageCallback = null) {
    if ($imageCallback) {
      $file = Image::open($tmpPath);
      $file = $imageCallback($file);
      $tmpPath = $tmpPath . '.' . $ext;
      $file->save($tmpPath, array('quality' => $quality));
    }
    File::move($tmpPath, $newPath);
  }

  /**
   * creates folder recursively if there's none
   * must have a prefix, by default /public
   * @param  string $path Path to be created
   */
  public function directorize($path){
    $folders = explode('/', $path);
    $pathbuild = $this->prefix;
    foreach ($folders as $folder) {
      $pathbuild .= "{$folder}/";
      $cond = File::exists($pathbuild) && File::isDirectory($pathbuild);
      if (! $cond){
        File::makeDirectory($pathbuild);
        File::put("{$pathbuild}.gitkeep", "Generated by `anekdotes/manager`");
      }
    }
  }
}
