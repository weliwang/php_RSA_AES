<?php
include("authorization.php");
if($_FILES["file"]["error"]>0)
{
	echo "Error:".$_FILES["file"]["error"]."<hr>";
}
else
{
	/*echo "file name:".$_FILES["file"]["name"]."<br>";
	echo "file type:".$_FILES["file"]["type"]."<br>";
	echo "file size:".$_FILES["file"]["size"]."<br>";
	echo "file temp name:".$_FILES["file"]["tmp_name"]."<br>";
	echo "<hr>";*/
	if(empty( $_POST[encode])==false)//this is for client
	{
		$r = public_RSA2048_encrypt($_POST[encode],$_FILES["file"]["tmp_name"]);
		echo "RSA2048 public key Encode result:".$r."<hr>";
	}
	if(empty( $_POST[decode_ver2])==false)//this is for client
	{
		$r = public_RSA2048_decrypt($_POST[decode_ver2],$_FILES["file"]["tmp_name"]);
		echo "RSA2048 public key Decode result:".$r."<hr>";
	}
}
if(empty( $_POST[decode])==false)//this is for server
{
	$r = private_RSA2048_decrypt($_POST[decode]);
	echo "RSA2048 private key Decode result:".$r."<hr>";
}
if(empty( $_POST[encode_ver2])==false)//this is for server
{
	$r = private_RSA2048_encrypt($_POST[encode_ver2]);
	echo "RSA2048 private key Encode result:".$r."<hr>";
}
//---------------------AES256 function begin------------------------------------------
if(empty( $_POST[encodeAES256])==false)
{
	echo "app_cc_aes_key : ";
	var_dump(AES_KEY);
	echo '<br>';
	echo "app_cc_aes_iv :";
	var_dump(AES_IV);
	echo '<br>';
	$r = AES256_encrypt($_POST[encodeAES256]);
	echo "AES256 Encode result:".$r."<hr>";
	
}

if(empty( $_POST[decodeAES256])==false)
{
	echo "app_cc_aes_key : ";
	var_dump(AES_KEY);
	echo '<br>';
	echo "app_cc_aes_iv :";
	var_dump(AES_IV);
	echo '<br>';
	$r = AES256_decrypt($_POST[decodeAES256]);
	echo "AES256 Decode result:".$r."<hr>";
}
//---------------------AES256 function end------------------------------------------


//echo "Encode:". $_POST[decode];
//echo "<br>";
//echo "Decode:". $_POST[encode];
?>