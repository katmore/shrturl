<?php
namespace Katmore\Shrt;
class ServiceError {
   private $response_code;
   private $message;
   public function getThemeUrl() {
      return shrt_error_url_base . "/theme/" .shrt_error_theme;
   }
   public function header() {
      header($_SERVER['SERVER_PROTOCOL'] . " ".$this->response_code." ".$this->message, true, $this->response_code);
   }
   public function display() {
      $errfile = shrt_error_path."/theme/".shrt_error_theme."/".$this->response_code.".inc.php";
      if (is_readable($errfile) ) {
         require($errfile);
      } else {
         echo "error: ".$this->response_code." ".$this->message;
      }
   }
   public function __construct($response_code,$message,$themePath,$themeURL) {
      $this->response_code = $response_code;
      $this->message = $message;
   }
}