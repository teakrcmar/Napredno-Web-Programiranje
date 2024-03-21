<?php

 $cipher = "AES-256-CBC"; 
 $key="veryweakkey";
 $iv='initVector123456';
 $Path = $_SERVER['DOCUMENT_ROOT'] ."/zad2/encoded/";//location of the encripted images

 $decodedPath = $_SERVER['DOCUMENT_ROOT'] ."/zad2/decoded/";// location in which decoded images are stored

foreach (glob($Path."*.aes256") as $filepath) { // chosing images with extension .aes256 from the folder
	
	$codedImageString=file_get_contents($filepath); //loading of the crypted string
	
    $bs64decoded=base64_decode($codedImageString); // string is coded with base64 algorithm so we need to decode this before we send it to the AES decripting function
    $aesDecodedImage = openssl_decrypt($bs64decoded, $cipher, $key,OPENSSL_RAW_DATA, $iv);
	
	$position=strripos($filepath,'/'); //returns the last position of the wanted string  inside the given stringg
	
	    $fileName = substr($filepath, $position);
		$fileName = substr($fileName, 0,-7);	
	
	$handle = fopen($decodedPath.$fileName, "w");// takes fwrite reference

	fwrite( $handle, $aesDecodedImage);
	fclose($handle);
}
?>