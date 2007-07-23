<?php
require_once 'image.php';

$dir = './images/';
UploadedImage::setDirectory($dir);

foreach($_FILES['images']['name'] as $i => $name) {
  if (!$_FILES['images']['size'][$i]) {
    # echo "skipping $name<br />";
    # http://hr.php.net/manual/en/features.file-upload.errors.php
    continue;
  }
  
  $image = new UploadedImage('images', $i);
  $image->resize(140, 78)->saveAs($dir . $image->name . '-thumb.' . $image->extension);
  
  # echo "processed $name<br />";
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
