<?php
namespace Katmore\Shrt;
class CodeGenerator extends Code {
   
   protected static function _randInt($min,$max) {
      return mt_rand($min,$max);
   }
   const max_attempts = 100;
   public function __construct(\PDO $pdo,$len=3,$charpool="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789") {
      for($a=0;$a<self::max_attempts;$a++) {
         $code ="";
         for ($l=0;$l<$len;$l++) {
            $code .=$charpool[self::_randInt(0, (strlen($allvalid) - 1)) ];
         }
         echo $code;
         try {
            $stmt = $pdo->prepare("INSERT INTO url SET code=:code");
            $stmt->execute(['code'=>$code]);
            $this->_code = $code;
            return;
         } catch (\PDOException $e) {
            if (!$e->errorInfo[1] == 1062) {
               throw $e;
            }
         }
      }
      throw new CodeGeneratorExhausted(self::max_attempts);
   }
}