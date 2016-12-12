<?php
namespace Shrturl;
class Service {
   
   public static function codeSession(Factory $factory, $codeUrlPrefix=null,$request=null) {
      if (is_null($request) && !$request = self::_getRequest()) return;
      
      if ( isset($request["target"]) ) {
         $code = $factory->targetToCode($request["target"]);
         if (self::_useHeaders()) {
            header('Content-Type: text/plain');
            header('HTTP/1.0 200 OK', true, 200);
         }
         echo $code->formatCode("$codeUrlPrefix/%code%");
         return $code;
      }
   }
   
   public static function changeTargetSession(Factory $factory, $codeUrlPrefix=null,$request=null) {
      if (is_null($request) && !$request = self::_getRequest()) return;
      
      if ( !isset($request["target"]) || !isset($request["code"])) return;
      if ($changeTarget = $factory->changeTarget($request["target"],$request["code"])) {
         if (self::_useHeaders()) {
            header("Content-type: ".$shrtconfig["Content-type"]);
            header('HTTP/1.0 200 OK', true, 200);
         }
         echo $changeTarget->formatCode("$codeUrlPrefix/%code%");
         return $changeTarget;
      } else {
         $error = new ServiceError(404,"Not Found",self::$_themePath,self::$_themeURL);
         if (self::_useHeaders()) $error->header();
         $error->display();
         return $error;
      }     
   }
   public static function multiTargetSession(Factory $factory, $codeUrlPrefix=null, $input=null, $request=null) {
      if (is_null($input)) {
         if (is_null($targetList = json_decode(file_get_contents("php://input"),true))) {
            if (is_null($request) && !$request = self::_getRequest()) return null;
            if (empty($request['request']) || is_null($targetList = json_decode($request["request"],true))) {
               return null;
            }
         }
      } else {
         if (is_array($input)) {
            $targetList = $input;
         } else {
            if (!$targetList = json_decode($input,true)) {
               return null;
            }
         }
      }
      if (is_array($targetList)) {
         $response=[];
         $codeList=[];
         foreach($targetList as $target) {
            $code = $factory->targetToCode();
            $codeList[]=$code;
            $response[$target] = $code->formatCode("$codeUrlPrefix/%code%");
         }
         if (self::_useHeaders()) {
            header('HTTP/1.0 200 OK', true, 200);
            header('Content-type: application/json');
         }
         echo json_encode($response);
         return $codeList;
      }
   }
   protected static function _useHeaders() {
      return isset($_SERVER) && is_array($_SERVER) && !empty($_SERVER['SERVER_PROTOCOL']) && !headers_sent();
   }
   public static function targetSession(Factory $factory, $codeUrlPrefix=null, $request=null) {
      
      if (is_null($request) && !$request = self::_getRequest()) return;
      $code = null;
      if (isset($request["code"])) {
         $code = $request['code'];
      }
      if (!empty($code)) {
         if ($target = $factory->codeToTarget($request["code"])) {
            if (self::_useHeaders()) {
               header("Content-type: ".$shrtconfig["Content-type"]);
               header('HTTP/1.1 301 Moved Permanently', true, 301);
               header("Location: ".$target->getTargetURL());
            } else {
               echo $target->getTargetURL();
            }
            return $target;
         } else {
            $error = new ServiceError(404,"Not Found",self::$_themePath,self::$_themeURL);
            if (self::_useHeaders()) $error->header();
            $error->display();
            return $error;
         }
      }
      
   }
   public static function errorSession($statusCode,$statusString,$infoMessage=null) {
      $error = new ServiceError($statusCode,$statusString,self::$_themePath,self::$_themeURL);
      if (self::_useHeaders()) $error->header();
      $error->display($infoMessage);
      return $error;
   }
   protected static function _getRequest() {
      if (isset($_REQUEST)) return $_REQUEST;
      if (isset($_GET)) return $_GET;
   }
   
   protected static function _getRequestMethod() {
      if (isset($_SERVER) && !empty($_SERVER['REQUEST_METHOD'])) {
         return $_SERVER['REQUEST_METHOD'];
      }
      return "GET";
   }
   
   protected static $_themeURL;
   public static function setThemeURL($themeURL) {
      self::$_themeURL = $themeURL;
   }
   
   protected static $_themePath;
   public static function setThemePath($themePath) {
      self::$_themePath = $themePath;
   }
   
   public function __construct(Factory $factory, $codeUrlPrefix=null,array $request=null,$requestMethod=null) {
      if (!$requestMethod) $requestMethod = self::_getRequestMethod();
      if ($requestMethod==="GET") {
         if (self::targetSession($factory,$codeUrlPrefix,$request)) return;
         if (self::multiTargetSession($factory,$codeUrlPrefix)) return;
      }elseif ($requestMethod==="POST"||$requestMethod==="POST") {
         if (self::changeTargetSession($factory,$codeUrlPrefix)) return;
         if (self::codeSession($factory,$codeUrlPrefix)) return;
      }
      self::errorSession(400,"Bad Request");
   }
}