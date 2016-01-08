<?php
namespace Katmore\Shrt;

class Factory {
   private $_pdo;
   
   public function __construct(
      \PDO $pdo
   ) {
      $this->_pdo = $pdo;
   }
   /**
    * @return \Katmore\Shrt\Target
    */
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
   /**
    * @return \PDO
    */
   public static function loadPDO($config) {
      if (isset($config['options']) && is_array($config['options'])) {
         $options = $config['options'];
      }else {
         $options=[];
      }
      if (!isset($options[\PDO::ATTR_ERRMODE]) || $options[\PDO::ATTR_ERRMODE]!=\PDO::ERRMODE_EXCEPTION) {
         $options[\PDO::ATTR_ERRMODE]=\PDO::ERRMODE_EXCEPTION;
      }
      return new \PDO(
            $config["dsn"],
            $config["user"],
            $config["pass"],
            $options
      );      
   }
   
}