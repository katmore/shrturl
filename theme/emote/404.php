<?php
if (! $this instanceof \Katmore\Shrt\Error ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$this->getThemeUrl()?>/frown.jpg"><br>
      <h1>404: Not Found</h1>
      <p>This short URL has not been created.</p>
   </body>
</html>