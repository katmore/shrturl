<?php
/**
 * Demo AJAX webpage using the shrt URL service  
 */

/*
 * change to URL where your shrturl service (service.php) is deployed
 */
$requesturl = "/service.php";

/*
 * this is the domain name SHRT
 * use "default" if you are unsure
 *    this only affects the URL returned 
 */
$requesturl_url_base = "default";
/*
 * the number of targets to shorten at a time
 */
$num_targets = 5;

?>
<!DOCTYPE HTML>
<html>
   <head>
      <title>example shrturl request using json document</title>
   </head>
   <body>
   <script src="https://cdn.jsdelivr.net/clipboard.js/1.5.5/clipboard.min.js"></script>
   <h1>Shorten URL Demo</h1>
   <p>Use the 'inspect element' tool on your browser to understand how the Shrt URL Web Service works.</p> 
      <form method="POST" action="" name="shrt">
      <table>
         <tr>
            <td>target url
            <td>short url
            <td>&nbsp;
         <tr>
            <td>
               <input size="40" type="text" name="target-url">
            <td>
               <input size="40" type="text" name="short-url" id="shrt-url">
            <td>
               <button class="btn" data-clipboard-target="#shrt-url">
                   <img style="width:40px;" src="https://upload.wikimedia.org/wikipedia/commons/9/91/Octicons-clippy.svg" alt="Copy to clipboard">
               </button>
         <tr>
            <td>
         <input type="submit" name="submit" value="Shorten">
            <td>&nbsp;
      </table>
      </form>
           
   </body>
</html>