<?php
/**
 * Entry point to installer script
 */
$app_dir = __DIR__.'/../app';

call_user_func(function($config) use ($app_dir) {
   
   require_once "$app_dir/autoload.php";
   
});