<?php
namespace Shrturl;
class Code {
   /**
    * @var string Shrt Code
    */
   protected $_code;
   /**
    * @var string Target URL
    */
   private $_targetURL;
   public function getTargetURL() {
      return $this->_targetURL;
   }
   /**
    * Returns a formatted short code. Returns only the short code if the substring specification
    *    is invlaid.
    *    
    * @return string
    * @param string $format Specifies the code format. To be a valid format the substring
    *    "%code%" must exist; it is replaced with the object's corresponding short code.
    */
   public function formatCode($format="%code%") {
      if (empty($format) || !is_string($format) || (false===strpos($format,"%code%"))) return $this->_code;
      return str_replace("%code%",$this->_code,$options['format']);
   }
   /**
    * @param \PDO $pdo Connected pdo class
    * @param 
    */
   public function __construct(\PDO $pdo,$targetUrl) {
      if (!is_string($targetUrl) || empty($targetUrl)) return;
      $this->_targetURL = $targetUrl;
      $stmt = $pdo->prepare("
      SELECT
      code
      FROM
      url
      WHERE
      md5=md5(:target)
      ");
      $stmt->execute([":target"=> $targetUrl]);
      if ($stmt->rowCount()) {
         $this->_code = $stmt->fetch(\PDO::FETCH_ASSOC)['code'];
      } else {
         $pdo->startTransaction();
         try {
            $stmt = $pdo->query("
            SELECT
               code
            FROM
               url
            WHERE
               md5
            IS NULL ORDER BY id ASC LIMIT 0,1 FOR UPDATE
            ");
            $this->_code = $stmt->fetch(\PDO::FETCH_ASSOC);
            $stmt = $pdo->prepare("UPDATE url SET md5=md5(:target),target=:target WHERE code=:code");
            $stmt->execute([":target"=>(string) $targetUrl,":code"=> (string) $this->_code]);
         } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
         }
         $pdo->commit();
      }
   }
}