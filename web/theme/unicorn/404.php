<?php
if (!isset($themeData) || !$themeData instanceof \Katmore\Shrt\ThemeData ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$themeData->themeURL?>/angry_unicorn.png"><br>
      <h1>404: Not Found</h1>
      <p>this short URL has not been created</p>
   </body>
</html>