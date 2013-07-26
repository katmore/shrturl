<?php
/**
 * JSON Request Example:
 *    NOTE: This is NOT part of the shrturl service. This is an example of how to USE the service.
 * 
 * 
 * 
 * 
 * 
 */

/*
 * change to URL where your shrturl service is deployed
 */
$requesturl_base = "https://www.example.com/shrt/";

/*
 * this is the domain name SHRT
 * use "default" if you are unsure
 *    this only affects the URL returned, use 
 */
$requesturl_url_base = "default";
/*
 * the number of targets to shorten at a time
 */
$num_targets = 5;
 
if (isset($_POST["submit"])) {
   if (isset($_POST["target"]))
   if (is_array( $_POST["target"] )) {
   //form it into a 'request'
      $request["shrt"]["target"] = array();
      foreach($_POST["target"] as $target) {
         $request["shrt"]["target"][] = $target;
      }
   //serialize to json
      $requestdoc = json_encode($request);
   //form requesturl
      $requesturl = "$requesturl_base?POST&url_base=$requesturl_url_base";
   //read response
      $requestfullpath = $requesturl."&request=".urlencode($requestdoc);
      
      $responsedoc = file_get_contents($requestfullpath);
      $response = json_decode($responsedoc,true);
   }
}

$shorturl = array();
$target = array();
//populate shorturl
for ($i = 0;$i<$num_targets;$i++) {
   if (
         (isset($response["shrt"]["shorturl"][$i])) &&
         (isset($response["shrt"]["target"][$i]))
      )
    {
      $shorturl[$i] = $response["shrt"]["shorturl"][$i];
      $target[$i] = $response["shrt"]["target"][$i];
   } else {
      $shorturl[$i] = "";
      $target[$i] = "";
   }
}

?>
<!DOCTYPE HTML>
<html>
   <head>
      <title>example shrturl request using json document</title>
   </head>
   <body>
   <script type="text/javascript">
//http://ntt.cc/wp-content/uploads/2008/01/copytoclipboard.js
   </script>
   <h1>example shrturl request using json</h1>
      <form method="POST" action="" name="shrt">
      <table>
         <tr>
            <td>target url
            <td>short url
            <td>&nbsp;
<?php
   for($i=0;$i<$num_targets;$i++) {
?>
         <tr>
            <td>
               <input size="40" type="text" name="target[]" value="<?php echo $target[$i]; ?>">
            <td>
               <input size="40" type="text" name="url_<?php echo $i;?>" value="<?php echo $shorturl[$i]; ?>">
            <td>
               <input type='button' name='copylink' onclick="CopyToClipboard_<?php echo $i; ?>()" value="Copy Link">
<?php
   }
?>
         <tr>
            <td>
         <input type="submit" name="submit" value="submit">
            <td>&nbsp;
      </table>
      </form>
      <p>Shorten multiple targets at a time with shrturl using a json request.
      <p>This form does not submit directly to the shrturl service.
      <p>It submits it to this page, which will process the form into a shrturl json request.
      <p>The page then submits to the shrturl service, and displays the results in the form.
      <p>View the source of this file to see how it works.
      
      <p><strong>request document given to shrturl:</strong>
     
<p><textarea cols="80" rows="5"><?php if (isset($requestdoc)) echo nl2br(htmlentities($requestdoc)); ?></textarea>



      <p><strong>response document received from shrturl:</strong>    
<p><textarea cols="80" rows="5"><?php if (isset($responsedoc)) echo nl2br(htmlentities($responsedoc)); ?></textarea>

      <p><strong>full URL used for request:</strong>    
<p><textarea cols="80" rows="5"><?php if (isset($requestfullpath)) echo nl2br(htmlentities($requestfullpath)); ?></textarea>
 
      
   </body>
</html>