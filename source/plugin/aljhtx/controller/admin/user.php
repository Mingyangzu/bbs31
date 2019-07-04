<?php

/**
 * Controller��userģ��
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
     * �����������ʼ����
     *
     * @return void
     */
    public function import(){
            if(intval($this->page->get->i) <=0 || intval($this->page->get->i) > 4){
                exit;
            }
            $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//��ǰҳ
            $nextPage = $curPage + 1;
            $perPageNum = 50;//ÿ����������ٸ��û�
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
            $start = ($curPage-1) * $perPageNum;//��������ʼ�û�����
            //$start = $start ? $start : 1;
            $end  = $start+($perPageNum>=$userNum ? $userNum: $perPageNum);//����������û�����
            if($curPage > $maxPapge){
                header('Location: plugin.php?id=aljhtx&c=user&a=showImportMessage');
                exit;
            }

            //��ʼ���ݵ���
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
            //�������ݵ���

            $progressBar = T::getObject('progressbar', array(
                'requestPage' => $this->page,
                'tips' => lang("plugin/aljhtx","user_php_3").$start.'-'.$end.lang("plugin/aljhtx","user_php_4"),
                'curPage' => $curPage,
                'maxPage' => $maxPapge ? $maxPapge : 1,
                'url' => 'plugin.php?id=aljhtx&c=user&a=import&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage.'&i='.$this->page->get->i,
                'goUrl' => 'plugin.php?id=aljhtx&c=user&a=showImportMessage',
            ));
            $progressBar->start(); //���ؽ�����ģ��
    }

    /**
     * ����Ƿ�װҪ�����΢�ŵ�¼���
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
     * ���QQ
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
     * ���΢��
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
     * �޸�����
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
     * �����ʾҳ
     *
     * @return void
     */
    public function showMessage(){
        $this->page->showMessage(lang("plugin/aljhtx","user_php_5"), '<a style="color: rgb(30, 159, 255);" href="plugin.php?id=aljhtx&c=user&a=sendgroupmessage">' . lang("plugin/aljhtx","user_php_1") . '</a>');
    }

    /**
     * ��������ʾҳ
     *
     * @return void
     */
    public function showImportMessage(){
        $this->page->showMessage(lang("plugin/aljhtx","user_php_6"), '<a style="color: rgb(30, 159, 255);" href="plugin.php?id=aljhtx&c=user&a=user">' . lang("plugin/aljhtx","user_php_2") . '</a>');
    }


    /**
     * ����û��б�ҳ
     *
     * @return void
     */
    public function user(){
        T::getObject('Order');
        T::getObject('Aljbd');
        T::getObject('Aljwsq');
        T::getObject('Aljqq');
        $userList = User::getUserListByGroupid('', '', 'DESC', $this->page->get->curpage, 20, $this->page); //�û��б�����
        $pagingUrl = A_URL.'&search='.$this->page->get->search.'&curpage='; //��ҳ��ת����
        $userGroupList = User::getUserGroupList(); //�û�������
        $userNum = User::countUserNumByGroupid(); //���û���
        if($this->page->config->aljwsq){
            $wechatUserNum = Aljwsq::count(); //΢�����û���
        }
        if($this->page->config->aljqq){
            $qqUserNum = Aljqq::count(); //QQ���û���
        }
        if($this->page->config->aljbd){
            $bdUserNum = Aljbd::count(); //�̼��û���
        }
        if($this->page->config->aljgwc){
            $countOrderMoney = Order::countOrderMoney(); //���������ܽ��
        }
        $wechatUserNum = $wechatUserNum ? $wechatUserNum : 0;
        $qqUserNum = $qqUserNum ? $qqUserNum : 0;
        $bdUserNum = $bdUserNum ? $bdUserNum : 0;
        $countOrderMoney = $countOrderMoney ? $countOrderMoney : 0;
        $this->page->assign('dourl', PAGE_URL); //��ǰ����ҳ��URL
        $this->page->assign('userGroupList', $userGroupList, true); //�û�������
        $this->page->assign('userList', $userList); //�û��б�����
        $this->page->assign('mobileUserNum', User::countUserNumByMobile()); //�Ѱ��ֻ����û�
        $this->page->assign('bdUserNum', $bdUserNum); //�̼��û���
        $this->page->assign('countOrderMoney', $countOrderMoney); //���������ܽ��
        $this->page->assign('wechatUserNum', $wechatUserNum); //΢�����û���
        $this->page->assign('qqUserNum', $qqUserNum); //QQ���û���
        $this->page->assign('userNum', $userNum); //���û���
        $this->page->assign('paging', $this->page->paging($userNum, $pagingUrl), true); //���û���
        $this->page->display();
    }

    /**
     * ��ָ���û���Ⱥ����Ϣ-��ʼȺ��
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
     * ��ָ���û���Ⱥ����Ϣ��iframe����ҳ-����ģ��
     *
     * @return void
     */
    public function sendGroupMessage(){
        if ($this->page->get->formhash == FORMHASH) {
            $curPage = $this->page->get->curpage ? $this->page->get->curpage : 1;//��ǰҳ
            $nextPage = $curPage + 1;
            $perPageNum = 50;//ÿ����������ٸ��û�
            $groupUserNum = User::countUserNumByGroupid($this->page->get->groupid);
            $maxPapge = ceil($groupUserNum/$perPageNum);
            $start = ($curPage-1) * $perPageNum;//��������ʼ�û�����
            //$start = $start ? $start : 1;
            $end  = $start+($perPageNum>=$groupUserNum ? $groupUserNum: $perPageNum);//����������û�����
            if($curPage > $maxPapge){
                header('Location: plugin.php?id=aljhtx&c=user&a=showmessage');
                exit;
            }

            //��Ⱥ���������͵�����-��ʼ
            $cron = T::getObject('cron', array(
                'pid' => 0,
                'touid' => 0,
                'sync' => 1, //0Ϊ�첽���� 1Ϊͬ������
                'param' => serialize(array('groupid' => $this->page->get->groupid, 'type' => $this->page->get->type, 'curPage' => $curPage)),
                'type' => 'sendGroupMessage',
                'content' => $this->page->get->content
            ));
            $cronId = $cron->push();
            //��Ⱥ���������͵�����-����

            $progressBar = T::getObject('progressbar', array(
                'requestPage' => $this->page,
                'tips' => lang("plugin/aljhtx","user_php_7").$start.'-'.$end.lang("plugin/aljhtx","user_php_8"),
                'curPage' => $curPage,
                'maxPage' => $maxPapge ? $maxPapge : 1,
                'doSendGroupMessageUrl' => C_URL . '&a=' . 'doSendGroupMessage&ajax=yes&cronid=' . $cronId,
                'url' => 'plugin.php?id=aljhtx&c=user&a=sendgroupmessage&ajax=yes&formhash='.FORMHASH.'&curpage='.$nextPage.'&groupid='.$this->page->get->groupid,
                'goUrl' => 'plugin.php?id=aljhtx&c=user&a=showmessage',
            ));
            $progressBar->start(); //���ؽ�����ģ��
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
     * ��ָ���û�����Ϣ��iframe����ҳ
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

