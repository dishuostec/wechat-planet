<?php

class Cookie extends CApplicationComponent
{
    public $salt = '';
    private $key = '';

    public function init()
    {
        $this->key = sha1($this->salt);
        parent::init();
    }

    public function set($key, $value = NULL,
                        $expiretime = 2592000)
    {
        $value = base64_encode(self::enCrypt($value));
        $cookie = new CHttpCookie($key, $value);
        $cookie->expire = time() + $expiretime;
        Mod::app()->request->cookies[$key] = $cookie;
    }

    public function get($key)
    {
        $cookie = Mod::app()->request->cookies[$key];
        $value = is_null($cookie) ? '' : base64_decode($cookie->value);
        return self::deCrypt($value);
    }

    public function del($key)
    {
        $cookie = new CHttpCookie($key, NULL);
        $cookie->expire = time() - 1;
        Mod::app()->request->cookies[$key] = $cookie;
    }

    public function enCrypt($input)
    {
        $key = $this->key;

        $td = mcrypt_module_open('tripledes', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $key = substr($key, 0, mcrypt_enc_get_key_size($td));
        mcrypt_generic_init($td, $key, $iv);
        $encrypted_data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $encrypted_data;
    }

    public function deCrypt($input)
    {
        if (empty($input)) {
            return '';
        }

        $key = $this->key;

        $td = mcrypt_module_open('tripledes', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $key = substr($key, 0, mcrypt_enc_get_key_size($td));
        mcrypt_generic_init($td, $key, $iv);
        $decrypted_data = mdecrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return trim($decrypted_data);
    }
}