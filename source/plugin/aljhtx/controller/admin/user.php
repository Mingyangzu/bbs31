<?php

/**
 * Controller的user模块
 *
 * @author yuxinqi<yuxinqi@vip.qq.com>
 * @version 1.0
 * @link http://docs.liangjianyun.com/
 */


if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class UserAction{
    public $page;

    public function __construct($page) {
        $this->page = $page;
    }

    /**
     * 进入进度条开始导入
     *
     * @return void
     */
    public function import(){
            if(intval($this->page->get->i) <=0 || intval($this->page->get->i) > 4){
                exit;
            }
            $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//当前页
            $nextPage = $curPage + 1;
            $perPageNum = 50;//每次请求处理多少个用户
            if($this->page->get->i == 1){
                $tableName = 'common_member_wechat';
            }else if($this->page->get->i == 2){
                $tableName = 'wq_login_member';
            }else if($this->page->get->i == 3){
                $tableName = 'fn_wx_login_binduser';
            }else if($this->page->get->i == 4){
                $tableName = 'comiis_weixin';
            }
            $userNum = DB::result_first('select count(*) from %t', array($tableName));
            $maxPapge = ceil($userNum/$perPageNum);
            $start = ($curPage-1) * $perPageNum;//待处理起始用户数量
            //$start = $start ? $start : 1;
            $end  = $start+($perPageNum>=$userNum ? $userNum: $perPageNum);//待处理结束用户数量
            if($curPage > $maxPapge){
                header('Location: plugin.php?id=aljhtx&c=user&a=showImportMessage');
                exit;
            }

            //开始数据导入
            $userList = DB::fetch_all('select * from %t limit %d, %d', array($tableName, $start, $perPageNum));
            foreach($userList as $k => $v){
                if(DB::fetch_first('select * from %t where openid=%s', array('aljwsq_mapp_user', $v['openid']))){
                    DB::update('aljwsq_mapp_user', array(
                        'uid' => $v['uid'],
                        'unionid' => $v['unionid'],
                    ), array(
                        'openid' => $v['openid'],
                    ));
                }else{
                    DB::insert('aljwsq_mapp_user', array(
                        'uid' => $v['uid'],
                        'openid' => $v['openid'],
                        'unionid' => $v['unionid'],
                    ));
                }

            }
            //结束数据导入

            $progressBar = T::getObject('progressbar', array(
                'requestPage' => $this->page,
                'tips' => lang("plugin/aljhtx","user_php_3").$start.'-'.$end.lang("plugin/aljhtx","user_php_4"),
                'curPage' => $curPage,
                'maxPage' => $maxPapge ? $maxPapge : 1,
                'url' => 'plugin.php?id=aljhtx&c=user&a=import&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage.'&i='.$this->page->get->i,
                'goUrl' => 'plugin.php?id=aljhtx&c=user&a=showImportMessage',
            ));
            $progressBar->start(); //加载进度条模板
    }

    /**
     * 检测是否安装要导入的微信登录插件
     *
     * @return void
     */
    public function importcheck(){

        if(!$this->page->config->mapp_wechat){
            T::responseJson(array('code' => 40002, 'msg' => 'error'));
        }

        if($this->page->get->i == 1){
            if(!$this->page->config->xigua_login){
                T::responseJson(array('code' => 40001, 'msg' => 'error'));
            }
        }
        if($this->page->get->i == 2){
            if(!$this->page->config->wq_login){
                T::responseJson(array('code' => 40001, 'msg' => 'error'));
            }
        }
        if($this->page->get->i == 3){
            if(!$this->page->config->fn_wx_login){
                T::responseJson(array('code' => 40001, 'msg' => 'error'));
            }
        }
        if($this->page->get->i == 4){
            if(!$this->page->config->comiis_weixin){
                T::responseJson(array('code' => 40001, 'msg' => 'error'));
            }
        }
        T::responseJson();
    }

    /**
     * 解绑QQ
     *
     * @return void
     */
    public function unbindQq(){
        if($this->page->config->aljqq){
            $aljqq = T::getObject('Aljqq');
            Aljqq::delete($this->page->get->frienduid);
        }
        T::responseJson();
    }

    /**
     * 解绑微信
     *
     * @return void
     */
    public function unbindWechat(){
        if($this->page->config->aljwsq){
            T::getObject('Aljwsq');
            Aljwsq::delete($this->page->get->frienduid);
        }
        T::responseJson();
    }

    /**
     * 修改密码
     *
     * @return void
     */
    public function changePassword(){
        $friendUser = User::getUser($this->page->get->frienduid);
        if($_GET['formhash'] == FORMHASH){
            loaducenter();
            uc_user_edit($friendUser->username,'',$this->page->get->password,'',1,0,'');
            T::responseJson();
        }
    }

    /**
     * 结果提示页
     *
     * @return void
     */
    public function showMessage(){
        $this->page->showMessage(lang("plugin/aljhtx","user_php_5"), '<a style="color: rgb(30, 159, 255);" href="plugin.php?id=aljhtx&c=user&a=sendgroupmessage">' . lang("plugin/aljhtx","user_php_1") . '</a>');
    }

    /**
     * 导入结果提示页
     *
     * @return void
     */
    public function showImportMessage(){
        $this->page->showMessage(lang("plugin/aljhtx","user_php_6"), '<a style="color: rgb(30, 159, 255);" href="plugin.php?id=aljhtx&c=user&a=user">' . lang("plugin/aljhtx","user_php_2") . '</a>');
    }


    /**
     * 输出用户列表页
     *
     * @return void
     */
    public function user(){
        T::getObject('Order');
        T::getObject('Aljbd');
        T::getObject('Aljwsq');
        T::getObject('Aljqq');
        $userList = User::getUserListByGroupid('', '', 'DESC', $this->page->get->curpage, 20, $this->page); //用户列表数据
        $pagingUrl = A_URL.'&search='.$this->page->get->search.'&curpage='; //分页跳转链接
        $userGroupList = User::getUserGroupList(); //用户组数据
        $userNum = User::countUserNumByGroupid(); //总用户数
        if($this->page->config->aljwsq){
            $wechatUserNum = Aljwsq::count(); //微信总用户数
        }
        if($this->page->config->aljqq){
            $qqUserNum = Aljqq::count(); //QQ总用户数
        }
        if($this->page->config->aljbd){
            $bdUserNum = Aljbd::count(); //商家用户数
        }
        if($this->page->config->aljgwc){
            $countOrderMoney = Order::countOrderMoney(); //订单销售总金额
        }
        $wechatUserNum = $wechatUserNum ? $wechatUserNum : 0;
        $qqUserNum = $qqUserNum ? $qqUserNum : 0;
        $bdUserNum = $bdUserNum ? $bdUserNum : 0;
        $countOrderMoney = $countOrderMoney ? $countOrderMoney : 0;
        $this->page->assign('dourl', PAGE_URL); //当前请求页面URL
        $this->page->assign('userGroupList', $userGroupList, true); //用户组数据
        $this->page->assign('userList', $userList); //用户列表数据
        $this->page->assign('mobileUserNum', User::countUserNumByMobile()); //已绑定手机号用户
        $this->page->assign('bdUserNum', $bdUserNum); //商家用户数
        $this->page->assign('countOrderMoney', $countOrderMoney); //订单销售总金额
        $this->page->assign('wechatUserNum', $wechatUserNum); //微信总用户数
        $this->page->assign('qqUserNum', $qqUserNum); //QQ总用户数
        $this->page->assign('userNum', $userNum); //总用户数
        $this->page->assign('paging', $this->page->paging($userNum, $pagingUrl), true); //总用户数
        $this->page->display();
    }

    /**
     * 给指定用户组群发消息-开始群发
     *
     * @return void
     */
    public function doSendGroupMessage(){
        T::getObject('Cron');
        $cron = Cron::getCronByid($this->page->get->cronid);
        $userList = User::getUserListByGroupid($cron->param->groupid);
        $message = new Message($this->page->loginUser, $cron->content);

        foreach ($userList as $friendUser) {
            foreach(array_unique((array)$cron->param->type) as $v){
                $message->send($v, $friendUser);
            }
        }
        T::responseJson();
    }

    /**
     * 给指定用户组群发消息的iframe弹窗页-加载模板
     *
     * @return void
     */
    public function sendGroupMessage(){
        if ($this->page->get->formhash == FORMHASH) {
            $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//当前页
            $nextPage = $curPage + 1;
            $perPageNum = 50;//每次请求处理多少个用户
            $groupUserNum = User::countUserNumByGroupid($this->page->get->groupid);
            $maxPapge = ceil($groupUserNum/$perPageNum);
            $start = ($curPage-1) * $perPageNum;//待处理起始用户数量
            //$start = $start ? $start : 1;
            $end  = $start+($perPageNum>=$groupUserNum ? $groupUserNum: $perPageNum);//待处理结束用户数量
            if($curPage > $maxPapge){
                header('Location: plugin.php?id=aljhtx&c=user&a=showmessage');
                exit;
            }

            //将群发任务推送到队列-开始
            $cron = T::getObject('cron', array(
                'pid' => 0,
                'touid' => 0,
                'sync' => 1, //0为异步任务 1为同步任务
                'param' => serialize(array('groupid' => $this->page->get->groupid, 'type' => $this->page->get->type, 'curPage' => $curPage)),
                'type' => 'sendGroupMessage',
                'content' => $this->page->get->content
            ));
            $cronId = $cron->push();
            //将群发任务推送到队列-结束

            $progressBar = T::getObject('progressbar', array(
                'requestPage' => $this->page,
                'tips' => lang("plugin/aljhtx","user_php_7").$start.'-'.$end.lang("plugin/aljhtx","user_php_8"),
                'curPage' => $curPage,
                'maxPage' => $maxPapge ? $maxPapge : 1,
                'doSendGroupMessageUrl' => C_URL . '&a=' . 'doSendGroupMessage&ajax=yes&cronid=' . $cronId,
                'url' => 'plugin.php?id=aljhtx&c=user&a=sendgroupmessage&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage.'&groupid='.$this->page->get->groupid,
                'goUrl' => 'plugin.php?id=aljhtx&c=user&a=showmessage',
            ));
            $progressBar->start(); //加载进度条模板
        } else {
            $this->page->assign('curpage', $this->page->get->curpage);
            $this->page->assign('groupList', User::getUserGroupList(), true);
            $this->page->assign('checkAljol', $this->page->config->aljol ? 'checked' : '');
            $this->page->assign('checkAljdx', $this->page->config->aljdx ? 'checked' : '');
            $this->page->assign('checkMappTemplate', $this->page->config->mapp_template ? 'checked' : '');
            $this->page->display();
        }
    }

    /**
     * 给指定用户发消息的iframe弹窗页
     *
     * @return void
     */
    public function sendMessage(){
        $friendUser = User::getUser($this->page->get->frienduid);
        if(submitcheck('formhash')){
            $message = new Message($this->page->loginUser, $this->page->get->content, $friendUser);
            foreach(array_unique((array)$this->page->get->type) as $v){
                $message->send($v);
            }
            $this->page->tips();
        }else{
            $this->page->assign('friendUid', $friendUser->uid);
            $this->page->assign('userName', $friendUser->username);
            $this->page->assign('checkAljol', $this->page->config->aljol ? 'checked' : '');
            $this->page->assign('checkAljdx', $this->page->config->aljdx ? 'checked' : '');
            $this->page->assign('checkMappTemplate', $this->page->config->mapp_template ? 'checked' : '');
            $this->page->display();
        }

    }
}

