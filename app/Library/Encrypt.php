<?php

namespace App\Library;
use Hash;

class Encrypt {

    private static $privateKey = 'U2ogeeGL6FLYAQ6iX1PiXVRllOuCaCZr0sRyRozFfqegDoJf9JbETePtsnrg';
    public static function encrypt($string){
    	$string = time().'-'.$string;
        $encryptedString=openssl_encrypt($string, 'AES-128-ECB', self::$privateKey);
        return $encryptedString;
    }

    public static function decrypt($string){
        $decryptedString=openssl_decrypt($string, 'AES-128-ECB', self::$privateKey);
        list($time, $decryptedString) = array_pad(explode('-', $decryptedString),2,null);
        return $decryptedString;
    }

    public static function encryptIt($string, $encryptionKey=null){
        if ($encryptionKey == null) {
            $encryptionKey = time();
        }
        $hashedKey = Hash::make($encryptionKey);
        $string = $hashedKey.'-'.time().'-'.$string;
        $encryptedString=openssl_encrypt($string, 'AES-128-ECB', self::$privateKey);
        return $encryptedString;
    }

    public static function decryptIt($string, $encryptionKey=null){
        $decryptedString=openssl_decrypt($string, 'AES-128-ECB', self::$privateKey);
        list($hashedKey, $timestamp, $decryptedString) = array_pad(explode('-', $decryptedString),3,null);
        if (!empty($encryptionKey)) {            
            if (!Hash::check($encryptionKey, $hashedKey)){
                return false;
            }
        }
        return $decryptedString;
    }

    public static function validateIt($string, $encryptionKey=null){
        $decryptedString=openssl_decrypt($string, 'AES-128-ECB', self::$privateKey);
        list($hashedKey, $timestamp, $decryptedString) = array_pad(explode('-', $decryptedString),3,null);
        if (!empty($encryptionKey)) {            
            if (Hash::check($encryptionKey, $hashedKey)){
                return true;
            }
        }
        return false;
    }
}
