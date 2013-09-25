<?php
class Tx_Meddevutils_Utils_Crypt {
    public $key = '';
    public $iv = '';
    public $algo = '';

    public function __construct($key,$iv, $algo = MCRYPT_TRIPLEDES)
    {
        $this->key = $key;
        $this->iv = $iv;
        $this->algo = $algo;
    }

    public function encrypt($str)
    {
        $text = $this->_pkcs5Pad($str,mcrypt_get_block_size($this->algo,'cbc'));
        $cipher = mcrypt_module_open($this->algo,'','cbc','');
        mcrypt_generic_init($cipher, $this->key, $this->iv);
        $encrypted = mcrypt_generic($cipher,$text);
        mcrypt_generic_deinit($cipher);
        return base64_encode($encrypted);
    }

    public function decrypt($str)
    {
        $cipher = mcrypt_module_open($this->algo,'','cbc','');
        mcrypt_generic_init($cipher, $this->key, $this->iv);
        $decrypted = mdecrypt_generic($cipher,base64_decode($str));
        mcrypt_generic_deinit($cipher);
        return $this->_pkcs5Unpad($decrypted);
    }

    protected function _pkcs5Pad($str, $blocksize)
    {
        $pad = $blocksize - (strlen($str) % $blocksize);
        return $str . str_repeat(chr($pad), $pad);
    }

    protected function _pkcs5Unpad($str)
    {
        $pad = ord($str{strlen($str)-1});
        if ($pad > strlen($str)) return false;
        if (strspn($str, chr($pad), strlen($str) - $pad) != $pad) return false;
        return substr($str, 0, -1 * $pad);
    }
}