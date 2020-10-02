<?php
    // https://stackoverflow.com/questions/33999475/prevent-direct-url-access-to-php-file
    if (!isset($_SERVER['HTTP_REFERER']) ) {
        /* choose the appropriate page to redirect users */
        die( header( 'location: index.php' ) );
    }
?>
