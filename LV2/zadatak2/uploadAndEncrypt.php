<?php
$files = @$_FILES["files"];


if($files["name"] != '') {

  $newPath = $_SERVER['DOCUMENT_ROOT'] ."/zad2/encoded/";
  $fullPath = $_REQUEST["path"].$files["name"]; 

      $imageString = file_get_contents($files['tmp_name']); // we send raw image that is temporarily in $files['tmp_name']
	  
	  $cipher = "AES-256-CBC"; //type of the algorithm for crypting
	  $key="veryweakkey"; // crypting key
      $iv='initVector123456';// initialisation vector needed for the algorithm with min length of 16 characters
  
     $encryptedImage = openssl_encrypt($imageString, $cipher, $key, 0, $iv); //encyption function

      $myfile = fopen("./encoded/"."$fullPath.aes256", "w") or die("Unable to open file!"); // creation of new file with extension .aes256 in which crypted key and base64 coded string are written
      fwrite($myfile, $encryptedImage);// writing in the created file
      fclose($myfile);

      echo("<br><br>Upload succesfull!<br><br>");
      //echo "<h1><a href='$fullPath'>OK-Click here!</a></h1>";
    
}
echo 
'<html>
<head>
<title>Upload files...</title>
</head>
<body>
<form method=POST enctype="multipart/form-data" action="">
<input type=text name=path accept = "encoded/jpeg, encoded/png, application/pdf">
<input type="file" name="files" accept="image/jpeg, image/png, application/pdf">
<input type=submit value="Up">
</form>
</body></html>';
?>