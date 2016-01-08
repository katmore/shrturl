<?php
namespace Katmore\Shrt;
class CodeGeneratorExhausted extends \Exception {
   public function getMaxAttempts() {
      return $_maxAttempts;
   }
   private $_maxAttempts;
   public function __construct($maxAttempts) {
      $this->_maxAttempts = $maxAttempts;
      parent::__construct("unable to generate a unique short code after $maxAttempts attempts");
   }
}