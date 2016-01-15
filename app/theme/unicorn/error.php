<?php
if (!isset($themeData) || !$themeData instanceof \Katmore\Shrt\ThemeData ) {return false;}
?>
<!DOCTYPE HTML>
<html>
   <head>
   </head>
   <body>
      <img src="<?=$themeData->themeURL?>/angry_unicorn.png"><br>
      <h1><?=$themeData->statusCode?>: <?=$themeData->statusString?></h1>
      <p><?=$themeData->infoMessage?></p>
   </body>
</html>