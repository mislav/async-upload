<?php
$files = glob('./images/*-thumb.*');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hr" lang="hr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>parkour.hr - admin - slike</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="prototype.js"></script>
  <script type="text/javascript" src="scriptaculous/effects.js"></script>
  <script type="text/javascript" src="application.js"></script>
</head>
<body>

<div id="main">
  <div id="images">
    <? foreach($files as $filename_thumb):
      $filename = str_replace('-thumb', '', $filename_thumb);
      $name = preg_replace('/\.\w+$/', '', $filename);
      include '_image.tpl.php';
    endforeach ?>
  </div>

  <div id="sidebar">
    <form id="upload" action="upload.php" method="POST" enctype="multipart/form-data">
      <h2>Upload a new image</h2>
      <p>Choose a file: <a href="#" onclick="addFileField(); return false">(add more fields)</a></p>

      <div id="inputs">
        <div><input type="file" name="images[]" /></div>
      </div>

      <div class="buttons">
        <button type="submit" class="positive">Upload images! &raquo;</button>
      </div>
    </form>

    <div id="messages">
      <div class="error">Error: upload limit exceeded</div>
      <div class="notice">Successfully uploaded "Some File".</div>
    </div>
  </div>
</div>

</body>
</html>
