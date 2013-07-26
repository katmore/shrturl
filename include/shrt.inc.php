<?php

/*
 * shrt_error($response_code,$message);
 */
class shrt_error {
   private $response_code;
   private $message;
   public function showThemeUrl() {
      echo shrt_error_url_base . "/theme/" .shrt_error_theme;
   }
   public function show() {
      header($_SERVER['SERVER_PROTOCOL'] . " ".$this->response_code." ".$this->message, true, $this->response_code);
      $errfile = shrt_error_path."/theme/".shrt_error_theme."/".$this->response_code.".inc.php";
      if (is_readable($errfile) ) {
         require($errfile);
      } else {
         echo "error: ".$this->response_code." ".$this->message;
      }
   }
   public function __construct($response_code,$message) {
      $this->response_code = $response_code;
      $this->message = $message;
   }
}

class shrt_allow {
   private $result;
   public function result() {
      return $this->result;
   }
   public function __construct($allowed_ipaddr) {
      $this->result = false;
      if (!is_array($allowed_ipaddr)) throw new Exception("did not get expected array of allowed IP addresses");
      foreach ($allowed_ipaddr as $ipaddr) {
         if ($_SERVER['REMOTE_ADDR'] == $ipaddr) {
            $this->result = true;
            return;
         }
      }
   }
}

class shrt_changetarget {
   
   public function __construct($newTarget,$code,$myi) {
      $newTarget = $myi->real_escape_string($newTarget);
      $code = $myi->real_escape_string($code);
      $sql = "
      UPDATE
         url
      SET
         target='$newTarget'
      WHERE
         code='$code'
      ";
      if (!$result = $myi->query($sql)) {
         throw new Exception("mysql error ".$myi->errno. " " . $myi->error, 1);
      }
      if ($myi->affected_rows<1) return false;
      return true;
   }
   
}

class shrt_target {
   private $value;
   public function value() {
      return $this->value;
   }
   public function __toString() {
      return $this->value;
   }
   public function show() {
      if ($this->value === false) throw new Exception("no url found with this code");
      echo $this->value();
   }
   public function __construct($code,$myi) {
      
      $code = $myi->real_escape_string($code);
      $sql = "
      SELECT
         target
      FROM
         url
      WHERE
         code='$code'
      AND
         target IS NOT NULL
      LIMIT 0,1
      ";
      if (!$result = $myi->query($sql)) {
         throw new Exception("mysql error ".$myi->errno. " " . $myi->error, 1);
      }
      if ($result->num_rows<1) {
         $this->value = false;
         return;
      }
      $row = $result->fetch_assoc();
      $this->value = $row["target"];
   }
}

class shrt_code {
   private $value;
   public function value($baseurl=shrt_url_base) {
      if ($this->value == "") return "";
      return $baseurl . $this->value;
   }
   public function __toString() {
      return $this->value;
   }
   public function show($baseurl=shrt_url_base) {
      echo $this->value($baseurl);
   }
   private function newCode($target,$myi) {
      $target = $myi->real_escape_string($target);
      $sql = "START TRANSACTION";
      if (!$result = $myi->query($sql)) {
         throw new Exception("mysql error ".$myi->errno. " " . $myi->error, 1);
      }
      $sql = "
      SELECT
         code
      FROM
         url
      WHERE
         md5
      IS NULL ORDER BY id ASC LIMIT 0,1 FOR UPDATE";
      if (!$result = $myi->query($sql)) {
         throw new Exception("mysql error ".$myi->errno. " " . $myi->error, 1);
      }
      if ($result->num_rows<1) {
         throw new Exception("out of codes",2);
      }   
      $row = $result->fetch_assoc();
      $code = $row["code"];
      $sql = "
      UPDATE url SET md5=md5('$target'),target='$target' WHERE code='$code'
      ";
      if (!$result = $myi->query($sql)) {
         throw new Exception("mysql error ".$myi->errno. " " . $myi->error, 1);
      }
      $sql = "COMMIT";
      if (!$result = $myi->query($sql)) {
         throw new Exception("mysql error ".$myi->errno. " " . $myi->error, 1);
      }
      $this->value = $code;
   }
   private function findCode($target,$myi) {
      $target = $myi->escape_string($target);
      $sql = "
      SELECT
         code
      FROM
         url
      WHERE
         md5=md5('$target')
      LIMIT 0,1
      ";
      if (!$result = $myi->query($sql)) {
         throw new Exception("mysql error ".$myi->errno. " " . $myi->error, 1);
      }
      if ($result->num_rows<1) {
         return false;
      }
      $row = $result->fetch_assoc();
      $this->value = $row["code"];
      return true;
   }
   public function __construct($target,$myi) {
      if ($target=="") return "";
      if (!$this->findCode($target,$myi)) {
         $this->newCode($target,$myi);
      }
   }
}

class shrt {
   private $myi;
   public function error($response_code,$message) {
      $err = new shrt_error($response_code,$message);
      $err->show();
      die();
   }
   public function __construct(
      $my_host=shrt_my_host,$my_user=shrt_my_user,$my_pass=shrt_my_pass,$my_dbname=shrt_my_dbname
   ) {
      $this->myi = new mysqli($my_host,$my_user,$my_pass,$my_dbname);
      $this->myi->autocommit = false;
   }
   public function allowPOST() {
      $allow = new shrt_allow(unserialize(shrt_allow_POST));
      return $allow->result();
   }
   public function target($code) {
      return new shrt_target($code,$this->myi);
   }
   public function code($target) {
      return new shrt_code($target,$this->myi);
   }
   public function changeTarget($newTarget,$code) {
      return new shrt_changetarget($newTarget,$code,$this->myi);
   }
}









