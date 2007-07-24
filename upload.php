<?php
require_once 'image.php';
require_once 'Zend/Json.php';

$dir = './images/';
UploadedImage::setDirectory($dir);

$response = array(
  'directory' => $dir,
  'failed' => array(),
  'saved' => array(),
  );

foreach($_FILES['images']['name'] as $i => $name) {
  if (!$_FILES['images']['size'][$i]) {
    $response['failed'][] = array($name,
      UploadedImage::getErrorMessage($_FILES['images']['error'][$i]));
    continue;
  }
  
  $image = new UploadedImage('images', $i);
  $filename = $image->path;
  $filename_thumb = $dir . $image->name . '-thumb.' . $image->extension;
  $image->resize(140, 78)->saveAs($filename_thumb);

  
  ob_start();
  include '_image.tpl.php';
  $response['saved'][] = ob_get_clean();
}

# header('Content-type: text/plain');
# header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
<script type="text/javascript">
var data = <?= Zend_Json::encode($response) ?>;
parent.processResponse(data)
</script>
