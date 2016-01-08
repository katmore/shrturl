<?php
if (!isset($themeData) || !$themeData instanceof \Katmore\Shrt\ThemeData ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$themeData->themeURL?>/frown.jpg"><br>
      <h1>400: Bad Request</h1>
   </body>
</html>