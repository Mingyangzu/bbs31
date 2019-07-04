<?php

/**
 * 快递查询处理类
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Express{

    public $ekey;
    public $reqURL = 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';
    public $ebid;
    //快递公司
    public $kgs;
    //快递单号
    public $number;

    public function __construct($param) {
        $this->ekey = $param['ekey'];
        $this->ebid = $param['ebid'];

        if($param['kgs']){
            $this->kgs = $param['kgs'];
        }

        if($param['number']){
            $this->number = $param['number'];
        }
    }

    public function setKgs($kgs){
        $this->kgs = $kgs;
    }

    public function setNumber($number){
        $this->number = $number;
    }

    public function responseExpressInfo(){
        $logisticResult = $this->getOrderTracesByJson($this->kgs,$this->number);

        $data = json_decode($logisticResult,true);
        if($data['Success'] == true){//返回信息成功
            $str = "";
            for($i=0;$i<count($data['Traces']);$i++){
                $str .= lang("plugin/aljhtx","class_express_php_1").$data['Traces'][$i]['AcceptTime']."<br/>".lang("plugin/aljhtx","class_express_php_2").$data['Traces'][$i]['AcceptStation']."<br/>";
            }
            echo lang("plugin/aljhtx","class_express_php_3").$data['LogisticCode']."<br/>".lang("plugin/aljhtx","class_express_php_4") . "<br/>" . diconv($str, 'utf-8', CHARSET);
        }else{
            echo lang("plugin/aljhtx","class_express_php_5");
        }
    }


    /**
     * Json方式 查询订单物流轨迹
     *
     * @return string
     */
    public function getOrderTracesByJson(){
        global $_G;

        if($_G['cache']['plugin']['aljhtx']['expressType'] == 1){
            return $this->getOrderTracesByJsonAli($this->kgs,$this->number);
        }else {
            $requestData = "{'OrderCode':'','ShipperCode':'" . $this->kgs . "','LogisticCode':'" . $this->number . "'}";
            $datas = array(
                'EBusinessID' => $this->ebid,
                'RequestType' => '1002',
                'RequestData' => urlencode($requestData),
                'DataType' => '2',
            );
            $datas['DataSign'] = $this->encrypt($requestData, $this->ekey);
            $result = $this->sendPost($this->reqURL, $datas);
            return $result;
        }
    }
    /**
     * Json方式 查询订单物流轨迹
     *
     * @return string
     */
    public function getOrderTracesByJsonAli(){
        global $_G;
        $wuliu = DB::fetch_first('select * from %t where worderid = %s', array('aljbd_wuliu', $_GET['number']));

        if($wuliu['updatetime'] > TIMESTAMP - $_G['cache']['plugin']['aljhtx']['AppCacheTime']){
            return $wuliu['info'];
        }
        $host = "https://ali-deliver.showapi.com";
        $path = "/showapi_expInfo";
        $method = "GET";
        $appcode = $_G['cache']['plugin']['aljhtx']['AppCode'];
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "com=auto&nu=".$_GET['number'];
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $msg = json_decode(curl_exec($curl), true);
        if($msg['showapi_res_code'] === 0){
            $data['Success'] = true;
        }else{
            $data['Success'] = false;
        }

        foreach($msg['showapi_res_body']['data'] as $k =>$v){
            $tmp['AcceptTime'] = $v['time'];
            $tmp['AcceptStation'] = $v['context'];
            $data['Traces'][$k] = $tmp;
        }
        $data['Traces'] = array_reverse($data['Traces']);

        DB::update('aljbd_wuliu', array('updatetime' => TIMESTAMP, 'info' => json_encode($data)), array('worderid' => $_GET['number']));
        return json_encode($data);
    }
    /**
     * post提交数据, url响应返回的html
     *
     * @param string $url 请求Url
     * @param array $datas 提交的数据
     * @return string
     */
    public function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);
        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param string $data 内容
     * @param string  $ekey ekey
     * @return string
     */
    function encrypt($data, $ekey) {
        return urlencode(base64_encode(md5($data.$ekey)));
    }
}
