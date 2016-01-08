<?php
namespace Katmore\Shrt;
class ChangeTarget extends Code {
    
   public function __construct(\PDO $pdo,$newTarget,$code) {
      $stmt = $pdo->prepare("
      UPDATE
      url
      SET
      target=:newTarget
      WHERE
      code=:code
      ");
      $stmt->execute([":newTarget"=> $newTarget, ":code"=> $code]);      
      if (!$stmt->rowCount()) return false;
      return true;
   }
    
}