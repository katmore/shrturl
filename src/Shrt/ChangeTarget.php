<?php
namespace Katmore\Shrt;
class ChangeTarget extends Code {
    
   public function __construct(\PDO $pdo,$newTarget,$code) {
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