<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/27
 * Time: 14:47
 */
namespace Org\Util;

use Think\Exception;

class Decrypt
{
    public $key_2;
    public $td;
    public $ks;
    public $iv;
    public $salt;
    public $filepath;
    public function __construct(){
        $this->filepath="./licence.txt";
        $this->salt="$^%&*~!M";//盐值，用以提高密文的安全性
    }
    /**
     * 对密文进行解密
     * @param $key 密钥
     */
    public function decode($key) {
        $this->td = mcrypt_module_open(MCRYPT_DES,'','ecb',''); //使用MCRYPT_DES算法,ecb模式
        $size=mcrypt_enc_get_iv_size($this->td);//设置初始向量的大小
        $this->iv = mcrypt_create_iv($size, MCRYPT_RAND);//创建初始向量
        $this->ks = mcrypt_enc_get_key_size($this->td);
        try {
            if (!file_exists($this->filepath)){
                throw new Exception("授权文件不存在");
            }else{
                $fp=fopen($this->filepath,'r');
                $secret=fread($fp,filesize($this->filepath));
                $this->key_2 = substr(md5(md5($key).$this->salt),0,$this->ks);
                //初始解密处理
                mcrypt_generic_init($this->td, $this->key_2, $this->iv);
                //解密
                $decrypted = mdecrypt_generic($this->td, $secret);
                //解密后,可能会有后续的\0,需去掉
                $decrypted=trim($decrypted) . "\n";
                //结束
                mcrypt_generic_deinit($this->td);
                mcrypt_module_close($this->td);
                return $decrypted;
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }
}