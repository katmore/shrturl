<?php
if (!isset($themeData) || !$themeData instanceof \Katmore\Shrt\ThemeData ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$themeData->themeURL?>/angry_unicorn.png"><br>
      <h1>400: Bad Request</h1>
      <p>you have not requested anything that we understand</p>
   </body>
</html>