<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="favicon-96x96.png">
    <title>WineStyles</title>
  </head>
  <body>
    <div class="container-fluid py-4 row justify-content-center m-0">
        <div class="container-sm row justify-content-center">
        <h1 class="h1 w-100 p-5">WineStyles</h1>
        <?php 
            spl_autoload_register(function ($class_name) {
                include_once 'classes/'.$class_name.'.php';
            });
            include_once 'settings.php';
            $mobile_detect = new Mobile_Detect;
            $is_Mobile = $mobile_detect->isMobile();
            try {
                $db = new DB($settings['db']);
            } catch (Exception $e) {
                file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').$e->getMessage().PHP_EOL, FILE_APPEND | LOCK_EX);
            }
            try {
                $sizes = $db->get_sizes(true);
            } catch (Exception $e) {
                file_put_contents($settings['log_file'], date('d-m-Y H:i:s ').$e->getMessage().PHP_EOL, FILE_APPEND | LOCK_EX);
                $sizes = [];
            }
            $gallery_dir = __DIR__.$settings['gallery_dir'];
            $gallery_url = $settings['gallery_dir'];
            foreach ( glob($gallery_dir.'*.jpg', GLOB_BRACE) as $filename ) { 
                if ( !is_dir($filename) ) { 
                    $name = substr(basename($filename), 0, -4); ?>
                <div class="col-md-6 col-lg-4 row justify-content-center align-items-center flex-column px-4 p-2">
                    <?php 
                        $i = 0;
                        foreach ( $sizes as $size ) { 
                            if ( !( ( $size['name'] === 'mic' ) && ( !$is_Mobile ) ) && !( ( $size['name'] === 'big' ) && ( $is_Mobile ) ) ) { ?>
                                <a data-fancybox="fancybox_gallery_<?php echo $name ?>" href="/generator.php?name=<?php echo $name ?>&size=<?php echo $size['name'] ?>" class="w-100 text-center" title="<?php echo $size['name'].'_'.$name ?>">
                                    <?php if ( !$i++ ) { ?>
                                        <img src="/generator.php?name=<?php echo $name ?>&size=<?php echo $size['name'] ?>" class="img-fluid rounded" alt="<?php echo $size['name'].'_'.$name ?>" title="<?php echo $size['name'].'_'.$name ?>">
                                    <?php } ?>
                                </a>
                        <?php }
                    } ?>
                </div>
            <?php }
            }  ?>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
  </body>
</html>