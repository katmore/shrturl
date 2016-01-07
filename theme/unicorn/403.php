<?php
if (! $this instanceof \Katmore\Shrt\Error ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$this->getThemeUrl();?>/angry_unicorn.png"><br>
      <h1>403: Forbidden</h1>
      <p>you are not allowed to create short URLs</p>
   </body>
</html>