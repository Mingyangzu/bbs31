<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

USE OSS\OssClient;
USE OSS\Core\OssException;

require "source/plugin/aljoss/autoload.php";

Class Oss{
    public $Access_Key;
    public $Secret_Key;
    public $bucket;
    public $endpoint;
    public $auth;
    public function __construct(){
        $this->init();
    }

    public function init(){
        global $_G;
        $config_aljoss = $_G['cache']['plugin']['aljoss'];
        $this->Access_Key   = $config_aljoss['Access_Key'];
        $this->Secret_Key   = $config_aljoss['Secret_Key'];
        $this->bucket       = $config_aljoss['bucket'];
        $this->endpoint     = $config_aljoss['endpoint'];
        $this->auth     = new OssClient($this->Access_Key,$this->Secret_Key,$this->endpoint);
    }


    public function upload($dst,$src){
        //获取对象
        $auth = new OssClient($this->Access_Key,$this->Secret_Key,$this->endpoint);
        try {
            //上传图片
            $result  = $auth->uploadFile($this->bucket,$dst,$src);
            return $result;
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }


    public function uploadVideo($dst,$src){
        $ossClient = new OssClient($this->Access_Key,$this->Secret_Key,$this->endpoint);
        try{
            $result = $ossClient->multiuploadFile($this->bucket,$dst,$src);
            return $result['info']['url'];
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ":  OK" . "\n");

    }
    public function delete($file){
        global $_G;
        $config_aljoss = $_G['cache']['plugin']['aljoss'];
        $object = str_replace('http://'.$config_aljoss['domain'].'/', '', $file);
        $object = str_replace('https://'.$config_aljoss['domain'].'/', '', $object);
        $object = str_replace('http://'.$config_aljoss['cdn_domain'].'/', '', $object);
        $object = str_replace('https://'.$config_aljoss['cdn_domain'].'/', '', $object);
        $this->auth->deleteObject($this->bucket, $object);
    }

}