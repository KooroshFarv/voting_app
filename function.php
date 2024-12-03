<?php


function set_cookie($key, $value) {
    if (php_sapi_name() == 'cli') {
        $_COOKIE[$key] = $value;
    } else {
        setcookie($key, $value, time() + (86400 * 10), "/"); 
    }
    return true;
}
?>
