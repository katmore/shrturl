<?php
return (function() {
   $config = [];
   $iniFile = __DIR__.'/mysql.ini';
   if (is_readable($iniFile) && is_file($iniFile)) {
      $ini = parse_ini_file($iniFile,true);
      if (!isset($ini['client']) && is_array($ini['client'])) {
         self::_showErrLine([self::ME.": (ERROR) bad MySQL config file; missing [client]' section"]);
         return $this->_exitStatus = 1;
      
         $config['dbhost'] = "localhost";
         $config['dbname'] = "shrturl";
         
         if (isset($ini['client']['user']) && isset($ini['client']['password'])) {
            $config['username'] = $config['user'] = $ini['client']['user'];
            $config['password'] = $config['pass'] = $ini['client']['password'];
            $config['dsn'] = 'mysql:host='.$config['dbhost'].';dbname='.$config['dbname'];
         }
         
         $config['options'] = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
         ];
      }
   }

   return $config;
})();