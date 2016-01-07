<?php
if (! $this instanceof \Katmore\Shrt\Error ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$this->getThemeUrl()?>/frown.jpg"><br>
      <h1>403: Forbidden</h1>
      <p>Not allowed to create short URLs.</p>
   </body>
</html>