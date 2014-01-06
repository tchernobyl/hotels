<?php

 class saveImage

{

public function insertImage($url){


define('DIRECTORY', '/home/pw2/Pictures/hotels');

$content = file_get_contents($url);
file_put_contents(DIRECTORY.'/image.jpg', $content);
$filesize= filesize(DIRECTORY.'/image.jpg');

$filemime='image/jpeg';
echo $filemime.'--'.$filesize;
}
}