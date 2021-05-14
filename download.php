<?php

include_once('database.php');

$chave = $url[1];

$arquivo = FetchRow("SELECT * FROM file WHERE idunica='$chave'");

$path = dirname(__FILE__) . '/uploads/';
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

$file_name = $arquivo->arquivo;
$formato = array_reverse(explode('.', $file_name))[0];

$file_url = $path . $chave . '.' . $formato;
$file_url = $path . $chave . '.kp';


header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($file_url));
ob_clean();
flush();
readfile($file_url);
exit();
