<?php

function search($inputDirectory, $width, $outputDirectory)
{
    $fileInfo = finfo_open(FILEINFO_MIME);
    $getFiles = scandir($inputDirectory);

    if (!file_exists($outputDirectory)) {
        mkdir($outputDirectory);
    }
    for ($i = 2; $i < count($getFiles); $i++) {
        $path = $inputDirectory . DIRECTORY_SEPARATOR . $getFiles[$i];
        $fRes = finfo_file($fileInfo, $path);
        //проверяем, какие из файлов в директории являются изображениями
        if (substr($fRes, 0, 5) == 'image') {
            //реализация поиска необходимых картинок, которые больше или равны заданной ширине
            if (getimagesize($path)[0] >= $width) {
                print_r(getimagesize($path)[0] . "\n");
                file_put_contents(
                    $outputDirectory . DIRECTORY_SEPARATOR . $getFiles[$i],
                    file_get_contents($path)
                );
            }
        }
    }
    print_r("Job done.\n");
}

$exitMessage = "\nUsage: php {$argv[0]} [output directory] [image width] [output directory(optional)]\n";

try {
    search(
        isset($argv[1]) ? $argv[1] : exit($exitMessage),
        isset($argv[2]) ? (int)$argv[2] : exit($exitMessage),
        isset($argv[3]) ? $argv[3] : bin2hex(random_bytes(8))
    );
} catch (Exception $e) {
    print_r($e);
}
