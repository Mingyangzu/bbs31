<?php

/**
 * Controller的微信处理模块
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class WechatAction{
    public $page;

    public function __construct() {
        global $requestPage;
        $this->page = $requestPage;
        if(!$this->page->config->mapp_template){
            $this->page->showMessage(lang("plugin/aljhtx","wechat_php_14"), '<a style="color: rgb(30, 159, 255);" target="_blank" href="http://addon.dismall.com/?@mapp_template.plugin">' . lang("plugin/aljhtx","wechat_php_1") . '</a>');
        }
        if(!$this->page->config->mapp_wechat){
            $this->page->showMessage(lang("plugin/aljhtx","wechat_php_15"), '<a style="color: rgb(30, 159, 255);" target="_blank" href="http://addon.dismall.com/?@mapp_wechat.plugin">' . lang("plugin/aljhtx","wechat_php_2") . '</a>');
        }
    }

    public function notify_url(){
        $req = 'cmd=_notify-validate';
        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
        $verify_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_notify-validate&' . http_build_query($_POST);
        $response = dfsockopen($verify_url);
        T::wlog('source/plugin/aljhtx/test.txt', $response);
        $header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30); // 沙盒用
        //$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30); // 正式用
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $mc_gross = $_POST['mc_gross ']; // 付款金额
        $custom = $_POST['custom ']; // 得到订单号

        if (!$fp) {
// HTTP ERROR
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                //T::wlog('source/plugin/aljhtx/test1.txt', $res);
                if (strcmp ($res, "VERIFIED") == 0) {
                    //验证通过，处理业务逻辑
                    //T::wlog('source/plugin/aljhtx/test1.txt', lang("plugin/aljhtx","wechat_php_16"));
                }
                else if (strcmp ($res, "INVALID") == 0) {
                    // 验证失败，可以不处理。
                }
            }
            fclose ($fp);
        }
    }

    public function test(){
        $this->page->display();
    }
    /**
     * 推送结果提示页
     *
     * @return void
     */
    public function showSuccessMessage(){
        $this->page->showMessage(lang("plugin/aljhtx","wechat_php_17"), '<a style="color: rgb(30, 159, 255);" href="plugin.php?id=aljhtx&c=wechat&a=sendmessage&template_id='.$this->page->get->template_id.'">' . lang("plugin/aljhtx","wechat_php_3") . '</a>');
    }

    /**
     * 同步结果提示页
     *
     * @return void
     */
    public function showImportMessage(){
        $this->page->showMessage(lang("plugin/aljhtx","wechat_php_18"), '<a style="color: rgb(30, 159, 255);" href="plugin.php?id=aljhtx&c=wechat&a=userlist">' . lang("plugin/aljhtx","wechat_php_4") . '</a>');
    }

    /**
     * 更新用户资料结果提示页
     *
     * @return void
     */
    public function showUpdateMessage(){
        $this->page->showMessage(lang("plugin/aljhtx","wechat_php_19"), '<a style="color: rgb(30, 159, 255);" href="plugin.php?id=aljhtx&c=wechat&a=userlist">' . lang("plugin/aljhtx","wechat_php_5") . '</a>');
    }

    /**
     * bindtemplate成功提示页
     *
     * @return void
     */
    public function showBindTemplateMessage(){
        $this->page->showMessage(lang("plugin/aljhtx","wechat_php_20"), '<a style="color: rgb(30, 159, 255);" href="plugin.php?id=aljhtx&c=wechat&a=bindtemplate&template_id='.$this->page->get->template_id.'&type='.$this->page->get->type.'">' . lang("plugin/aljhtx","wechat_php_6") . '</a>');
    }

    public function delete(){
        DB::delete('aljwsq_mapp_user', array('openid' => $this->page->get->openid));
        T::responseJson();
    }
    public function deletelog(){
        DB::delete('aljwsq_mapp_template_log', array('id' => $this->page->get->logid));
        T::responseJson();
    }
    public function log(){
        T::getObject('Aljwsq');
        $count = Aljwsq::countLogNum($this->page->get->search);
        $logList = Aljwsq::getLogList($this->page->get->search, $this->page->get->page>0 ? $this->page->get->page :1, 20);
        if($this->page->get->render == 'yes'){
            foreach($logList as $k => $v){
                $logList[$k]['dateline'] = dgmdate($v['dateline'], 'u');
                $logList[$k]['avatar'] = avatar($v['uid'], 'small', true);
                if($logList[$k]['param']){
                    $logList[$k]['param'] = dunserialize($logList[$k]['param']);
                    foreach($logList[$k]['param'] as $kp => $vp){
                        if($vp[0]){
                            $logList[$k]['content'] .= $vp[0].lang("plugin/aljhtx","wechat_php_21").'<span style="color:'.$vp[1]['color'].'">'.$vp[1]['value']."</span><br>";
                        }else{
                            $logList[$k]['content'] .= '<span style="color:'.$vp[1]['color'].'">'.$vp[1]['value']."</span><br>";
                        }

                    }
                }

                if($v['status'] == 1){
                    $logList[$k]['status'] = lang("plugin/aljhtx","wechat_php_22");
                }else{
                    $logList[$k]['status'] = $v['a'];
                }
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
        $this->page->assign('logList', $logList, true);
        $this->page->display();
    }
    public function bind_nofollow(){
        T::getObject('Aljwsq');
        $count = Aljwsq::countBindNoFollowUserNum($this->page->get->search);
        $logList = Aljwsq::getBindNoFollowUserList($this->page->get->search, $this->page->get->page>0 ? $this->page->get->page :1, 20);
        if($this->page->get->render == 'yes'){
            foreach($logList as $k => $v){
                $logList[$k]['bindtime'] = dgmdate($v['bindtime'], 'u');
                $logList[$k]['avatar'] = avatar($v['uid'], 'small', true);
                if($v['sex'] == 1){
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_23");
                }else if($v['sex'] == 2){
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_24");
                }else{
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_25");
                }

                $logList[$k]['subscribe_time'] = dgmdate($v['subscribe_time'], 'u');
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
        $this->page->assign('logList', $logList, true);
        $this->page->display();
    }

    public function bind_follow(){
        T::getObject('Aljwsq');
        $count = Aljwsq::countBindFollowUserNum($this->page->get->search);
        $logList = Aljwsq::getBindFollowUserList($this->page->get->search, $this->page->get->page>0 ? $this->page->get->page :1, 20);
        if($this->page->get->render == 'yes'){
            foreach($logList as $k => $v){
                $logList[$k]['bindtime'] = dgmdate($v['bindtime'], 'u');
                $logList[$k]['avatar'] = avatar($v['uid'], 'small', true);
                if($v['sex'] == 1){
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_26");
                }else if($v['sex'] == 2){
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_27");
                }else{
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_28");
                }

                $logList[$k]['subscribe_time'] = dgmdate($v['subscribe_time'], 'u');
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
        $this->page->assign('logList', $logList, true);
        $this->page->display();
    }


    public function mapp_wechat(){
        T::getObject('Aljwsq');
        $count = Aljwsq::countBindUserNum($this->page->get->search);
        $logList = Aljwsq::getBindUserList($this->page->get->search, $this->page->get->page>0 ? $this->page->get->page :1, 20);
        if($this->page->get->render == 'yes'){
            foreach($logList as $k => $v){
                $logList[$k]['bindtime'] = dgmdate($v['bindtime'], 'u');
                $logList[$k]['avatar'] = avatar($v['uid'], 'small', true);
                if($v['sex'] == 1){
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_29");
                }else if($v['sex'] == 2){
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_30");
                }else{
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_31");
                }

                $logList[$k]['subscribe_time'] = dgmdate($v['subscribe_time'], 'u');
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
        $this->page->assign('logList', $logList, true);
        $this->page->display();
    }

    public function import(){
        require_once DISCUZ_ROOT . './source/plugin/aljwsq/mapp_wechatclient.lib.class.php';
        $wechat_client = new WeChatClient($this->page->config->aljwsq->appid, $this->page->config->aljwsq->appsecret);
        $openids = array();
        $data = $wechat_client->getFollowersList();

        if ($data['list']) {
            $openids = array_merge($openids, $data['list']);
        }

        while ($data['next_id']) {
            $data = $wechat_client->getFollowersList($data['next_id']);
            if ($data['list']) {
                $openids = array_merge($openids, $data['list']);
            }
        }
        foreach($openids as $openid){
            if(!DB::fetch_first('select * from %t where openid=%s', array('aljwsq_mapp_user', $openid))){
                DB::insert('aljwsq_mapp_user', array('openid' => $openid));
            }else{
                DB::update('aljwsq_mapp_user', array('subscribe' => 1), array('openid' => $openid));
            }
        }
        $progressBar = T::getObject('progressbar', array(
            'requestPage' => $this->page,
            'tips' => lang("plugin/aljhtx","wechat_php_32"),
            'curPage' => $this->page->get->curpage ? $this->page->get->curpage : 1,
            'maxPage' => 1,
            'url' => 'plugin.php?id=aljhtx&c=wechat&a=showImportMessage',
            'goUrl' => 'plugin.php?id=aljhtx&c=wechat&a=showImportMessage',
        ));
        $progressBar->start(); //加载进度条模板
    }

    public function update(){
        $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//当前页
        $nextPage = $curPage + 1;
        $perPageNum = 100;//每次请求处理多少个用户
        $userNum = DB::result_first('select count(*) from %t', array('aljwsq_mapp_user'));
        $maxPapge = ceil($userNum/$perPageNum);
        $start = ($curPage-1) * $perPageNum;//待处理起始用户数量
        //$start = $start ? $start : 1;
        $end  = $start+($perPageNum>=$userNum ? $userNum: $perPageNum);//待处理结束用户数量
        if($curPage > $maxPapge){
            header('Location: plugin.php?id=aljhtx&c=wechat&a=showUpdateMessage');
            exit;
        }

        $users = C::t('#aljwsq#aljwsq_mapp_user')->range($start, $perPageNum,'desc');
        foreach($users as $user){
            if($user['nickname']){
                //continue;
            }
            $this->updateWechatUserInfo($user);
        }

        $progressBar = T::getObject('progressbar', array(
            'requestPage' => $this->page,
            'tips' => lang("plugin/aljhtx","wechat_php_33").$start.'-'.$end.lang("plugin/aljhtx","wechat_php_34"),
            'curPage' => $curPage,
            'maxPage' => $maxPapge ? $maxPapge : 1,
            'url' => 'plugin.php?id=aljhtx&c=wechat&a=update&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage,
            'goUrl' => 'plugin.php?id=aljhtx&c=wechat&a=showupdatetmessage',
        ));
        $progressBar->start(); //加载进度条模板
    }
    public function updateWechatUserInfoByOpenid(){
        $user = C::t('#aljwsq#aljwsq_mapp_user')->fetch($this->page->get->openid);
        $this->updateWechatUserInfo($user);
        T::responseJson();
    }
    public function updateWechatUserInfo($user){
        require_once DISCUZ_ROOT . './source/plugin/aljwsq/mapp_wechatclient.lib.class.php';
        $wechat_client = new WeChatClient($this->page->config->aljwsq->appid, $this->page->config->aljwsq->appsecret);
        $wuser = $wechat_client -> getUserInfoById($user['openid']);
        if($wuser['subscribe']){
            $groupid = $wechat_client -> getGroupidByUserid($user['openid']);
            $updatearray = array(
                'nickname' => diconv($wuser['nickname'], 'utf-8', CHARSET),
                'groupid' => $groupid,
                'sex' => $wuser['sex'],
                'city' => diconv($wuser['city'], 'utf-8', CHARSET),
                'unionid' => diconv($wuser['unionid'], 'utf-8', CHARSET),
                'country' => diconv($wuser['country'], 'utf-8', CHARSET),
                'province' => diconv($wuser['province'], 'utf-8', CHARSET),
                'language' => $wuser['language'],
                'headimgurl' => $wuser['headimgurl'],
                'subscribe' => $wuser['subscribe'],
                'subscribe_time' => $wuser['subscribe_time'],
                'lasttime' => TIMESTAMP,
            );
            $forumuser = C::t('common_member') -> fetch($user['uid']);
            $updatearray['username'] = $forumuser['username'];
            C::t('#aljwsq#aljwsq_mapp_user')->update($user['openid'], $updatearray);
        }else{
            if(empty($user['uid'])){
                C::t('#aljwsq#aljwsq_mapp_user')->delete($user['openid']);
            }
        }
        $is_plugin = DB::fetch_first('select * from %t where  identifier = %s',array('common_plugin','appbyme_app'));
        if($wuser['unionid'] && $is_plugin){
            $appbymeuser = DB::fetch_first('select * from %t where uid=%d',array('appbyme_connection',$user['uid']));
            if($appbymeuser){
                if(empty($appbymeuser['unionid'])){
                    DB::query('update %t set param=%s where uid=%d',array('appbyme_connection',$wuser['unionid'],$user['uid']));
                }
            }else{
                DB::query('INSERT INTO ' . DB::table('appbyme_connection') . " (uid,openid,status,type,param)values(" . $user['uid'] . ",'',1,1,'" . $wuser['unionid'] . "') ");
            }
        }
    }
    public function userList(){
        T::getObject('Aljwsq');
        $count = Aljwsq::countFollowUserNum($this->page->get->search);
        $logList = Aljwsq::getFollowUserList($this->page->get->search, $this->page->get->page>0 ? $this->page->get->page :1, 20);
        if($this->page->get->render == 'yes'){
            foreach($logList as $k => $v){
                if($v['sex'] == 1){
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_35");
                }else if($v['sex'] == 2){
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_36");
                }else{
                    $logList[$k]['sex'] = lang("plugin/aljhtx","wechat_php_37");
                }
                $logList[$k]['subscribe_time'] = dgmdate($v['subscribe_time'], 'u');
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => $count,
                'data' => $logList
            ));
        }
        $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
        $this->page->assign('logList', $logList, true);
        $this->page->display();
    }
    public function sendMessage(){
        if($this->page->get->formhash == FORMHASH){
            $template = DB::fetch_first('select * from %t where template_id = %s', array('aljhtx_wechat_template', $this->page->get->template_id));
            $new = str_replace("{{first.DATA}}", "", $template['content']);
            $new = str_replace("{{remark.DATA}}", "", $new);
            $new = explode("\n", $new);
            foreach($new as $key => $value){
                if($value){
                    $value = str_replace('{{', '', $value);
                    $value = str_replace('.DATA}}', '', $value);
                    $newContent[$key] = explode(lang("plugin/aljhtx","wechat_php_38"), $value);
                }
            }

            if($this->page->get->first){
                $params['first'] = T::objectToArray($this->page->get->first);
            }
            foreach($newContent as $k => $v){
                if($v[1]){
                    $params[$v[1]] = T::objectToArray($this->page->get->{$v[1]});
                }
            }
            $params['remark'] = T::objectToArray($this->page->get->remark);


            if($this->page->get->curpage>1){
                $data = unserialize($template['last_content']);
            }else{
                $data = array(
                    'template_id' => $this->page->get->template_id,
                    'url'  => $this->page->get->url,
                    'topcolor'  => '#ff0000',
                    'data'  => T::ajaxPostCharSet($params),
                );
            }

            if($this->page->get->curpage<=1){
                DB::update('aljhtx_wechat_template', array('last_content' => serialize(T::ajaxGetCharSet($data))), array('template_id' => $this->page->get->template_id));
            }

            $progressBarParams = array(
                'requestPage' => $this->page,
                'tips' => lang("plugin/aljhtx","wechat_php_39"),
                'curPage' => $this->page->get->curpage ? $this->page->get->curpage : 1,
                'maxPage' => 1,
                'url' => 'plugin.php?id=aljhtx&c=wechat&a=showsuccessmessage&template_id='.$this->page->get->template_id,
                'goUrl' => 'plugin.php?id=aljhtx&c=wechat&a=showsuccessmessage&template_id='.$this->page->get->template_id,
            );
            if($this->page->get->type == 4){
                $data['touser'] = $this->page->get->openid;
                $this->sendTemplate($data);
            }else if($this->page->get->type == 5){
                $user = DB::fetch_first('select * from %t where username=%s', array('common_member', $this->page->get->username));
                $wxuser = DB::fetch_first('select * from %t where uid=%d', array($this->page->config->mapp_template->usertable, $user['uid']));
                $data['touser'] = $wxuser['openid'];
                $this->sendTemplate($data);
            }else if($this->page->get->type == 1){
                $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//当前页
                $nextPage = $curPage + 1;
                $perPageNum = 50;//每次请求处理多少个用户
                $userNum = DB::result_first('select count(*) from %t where subscribe=1 and uid>0', array('aljwsq_mapp_user'));
                $maxPapge = ceil($userNum/$perPageNum);
                $start = ($curPage-1) * $perPageNum;//待处理起始用户数量

                $end  = $start+($perPageNum>=$userNum ? $userNum: $perPageNum);//待处理结束用户数量
                if($curPage > $maxPapge){
                    header('Location: '.$progressBarParams['goUrl']);
                    exit;
                }

                $userList = DB::fetch_all('select * from %t where subscribe=1 and uid>0 limit %d, %d', array('aljwsq_mapp_user', $start, $perPageNum));
                foreach($userList as $user){
                    $data['touser'] = $user['openid'];
                    $this->sendTemplate($data);
                }
                $progressBarParams['tips'] = lang("plugin/aljhtx","wechat_php_40").$start.'-'.$end.lang("plugin/aljhtx","wechat_php_41");
                $progressBarParams['curPage'] = $curPage;
                $progressBarParams['maxPage'] = $maxPapge ? $maxPapge : 1;
                $progressBarParams['url'] = 'plugin.php?id=aljhtx&c=wechat&a=sendmessage&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage.'&type='.$this->page->get->type.'&template_id='.$this->page->get->template_id;
            }else if($this->page->get->type == 2){
                $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//当前页
                $nextPage = $curPage + 1;
                $perPageNum = 50;//每次请求处理多少个用户
                $userNum = DB::result_first('select count(*) from %t where subscribe=1', array('aljwsq_mapp_user'));
                $maxPapge = ceil($userNum/$perPageNum);
                $start = ($curPage-1) * $perPageNum;//待处理起始用户数量

                $end  = $start+($perPageNum>=$userNum ? $userNum: $perPageNum);//待处理结束用户数量
                if($curPage > $maxPapge){
                    header('Location: '.$progressBarParams['goUrl']);
                    exit;
                }

                $userList = DB::fetch_all('select * from %t where subscribe=1 and uid>0 limit %d, %d', array('aljwsq_mapp_user', $start, $perPageNum));
                foreach($userList as $user){
                    $data['touser'] = $user['openid'];
                    $this->sendTemplate($data);
                }
                $progressBarParams['tips'] = lang("plugin/aljhtx","wechat_php_42").$start.'-'.$end.lang("plugin/aljhtx","wechat_php_43");
                $progressBarParams['curPage'] = $curPage;
                $progressBarParams['maxPage'] = $maxPapge ? $maxPapge : 1;
                $progressBarParams['url'] = 'plugin.php?id=aljhtx&c=wechat&a=sendmessage&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage.'&type='.$this->page->get->type.'&template_id='.$this->page->get->template_id;
            }else if($this->page->get->type == 6){
                $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//当前页
                $nextPage = $curPage + 1;
                $perPageNum = 50;//每次请求处理多少个用户
                $userNum = DB::result_first('select count(*) from %t where subscribe=1 and uid>0 and uid>=%d and uid<=%d', array('aljwsq_mapp_user', $this->page->get->uid_min, $this->page->get->uid_max));
                $maxPapge = ceil($userNum/$perPageNum);
                $start = ($curPage-1) * $perPageNum;//待处理起始用户数量

                $end  = $start+($perPageNum>=$userNum ? $userNum: $perPageNum);//待处理结束用户数量
                if($curPage > $maxPapge){
                    header('Location: '.$progressBarParams['goUrl']);
                    exit;
                }

                $userList = DB::fetch_all('select * from %t where subscribe=1 and uid>0 and uid>=%d and uid<=%d limit %d, %d', array('aljwsq_mapp_user', $this->page->get->uid_min, $this->page->get->uid_max, $start, $perPageNum));
                foreach($userList as $user){
                    $data['touser'] = $user['openid'];
                    $this->sendTemplate($data);
                }
                $progressBarParams['tips'] = lang("plugin/aljhtx","wechat_php_44").$start.'-'.$end.lang("plugin/aljhtx","wechat_php_45");
                $progressBarParams['curPage'] = $curPage;
                $progressBarParams['maxPage'] = $maxPapge ? $maxPapge : 1;
                $progressBarParams['url'] = 'plugin.php?id=aljhtx&c=wechat&a=sendmessage&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage.'&type='.$this->page->get->type.'&template_id='.$this->page->get->template_id;
            }else if($this->page->get->type == 3){
                $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//当前页
                $nextPage = $curPage + 1;
                $perPageNum = 50;

                if($curPage>1){
                    $gids = explode('-', $this->page->get->gids);
                }else{
                    foreach(T::objectToArray($this->page->get->gids) as $k => $v){
                        $gids[] = $k;
                    }
                }

                $userNum = $gids ? DB::result_first('select count(*) from %t a left join %t b on a.uid=b.uid where a.subscribe=1 and b.groupid in (%i)', array('aljwsq_mapp_user', 'common_member', implode(',', $gids))) : 0;
                $maxPapge = ceil($userNum/$perPageNum);
                $start = ($curPage-1) * $perPageNum;//待处理起始用户数量

                $end  = $start+($perPageNum>=$userNum ? $userNum: $perPageNum);//待处理结束用户数量
                if($curPage > $maxPapge){
                    header('Location: '.$progressBarParams['goUrl']);
                    exit;
                }

                $userList = $gids ? DB::fetch_all('select a.* from %t a left join %t b on a.uid=b.uid where a.subscribe=1 and b.groupid in (%i) limit %d, %d', array('aljwsq_mapp_user','common_member', implode(',', $gids), $start, $perPageNum)) : array();

                foreach($userList as $user){
                    $data['touser'] = $user['openid'];
                    $this->sendTemplate($data);
                }
                $progressBarParams['tips'] = lang("plugin/aljhtx","wechat_php_46").$start.'-'.$end.lang("plugin/aljhtx","wechat_php_47");
                $progressBarParams['curPage'] = $curPage;
                $progressBarParams['maxPage'] = $maxPapge ? $maxPapge : 1;
                $progressBarParams['url'] = 'plugin.php?id=aljhtx&c=wechat&a=sendmessage&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage.'&type='.$this->page->get->type.'&template_id='.$this->page->get->template_id.'&gids='.implode('-', $gids);
            }else if($this->page->get->type == 7){

                $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//当前页
                $nextPage = $curPage + 1;
                $perPageNum = 50;

                if($curPage>1){
                    $fids = explode('-', $this->page->get->fids);
                }else{
                    foreach(T::objectToArray($this->page->get->fids) as $k => $v){
                        $fids[] = $k;
                    }
                }

                $userNum = $fids ? DB::result_first('select count(*) from %t a left join %t b on a.uid=b.uid where a.subscribe=1 and b.fid in (%i)', array('aljwsq_mapp_user', 'forum_groupuser', implode(',', $fids))) : 0;
                $maxPapge = ceil($userNum/$perPageNum);
                $start = ($curPage-1) * $perPageNum;//待处理起始用户数量

                $end  = $start+($perPageNum>=$userNum ? $userNum: $perPageNum);//待处理结束用户数量
                if($curPage > $maxPapge){
                    header('Location: '.$progressBarParams['goUrl']);
                    exit;
                }

                $userList = $fids ? DB::fetch_all('select a.* from %t a left join %t b on a.uid=b.uid where a.subscribe=1 and b.fid in (%i) limit %d, %d', array('aljwsq_mapp_user','forum_groupuser', implode(',', $fids), $start, $perPageNum)) : array();
                foreach($userList as $user){
                    $data['touser'] = $user['openid'];
                    $this->sendTemplate($data);
                }
                $progressBarParams['tips'] = lang("plugin/aljhtx","wechat_php_48").$start.'-'.$end.lang("plugin/aljhtx","wechat_php_49");
                $progressBarParams['curPage'] = $curPage;
                $progressBarParams['maxPage'] = $maxPapge ? $maxPapge : 1;
                $progressBarParams['url'] = 'plugin.php?id=aljhtx&c=wechat&a=sendmessage&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage.'&type='.$this->page->get->type.'&template_id='.$this->page->get->template_id.'&fids='.implode('-', $fids);
            }
            $progressBar = T::getObject('progressbar', $progressBarParams);
            $progressBar->start(); //加载进度条模板
        }else{
            $templateList = DB::fetch_all('select * from %t where template_id = %s', array('aljhtx_wechat_template', $this->page->get->template_id));
            //$templateList = DB::fetch_all('select * from %t', array('aljhtx_wechat_template'));
            foreach($templateList as $k => $v){
                if(!$v['primary_industry']){
                    unset($templateList[$k]);
                    continue;
                }
                $new = str_replace("{{first.DATA}}", lang("plugin/aljhtx","wechat_php_7").'{{first.DATA}}', $v['content']);
                $new = str_replace("{{remark.DATA}}", lang("plugin/aljhtx","wechat_php_8").'{{remark.DATA}}', $new);
                $new = explode("\n", $new);

                foreach($new as $key => $value){
                    $value = trim($value);
                    if($value){
                        $value = str_replace('{{', '', $value);
                        $value = str_replace('.DATA}}', '', $value);
                        $newContent[$key] = explode(lang("plugin/aljhtx","wechat_php_50"), $value);
                    }
                }

                $templateList[$k]['last_content'] = unserialize($v['last_content']);
                $templateList[$k]['newContent'] = $newContent;
                $templateList[$k]['content'] = str_replace("\n", "<br>", $v['content']);
                $templateList[$k]['example'] = str_replace("\n", "<br>", $v['example']);
            }

            $typeList = array(
                1 => lang("plugin/aljhtx","wechat_php_51"),
                2 => lang("plugin/aljhtx","wechat_php_52"),
                7 => lang("plugin/aljhtx","wechat_php_53"),
                3 => lang("plugin/aljhtx","wechat_php_54"),
                4 => 'OPENID',
                5 => lang("plugin/aljhtx","wechat_php_55"),
                6 => 'UID'.lang("plugin/aljhtx","wechat_php_56"),
            );

            $userGroupList = User::getUserGroupList(); //用户组数据
            $this->page->assign('typeList', $typeList, true);
            $this->page->assign('userGroupList', $userGroupList, true);
            $this->page->assign('templateList', $templateList, true);
            $this->page->display();
        }
    }

    public function deletebindtemplate(){
        DB::delete('aljhtx_wechat_bindtemplate', array(
            'id' => $this->page->get->bid
        ));
        T::responseJson();
    }

    public function bindTemplateList(){
        if($this->page->get->render == 'yes'){
            $logList = DB::fetch_all('select a.*, b.title from %t a left join %t b on a.template_id=b.template_id where a.type=%s', array('aljhtx_wechat_bindtemplate', 'aljhtx_wechat_template', $this->page->get->type));
            $bindTemplate = Page::loadConfig('bind_template');
            foreach($logList as $k => $v){
                $v['type'] = $bindTemplate[$v['type']]['name'];
                $logList[$k] = $v;
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => count($logList),
                'data' => $logList
            ));
        }
        $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
        $this->page->assign('type', $this->page->get->type);
        $this->page->assign('logList', $logList, true);
        $this->page->display();
    }

    public function bindTemplate(){
        $bindTemplate = DB::fetch_first('select * from %t where type=%s and template_id=%s', array('aljhtx_wechat_bindtemplate', $this->page->get->type, $this->page->get->template_id));
        if($bindTemplate){
            $bindTemplate['param'] = unserialize($bindTemplate['param']);
        }
        foreach(T::objectToArray($this->page->get->param) as $k => $v){
            $param[$k] = $v;
        }
        if($this->page->get->formhash == FORMHASH){
            if($bindTemplate){
                DB::update('aljhtx_wechat_bindtemplate', array(
                    'param' => serialize($param)
                ), array(
                    'type' => $this->page->get->type,
                    'template_id' => $this->page->get->template_id
                ));
            }else{
                DB::insert('aljhtx_wechat_bindtemplate', array(
                    'type' => $this->page->get->type,
                    'template_id' => $this->page->get->template_id,
                    'param' => serialize($param),
                ));
            }
            header('Location: plugin.php?id=aljhtx&c=wechat&a=showBindTemplateMessage&template_id='.$this->page->get->template_id.'&type='.$this->page->get->type);
        }else{
            if(DB::fetch_first('select * from %t where template_id != %s and type=%s', array('aljhtx_wechat_bindtemplate', $this->page->get->template_id, $this->page->get->type))){
                $this->page->showMessage(lang("plugin/aljhtx","wechat_php_57"), '<a style="color: rgb(30, 159, 255);" href="plugin.php?id=aljhtx&c=wechat&a=bindtemplatelist&type='.$this->page->get->type.'">' . lang("plugin/aljhtx","wechat_php_9") . '</a>');
            }
            $templateList = DB::fetch_all('select * from %t where template_id = %s', array('aljhtx_wechat_template', $this->page->get->template_id));
            //$templateList = DB::fetch_all('select * from %t', array('aljhtx_wechat_template'));
            foreach($templateList as $k => $v){
                if(!$v['primary_industry']){
                    unset($templateList[$k]);
                    continue;
                }
                $new = str_replace("{{first.DATA}}", lang("plugin/aljhtx","wechat_php_10").'{{first.DATA}}', $v['content']);
                $new = str_replace("{{remark.DATA}}", lang("plugin/aljhtx","wechat_php_11").'{{remark.DATA}}', $new);
                $new = explode("\n", $new);

                foreach($new as $key => $value){
                    $value = trim($value);
                    if($value){
                        $value = str_replace('{{', '', $value);
                        $value = str_replace('.DATA}}', '', $value);
                        $newContent[$key] = explode(lang("plugin/aljhtx","wechat_php_58"), $value);
                    }
                }

                $templateList[$k]['last_content'] = unserialize($v['last_content']);
                $templateList[$k]['newContent'] = $newContent;
                $templateList[$k]['content'] = str_replace("\n", "<br>", $v['content']);
                $templateList[$k]['example'] = str_replace("\n", "<br>", $v['example']);
            }
            $typeList = Page::loadConfig('bind_template');
            $this->page->assign('type_id', $this->page->get->type);
            $this->page->assign('type', $typeList[$this->page->get->type]);
            $this->page->assign('templateList', $templateList, true);
            $this->page->assign('bindTemplate', $bindTemplate, true);
            $this->page->display();
        }
    }

    public function select_type(){
        if($this->page->get->formhash == FORMHASH){
            header('Location: plugin.php?id=aljhtx&c=wechat&a=bindtemplate&template_id='.$this->page->get->template_id.'&type='.$this->page->get->type);
        }else{
            $this->page->assign('template_id', $this->page->get->template_id);
            $this->page->assign('typeList', Page::loadConfig('bind_template'), true);
            $this->page->display();
        }
    }


    public function sendTemplate($data){

        $template = DB::fetch_first('select * from %t where template_id = %s', array('aljhtx_wechat_template', $this->page->get->template_id));
        $new = str_replace("{{first.DATA}}", lang("plugin/aljhtx","wechat_php_12").'{{first.DATA}}', $template['content']);
        $new = str_replace("{{remark.DATA}}", lang("plugin/aljhtx","wechat_php_13").'{{remark.DATA}}', $new);
        $new = explode("\n", $new);
        foreach($new as $key => $value){
            $value = trim($value);
            if($value){
                $value = str_replace('{{', '', $value);
                $value = str_replace('.DATA}}', '', $value);
                $newContent[$key] = explode(lang("plugin/aljhtx","wechat_php_59"), $value);
            }
        }
        if($this->page->get->curpage<=1){
            $param = T::ajaxGetCharSet($data['data']);
        }else{
            $param = $data['data'];
            $data['data'] = T::ajaxPostCharSet($data['data']);
        }

        foreach($newContent as $k => $v){
            if($v[1] == 'first' || $v[1] == 'remark'){
                $newContent[$k][0] = '';
            }
            $newContent[$k][1] = $param[$v[1]];
        }
        require_once DISCUZ_ROOT . './source/plugin/aljwsq/mapp_wechatclient.lib.class.php';
        $wechat_client = new WeChatClient($this->page->config->aljwsq->appid, $this->page->config->aljwsq->appsecret);
        $result = $wechat_client -> sendtemplate($data);
        if($result){
            DB::query('update %t set succeed_times = succeed_times + 1, times = times + 1, last_time=%d  where template_id = %s', array('aljhtx_wechat_template', TIMESTAMP, $this->page->get->template_id));
        }else{
            DB::query('update %t set times = times + 1, last_time=%d where template_id = %s', array('aljhtx_wechat_template', TIMESTAMP, $this->page->get->template_id));
        }
        $insertdata = array(
            'touser' => $data['touser'],
            'template_id' => $this->page->get->template_id,
            'url'  => $data['url'],
            'dateline'  => TIMESTAMP,
            'status'  => $result ? 1 : 0,
            'template'  => serialize(T::ajaxGetCharSet($data)),
            'param'  => serialize($newContent),
            'a'  => diconv($wechat_client->error(), 'UTF-8'),
        );
        C::t('#mapp_template#aljwsq_mapp_template_log')->insert($insertdata);
    }

    public function deleteTemplate(){
        DB::delete('aljhtx_wechat_template', array(
           'template_id' => $this->page->get->template_id
        ));
        T::responseJson();
    }

    /**
     * 获取微信模板消息的模板列表
     *
     * @return void
     */
    public function getTemplateList(){
        $templateList = DB::fetch_all('select * from %t', array('aljhtx_wechat_template'));
        if(!$templateList || $this->page->get->reflash == 'yes'){
            require_once DISCUZ_ROOT . './source/plugin/aljwsq/mapp_wechatclient.lib.class.php';
            $wechat_client = new WeChatClient($this->page->config->aljwsq->appid, $this->page->config->aljwsq->appsecret);
            $result = T::ajaxGetCharSet($wechat_client->getTemplateList());
            if($result){
                foreach ($result['template_list'] as $key => $value) {
                    if(!DB::fetch_first('select * from %t where template_id=%s', array('aljhtx_wechat_template', $value['template_id']))){
                        DB::insert('aljhtx_wechat_template', $value);
                    }
                }
            }
            $templateList = $result['template_list'];
        }
        if($this->page->get->render == 'yes'){
            foreach($templateList as $k => $v){
                if(empty($v['primary_industry'])){
                    unset($templateList[$k]);
                    continue;
                }
                if($v['last_time']>0){
                    $templateList[$k]['last_time'] = dgmdate($v['last_time'], 'u');
                }else{
                    $templateList[$k]['last_time'] = '--';
                }
                $templateList[$k]['content'] = str_replace("\n", "<br>", $v['content']);
                $templateList[$k]['example'] = str_replace("\n", "<br>", $v['example']);
            }
            T::responseJson(array(
                'code' => 0,
                'msg' => "",
                'count' => count($templateList),
                'data' => $templateList
            ));
        }
/*
        foreach($templateList as $k => $v){
            $new = str_replace("{{first.DATA}}", "", $v['content']);
            $new = str_replace("{{remark.DATA}}", "", $new);
            $new = explode("\n", $new);
            foreach($new as $key => $value){
                $value = trim($value);
                if($value){
                    $new[$key] = explode(lang("plugin/aljhtx","wechat_php_60"), $value);
                }else{
                    unset($new[$key]);
                }
            }

            $templateList[$k]['newContent'] = $new;
            $templateList[$k]['content'] = str_replace("\n", "<br>", $v['content']);
            $templateList[$k]['example'] = str_replace("\n", "<br>", $v['example']);
        }
        //debug($templateList);
        $this->page->assign('templateList', $templateList, true);
*/
        $this->page->display();
    }


}

