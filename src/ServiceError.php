<?php
namespace Shrturl;
class ServiceError {
   private $_statusCode;
   private $_statusString;
   private $_themePath;
   private $_themeURL;
   
   public function header() {
      header("HTTP/1.1 ".$this->_statusCode." ".$this->_statusString, true, $this->_statusCode);
   }
   public function display($infoMessage=null) {
      $themeData = new ThemeData(['themePath'=>$this->_themePath,'themeURL'=>$this->_themeURL,'infoMessage'=>$infoMessage]);
      if ($errorTemplate = $themeData->getTemplatePath($this->_statusCode) ) {
         call_user_func(function() use ($errorTemplate,$themeData) {
            require($errorTemplate);
         });
      } elseif ($errorTemplate = $themeData->getTemplatePath("error") ) {
         $themeData = new ThemeData();
         call_user_func(function() use ($errorTemplate,$themeData) {
            require($errorTemplate);
         });
      } else {
         echo $this->_statusCode." ".$this->_statusString;
         if (is_string($infoMessage) && !empty($infoMessage)) echo ":".$infoMessage; 
      }
   }
   public function __construct($statusCode,$statusString,$themePath,$themeURL) {
      $this->_statusCode = $statusCode;
      $this->_statusString = $statusString;
      $this->_themePath = $themePath;
      $this->_themeURL = $themeURL;
   }
}