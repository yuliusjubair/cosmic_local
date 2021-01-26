<?php
// simple encryption by ravidhr
class Mxencryption {

  //private $secret_key;

  //public function __construct() {
    //$this->secret_key = substr(md5(base64_encode('ini_kunci_rahasia_statis')),16);
  //}

  public function genrandomstring($length) {
    $res = "";
    for($i=0;$i<$length;$i++) {
      $x = mt_rand(1,3);
      if($x === 1) {
        $res .= chr(mt_rand(48,57));
      } elseif($x === 2) {
        $res .= chr(mt_rand(97,122));
      } else {
        $res .= chr(mt_rand(65,90));
      }
    }
    return $res;
  }

  public function encrypt($msg) {
    $secret_key = substr(md5(base64_encode('cosmicsystem')),16);
    $enc_msg = "";
    $enc_key = "";
    $random_key = $this->genrandomstring(16);
    for($i = 0;$i < strlen($msg);$i++) {
      $enc_msg .= chr(ord($msg[$i]) ^ ord($random_key[$i % strlen($random_key)]));
    }
    for($j = 0;$j < 16; $j++) {
      $enc_key .= chr(ord($random_key[$j]) ^ ord($secret_key[$j]));
    }
    return bin2hex($enc_key.$enc_msg);
  }

  public function decrypt($msg) {
      try {
        $secret_key = substr(md5(base64_encode('cosmicsystem')),16);
        $msg = pack('H*',$msg);
        $enc_key = substr($msg,0,16);
        $enc_msg = substr($msg,16);
    
        $real_key = "";
        $dec_msg = "";
        for($i = 0;$i < 16;$i++) {
          $real_key .= chr(ord($enc_key[$i]) ^ ord($secret_key[$i]));
        }
    
        for($j=0;$j<strlen($enc_msg);$j++) {
          $dec_msg .= chr(ord($enc_msg[$j]) ^ ord($real_key[$j % strlen($real_key)]));
        }
        return $dec_msg;
    }catch (Exception $error) {
        return 0;
    }
  }
}

