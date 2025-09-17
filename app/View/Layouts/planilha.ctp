<?php
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header ("Content-type: application/vnd.ms-excel");
header ("Content-type: application/vnd.ms-excel;charset=".Configure::read('App.encoding'));

$fileName = isset($fileName) ? $fileName . '.xls' : 'documento.xls';
$title = isset($title) ? $title  : 'Relatório Gerado';

header("Content-Disposition: attachment; filename=\"$fileName");
header("Content-Description: $title");

echo $content_for_layout;
