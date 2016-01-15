<?php

$vendor_dir = __DIR__.'/../vendor';

return call_user_func(function() use(&$vendor_dir){
   require "$vendor_dir/autoload.php";
});