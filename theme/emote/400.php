<?php
if (! $this instanceof \Katmore\Shrt\Error ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$this->getThemeUrl()?>/frown.jpg"><br>
      <h1>400: Bad Request</h1>
   </body>
</html>