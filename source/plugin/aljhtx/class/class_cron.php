<?php

/**
 *定时任务类
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class Cron{

    public $task;

    public function __construct($task) {
        $this->task = (object)$task;
    }

    /**
     * 将任务推送到任务表
     *
     * @return int 返回任务ID
     */
    public function push(){
        global $_G;
        if(!$this->task->uid){
            $this->task->uid = $_G['uid'];
        }
        $this->task->status = 0;
        $this->task->dateline = TIMESTAMP;
        return DB::insert('aljhtx_cron', (array)$this->task, true);
    }

    /**
     * 将任务状态设置为已处理
     *
     * @return int 返回影响行数
     */
    public function pull(){
        return DB::query('update %t set status=2, success=%d where id=%d and status=1', array('aljhtx_cron', 2 , $this->task->id));
    }

    /**
     * 执行任务
     *
     * @return void
     */
    public function action(){
        if(DB::query('update %t set status=1, success=%d where id=%d and status=0', array('aljhtx_cron', 1, $this->task->id))){
            //debug($this->task);
            if($this->task->type == 'aljdx'){
                if($this->pull()) {
                    self::sms($this->task->pid, $this->task->content);
                }
            }
            if($this->task->type == 'bindtemplate'){
                if($this->pull()){
                    self::sendTemplate($this->task->param, $this->task->touid, $this->task->content);
                }
            }
        }
    }

    /**
     * 获取任务对象
     *
     * @param array $task 任务记录
     * @return void
     */
    public static function getCron($task = array()){
        if(!$task){
            $task = DB::fetch_first('select * from %t where status=0 and sync=0', array('aljhtx_cron'));
            $task['param'] = unserialize($task['param']);
        }
      return new Cron($task);
    }

    /**
     * 开始执行任务
     *
     * @param array $task 任务记录
     * @return void
     */
    public function start($task = array()){
        $cron = self::getCron($task);
        $cron->action();
    }

    /**
     * 开始执行任务
     *
     * @param int $cronId 任务ID
     * @return void
     */
    public static function getCronByid($cronId){
        $cron =  DB::fetch_first('select * from %t where id=%d', array('aljhtx_cron', $cronId));
        $cron['param'] = unserialize($cron['param']);
        DB::query('update %t set status=2, success=%d where id=%d', array('aljhtx_cron', 1, $cron['id']));
        return T::arrayToobject($cron);
    }

    /**
     * 发送手机短信息
     *
     * @param int $mobile 手机号
     * @param string $content 短信内容
     * @return void
     */
    public static function sms($mobile, $content){
        if(preg_match('#^\d[\d-]{3,20}\d$#', $mobile)) {
            require_once 'source/plugin/aljdx/function_dx.php';
            //1.手机号 2.插件标识 3.短信模板标识符 4.短信模板参数数组
            sendsmsbyvar($mobile, 'aljhtx', 'send', array('username' => $content.lang("plugin/aljhtx","class_cron_php_3")));
        }
    }

    public function sendTemplate($params, $uid, $url){
        global $_G;
        if(!in_array(strtolower(substr($url, 0, 6)), array('http:/', 'https:', 'ftp://'))){
            $mapp_wechat = unserialize($_G['setting']['mapp_wechat']);
            $wxurl = $mapp_wechat['wxurl'] ? $mapp_wechat['wxurl'] : $_G['siteurl'];
            $wxurl = trim($wxurl, '/').'/';
            $url = $wxurl.$url;
        }
        $openid = DB::result_first('select openid from %t where uid=%d', array('aljwsq_mapp_user', $uid));
        $bindTemplate = DB::fetch_first('select * from %t where type = %s', array('aljhtx_wechat_bindtemplate', $params['type']));
        $bindTemplateParam = unserialize($bindTemplate['param']);
        $bindTemplateConfig = include('source/plugin/aljhtx/config/bind_template.php');
        $type = $params['type'];
        $news_type = $params['news_type'];
        $logo = $params['logo'];
        $params['type'] = $bindTemplateConfig[$params['type']]['name'];
        foreach($bindTemplateParam as $k => $v){
            if($params[$v['value']]){
                $bindTemplateParam[$k]['value'] = $params[$v['value']];
            }else{
                $bindTemplateParam[$k]['value'] = $v['value'];
            }
        }
        $data = array(
            'template_id' => $bindTemplate['template_id'],
            'url'  => $url,
            'topcolor'  => '#ff0000',
            'data'  => T::ajaxPostCharSet($bindTemplateParam),
            'touser' => $openid
        );
        $template = DB::fetch_first('select * from %t where template_id = %s', array('aljhtx_wechat_template', $bindTemplate['template_id']));
        $new = str_replace("{{first.DATA}}", lang("plugin/aljhtx","class_cron_php_1").'{{first.DATA}}', $template['content']);
        $new = str_replace("{{remark.DATA}}", lang("plugin/aljhtx","class_cron_php_2").'{{remark.DATA}}', $new);
        $new = explode("\n", $new);

        foreach($new as $key => $value){
            $value = trim($value);
            if($value){
                $value = str_replace('{{', '', $value);
                $value = str_replace('.DATA}}', '', $value);
                $newContent[$key] = explode(lang("plugin/aljhtx","class_cron_php_4"), $value);
            }
        }
        $param = T::ajaxGetCharSet($data['data']);
        foreach($newContent as $k => $v){
            if($v[1] == 'first' || $v[1] == 'remark'){
                $newContent[$k][0] = '';
            }
            $newContent[$k][1] = $param[$v[1]];
        }

        DB::update('aljhtx_wechat_template', array('last_content' => serialize(T::ajaxGetCharSet($data))), array('template_id' => $bindTemplate['template_id']));
        require_once DISCUZ_ROOT . './source/plugin/aljwsq/mapp_wechatclient.lib.class.php';
        $wechat_client = new WeChatClient($_G['cache']['plugin']['aljwsq']['appid'], $_G['cache']['plugin']['aljwsq']['appsecret']);
        $result = $wechat_client -> sendtemplate($data);
        if($result){
            DB::query('update %t set succeed_times = succeed_times + 1, times = times + 1, last_time=%d  where template_id = %s', array('aljhtx_wechat_template', TIMESTAMP, $bindTemplate['template_id']));
        }else{
            DB::query('update %t set times = times + 1, last_time=%d where template_id = %s', array('aljhtx_wechat_template', TIMESTAMP, $bindTemplate['template_id']));
        }
        $insertdata = array(
            'touser' => $data['touser'],
            'template_id' => $bindTemplate['template_id'],
            'url'  => $url,
            'dateline'  => TIMESTAMP,
            'status'  => $result ? 1 : 0,
            'template'  => serialize(T::ajaxGetCharSet($data)),
            'param'  => serialize($newContent),
            'a'  => diconv($wechat_client->error(), 'UTF-8'),
        );
        C::t('#mapp_template#aljwsq_mapp_template_log')->insert($insertdata);
        $user = getuserbyuid($uid);
        $str = '';
        foreach($params as $k => $v){
            if($v){
                $str.=$bindTemplateConfig[$type]['cols'][$k].lang("plugin/aljhtx","class_cron_php_5").$v.PHP_EOL;
            }
        }
        DB::insert('aljhtx_notification', array(
            'uid' => $uid,
            'username' => $user['username'],
            'param' => serialize($params),
            'content' => $str,
            'type' => $type,
            'dateline' => TIMESTAMP,
            'url' => $url,
            'news_type' => $news_type,
            'logo' => $logo,
        ));
        if($_G['cache']['plugin']['aljbdx']['aljol'] || $_G['cache']['plugin']['aljgwc']['aljol']){
            $this->aljbd_to_aljol($uid, $type);
        }
    }
    /**
     * 推送信息至在线聊天首页
     *
     * @param int $uid 接收人
     * @param string $type 通知类型
     * @return void
     */
    public function aljbd_to_aljol($uid, $type){
        if($type == 'deliveryNotificationUser'){
            $this->cron_news_aljol($uid,lang("plugin/aljhtx","class_cron_php_6"),lang("plugin/aljhtx","class_cron_php_7"),1);
        }else if($type == 'pickUpCode'){
            $this->cron_news_aljol($uid,lang("plugin/aljhtx","class_cron_php_8"),lang("plugin/aljhtx","class_cron_php_9"),1);
        }else if($type == 'groupBuyingCoupon'){
            $this->cron_news_aljol($uid,lang("plugin/aljhtx","class_cron_php_10"),lang("plugin/aljhtx","class_cron_php_11"),1);
        }

    }
    public function cron_news_aljol($friendid,$chat,$username,$type=0) {
        $friendrecord = DB::fetch_first('select * from %t where friendid=%d and uid=%d and type=%d',array('aljol_news',$friendid,'-1000'.$type,$type));
        if(empty($friendrecord)) {
            DB::insert('aljol_news',array(
                'uid'=>'-1000'.$type,
                'username'=>$username,
                'friendid'=>$friendid,
                'datetime'=>TIMESTAMP,
                'lastnews'=>$chat,
                'type'=>$type,
            ));
        }else {
            DB::update('aljol_news',array('datetime'=>TIMESTAMP,'lastnews'=>$chat),array('id'=>$friendrecord['id']));
        }
    }
}