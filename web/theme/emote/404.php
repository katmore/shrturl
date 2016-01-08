<?php
if (!isset($themeData) || !$themeData instanceof \Katmore\Shrt\ThemeData ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$themeData->themeURL?>/frown.jpg"><br>
      <h1>404: Not Found</h1>
      <p>This short URL has not been created.</p>
   </body>
</html>