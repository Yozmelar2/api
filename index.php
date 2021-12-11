<?php

error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/EPhoto360.php';

$ephoto = new EPhoto360;


extract($_REQUEST);

$action = trim(getenv('ORIG_PATH_INFO') ?: getenv('PATH_INFO'), '/');

switch (strtolower($action)) {

    case 'writetext':
        if (isset($text) && isset($effect)) {
            $image = [];
            
            if (isset($image_url)) {
                
                if (!is_dir('tmp')) mkdir('tmp', 0740);
                
                $image = __DIR__ . '/tmp/' . uniqid() . '.jpg';
                
                copy($image_url, $image);
            }
            
            $url = $ephoto->writeText(
                $text, $effect, $image
            );
            
           if (!is_null($image) && file_exists($image))
                unlink($image);
        }

        break;
    case 'addeffect':
        if (isset($image_url) && isset($effect)) {
            !is_dir('tmp') && mkdir('tmp', 0740);
            
            $image = __DIR__ . '/tmp/' . uniqid() . '.jpg';
            
            copy($image_url, $image);

            $url = $ephoto->addEffect(
                $image, $effect
            );
            
            file_exists($image) && unlink($image);
        }

}
        if (is_null($url)) exit(json_encode([
            'success'    => false,
            'message'   => 'request invalid'
        ]));
        
        exit(json_encode([
            'success'    => !empty($url),
            'image_url' => $url ?: 'Can not find image url'
            
]));
        
        
