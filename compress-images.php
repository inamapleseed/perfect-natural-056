<?php
require 'vendor/autoload.php';

use \WebPConvert\WebPConvert as WebPConvert;

function getDirContents($dir, $filter = '', &$results = array()) {
    $files = scandir($dir);
    foreach($files as $key => $value){
        $path = realpath($dir. DIRECTORY_SEPARATOR . $value);
        if(!is_dir($path)) {
            if(empty($filter) || preg_match($filter, $path)) {
                $results[] = $path;
            }
        } elseif($value != "." && $value != "..") {
            getDirContents($path, $filter, $results);
        }
    }
    return $results;
}

set_time_limit(0);

$dir_img = str_replace("\\","/",realpath(dirname(__FILE__))."/") . '/image/cache';
//$this->deleteCachedImages(true);
$files = getDirContents($dir_img);

$ch = curl_init('http://api.resmush.it/ws.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_POST, TRUE);

//Supported File Format: PNG, JPG, GIF, BMP and TIF
foreach($files as $index => $file) {

    if( !strpos($file, '.htaccess') &&
        !strpos($file, '.pdf') &&
        !strpos($file, '/.') &&
        !strpos($file, '\.') &&
        !strpos($file, '.svg') &&
        !strpos($file, '.html') &&
        !strpos($file, '.php') &&
        !strpos($file, '.ico') &&
        !strpos($file, '.bak')
    ){
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'files'	=>	new CURLFile($file)
        ));

        $data = curl_exec($ch);

        $json = json_decode($data);

        if(!isset($json->error)) {
            //backup original file
            file_put_contents($file . ".bak", file_get_contents($file));
            //store compressed image in it's place
            file_put_contents($file, file_get_contents($json->dest));

            $destination = $file . '.webp';     // Store the converted images besides the original images (other options are available!)
            $options = [
                'show-report' => false
            ];
            WebPConvert::convert($file, $destination, $options);
        }
    }
    // End Foreach
}
curl_close($ch);