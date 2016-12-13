<?php
use Shrturl\CodeGenerator;
use Shrturl\Factory;
use Shrturl\Config;

return(function() {
   if (0!==($exitStatus=($cli = new class() {
      
      const ME_LABEL = 'Shrturl Code Generator';
      
      const USAGE = '<number of short codes to create> [--help] [--quiet | --verbose] [--length=<length of short codes (default: %FALLBACK_LEN%)>] [--app-dir=<path to project app directory (default: %DEFAULT_APP_DIR%)>]';
       
      const COPYRIGHT = '(c) 2012-2017 Doug Bird. All Rights Reserved.';
       
      const ME = 'make-codes.php';
      
      const DEFAULT_APP_DIR='./../app';
      
      const FALLBACK_APP_DIR=__DIR__.self::DEFAULT_APP_DIR;
      
      const FALLBACK_LEN = 5;
      
      const CODE_LEN_CEIL = 50;
      
      const CODE_LEN_FLOOR = 3;
      
      /**
       * @return void
       * @static
       */
      public static function showUsage() {
         $usage = self::USAGE;
         $usage = str_replace("%FALLBACK_LEN%", self::FALLBACK_LEN, $usage);
         $usage = str_replace("%DEFAULT_APP_DIR%", self::DEFAULT_APP_DIR, $usage);
         echo "Usage: ".PHP_EOL;
         echo "   ".SELF::ME." ".$usage.\PHP_EOL;
      }
      
      /**
       * @return void
       * @static
       */
      private static function _showIntro() {
         echo self::ME_LABEL."\n".self::COPYRIGHT.\PHP_EOL;
      }
      
      /**
       * @var int
       */
      private $_exitStatus=0;
       
      /**
       * @return int Exit status
       */
      public function getExitStatus() :int { return $this->_exitStatus; }
      
      private static function _getAppDir() :string {
         if (!empty(getopt("",["app-dir::",])['app-dir'])) {
            return getopt("",["app-dir::",])['app-dir'];
         }
         return self::FALLBACK_APP_DIR;
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
       * @var bool
       */
      private $_quiet;
      
      /**
       * @var bool
       */
      private $_verbose;
      
      public function __construct() {
         global $argv;
         
         if (isset(getopt("",["usage",])['usage']) || isset(getopt("",["help",])['help'])) {
            self::_showIntro();
            self::showUsage();
            return;
         }
         
         $this->_verbose = false;
         if (!($this->_quiet=isset(getopt("",["quiet",])['quiet']))) {
            $this->_verbose=isset(getopt("",["verbose",])['verbose']);
         }
         
         $this->_quiet || self::_showIntro();
         
         require self::_getAppDir() . "/bin-common.php";
         
         if (empty($argv[1])) {
            self::_showErrLine([self::ME . ": (ERROR) missing 'number of short codes to create'"]);
            return $this->_exitStatus = 1;
         }
         $num = $argv[1];
         
         if (
            ($num != sprintf("%d",$num)) ||
            ($num < 1)) {
            self::_showErrLine([self::ME . ": (ERROR) invalid 'number of short codes to create'; value must be an integer with a value of 1 or greater"]);
            return $this->_exitStatus = 1;
         }
         
         if (!empty(getopt("",["length::",])['length'])) {
         
            $len =getopt("",["length::",])['length'];
            if (
                  ($len != sprintf("%d",$len)) || 
                  ($len < self::CODE_LEN_FLOOR) ||
                  ($len > self::CODE_LEN_CEIL)) {
               self::_showErrLine([self::ME . ": (ERROR) invalid 'short code length'; must be an integer between ".self::CODE_LEN_FLOOR. " and ".self::CODE_LEN_CEIL]);
               return $this->_exitStatus = 1;
            }
         
            
         } else {
            $len = self::FALLBACK_LEN;
         }
         
         
         $num = (int) $num;
         $len = (int) $len;
         $mycfg = Config::LoadAssoc("mysql");

         try {
            if (!$pdo = Factory::loadPDO(Config::LoadAssoc("mysql"))) {
               self::_showErrLine([self::ME.": (ERROR) Could not establish PDO connection"]);
               return $this->_exitStatus = 1;
            }
         } catch (\PDOException $e) {
            self::_showErrLine([self::ME.": (ERROR) Connection failed: ".$e->getMessage()]);
            return $this->_exitStatus = 1;
         }
         
         $col = 8;
         $this->_quiet || self::_showLine([ "generating $num new short codes $len chars long"]);
         
         $c = 0;
         for ($i=0;$i<$num;$i++) {
            try {
               $code = new CodeGenerator($pdo,$len);
            } catch (\PDOException $e) {
               self::_showErrLine([self::ME.": (ERROR) Database error: ".$e->getMessage()]);
               if ($e->errorInfo[1] == 1146) {
                  self::_showErrLine([self::ME.": (ERROR) Missing expected table. Try: bin/db-install.php"]);
               }
               return $this->_exitStatus = 1;
            }
            $code->formatCode();
            $c++;
            if ($c==$col) {
               $this->_verbose && self::_showLine([]);
               $c=0;
            } else {
               $this->_verbose && self::_showLine(["\t"]);
            }
         }
      }
   })->getExitStatus())) {
   if (PHP_SAPI=='cli') {
      $cli->showUsage();
      exit($exitStatus);
   }
   return $exitStatus;
}
})();
