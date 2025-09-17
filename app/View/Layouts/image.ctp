<?php
header ("Content-type: image/png");
$fileName = isset($fileName) ? $fileName . '.png' : 'image.png';
header("Content-Disposition: attachment; filename=\"$fileName");
echo $imgData;