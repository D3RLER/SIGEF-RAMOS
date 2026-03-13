<?php
$url = 'http://www.fpdf.org/en/dl.php?v=186&f=zip';
$zipFile = __DIR__ . '/fpdf.zip';
$extractPath = __DIR__ . '/libs/';

if (!is_dir($extractPath)) {
    mkdir($extractPath, 0777, true);
}

echo "Descargando FPDF desde $url...\n";
$content = file_get_contents($url);
if ($content === false) {
    die("Error descargando el archivo.\n");
}
file_put_contents($zipFile, $content);

echo "Extrayendo...\n";
$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    // FPDF viene en una carpeta fpdf186/, extraeremos y moveremos el contenido principal
    $zip->extractTo($extractPath);
    $zip->close();
    echo "FPDF Extraido con exito.\n";
}
else {
    echo "Falla al abrir el ZIP.\n";
}
?>
