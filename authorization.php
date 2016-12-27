<?php

const PRIVATE_KEY = 'private_2048.key';
function public_RSA2048_encrypt($plaintext,$public_key_path)//for client side
{
    $fp = fopen($public_key_path, "r");
    $pub_key = fread($fp, 8192);
    fclose($fp);
    $pub_key_res = openssl_get_publickey($pub_key);
    if(!$pub_key_res) {
        throw new Exception('Public Key invalid');
    }
    openssl_public_encrypt($plaintext,$crypttext, $pub_key_res);
    openssl_free_key($pub_key_res);
    return(base64_encode($crypttext));
}
function public_RSA2048_decrypt($encryptedext,$public_key_path)//for client side,to decode server data
{//server only
    $fp = fopen($public_key_path, "r");
    $public_key = fread($fp, 8192);
    fclose($fp);
    $public_key_res = openssl_pkey_get_public($public_key);
    //echo $public_key;
    // $private_key_res = openssl_get_privatekey($priv_key, PASSPHRASE); // 如果使用密碼
    if(!$public_key_res) {
        throw new Exception('Public Key invalid');
    }
    if(openssl_public_decrypt(base64_decode($encryptedext), $decrypted, $public_key_res)==FALSE)
    {
	    while ($msg = openssl_error_string())
	    {
	        echo $msg; 
	    }
	    echo "<BR><BR>";
		echo "return false";
	}
    openssl_free_key($public_key_res);
    echo "gg result:".$decrypted;
    return $decrypted;
    /*$decrypted = '';
    $public_key = openssl_pkey_get_public($public_key1);
    $array_data = toArray($encryptedext);
    $str = '';
    foreach ($array_data as $value) {
        openssl_public_decrypt($value, $decrypted, $public_key);
        $str .= $decrypted;
    }
    return base64_decode($str);*/
}

function private_RSA2048_decrypt($encryptedext){//server only
    $fp = fopen(PRIVATE_KEY, "r");
    $priv_key = fread($fp, 8192);
    fclose($fp);
    $private_key_res = openssl_get_privatekey($priv_key);
    // $private_key_res = openssl_get_privatekey($priv_key, PASSPHRASE); // 如果使用密碼
    if(!$private_key_res) {
        throw new Exception('Private Key invalid');
    }
    openssl_private_decrypt(base64_decode($encryptedext), $decrypted, $private_key_res);
    openssl_free_key($private_key_res);
    return $decrypted;
}

function private_RSA2048_encrypt($plaintext)//server only,server encrypt,client using public key to decode
{
	$fp = fopen(PRIVATE_KEY, "r");
    $pri_key = fread($fp, 8192);
    fclose($fp);

   	$pri_key_res = openssl_pkey_get_private($pri_key);
    if(!$pri_key_res) {
        throw new Exception('Private Key invalid');
    }
    openssl_private_encrypt($plaintext,$crypttext, $pri_key_res);
    openssl_free_key($pri_key_res);
    //echo "OK".base64_encode($crypttext);
    return(base64_encode($crypttext));

/*
	$encrypted = '';
	echo $plaintext;
    $private_key = openssl_pkey_get_private($pri_key);
    $fstr = array();
    $array_data = splitEncode($plaintext);
    foreach ($array_data as $value) {
        openssl_private_encrypt($value, $encrypted, $private_key);
        $fstr[] = $encrypted;
    }
    return base64_encode(serialize($fstr));
    //return "";*/
}

//--------------------AES256 begin------------------------------------------------------
const AES_KEY = "1234567890123456";
const AES_IV  = "6543210987654321";

function AES256_encrypt($plaintext)
{
	$encrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, AES_KEY, $plaintext, MCRYPT_MODE_CBC, AES_IV);
	$encrypt_text = base64_encode($encrypt);
	return $encrypt_text;
}
function AES256_decrypt($encryptedext)
{
	$data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, AES_KEY, base64_decode($encryptedext), MCRYPT_MODE_CBC, AES_IV);
	return $data;
}
//--------------------AES256 end--------------------------------------------------------

    function splitEncode($data)
    {
        $data = base64_encode($data);
        $total_lenth = strlen($data);
        $per = 96;
        $dy = $total_lenth % $per;
        $total_block = $dy ? ($total_lenth / $per) : ($total_lenth / $per - 1);
        for ($i = 0; $i < $total_block; $i++) {
            $return[] = substr($data, $i * $per, $per);
        }
        return $return;
    }

    
    function toArray($data)
    {
        $data = base64_decode($data);
        $array_data = unserialize($data);
        if (!is_array($array_data)) {
        	echo "YYYYYYYYY";
            throw new Exception('not match');
        }
        return $array_data;
    }

?>