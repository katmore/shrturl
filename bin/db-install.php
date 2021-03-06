<?php
use Ifsnop\Mysqldump\Mysqldump;
return(function() {
   if (0!==($exitStatus=($installer = new class() {

   const ME_LABEL = 'Shrturl Database Installer';
   
   const HELP_LABEL = "Shrturl Project: https://github.com/katmore/shrturl";
   
   const USAGE = '[--help [ [--non-interactive] [--quiet | --verbose] [--my-cnf | [--username=<db username> [--password=<db password>]] [ [--host=<mysql host> [--dbname=<mysql dbname>]] | [--dsn=<PDO Data Source Name>]]]]]';
   
   const COPYRIGHT = '(c) 2012-2017 Doug Bird. All Rights Reserved.';
   
   const ME = 'db-install.php';
   
   const FALLBACK_DBNAME = "shrturl";
   
   const FALLBACK_HOST = "localhost";
   
   const FALLBACK_USERNAME = "root";
   
   const FALLBACK_SCHEMA_JSON = __DIR__ . "/../app/data/mysql/schema.json";
   
   const FALLBACK_APP_DIR=__DIR__.'/../app';
   private static function _getAppDir() :string {
      if (!empty(getopt("",["app-dir::",])['app-dir'])) {
         return getopt("",["app-dir::",])['app-dir'];
      }
      return self::FALLBACK_APP_DIR;
   }
   /**
    * @return void
    * @static
    */
   public static function showUsage() {
      echo "Usage: ".PHP_EOL;
      echo "   ".SELF::ME." ".self::USAGE.\PHP_EOL;
   }
   
   public static function showHelp() {
      echo self::HELP_LABEL."\n";
      $fallbackDbname = self::FALLBACK_DBNAME;
      $fallbackHost = self::FALLBACK_HOST;
      $fallbackUsername = self::FALLBACK_USERNAME;
      echo <<<"EOT"
Output Control:
--help
   Enable "help mode": outputs this message then exits.

--quiet
   Enable "quiet mode": only output will be errors to STDERR.

--verbose (ignored if --quiet switch is present)
   Enable "verbose mode": outputs extra information (such as full system paths, etc.)

--non-interactive
   Enable "non interactive mode": No input prompts will be issued (such as for username, password, etc.)

Database Options:
--my-cnf=[<path to MySQL config file>]
   Indicate that the database parameters for host, username, and password should be retrieved from a MySQL config file.
   The path is optional and when omitted defaults to: {home dir}/.my.cnf
   NOTE: The MySQL config file's value for the database name is ignored. 

--dbname=<mysql dbname> (optional, cannot be in conjunction used with --dsn option)
   MySQL Dbname
   default value: "$fallbackDbname"

--username=<db username> (optional, cannot be in conjunction used with --my-cnf option)
   DB Username
   default value: "$fallbackUsername"

--password=<db password> (optional, cannot be in conjunction used with --my-cnf option)
   DB Password
   default value: {none}

--host=<mysql host> (optional, cannot be used in conjunction with the --dsn option)
   MySQL Host
   default value: "$fallbackHost"

--dsn=<PDO Data Source Name> (optional, cannot be used in conjunction with the --my-cnf option)
   PDO Data Source Name string
   See: http://php.net/manual/en/pdo.construct.php
EOT;
   }

   
   /**
    * @return void
    * @static
    */
   private static function _showIntro() {
      echo self::ME_LABEL."\n".self::COPYRIGHT.\PHP_EOL;
   }
   
   /**
    * @return void
    * @param string[]
    * @static
    */
   private static function _showErrLine(array $strLines) {
      $stderr = fopen('php://stderr', 'w');
      foreach ($strLines as $line) fwrite($stderr, "$line".\PHP_EOL);
      fclose($stderr);
   }
   /**
    * @return void
    * @param string[]
    * @static
    */
   private static function _showLine(array $strLines) {
      foreach ($strLines as $line) echo "$line".\PHP_EOL;
   }
   
   /**
    * @var int
    */
   private $_exitStatus=0;
   
   /**
    * @return int Exit status
    */
   public function getExitStatus() :int { return $this->_exitStatus; }
   
   /**
    * @return \PDO | int provides PDO object or exit status code 
    */
   private function _getPdo() {
      $option=getopt("",[
         "my-cnf::",
         "username::",
         "password::",
         "dsn::",
      ]);
      
      
      
      $pdoconfig = [
         'username'=>self::FALLBACK_USERNAME,
         'password'=>null,
         'dsn'=>null,
      ];
      
      $myCnf=null;
      if (isset($option['my-cnf'])) {
         if (!empty($option['my-cnf'])) {
            if (!is_file($option['my-cnf']) || !is_readable($option['my-cnf'])) {
               self::_showErrLine([self::ME.": (ERROR) MySQL config path did not resolve to a readable file: {$option['my-cnf']}"]);
               return $this->_exitStatus = 1;
            }
            $myCnfFile= $option['my-cnf'];
         } else {
            if (!empty($_SERVER['HOME']) && realpath($_SERVER['HOME']) && is_dir($_SERVER['HOME'])) {
               $myCnfFile = realpath($_SERVER['HOME'])."/.my.cnf";
               if (!is_file($myCnfFile) || !is_readable($myCnfFile)) {
                  self::_showErrLine([self::ME.": (ERROR) A readable MySQL config file did not exist in the user home directory: $myCnfFile"]);
                  return $this->_exitStatus = 1;
               }
            } else {
               self::_showErrLine([self::ME.": (ERROR) The PHP Environment did not provide a 'HOME' directory for .my.cnf config file."]);
               return $this->_exitStatus = 1;
            }
         }

         if ($this->_verbose) {
            self::_showLine(["using MySQL config file: $myCnfFile"]);
         }
         $myCnf = parse_ini_file($myCnfFile,true);
         if (!isset($myCnf['client']) || !is_array($myCnf['client'])) {
            self::_showErrLine([self::ME.": (ERROR) bad MySQL config file; missing [client]' section"]);
            return $this->_exitStatus = 1;
         }
         $usedSetting=[];
         $myCnfSetting=['host','user','password'];
         $myCnfValue=[];
         foreach($myCnfSetting as $k) {
            if (!empty($myCnf['client'][$k])) {
               $myCnfValue[$k]=$myCnf['client'][$k];
               $usedSetting[]=$k;
            }
         }
         unset($k);
         unset($v);
         
         if (!count($usedSetting)) {
            self::_showErrLine([self::ME.": (ERROR) bad MySQL config file; at least one of the following settings must exist in the [client] section: ".implode(", ",$myCnfSetting)]);
            return $this->_exitStatus = 1;
         }
         
         $this->_quiet || self::_showLine(['using MySQL config file settings for: '.implode(', ',$usedSetting)]);
         
         if (!empty(getopt("",['dbname'])['dbname'])) {
            $dbname=getopt("",['dbname'])['dbname'];
         } else {
            $dbname=self::FALLBACK_DBNAME;
         }
         
         if (!empty($myCnfSetting['host'])) {
            $host = $myCnfSetting['host'];
         } else {
            if (!empty(getopt("",['host'])['host'])) {
               $host=getopt("",['host'])['host'];
            } else {
               $host=self::FALLBACK_HOST;
            }
         }
         
         if (!empty($myCnfValue['user'])) $pdoconfig['username']=$myCnfValue['user'];
         if (!empty($myCnfValue['password'])) $pdoconfig['password']=$myCnfValue['password'];
         
         unset($myCnfValue);
         unset($myCnfSetting);
         unset($usedSetting);
      } else {
         foreach($pdoconfig as $k=>&$v) {
            if (!empty($option[$k])) $v=$option[$k];
         }
         unset($k);
         unset($v);
         /*
          'host'=>'localhost',
          'dbname'=>'shrturl',
          */
         if (empty($pdoconfig['dsn'])) {
            if (!empty(getopt("",['host'])['host'])) {
               $host=getopt("",['host'])['host'];
            } else {
               $host=self::FALLBACK_HOST;
            }
         }
      }
      if (!empty(getopt("",['dbname'])['dbname'])) {
         $dbname=getopt("",['dbname'])['dbname'];
      } else {
         $dbname=self::FALLBACK_DBNAME;
      }
      $pdoconfig['dsn'] = "mysql:host=$host;dbname=$dbname";
      
      try {
         $this->_quiet || self::_showLine(["Starting PDO connection..."]);
         $pdo = new class (
               $pdoconfig['dsn'],
               $pdoconfig['username'],
               $pdoconfig['password'],
               [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
               ) extends \PDO {
                  public $dsn;
                  public $username;
                  public $password;
                  public $options;
                  public function __construct($dsn,$username,$password,$options) {
                     $this->dsn = $dsn;
                     $this->username = $username;
                     $this->password = $password;
                     $this->options = $options;
                     parent::__construct($dsn,$username,$password,$options);
                  }
         };
      } catch (PDOException $e) {
         if ($this->_nonInteractive || $myCnf) {
            $err=self::ME.": (ERROR) Connection failed: ".$e->getMessage();
      
            if($myCnf) $err.=" using MySQL config file: $myCnfFile";
      
            self::_showErrLine([$err]);
            return $this->_exitStatus = 1;
         } else {
            self::_showErrLine([self::ME.": (NOTICE) Connection failed: ".$e->getMessage()]);
            echo PHP_EOL."Interactive mode: provide new connection details or Ctrl+C to exit...".PHP_EOL;
            $newHost = readline("Enter the host ($host): ");
            if (!empty($newHost)) {
               $host = $newHost;
            }
            $newDbname = readline("Enter the dbname ($dbname): ");
            if (!empty($newDbname)) {
               $dbname = $newDbname;
            }
            $pdoconfig['dsn'] = "mysql:host=$host;dbname=$dbname";
            $newUsername = readline("Enter the username ({$pdoconfig['username']}): ");
            if (!empty($newUsername)) {
               $pdoconfig['username'] = $newUsername;
            }
            exec("which stty",$output,$sttyStatus);
            if (empty($sttyStatus)) {
               echo "Enter the password ({hidden}): ";
               $newPassword = preg_replace('/\r?\n$/', '', `stty -echo; head -n1 ; stty echo`);
               echo PHP_EOL;
               $pdoconfig['password'] = $newPassword;
            }
            try {
               $pdo = new class (
                     $pdoconfig['dsn'],
                     $pdoconfig['username'],
                     $pdoconfig['password'],
                     [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
                     ) extends \PDO {
                  public $dsn;
                  public $username;
                  public $password;
                  public $options;
                  public function __construct($dsn,$username,$password,$options) {
                     $this->dsn = $dsn;
                     $this->username = $username;
                     $this->password = $password;
                     $this->options = $options;
                     parent::__construct($dsn,$username,$password,$options);
                  }
               };
            } catch (PDOException $e) {
               self::_showErrLine([self::ME.": (ERROR) Connection failed: ".$e->getMessage()]);
               return $this->_exitStatus = 1;
            }
         }
      }
      $this->_quiet || self::_showLine(["(PDO connection success)"]);
      return $pdo;
   }
   /**
    * @var bool
    */
   private $_quiet;
   
   /**
    * @var bool
    */
   private $_verbose;
   
   /**
    * @var bool
    */
   private $_nonInteractive;
   
   public function __construct() {
      
      if (isset(getopt("",["help",])['help'])) {
         self::_showIntro();
         self::showHelp();
         return;
      }
      
      $this->_verbose = false;
      if (!($this->_quiet=isset(getopt("",["quiet",])['quiet']))) {
         $this->_verbose=isset(getopt("",["verbose",])['verbose']);
      }
      
      $this->_nonInteractive = isset(getopt("",["non-interactive",])['non-interactive']);
      
      $this->_quiet || self::_showIntro();
      
      require self::_getAppDir() . "/bin-common.php";
      
      if (!($pdo = self::_getPdo()) instanceof PDO) return $pdo;
      
      $schemaJson = self::FALLBACK_SCHEMA_JSON;
      if (!is_file(realpath($schemaJson))) {
         self::_showErrLine([self::ME.": (ERROR) schema-json path did not resolve to readable file: '$schemaJson'"]);
         return $this->_exitStatus = 1;
      }
      $schemaJson = realpath($schemaJson);
      
      $this->_quiet || self::_showLine(["schema-json: $schemaJson"]);
      
      $schemaDir = pathinfo($schemaJson,\PATHINFO_DIRNAME);
      if (!is_dir($schemaDir)) {
         self::_showErrLine([self::ME.": (ERROR) could not stat schema-json directory: '$schemaDir'"]);
         return $this->_exitStatus = 1;
      }
      $schemaDir = realpath($schemaDir);
      
      $this->_verbose && self::_showLine(["schema-dir: $schemaDir"]);
      
      $schemaCfg = new class(json_decode(file_get_contents($schemaJson),true)) {
         public $version;
         public $table;
         public $sql_dir;
         public $ns;
         public function __construct(array $cfg) {
             foreach($this as $prop=>&$v) {
                if (!empty($cfg[str_replace("_","-",$prop)])) $v=$cfg[str_replace("_","-",$prop)];
             }
             unset($prop);
             unset($v);
         }
      };
      
      $badCfg=[];
      foreach($schemaCfg as $prop=>$v) {
         if (empty($v)) $badCfg[]="   missing value for '".str_replace("_","-",$prop)."'";
      }
      unset($prop);
      unset($v);
      
      if ($badCfg) {
         self::_showErrLine([self::ME.": (ERROR) bad schema.json:"]);
         self::_showErrLine($badCfg);
         return $this->_exitStatus = 1;
      }
      
      if (!is_dir($schemaCfg->sql_dir) && (substr($schemaCfg->sql_dir,0,1)!='/')) {
         $schemaCfg->sql_dir = $schemaDir."/".$schemaCfg->sql_dir;
      }
      
      $schemaCfg->sql_dir = str_replace("/",DIRECTORY_SEPARATOR,$schemaCfg->sql_dir);
      if (!realpath($schemaCfg->sql_dir)) {
         self::_showErrLine([self::ME.": (ERROR) bad schema.json: The value of 'sql-dir' did not resolve to a readable system path: {$schemaCfg->sql_dir}"]);
         return $this->_exitStatus = 1;
      }
      $schemaCfg->sql_dir = realpath($schemaCfg->sql_dir);
      
      if (!is_dir($schemaCfg->sql_dir)) {
         self::_showErrLine([self::ME.": (ERROR) bad schema.json: The value of 'sql-dir' must resolve to a system directory: {$schemaCfg->sql_dir}"]);
         return $this->_exitStatus = 1;
      }
      
      $this->_quiet || self::_showLine(["latest schema version: v{$schemaCfg->version}"]);
      
      $this->_verbose && self::_showLine(["schema-table: {$pdo->query('select database()')->fetchColumn()}.{$schemaCfg->table}"]);
      
      $version = null;
      $stmt = $pdo->query("SHOW TABLES LIKE '{$schemaCfg->table}'");
      if (!$stmt->rowCount()) {
         $pdo->query("CREATE TABLE `{$schemaCfg->table}` (
           `ns` varchar(100) COLLATE utf8_bin NOT NULL,
           `version` varchar(20) COLLATE utf8_bin NOT NULL,
           `installed_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
           PRIMARY KEY (`ns`,`version`),
           KEY (`ns`,`installed_time`)
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      } else {
         $stmt = $pdo->prepare("
         SELECT
            `version`
         FROM
            `{$schemaCfg->table}`
         WHERE
            `ns`=:ns
         ORDER BY
            `installed_time` 
         ASC
         LIMIT 1
         ");
         $stmt->execute([':ns'=>$schemaCfg->ns]);
         if ($stmt->rowCount()) {
            $version = $stmt->fetch(PDO::FETCH_ASSOC)['version'];
         }
      }
      
      $stmt = $pdo->query("SHOW TABLES");
      if ($stmt->rowCount()>1) {
         $backupFile = "{$pdo->query('select database()')->fetchColumn()}-".date("Ymd")."T".date("HiO").".sql";
         self::_showErrLine([self::ME.": (WARNING) found existing database tables"]);
         if ($this->_nonInteractive) {
            $createBackup = true;
         } else {
            $createBackup = null;
            for($i=0;$i<5;$i++) {
               $confirm = readline("Create backup? [y/n]: ");
               if (substr($confirm,0,1)=='y') {
                  $createBackup = true;
                  break 1;
               } elseif (substr($confirm,0,1)=='n') {
                  $createBackup = false;
                  break 1;
               }
            }
            if ($createBackup===null) {
               self::_showErrLine([self::ME.": (ERROR) Invalid backup confirmation $i times."]);
               return $this->_exitStatus = 1;
            }
         }
         if ($createBackup) {
            self::_showErrLine([self::ME.": (NOTICE) existing database will be exported to: $backupFile"]);
            $this->_quiet || self::_showLine(["started export at ".date("c")."..."]);
            $dump = new Mysqldump($pdo->dsn, $pdo->username, $pdo->password,[],$pdo->options);
            $dump->start($backupFile);
            $this->_quiet || self::_showLine(["(export complete)"]);
         }
      }
      
      
      if (!$version) {
         $dumpSql = "{$schemaCfg->sql_dir}/{$schemaCfg->version}/schema-dump.sql";
         $this->_verbose && self::_showLine(["restoring database using schema v{$schemaCfg->version} using: $dumpSql"]);
         if (!is_readable($dumpSql) || !is_file($dumpSql)) {
            self::_showErrLine([self::ME.": (ERROR) schema dump did not resolve to readable file: $dumpSql"]);
            return $this->_exitStatus = 1;
         }
         try {
            $pdo->exec(file_get_contents($dumpSql));
         } catch (\PDOException $e) {
            self::_showErrLine([self::ME.": (ERROR) Database error: ".$e->getMessage()]);
            return $this->_exitStatus = 1;
         }
         $version=$schemaCfg->version;
         $pdo->prepare("
         INSERT INTO
            `{$schemaCfg->table}`
         SET
            ns=:ns,
            version=:version
         ")->execute([':version'=>$version,':ns'=>$schemaCfg->ns]);
         unset($dumpSql);
      } else {
         for($newVersion = floatval($version)+0.01;$newVersion<($schemaCfg->version+0.01);$newVersion+=0.01) {
            $updateSql = "{$schemaCfg->sql_dir}/$newVersion/schema-updates.sql";
            $this->_verbose && self::_showLine(["updating from v$version to v$newVersion using: $updateSql"]);
            if (!is_readable($updateSql) || !is_file($updateSql)) {
               self::_showErrLine([self::ME.": (ERROR) update sql did not resolve to readable file: $updateSql"]);
               return $this->_exitStatus = 1;
            }
            $pdo->exec(file_get_contents($updateSql));
            $pdo->prepare("
            INSERT INTO
               `{$schemaCfg->table}`
            SET
               ns=:ns,
               version=:version
            ")->execute([':version'=>$newVersion,':ns'=>$schemaCfg->ns]);
            sleep(1);
            $version=$newVersion;
         }
         unset($newVersion);
         unset($updateSql);
      }
      
      if ($version!=$schemaCfg->version) {
         self::_showErrLine([self::ME.": (ERROR) unable to update to latest schema v{$schemaCfg->version}"]);
         return $this->_exitStatus = 1;
      }
      
      $this->_quiet || self::_showLine(["database '{$pdo->query('select database()')->fetchColumn()}' updated to schema v$version"]);
   }
   

   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

})->getExitStatus())) {
   if (PHP_SAPI=='cli') {
      $installer->showUsage();
      exit($exitStatus);
   }
   return $exitStatus;
}
})();








