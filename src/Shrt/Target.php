<?php
namespace Katmore\Shrt;
class Target {
   protected $_targetURL;
   public function getTargetURL() {
      return $this->_targetURL;
   }
   public function __toString() {
      return (string) $this->_targetURL;
   }
   public function __construct(\PDO $pdo,$code) {
      $this->_targetURL=null;
      $stmt = $pdo->prepare("
      SELECT
         target
      FROM
         url
      WHERE
         code=:code
      AND
         target IS NOT NULL
      ");
      $stmt->bindValue(":code", $code, \PDO::PARAM_STR);
      $stmt->execute();
      if ($stmt->rowCount()<1) {
         return;
      }
      $this->_targetURL = $stmt->fetch(\PDO::FETCH_ASSOC)['target'];
   }
}