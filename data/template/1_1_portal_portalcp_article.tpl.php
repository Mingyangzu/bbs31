<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('portalcp_article');
0
|| checktplrefresh('./template/default/portal/portalcp_article.htm', './template/default/home/editor_image_menu.htm', 1562549342, '1', './data/template/1_1_portal_portalcp_article.tpl.php', './template/default', 'portal/portalcp_article')
|| checktplrefresh('./template/default/portal/portalcp_article.htm', './template/default/common/seccheck.htm', 1562549342, '1', './data/template/1_1_portal_portalcp_article.tpl.php', './template/default', 'portal/portalcp_article')
|| checktplrefresh('./template/default/portal/portalcp_article.htm', './template/default/common/upload.htm', 1562549342, '1', './data/template/1_1_portal_portalcp_article.tpl.php', './template/default', 'portal/portalcp_article')
;?><?php include template('common/header'); if($op == 'delete') { ?>

<h3 class="flb">
<em>删除文章</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>

<form method="post" autocomplete="off" action="portal.php?mod=portalcp&amp;ac=article&amp;op=delete&amp;aid=<?php echo $_GET['aid'];?>">
<div class="c">
<?php if($_G['group']['allowpostarticlemod'] && $article['status'] == 1) { ?>
确认要删除此文章吗？
<input type="hidden" name="optype" value="0" class="pc" />
<?php } else { ?>
<label class="lb"><input type="radio" name="optype" value="0" class="pc" />直接删除</label>
<label class="lb"><input type="radio" name="optype" value="1" class="pc" checked="checked" />放入回收站</label>
<?php } ?>
</div>
<p class="o pns">
<button type="submit" name="btnsubmit" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
<input type="hidden" name="aid" value="<?php echo $_GET['aid'];?>" />
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="deletesubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
</form>
<?php } elseif($op == 'verify') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">审核文章</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>

<form method="post" autocomplete="off" id="aritcle_verify_<?php echo $aid;?>" action="portal.php?mod=portalcp&amp;ac=article&amp;op=verify&amp;aid=<?php echo $aid;?>">
<div class="c">
<label for="status_0" class="lb"><input type="radio" class="pr" name="status" value="0" id="status_0"<?php if($article['status']=='1') { ?> checked="checked"<?php } ?> />通过</label>
<label for="status_x" class="lb"><input type="radio" class="pr" name="status" value="-1" id="status_x" />删除</label>
<label for="status_2" class="lb"><input type="radio" class="pr" name="status" value="2" id="status_2"<?php if($article['status']=='2') { ?> checked="checked"<?php } ?> />忽略</label>
</div>
<p class="o pns">
<button type="submit" name="btnsubmit" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
<input type="hidden" name="aid" value="<?php echo $aid;?>" />
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<input type="hidden" name="verifysubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
</form>
<?php } elseif($op == 'related') { if($ra) { ?>
<li id="raid_li_<?php echo $ra['aid'];?>"><input type="hidden" name="raids[]" value="<?php echo $ra['aid'];?>" size="5">[ <?php echo $ra['aid'];?> ] <a href="<?php echo fetch_article_url($ra);; ?>" target="_blank"><?php echo $ra['title'];?></a> <a href="javascript:;" onclick="raid_delete(<?php echo $ra['aid'];?>);">删除</a></li>
<?php } } elseif($op == 'pushplus') { ?>
<h3 class="flb">
<em>文章连载</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<form method="post" target="_blank" action="portal.php?mod=portalcp&amp;ac=article&amp;tid=<?php echo $tid;?>&amp;aid=<?php echo $aid;?>">
<div class="c">
<b><?php echo $pushcount;?></b> 个新帖将添加到文章连载。<a href="<?php echo $article_url;?>" target="_blank" class="xi2">(查看文章)</a>
<?php if($pushedcount) { ?><br />提示：<?php echo $pushedcount;?> 个帖子已经在连载中了<?php } ?>
<div id="pushplus_list"><?php if(is_array($pids)) foreach($pids as $pid) { ?><input type="hidden" name="pushpluspids[]" value="<?php echo $pid;?>" />
<?php } ?>
</div>
</div>
<p class="o pns">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="pushplussubmit" value="1" />

<input type="hidden" name="toedit" value="1" />
<button type="submit" class="pn pnc vm"><span>提交</span></button>
</p>
</form>
<?php } elseif($op == 'add_success') { ?>
<div class="nfl">
<div class="f_c altw">
<div class="alert_right">
<p>发布文章成功</p>
<p class="alert_btnleft">
<a href="<?php echo $article_add_url;?>&op=edit&aid=<?php echo $aid;?>">编辑文章</a>
<span class="pipe">|</span>
<a href="<?php echo $article_add_url;?>">继续发布新文章</a>
<span class="pipe">|</span>
<a href="portal.php?mod=view&amp;aid=<?php echo $aid;?>" target="_blank">查看文章</a>
<?php if($htmlstatus) { ?>
<span class="pipe">|</span>
<span id='makehtml_' mktitle="文章"></span>
<?php } ?>
</p>
</div>
</div>
</div>
<script src="<?php echo STATICURL;?>js/makehtml.js" type="text/javascript" type="text/javascript"></script>
<script type="text/javascript">
<?php if(!empty($_G['cookie']['clearUserdata']) && $_G['cookie']['clearUserdata'] == 'home') { ?>
saveUserdata('home', '')
<?php } ?>
make_html('portal.php?mod=view&aid=<?php echo $aid;?>', $('makehtml_'));
</script>
<?php } else { ?>

<div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="<?php echo $_G['setting']['navs']['1']['filename'];?>"><?php echo $_G['setting']['navs']['1']['navname'];?></a> <em>&rsaquo;</em>
<?php if($catid ) { ?>
<a href="<?php echo $portalcategory[$catid]['caturl'];?>"><?php echo $portalcategory[$catid]['catname'];?></a> <em>&rsaquo;</em>
<?php } if(!empty($aid)) { ?>
<a href="portal.php?mod=portalcp&amp;ac=article&amp;aid=<?php echo $article['aid'];?>">编辑文章</a>
<?php } else { ?>
<a href="portal.php?mod=portalcp&amp;ac=article&amp;catid=<?php echo $catid;?>">发布文章</a>
<?php } ?>
</div>
</div>

<div id="ct" class="wp cl">
<div class="mn">
<div class="bm bw0">
<h1 class="mbm bbs mt"><?php if(!empty($aid)) { ?>编辑文章<?php } else { ?>发布文章<?php } ?></h1>
<script src="<?php echo $_G['setting']['jspath'];?>calendar.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<form method="post" autocomplete="off" id="articleform" action="portal.php?mod=portalcp&amp;ac=article<?php if($_GET['modarticlekey']) { ?>&amp;modarticlekey=<?php echo $_GET['modarticlekey'];?><?php } ?>" enctype="multipart/form-data">
<?php if(!empty($_G['setting']['pluginhooks']['portalcp_top'])) echo $_G['setting']['pluginhooks']['portalcp_top'];?>
<div class="dopt cl">
<span class="z mtn" style="width: 80px;">标　　题:&nbsp;</span>
<input type="text" name="title" id="title" class="px" value="<?php echo $article['title'];?>" size="80" />
<input type="button" id="color_style" class="pn colorwd" title="点击选择颜色" fwin="eleStyle" onclick="change_title_color(this.id);" style="background-color:<?php echo $stylecheck['0'];?>" />
<input type="hidden" id="highlight_style_0" name="highlight_style[0]" value="<?php echo $stylecheck['0'];?>" />
<input type="hidden" id="highlight_style_1" name="highlight_style[1]" value="<?php echo $stylecheck['1'];?>" />
<input type="hidden" id="highlight_style_2" name="highlight_style[2]" value="<?php echo $stylecheck['2'];?>" />
<input type="hidden" id="highlight_style_3" name="highlight_style[3]" value="<?php echo $stylecheck['3'];?>" />
<a href="javascript:;" id="highlight_op_1" onclick="switchhl(this, 1)" class="dopt_b<?php if($stylecheck['1']) { ?> cnt<?php } ?>" style="text-decoration:none;font-weight:700" title="文字加粗">B</a>
<a href="javascript:;" id="highlight_op_2" onclick="switchhl(this, 2)" class="dopt_i<?php if($stylecheck['2']) { ?> cnt<?php } ?>" style="text-decoration:none;font-style:italic" title="文字斜体">I</a>
<a href="javascript:;" id="highlight_op_3" onclick="switchhl(this, 3)" class="dopt_l<?php if($stylecheck['3']) { ?> cnt<?php } ?>" style="text-decoration:underline" title="文字加下划线">U</a>
</div>
<div id="htmlname_" class="dopt mtn cl"<?php if(!$htmlstatus) { ?> style="display: none"<?php } ?>>
<span class="z mtn" style="width: 80px;">HTML文件名:</span>
<input type="text" name="htmlname" id="htmlname" class="px" value="<?php echo $article['htmlname'];?>" size="80" onblur="check_htmlname_exists(this)"/>.<?php echo $_G['setting']['makehtml']['extendname'];?>
<strong id="checkhtmlnamemsg"></strong>
<input type="hidden" name="oldhtmlname" id="oldhtmlname" value="<?php echo $article['htmlname'];?>" />
</div>
<div id="pagetitle_" class="dopt mtn cl"<?php if($article['contents'] < 2) { ?> style="display: none"<?php } ?>>
<span class="z mtn" style="width: 80px;">分页标题:&nbsp;</span>
<input type="text" name="pagetitle" id="pagetitle" class="px" value="<?php echo $article_content['title'];?>" size="80" />
</div>

<div class="exfm pns cl">
<div class="sinf sppoll z">
<dl>
<?php if($_G['cache']['portalcategory'] && $categoryselect) { ?>
<dt>频道栏目</dt>
<dd><div class="ftid"><?php echo $categoryselect;?></div><script type="text/javascript">simulateSelect('catid', 158);</script></dd>
<?php } ?>

<dt>文章来源</dt>
<dd>
<input type="text" id="from" name="from" class="px p_fre" value="<?php echo $article['from'];?>" <?php if($from_cookie) { ?>size="10"<?php } else { ?>size="30"<?php } ?> />
<?php if($from_cookie) { ?>
<select name="from_cookie" id="from_cookie" class="ps" onchange="$('from').value=this.value;" style="width:96px;">
<option value="" selected>请选择</option><?php if(is_array($from_cookie)) foreach($from_cookie as $var) { ?><option value="<?php echo $var;?>"><?php echo $var;?></option>
<?php } ?>
</select>
<?php } ?>
</dd>
<dt>来源地址</dt>
<dd><input type="text" name="fromurl" class="px p_fre" value="<?php echo $article['fromurl'];?>" size="30" /></dd>
<dt>发布时间</dt>
<dd><input type="text" name="dateline" class="px p_fre" value="<?php echo $article['dateline'];?>" size="30" onclick="showcalendar(event, this, true)" /></dd>

</dl>
</div>
<div class="sadd z">
<dl>
<?php if(empty($article['aid'])) { ?>
<dt>自动获取</dt>
<dd>
<span class="ftid">
<select name="from_idtype" id="from_idtype" class="ps" change="$('ele_getauthorall').style.display=($('from_idtype').value=='tid' ? '' : 'none');">
<option value="tid"<?php echo $idtypes['tid'];?>>帖子 tid</option>
<option value="blogid"<?php echo $idtypes['blogid'];?>>日志 id</option>
</select>

</span>
<script type="text/javascript">simulateSelect('from_idtype', 81);</script>
<input type="text" name="from_id" id="from_id" value="<?php echo $_GET['from_id'];?>" size="8" class="px p_fre vm" />&nbsp;
<?php if($_GET['from_idtype'] == 'tid') { ?>
<span id="ele_getauthorall">
<label for="getauthorall"><input type="checkbox" name="getauthorall" id="getauthorall" value="1" class="pc" <?php if($_GET['getauthorall']) { ?>checked="checked"<?php } ?>/>获取楼主所有帖子</label>
</span>
<?php } ?>
<button type="button" name="from_button" class="pn vm" onclick="return from_get();"><em>获取</em></button>
<input type="hidden" name="id" value="<?php echo $_GET['from_id'];?>" />
<input type="hidden" name="idtype" value="<?php echo $_GET['from_idtype'];?>" />
</dd>
<?php } ?>
<dt>跳转URL</dt>
<dd><input type="text" class="px p_fre" name="url" value="<?php echo $article['url'];?>" size="30" /></dd>
<dt>原作者</dt>
<dd><input type="text" name="author" class="px p_fre" value="<?php echo $article['author'];?>" size="30" /></dd>
<?php if($category[$catid]['allowcomment']) { ?>
<dt>评论设置</dt>
<dd><label for="ck_allowcomment"><input type="checkbox" name="forbidcomment" id="ck_allowcomment" class="pc" value="1"<?php if(isset($article['allowcomment']) && empty($article['allowcomment'])) { ?>checked="checked"<?php } ?> />禁止评论</label></dd>
<?php } ?>
</dl>
<div><input type="hidden" id="conver" name="conver" value="" /></div>
</div>
<?php if(!empty($_G['setting']['pluginhooks']['portalcp_extend'])) echo $_G['setting']['pluginhooks']['portalcp_extend'];?>
</div>

<div class="pbw">
<script src="<?php echo STATICURL;?>image/editor/editor_function.js?<?php echo VERHASH;?>" type="text/javascript"></script><div id="icoImg_image_menu" style="display: none" unselectable="on">
<table width="100%" cellpadding="0" cellspacing="0" class="fwin"><tr><td class="t_l"></td><td class="t_c"></td><td class="t_r"></td></tr><tr><td class="m_l">&nbsp;&nbsp;</td><td class="m_c"><div class="mtm mbm">
<ul class="tb tb_s cl" id="icoImg_image_ctrl" style="margin-top:0;margin-bottom:0;">
<li class="y"><span class="flbc" onclick="hideAttachMenu('icoImg_image_menu')">关闭</span></li>
<?php if($_G['basescript'] == 'home' && $_G['group']['allowupload'] || $_G['basescript'] == 'portal') { ?>
<li class="current" id="icoImg_btn_imgattachlist"><a href="javascript:;" hidefocus="true" onclick="switchImagebutton('imgattachlist');">上传图片</a></li>
<?php } if(helper_access::check_module('album')) { ?>
<li id="icoImg_btn_albumlist" <?php if($_G['basescript'] == 'home' && !$_G['group']['allowupload']) { ?> class="current"<?php } ?>><a href="javascript:;" hidefocus="true" onclick="switchImagebutton('albumlist');">相册图片</a></li>
<?php } ?>
<li id="icoImg_btn_www" <?php if($_G['basescript'] == 'home' && !$_G['group']['allowupload'] && !helper_access::check_module('album')) { ?> class="current"<?php } ?>><a href="javascript:;" hidefocus="true" onclick="switchImagebutton('www');">网络图片</a></li>
</ul>
<div class="p_opt popupfix" unselectable="on" id="icoImg_www" <?php if($_G['basescript'] == 'home' && ($_G['group']['allowupload'] || helper_access::check_module('album')) || $_G['basescript'] == 'portal') { ?> style="display: none"<?php } ?>>
<table cellpadding="0" cellspacing="0" width="100%">
<tr class="xg1">
<th>请输入图片地址</th>
<th>宽(可选)</th>
<th>高(可选)</th>
</tr>
<tr>
<td width="74%"><input type="text" id="icoImg_image_param_1" onchange="loadimgsize(this.value)" style="width: 95%;" value="" class="px" autocomplete="off" /></td>
<td width="13%"><input id="icoImg_image_param_2" size="3" value="" class="px p_fre" autocomplete="off" /></td>
<td width="13%"><input id="icoImg_image_param_3" size="3" value="" class="px p_fre" autocomplete="off" /></td>
</tr>
<tr>
<td colspan="3" class="pns ptn">
<button type="button" class="pn pnc" onclick="insertWWWImg();"><strong>提交</strong></button>
</td>
</tr>
</table>
</div>
<div class="p_opt" unselectable="on" id="icoImg_imgattachlist"<?php if($_G['basescript'] == 'home' && !$_G['group']['allowupload']) { ?> style="display: none;"<?php } ?>>
<div class="pbm bbda cl">
<div id="uploadPanel" class="y">
<?php if($_G['basescript'] != 'portal') { ?>
上传到:
<select name="savealbumid" id="savealbumid" class="ps vm" onchange="if(this.value == '-1') {selectCreateTab(0);}">
<option value="0">默认相册</option><?php if(is_array($albums)) foreach($albums as $value) { ?><option value="<?php echo $value['albumid'];?>"><?php echo $value['albumname'];?></option>
<?php } ?>
<option value="-1" style="color:red;">+创建新相册</option>
</select>
<?php } ?>
</div>
<div id="createalbum" class="y" style="display:none">
<input type="text" name="newalbum" id="newalbum" class="px vm" value="请输入相册名称"  onfocus="if(this.value == '请输入相册名称') {this.value = '';}" onblur="if(this.value == '') {this.value = '请输入相册名称';}" />
<button type="button" class="pn pnc" onclick="createNewAlbum();"><span>创建</span></button>
<button type="button" class="pn" onclick="selectCreateTab(1);"><span>取消</span></button>
</div>
<span id="imgSpanButtonPlaceholder"></span>
</div>
<div class="upfilelist upfl bbda">
<div id="imgattachlist">
<?php if($_G['basescript'] == 'portal') { ?><?php echo $article['attachs'];?><?php } ?>
</div>
<div class="fieldset flash" id="imgUploadProgress"></div>
</div>
<p class="notice">点击图片添加到编辑器内容中</p>
</div>
<?php if(helper_access::check_module('album')) { ?>
<div class="p_opt" unselectable="on" id="icoImg_albumlist" <?php if($_G['basescript'] == 'home' && $_G['group']['allowupload'] || $_G['basescript'] == 'portal') { ?> style="display: none;"<?php } ?>>
<div class="upfilelist pbm bbda">
选择相册:
<select name="view_albumid" onchange="picView(this.value, 'albumphoto')" class="ps">
<option value="none">选择相册</option>
<option value="0">默认相册</option><?php if(is_array($albums)) foreach($albums as $value) { ?><option value="<?php echo $value['albumid'];?>"><?php echo $value['albumname'];?></option>
<?php } ?>
</select>
<div id="albumphoto"></div>
</div>
<p class="notice">点击图片添加到编辑器内容中</p>
</div>
<?php } ?>
</div></td><td class="m_r"></td></tr><tr><td class="b_l"></td><td class="b_c"></td><td class="b_r"></td></tr></table>
</div>


<div id="icoAttach_attach_menu" style="display: none" unselectable="on">
<table width="100%" cellpadding="0" cellspacing="0" class="fwin">
<tr>
<td class="t_l"></td>
<td class="t_c"></td>
<td class="t_r"></td>
</tr>
<tr>
<td class="m_l">&nbsp;&nbsp;</td>
<td class="m_c">
<div class="mtm mbm">
<ul class="tb tb_s cl" id="icoAttach_attach_ctrl" style="margin-top:0;margin-bottom:0;">
<li class="y"><span class="flbc" onclick="hideAttachMenu('icoAttach_attach_menu')">关闭</span></li>
<li class="current" id="icoAttach_btn_attachlist"><a href="javascript:;" hidefocus="true" onclick="switchAttachbutton('attachlist');">上传附件</a></li>
</ul>
<div class="p_opt post_tablelist" unselectable="on" id="icoAttach_attachlist">
<div class="pbm bbda">
<span id="spanButtonPlaceholder"></span>
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="attach_tblheader" class="mtn bbs" style="display: none;">
<tr>
<td class="atnu"></td>
<td class="atna pbn">文件名</td>
<td class="atds pbn">文件大小</td>
<td class="attc"></td>
</tr>
</table>
<div class="upfl">
<div id="attachlist"></div>
<div class="fieldset flash" id="fsUploadProgress"></div>
</div>
<div class="notice upnf">
<p id="attach_notice">点击文件名将附件添加到文章中</p>
</div>
</div>
</div>
</td>
<td class="m_r"></td>
</tr>
<tr>
<td class="b_l"></td>
<td class="b_c"></td>
<td class="b_r"></td>
</tr>
</table>
</div>

<iframe name="attachframe" id="attachframe" style="display: none;"></iframe>

<?php if($_G['basescript'] == 'home' && empty($_G['setting']['pluginhooks']['spacecp_blog_upload_extend']) || $_G['basescript'] == 'portal' && empty($_G['setting']['pluginhooks']['portalcp_top_upload_extend'])) { if(empty($_G['uploadjs'])) { ?>
<script src="<?php echo $_G['setting']['jspath'];?>upload.js?<?php echo VERHASH;?>" type="text/javascript"></script><?php $_G['uploadjs'] = 1;?><?php } ?><script type="text/javascript">
var attachUpload = new SWFUpload({
// Backend Settings
upload_url: "<?php echo $_G['siteurl'];?>misc.php?mod=swfupload&action=swfupload&operation=<?php if($_G['basescript'] == 'portal') { ?>portal<?php } else { ?>album<?php } ?>",
post_params: {"uid" : "<?php echo $_G['uid'];?>", "hash":"<?php echo $swfconfig['hash'];?>"<?php if($_G['basescript'] == 'portal') { ?>,"aid":<?php echo $aid;?>,"catid":<?php echo $catid;?><?php } ?>},

// File Upload Settings
file_size_limit : "<?php echo $swfconfig['max'];?>",	// 100MB
<?php if($_G['basescript'] == 'portal') { ?>
file_types : "<?php echo $swfconfig['attachexts']['ext'];?>",
file_types_description : "<?php echo $swfconfig['attachexts']['depict'];?>",
<?php } else { ?>
file_types : "<?php echo $swfconfig['imageexts']['ext'];?>",
file_types_description : "<?php echo $swfconfig['imageexts']['depict'];?>",
<?php } ?>
file_upload_limit : 0,
file_queue_limit : 0,

// Event Handler Settings (all my handlers are in the Handler.js file)
swfupload_preload_handler : preLoad,
swfupload_load_failed_handler : loadFailed,
file_dialog_start_handler : fileDialogStart,
file_queued_handler : fileQueued,
file_queue_error_handler : fileQueueError,
file_dialog_complete_handler : fileDialogComplete,
upload_start_handler : uploadStart,
upload_progress_handler : uploadProgress,
upload_error_handler : uploadError,
upload_success_handler : uploadSuccess,
upload_complete_handler : uploadComplete,

// Button Settings
button_image_url : "<?php echo IMGDIR;?>/uploadbutton.png",
button_placeholder_id : "spanButtonPlaceholder",
button_width: 100,
button_height: 25,
button_cursor:SWFUpload.CURSOR.HAND,
button_window_mode: "transparent",

custom_settings : {
progressTarget : "fsUploadProgress",
uploadSource: 'portal',
uploadType: 'attach',
imgBoxObj: $('attachlist')
//thumbnail_height: 400,
//thumbnail_width: 400,
//thumbnail_quality: 100
},

// Debug Settings
debug: false
});
var upload = new SWFUpload({
// Backend Settings
upload_url: "<?php echo $_G['siteurl'];?>misc.php?mod=swfupload&action=swfupload&operation=<?php if($_G['basescript'] == 'portal') { ?>portal<?php } else { ?>album<?php } ?>",
post_params: {"uid" : "<?php echo $_G['uid'];?>", "hash":"<?php echo $swfconfig['hash'];?>"<?php if($_G['basescript'] == 'portal') { ?>,"aid":<?php echo $aid;?>,"catid":<?php echo $catid;?><?php } ?>},

// File Upload Settings
file_size_limit : "<?php echo $swfconfig['max'];?>",	// 100MB
file_types : "<?php echo $swfconfig['imageexts']['ext'];?>",
file_types_description : "<?php echo $swfconfig['imageexts']['depict'];?>",
file_upload_limit : 0,
file_queue_limit : 0,

// Event Handler Settings (all my handlers are in the Handler.js file)
swfupload_preload_handler : preLoad,
swfupload_load_failed_handler : loadFailed,
file_dialog_start_handler : fileDialogStart,
file_queued_handler : fileQueued,
file_queue_error_handler : fileQueueError,
file_dialog_complete_handler : fileDialogComplete,
upload_start_handler : uploadStart,
upload_progress_handler : uploadProgress,
upload_error_handler : uploadError,
upload_success_handler : uploadSuccess,
upload_complete_handler : uploadComplete,

// Button Settings
button_image_url : "<?php echo IMGDIR;?>/uploadbutton.png",
button_placeholder_id : "imgSpanButtonPlaceholder",
button_width: 100,
button_height: 25,
button_cursor:SWFUpload.CURSOR.HAND,
button_window_mode: "transparent",

custom_settings : {
progressTarget : "imgUploadProgress",
uploadSource: 'portal',
uploadType: <?php if($_G['basescript'] == 'portal') { ?>'portal'<?php } else { ?>'blog'<?php } ?>,
imgBoxObj: $('imgattachlist')
//thumbnail_height: 400,
//thumbnail_width: 400,
//thumbnail_quality: 100
},

// Debug Settings
debug: false
});
</script>
<?php } else { if($_G['basescript'] == 'home') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['spacecp_blog_upload_extend'])) echo $_G['setting']['pluginhooks']['spacecp_blog_upload_extend'];?>
<?php } elseif($_G['basescript'] == 'portal') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['portalcp_top_upload_extend'])) echo $_G['setting']['pluginhooks']['portalcp_top_upload_extend'];?>
<?php } } ?>
<script type="text/javascript">
function switchImagebutton(btn) {
switchButton(btn, 'image');
$('icoImg_image_menu').style.height = '';
doane();
}
function hideAttachMenu(id) {
if($(id)) {
$(id).style.visibility = 'hidden';
}
}

function insertWWWImg() {
var urlObj = $('icoImg_image_param_1');
if(urlObj.value != '') {
var widthObj = $('icoImg_image_param_2');
var heightObj = $('icoImg_image_param_3');
insertImage(urlObj.value, null, widthObj.value, heightObj.value);
urlObj.value = widthObj.value = heightObj.value = '';
}
}
//note 选择图片
function picView(albumid, listid) {
if(albumid == 'none') {
$(listid).innerHTML = '';
} else {
ajaxget('home.php?mod=misc&ac=ajax&op=album&id='+albumid+'&ajaxdiv=albumpic_body', listid);
}
}
function createNewAlbum() {
var inputObj = $('newalbum');
if(inputObj.value == '' || inputObj.value == '请输入相册名称') {
inputObj.value = '请输入相册名称';
} else {
var x = new Ajax();
x.get('home.php?mod=misc&ac=ajax&op=createalbum&inajax=1&name=' + inputObj.value, function(s){
var aid = parseInt(s);
var albumList = $('savealbumid');
var haveoption = false;
for(var i = 0; i < albumList.options.length; i++) {
if(albumList.options[i].value == aid) {
albumList.selectedIndex = i;
haveoption = true;
break;
}
}
if(!haveoption) {
var oOption = document.createElement("OPTION");
oOption.text = trim(inputObj.value);
oOption.value = aid;
albumList.options.add(oOption);
albumList.selectedIndex = albumList.options.length-1;
}
inputObj.value = ''
});
selectCreateTab(1)
}
}
function selectCreateTab(flag) {
var vwObj = $('uploadPanel');
var opObj = $('createalbum');
var selObj = $('savealbumid');
if(flag) {
vwObj.style.display = '';
opObj.style.display = 'none';
selObj.value = selObj.options[0].value;
} else {
vwObj.style.display = 'none';
opObj.style.display = '';
}
}
</script>
<textarea class="userData" name="content" id="uchome-ttHtmlEditor" style="height: 100%; width: 100%; display: none; border: 0px"><?php echo $article_content['content'];?></textarea>
<div style="border:1px solid #C5C5C5;height:400px;"><iframe src="home.php?mod=editor&amp;charset=<?php echo CHARSET;?>&amp;allowhtml=1&amp;isportal=1" name="uchome-ifrHtmlEditor" id="uchome-ifrHtmlEditor" scrolling="no" border="0" frameborder="0" style="width:100%;height:100%;position:relative;"></iframe></div>
</div>

<?php if(!empty($_G['setting']['pluginhooks']['portalcp_middle'])) echo $_G['setting']['pluginhooks']['portalcp_middle'];?>

<div class="bm bml">
<div class="bm_h cl">
<h2>摘要</h2>
</div>
<div class="bm_c"><textarea name="summary" cols="80" class="pt" style="width: 98.7%; height: 51px;"><?php echo $article['summary'];?></textarea></div>
</div>

<div class="bm bml">
<div class="bm_h cl">
<h2>聚合标签</h2>
</div>
<div class="bm_c"><?php if(is_array($article_tags)) foreach($article_tags as $key => $tag) { ?><input type="checkbox" name="tag[<?php echo $key;?>]" id="article_tag_<?php echo $key;?>" class="pc"<?php if($article_tags[$key]) { ?> checked="checked"<?php } ?> />
<label for="article_tag_<?php echo $key;?>" class="lb"><?php echo $tag_names[$key];?></label>
<?php } ?>
</div>
</div>

<div class="exfm">
<h2>添加相关文章 <a id="related_article" href="portal.php?mod=portalcp&amp;ac=related&amp;aid=<?php echo $aid;?>" class="xi2" style="text-decoration: underline;" onclick="showWindow(this.id, this.href+'&catid='+$('catid').value, 'get', 0);return false;">选择</a></h2>
<ul id="raid_div" class="xl">
<?php if($article['related']) { if(is_array($article['related'])) foreach($article['related'] as $raid => $rtitle) { ?><li id="raid_li_<?php echo $raid;?>"><input type="hidden" name="raids[]" value="<?php echo $raid;?>" size="5"><a href="portal.php?mod=view&amp;aid=<?php echo $raid;?>" target="_blank"><?php echo $rtitle;?></a> (文章 ID: <?php echo $raid;?>) <a href="javascript:;" onclick="raid_delete(<?php echo $raid;?>);" class="xg1">删除</a></li>
<?php } } ?>
</ul>
</div>

<?php if(!empty($_G['setting']['pluginhooks']['portalcp_bottom'])) echo $_G['setting']['pluginhooks']['portalcp_bottom'];?>

<?php if($secqaacheck || $seccodecheck) { ?><?php
$sectpl = <<<EOF
<sec> <span id="sec<hash>" onclick="showMenu(this.id)"><sec></span><div id="sec<hash>_menu" class="p_pop p_opt" style="display:none"><sec></div>
EOF;
?>
<div class="exfm pns"><?php $sechash = !isset($sechash) ? 'S'.($_G['inajax'] ? 'A' : '').$_G['sid'] : $sechash.random(3);
$sectpl = str_replace("'", "\'", $sectpl);?><?php if($secqaacheck) { ?>
<span id="secqaa_q<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updatesecqaa('q<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } if($seccodecheck) { ?>
<span id="seccode_c<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updateseccode('c<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } ?></div>
<?php } ?>

<div class="ptm pbm">
<button type="button" id="issuance" class="pn pnc" name="articlebutton" onclick="validate(this);"><strong>提交</strong></button>
<label id="innernavele"<?php if($article['contents'] < 2) { ?> style="display: none"<?php } ?> for="ck_showinnernav"><input type="checkbox" name="showinnernav" id="ck_showinnernav" class="pc" value="1"<?php if(!empty($article['showinnernav'])) { ?>checked="checked"<?php } ?> />显示分页导航</label>
</div>

<input type="hidden" id="aid" name="aid" value="<?php echo $article['aid'];?>" />
<input type="hidden" name="cid" value="<?php echo $article_content['cid'];?>" />
<input type="hidden" id="attach_ids" name="attach_ids" value="0" />
<input type="hidden" name="articlesubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
</form>
</div>
</div>
</div>
<iframe id="uploadframe" name="uploadframe" width="0" height="0" marginwidth="0" frameborder="0" src="about:blank"></iframe>
<script type="text/javascript">
function from_get() {
var el = $('catid');
var catid = el ? el.value : 0;
window.location.href='portal.php?mod=portalcp&ac=article&from_idtype='+$('from_idtype').value+'&catid='+catid+'&from_id='+$('from_id').value+'&getauthorall='+($('getauthorall').checked ? '1' : '');
return true;
}
function validate(obj) {
var title = $('title');
if(title) {
var slen = strlen(title.value);
if (slen < 1 || slen > 80) {
alert("标题长度(1~80字符)不符合要求");
title.focus();
return false;
}
}
if(!check_catid()) {
return false;
}
edit_save();
window.onbeforeunload = null;
obj.form.submit();
return false;
}
function check_catid(){
var catObj = $("catid");
if(catObj) {
if (catObj.value < 1) {
alert("请选择系统分类");
catObj.focus();
return false;
}
}
return true;
}
function raid_add() {
var raid = $('raid').value;
if($('raid_li_'+raid)) {
alert('该文章已经添加过了');
return false;
}
var url = 'portal.php?mod=portalcp&ac=article&op=related&inajax=1&aid=<?php echo $article['aid'];?>&raid='+raid;
var x = new Ajax();
x.get(url, function(s){
s = trim(s);
if(s) {
$('raid_div').innerHTML += s;
} else {
alert('没有找到指定的文章');
return false;
}
});
}
function raid_delete(aid) {
var node = $('raid_li_'+aid);
var p;
if(p = node.parentNode) {
p.removeChild(node);
}
}
function switchhl(obj, v) {
if(parseInt($('highlight_style_' + v).value)) {
$('highlight_style_' + v).value = 0;
obj.className = obj.className.replace(/ cnt/, '');
} else {
$('highlight_style_' + v).value = 1;
obj.className += ' cnt';
}
}
function change_title_color(hlid) {
var showid = hlid;
if(!$(showid + '_menu')) {
var str = '';
var coloroptions = {'0' : '#000', '1' : '#EE1B2E', '2' : '#EE5023', '3' : '#996600', '4' : '#3C9D40', '5' : '#2897C5', '6' : '#2B65B7', '7' : '#8F2A90', '8' : '#EC1282'};
var menu = document.createElement('div');
menu.id = showid + '_menu';
menu.className = 'cmen';
menu.style.display = 'none';
for(var i in coloroptions) {
str += '<a href="javascript:;" onclick="$(\'highlight_style_0\').value=\'' + coloroptions[i] + '\';$(\'' + showid + '\').style.backgroundColor=\'' + coloroptions[i] + '\';hideMenu(\'' + menu.id + '\')" style="background:' + coloroptions[i] + ';color:' + coloroptions[i] + ';">' + coloroptions[i] + '</a>';
}
menu.innerHTML = str;
$('append_parent').appendChild(menu);
}
showMenu({'ctrlid':hlid + '_ctrl','evt':'click','showid':showid});
}
if($('title')) {
$('title').focus();
}
function setConver(attach) {
$('conver').value = attach;
}

function deleteAttach(attachid, url) {
ajaxget(url);
$('attach_list_' + attachid).style.display = 'none';
if($('setconver' + attachid).checked) {
$('conver').value = '';
}
}
<?php if(!empty($article['conver'])) { ?>
setConver('<?php echo $article['conver'];?>');
<?php } ?>
function check_htmlname_exists(obj) {
name = obj.value;
var msg = $('checkhtmlnamemsg');
if(name && $('oldhtmlname').value != name) {
var catid = $('catid').value;
var aid = $('aid').value;
var x = new Ajax();
x.getJSON('portal.php?mod=portalcp&ac=article&op=checkhtmlname&htmlname='+name+'&catid='+catid+'&aid='+aid, function(s){
if(s['message'] == 'html_existed') {
obj.focus();
msg.style.color = 'red';
msg.style.paddingLeft = '10px';
msg.innerHTML = 'HTML静态文件名重名';
$('issuance').disabled = 'disabled';
} else {
msg.innerHTML = '';
$('issuance').disabled = '';
}
});
} else {
msg.innerHTML = '';
$('issuance').disabled = '';
}
}
</script>

<?php } include template('common/footer'); ?>