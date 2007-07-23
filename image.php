<?php
/** My awesome file resize class
 * 
 * Usage:
 *
 *   $image = new Image('plod.jpg');
 *   $image->resize(100, 150)->saveAs('plod-thumbnail.jpg');
 *
 * Or, with uploaded files (suppose your file info is in $_FILES['photo']):
 *
 *   $image = new UploadedImage('photo');
 *   $image->resize(300, 200)->saveAs('my-photo.jpg');
 *
 * That's it!!
 */

class Image {
  public $path;
  public $name;
  public $extension;
  public $width;
  public $height;
  private $data = null;
  
  function __construct($path, $name = null) {
    $this->path = $path;
    $this->setName($name);
  }

  function setName($name) {
    if (!$name) $name = basename($this->path);
    
    $extensionPattern = '/\.([a-z]{2,4}\d?)$/i';
    if (preg_match($extensionPattern, $name, $matches)) {
      $this->extension = strtolower($matches[1]);
      $name = preg_replace($extensionPattern, '', $name);
    }
    
    $this->name = $name;
  }

  protected function open() {
    if (!is_null($this->data)) return;
    $fn = 'imagecreatefrom' . $this->getImageType();
    $this->data = $fn($this->path);
    if($this->data === false)
      throw new Exception("failed to create image '{$this->name}' ({$this->path}) using $fn");
  }

  protected function readDimensions() {
    $this->width  = imagesx($this->data);
    $this->height = imagesy($this->data);
  }

  function getImageType() {
    return $this->extension == 'jpg' ? 'jpeg' : $this->extension;
  }
  
  static function imageTypeToExtension($imagetype, $include_dot = false) {
    if (!$imagetype) return null;
    $dot = $include_dot ? '.' : '';
    switch ($imagetype) {
      case IMAGETYPE_GIF     : return $dot.'gif';
      case IMAGETYPE_JPEG    : return $dot.'jpg';
      case IMAGETYPE_PNG     : return $dot.'png';
      case IMAGETYPE_SWF     : return $dot.'swf';
      case IMAGETYPE_PSD     : return $dot.'psd';
      case IMAGETYPE_WBMP    : return $dot.'wbmp';
      case IMAGETYPE_XBM     : return $dot.'xbm';
      case IMAGETYPE_TIFF_II : return $dot.'tiff';
      case IMAGETYPE_TIFF_MM : return $dot.'tiff';
      case IMAGETYPE_IFF     : return $dot.'aiff';
      case IMAGETYPE_JB2     : return $dot.'jb2';
      case IMAGETYPE_JPC     : return $dot.'jpc';
      case IMAGETYPE_JP2     : return $dot.'jp2';
      case IMAGETYPE_JPX     : return $dot.'jpf';
      case IMAGETYPE_SWC     : return $dot.'swc';
      default                : return false;
    }
  }

  private function fitInto($width, $height, $inside = true) {
    if ($this->width <= $width && $this->height <= $height) return array($this->width, $this->height);
    $Rw = $this->width / $width;
    $Rh = $this->height / $height;
    $R = $inside ? max($Rw, $Rh) : min($Rw, $Rh);
    return array($this->width / $R, $this->height / $R);
  }

  public function resize($targetWidth, $targetHeight) {
    $this->open();
    $this->readDimensions();

    list($newWidth, $newHeight) = $this->fitInto($targetWidth, $targetHeight);

    $new = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($new, $this->data, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
    imagedestroy($this->data);
    $this->data = &$new;
    return $this;
  }

  public function saveAs($path, $quality = 80) {
    $result = imagejpeg($this->data, $path, $quality);
    chmod($path, 0666);
    return $result;
  }
}

class UploadedImage extends Image {
  protected static $dir = './';
  
  static function setDirectory($dir) {
    self::$dir = $dir;
  }

  function __construct($name, $i = null) {
    $fileInfo = $_FILES[$name];
    $originalName = is_null($i) ? $fileInfo['name'] : $fileInfo['name'][$i];
    $tmpName = is_null($i) ? $fileInfo['tmp_name'] : $fileInfo['tmp_name'][$i];
    $this->setName($originalName);
    $this->path = self::$dir . $this->name . '.' . $this->extension;
    
    if (!move_uploaded_file($tmpName, $this->path))
      throw new Exception("Unable to move '{$this->name}' to {$this->path}");
    chmod($this->path, 0666);
  }
}

# vi:filetype=php
