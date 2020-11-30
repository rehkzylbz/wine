<?php
    spl_autoload_register(function ($class_name) {
        include_once 'classes/'.$class_name.'.php';
    });
    include_once 'settings.php';
    $filename = !empty($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
    $size = !empty($_GET['size']) ? htmlspecialchars($_GET['size']) : '';
    $file_url = $settings['cache_dir'].$size.'_'.$filename.'.jpg';
    if ( !file_exists(__DIR__.$file_url) ) {
        $file_url = image_create($filename.'.jpg', $size); 
    }
    file_out($file_url);
    
    function image_create($orig_name = '', $size = '') {
        global $settings;
        $result = '';
        $orig_file = __DIR__.$settings['gallery_dir'].$orig_name;
        if ( file_exists($orig_file) && !is_dir($orig_file) ) {
            if ( !check_gd() ) return $result;
            if ( !$sizes = get_sizes($size) ) return $result;
            $new_sizes = get_new_sizes($orig_file, $sizes[$size]['w'], $sizes[$size]['h']);
            if ( ( $image = image_scale($orig_file, $new_sizes[0], $new_sizes[1]) ) === false ) return $result;
            if ( !file_exists( __DIR__.$settings['cache_dir']) ) mkdir( __DIR__.$settings['cache_dir']);
            if ( ( $result = image_save($image, $settings['cache_dir'].$size.'_'.$orig_name) ) === false ) return $result;
            imagedestroy($image);
        }
        return $result;
    }
    
    function check_gd()
    {
        if ( extension_loaded('gd') ) return true;
        else {
            file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').'GD отсутствует'.PHP_EOL, FILE_APPEND | LOCK_EX);
            return false;
        }
    }
    
    function get_sizes($size = 'mic')
    {
        global $settings;
        try {
            $db = new DB($settings['db']);
        } catch (Exception $e) {
            file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').$e->getMessage().PHP_EOL, FILE_APPEND | LOCK_EX);
            return false;
        }
        try {
            $sizes = $db->get_sizes(true);
        } catch (Exception $e) {
            file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').$e->getMessage().PHP_EOL, FILE_APPEND | LOCK_EX);
            return false;
        }
        if ( !isset($sizes[$size]) ) {
            file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').'Запрошен некорректный размер '.$size.PHP_EOL, FILE_APPEND | LOCK_EX);
            return false;
        }
        if ( $sizes[$size]['w'] <= 0 || $sizes[$size]['h'] <= 0 ) {
            file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').'Задан некорректный размер '.$size.PHP_EOL, FILE_APPEND | LOCK_EX);
            return false;
        }
        return $sizes;
    }
    
    function get_new_sizes($orig_file ='', $limit_width = 1, $limit_height = 1)
    {
        $sizes = getimagesize($orig_file);
        if ( $sizes[0]/$sizes[1] >= $limit_width/$limit_height ) {
            $new_width = $limit_width;
            $new_height = round($new_width*$sizes[1]/$sizes[0]);
        }
        else {
            $new_height = $limit_height;
            $new_width = round($new_height*$sizes[0]/$sizes[1]);
        };
        $result = [$new_width, $new_height];
        return $result;
    }
    
    function image_scale($orig_file, $new_width = 1, $new_height = 1)
    {
        global $settings;
        if ( ($image = imagecreatefromjpeg($orig_file)) === false ) {
            file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').'Открыть изображение '.$orig_file.' не удалось'.PHP_EOL, FILE_APPEND | LOCK_EX);
            return false;
        }
        if ( $image = imagescale($image, $new_width, $new_height) ) return $image;
        else {
            file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').'Масштабировать изображение '.$orig_file.' не удалось'.PHP_EOL, FILE_APPEND | LOCK_EX);
            return false;
        }
    }
    
    function image_save($image, $to)
    {
        global $settings;
        if ( imagejpeg($image, __DIR__.$to) ) return $to;
        else {
            file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').'Сохранить изображение '.$to.' не удалось'.PHP_EOL, FILE_APPEND | LOCK_EX);
            return false;
        }
    }
    
    function file_out($file_url = '') {
        global $settings;
        if ( file_exists(__DIR__.$file_url) && !is_dir(__DIR__.$file_url) ) {
            header('X-Accel-Redirect: '.$file_url);
            header('Content-Type: '.mime_content_type(__DIR__.$file_url));
            exit;
        }
        else {
            header('X-Accel-Redirect: '.'/'.$settings['default_img']);
            header('Content-Type: '.mime_content_type(__DIR__.'/'.$settings['default_img']));
            exit;
        }
    }