<?php
/**
 *	开发团队：IT618
 *	it618_copyright 插件设计：<a href="http://www.cnit618.com" target="_blank" title="专业Discuz!应用及周边提供商">IT618</a>
 */
(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) && exit('Access Denied');
$tempabc=$_GET['identifier'];$reabc=array();
for($i=0;$i<strlen($tempabc);$i++){$reabc[]=substr($tempabc,$i,1);}
if($reabc[5]!='_')return;
if(submitcheck('it618submit')){
	$same1=0;
	$ok1=0;
	$same2=0;
	$ok2=0;
	$del=0;
	
	foreach($_GET['delete'] as $key => $delid) {
		$delid=intval($delid);
		DB::delete('it618_storemapad_class1', "id=$delid");
		$del=$del+1;
	}
	if($reabc[3]!='1')return;
	if(is_array($_GET['it618_classname'])) {
		foreach($_GET['it618_classname'] as $id => $val) {
			$upid=addslashes(trim($_GET['id'][$id]));
			$upit618_classname=addslashes(trim($_GET['it618_classname'][$id]));
			
			if($oldfind = DB::fetch_first("SELECT it618_classname FROM ".DB::table('it618_storemapad_class1')." WHERE it618_classname='".$upit618_classname."' and id<>".$upid)) {
				$same1=$same1+1;
			} else {
				DB::update('it618_storemapad_class1', array(
					'it618_classname' => trim($_GET['it618_classname'][$id]),
					'it618_order' => trim($_GET['it618_order'][$id]),
				), "id='$id'");
				if($id = DB::fetch_first("SELECT id FROM ".DB::table('it618_storemapad_class1')." WHERE id=".$upid)) $ok1=$ok1+1;
			}
		}
	}

	$newit618_classname_array = !empty($_GET['newit618_classname']) ? $_GET['newit618_classname'] : array();
	$newit618_order_array = !empty($_GET['newit618_order']) ? $_GET['newit618_order'] : array();
	
	foreach($newit618_classname_array as $key => $value) {
		$newit618_classname = addslashes(trim($newit618_classname_array[$key]));
		if($reabc[4]!='8')return;
		if($newit618_classname != '') {
			
			if($oldit618_classname = DB::fetch_first("SELECT it618_classname FROM ".DB::table('it618_storemapad_class1')." WHERE it618_classname='$newit618_classname'")) {
				$same2=$same2+1;
			} else {
				DB::insert('it618_storemapad_class1', array(
					'it618_classname' => trim($newit618_classname_array[$key]),
					'it618_order' => trim($newit618_order_array[$key]),
				));
				$ok2=$ok2+1;
			}
		}
	}

	cpmsg($it618_lang_admin[4].$same1.' '.$it618_lang_admin[5].$ok1.' '.$it618_lang_admin[6].$same2.' '.$it618_lang_admin[7].$ok2.' '.$it618_lang_admin[8].$del.')', "action=plugins&identifier=$identifier&cp=admin_class1&pmod=admin&operation=$operation&do=$do&page=$page", 'succeed');
}
if(submitcheck('it618sercsubmit')) {
		$extrasql = "AND it618_classname LIKE '%".addcslashes($_GET['beforeword'],'%_')."%'";	
}
showformheader("plugins&identifier=$identifier&cp=admin_class1&pmod=admin&operation=$operation&do=$do");
showtableheaders($it618_lang_admin[1],'it618_storemapad_class1');
	showsubmit('it618sercsubmit', $it618_lang_admin[9], $it618_lang_admin[10].' <input name="beforeword" value="" class="txt" />');
	$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('it618_storemapad_class1')." w WHERE 1 $extrasql");
	$multipage = multi($count, $ppp, $page, ADMINSCRIPT."?action=plugins&identifier=$identifier&cp=admin_class1&pmod=admin&operation=$operation&do=$do");
	if($reabc[7]!='t')return;
	echo '<tr><td colspan=4>'.$it618_lang_admin[11].$count.'</td></tr>';
	showsubtitle(array('', $it618_lang_admin[12], $it618_lang_admin[13],$it618_lang_admin[14],"<div style='width:100px'></div>"));
	
	$query = DB::query("SELECT * FROM ".DB::table('it618_storemapad_class1')." WHERE 1 $extrasql ORDER BY it618_order ASC LIMIT $startlimit, $ppp");
	while($it618_storemapad =	DB::fetch($query)) {
		$class2count = DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_storemapad_class2')." WHERE it618_class1_id=".$it618_storemapad['id']);
		$disabled="";
		if($class2count>0)$disabled="disabled=\"disabled\"";
		
		showtablerow('', array('class="td25"', '', ''), array(
			"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$it618_storemapad[id]\" $disabled><input type=\"hidden\" name=\"id[$it618_storemapad[id]]\" value=\"$it618_storemapad[id]\">",
			"<input type=\"text\" class=\"txt\" style=\"width:200px\" name=\"it618_classname[$it618_storemapad[id]]\" value=\"$it618_storemapad[it618_classname]\">",
			'<input class="txt" type="text" name="it618_order['.$it618_storemapad['id'].']" value="'.$it618_storemapad['it618_order'].'">',
			$class2count,
			""
		));
	}

	echo <<<EOT
	<script type="text/JavaScript">
	var rowtypedata = [
	[[1,''], [1,'<input type="text" class="txt" style=\"width:200px\" name="newit618_classname[]">'], [1, ' <input class="txt" type="text" name="newit618_order[]">'], [1,'']]
	];
	</script>
EOT;
	echo '<tr><td></td><td colspan="3"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.$lang['add_new'].'</a></div></td></tr>';
	
	showsubmit('it618submit', 'submit', 'del', "<input type=hidden value=$page name=page />", $multipage);
	showtablefooter();
?>