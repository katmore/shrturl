<?php
if (! $this instanceof \Katmore\Shrt\Error ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$this->getThemeUrl();?>/angry_unicorn.png"><br>
      <h1>400: Bad Request</h1>
      <p>you have not requested anything that we understand</p>
   </body>
</html>