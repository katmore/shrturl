<?php
namespace Katmore\Shrt;
class ThemeData {
   public $themePath;
   public $themeURL;
   public $infoMessage;
   public $statusCode;
   public $statusString;
   public function getTemplatePath($name) {
      if (is_readable($path = $this->_themePath."/$name.php")) {
         return $path;
      }
   }
   public function __construct(array $param=null) {
      foreach($this as $p=>&$v) {
         if (isset($param[$p])) $v=$param[$p];
      }
   }
}