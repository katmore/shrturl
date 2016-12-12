<?php
return (function() {
   $config['dbhost'] = "localhost";
   $config['dbname'] = "shrturl";
   
   $config['username'] = $config['user'] = "shrturl";
   $config['password'] = $config['pass'] = "";
   $config['dsn'] = 'mysql:host='.$config['dbhost'].';dbname='.$config['dbname'];
   $config['options'] = [
      \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
   ];
    
   return $config;
})();