<?php
namespace Katmore\Shrt;

class Factory {
   private $_pdo;
   
   public function __construct(
      \PDO $pdo
   ) {
      $this->_pdo = $pdo;
   }
   public function codeToTarget($code) {
      return new Target($code,$this->myi);
   }
   /**
    * @return \Katmore\Shrt\Code
    */
   public function targetToCode($target,$urlPrefix=null) {
      return new Code($this->_pdo,$target,$urlPrefix=null);
   }
   /**
    * @return \Katmore\Shrt\ChangeTarget
    */   
   public function changeTarget($newTarget,$code) {
      return new ChangeTarget($this->_pdo,$newTarget,$code);
   }
   
}