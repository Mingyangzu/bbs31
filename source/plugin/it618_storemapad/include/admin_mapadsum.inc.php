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
	$del=0;
	
	foreach($_GET['delete'] as $key => $delid) {
		$delid=intval($delid);
		DB::delete('it618_storemapad', "id=$delid");
		$del=$del+1;
	}
	
	foreach($_GET['it618_pointname'] as $id => $val) {
		if($reabc[6]!='s')return;
		DB::update('it618_storemapad', array(
			'it618_class2_id' => trim($_GET['it618_class2_id'][$id]),
			'it618_point' => trim($_GET['it618_point'][$id]),
			'it618_pointname' => trim($_GET['it618_pointname'][$id]),
			'it618_pointurl' => trim($_GET['it618_pointurl'][$id]),
			'it618_pointimg' => trim($_GET['it618_pointimg'][$id]),
			'it618_pointabout' => trim($_GET['it618_pointabout'][$id]),
			'it618_width' => trim($_GET['it618_width'][$id]),
			'it618_height' => trim($_GET['it618_height'][$id]),
		), "id='$id'");
		$ok=$ok+1;

	}

	cpmsg($it618_lang_admin[36].$del.$it618_lang_admin[37].$ok, "action=plugins&identifier=$identifier&cp=admin_mapadsum&pmod=admin&operation=$operation&do=$do&page=$page", 'succeed');
}
if($reabc[3]!='1')return;
if(submitcheck('it618sercsubmit')) {
	$extrasql = "and it618_pointname LIKE '%".addcslashes($_GET['beforeword'],'%_')."%'";	
	if(intval($_GET['it618_class2_selid'])!=0){
		$extrasql .= " and it618_class2_id = ".intval($_GET['it618_class2_selid']);
		
		$query = DB::query("SELECT * FROM ".DB::table('it618_storemapad_class2')." where it618_class1_id=".intval($_GET['it618_class1_selid'])." ORDER BY it618_order ASC");
		$indextmp=1;
		$index=0;
		while($it618_tmp =	DB::fetch($query)) {
			if($it618_tmp['id']==intval($_GET['it618_class2_selid'])){
				$index=$indextmp;
			}
			$indextmp+=1;
		}
		$jstmp.="redirec_class_sel('it618_class2_selid',".$index.");";
	}else{
		if(intval($_GET['it618_class1_selid'])!=0){
			$query1 = DB::query("SELECT * FROM ".DB::table('it618_storemapad_class2')." where it618_class1_id=".intval($_GET['it618_class1_selid']));
			$sqltmp="1=0";
			while($it618_tmp =	DB::fetch($query1)) {
				$sqltmp.=" or it618_class2_id = ".$it618_tmp['id'];	
			}
			$extrasql .=" and (".$sqltmp.")";
		}
	}
}
if($reabc[4]!='8')return;
$query1 = DB::query("SELECT * FROM ".DB::table('it618_storemapad_class1')." ORDER BY it618_order ASC");
while($it618_tmp =	DB::fetch($query1)) {
	$tmp.='<option value="'.$it618_tmp['id'].'">'.$it618_tmp['it618_classname'].'</option>';
}
$tmp_sel=str_replace('<option value="'.intval($_GET['it618_class1_selid']).'">','<option value="'.intval($_GET['it618_class1_selid']).'" selected="selected">',$tmp);

$countsel = DB::result_first("SELECT count(1) FROM ".DB::table('it618_storemapad_class2'));
$query1 = DB::query("SELECT * FROM ".DB::table('it618_storemapad_class1')." ORDER BY it618_order ASC");
$n1=1;
$tmp1='';
while($it618_tmp1 =	DB::fetch($query1)) {
	$n2=1;
	$query2 = DB::query("SELECT * FROM ".DB::table('it618_storemapad_class2')." where it618_class1_id=".$it618_tmp1['id']." ORDER BY it618_order ASC");
	while($it618_tmp2 =	DB::fetch($query2)) {
		$tmp1.='select_class['.$n1.']['.$n2.'] = new Option("'.$it618_tmp2['it618_classname'].'", "'.$it618_tmp2['id'].'");';
		$n2=$n2+1;
	}
	$n1=$n1+1;
}
if($reabc[7]!='t')return;
showformheader("plugins&identifier=$identifier&cp=admin_mapadsum&pmod=admin&operation=$operation&do=$do");
showtableheaders($it618_lang_admin[3],'it618_storemapad_sum');
	showsubmit('it618sercsubmit', $it618_lang_admin[9], $it618_lang_admin[38].' <select id="it618_class1_selid" name="it618_class1_selid" onchange="redirec_class(this.options.selectedIndex);"><option value="0">'.$it618_lang_admin[39].'</option>'.$tmp_sel.'</select><select id="it618_class2_selid" name="it618_class2_selid"><option value="0" selected="selected">'.$it618_lang_admin[40].'</option></select>'.$it618_lang_admin[25].' <input name="beforeword" value="" class="txt" />');
	$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('it618_storemapad')." w WHERE 1 $extrasql");
	$multipage = multi($count, $ppp, $page, ADMINSCRIPT."?action=plugins&identifier=$identifier&cp=admin_mapadsum&pmod=admin&operation=$operation&do=$do");
	
	echo '<tr><td colspan=7>'.$it618_lang_admin[26].$count.'</td></tr>';
	showsubtitle(array('', $it618_lang_admin[27],$it618_lang_admin[28], $it618_lang_admin[29],$it618_lang_admin[30],$it618_lang_admin[31],$it618_lang_admin[32],$it618_lang_admin[41],$it618_lang_admin[42],$it618_lang_admin[33],$it618_lang_admin[34],$it618_lang_admin[35]));

	$query = DB::query("SELECT * FROM ".DB::table('it618_storemapad')." WHERE 1 $extrasql LIMIT $startlimit, $ppp");
	while($it618_storemapad = DB::fetch($query)) {
		if($reabc[8]!='o')return;
		showtablerow('', array('class="td25"', '', '', '', '', '', '', '', '', ''), array(
			"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$it618_storemapad[id]\" $disabled><input type=\"hidden\" name=\"id[$it618_storemapad[id]]\" value=\"$it618_storemapad[id]\"><input type=\"hidden\" name=\"it618_class2_id[$it618_storemapad[id]]\" value=\"$it618_storemapad[it618_class2_id]\">",
			it618_storemapad_class1name($it618_storemapad['it618_class2_id']),
			it618_storemapad_class2name($it618_storemapad['it618_class2_id']),
			"<textarea class=\"txt\" style=\"width:80px;height:80px\" name=\"it618_pointname[$it618_storemapad[id]]\">$it618_storemapad[it618_pointname]</textarea>",
			"<textarea class=\"txt\" style=\"width:80px;height:80px\" name=\"it618_point[$it618_storemapad[id]]\">$it618_storemapad[it618_point]</textarea>",
			"<textarea class=\"txt\" style=\"width:80px;height:80px\" name=\"it618_pointimg[$it618_storemapad[id]]\">$it618_storemapad[it618_pointimg]</textarea>",
			"<textarea class=\"txt\" style=\"width:80px;height:80px\" name=\"it618_pointurl[$it618_storemapad[id]]\">$it618_storemapad[it618_pointurl]</textarea>",
			"<textarea class=\"txt\" style=\"width:50px;height:80px\" name=\"it618_width[$it618_storemapad[id]]\">$it618_storemapad[it618_width]</textarea>",
			"<textarea class=\"txt\" style=\"width:50px;height:80px\" name=\"it618_height[$it618_storemapad[id]]\">$it618_storemapad[it618_height]</textarea>",
			"<textarea class=\"txt\" style=\"width:210px;height:80px\" name=\"it618_pointabout[$it618_storemapad[id]]\">$it618_storemapad[it618_pointabout]</textarea>",
			it618_storemapad_getusername($it618_storemapad['it618_uid']),
			date('Y-m-d H:i:s', $it618_storemapad['it618_time'])
		));
	}
	
	function it618_storemapad_class1name($aid){
		$class1id = DB::result_first("select it618_class1_id from ".DB::table('it618_storemapad_class2')." where id=".$aid);
		return DB::result_first("select it618_classname from ".DB::table('it618_storemapad_class1')." where id=".$class1id);
	}
	
	function it618_storemapad_class2name($aid){
		return DB::result_first("select it618_classname from ".DB::table('it618_storemapad_class2')." where id=".$aid);
	}
	
	function it618_storemapad_getusername($uid){
		return DB::result_first("select username from ".DB::table('common_member')." where uid=".$uid);
	}
	if($reabc[0]!='i')return;
	
	
	echo '
	<script>
	var arrcount='.$countsel.';
	var select_class = new Array(arrcount+1);
	
	for (i=0; i<arrcount+1; i++) 
	{
	 select_class[i] = new Array();
	}
	
	'.$tmp1.'
	
	function redirec_class(x)
	{
	 var temp = document.getElementById("it618_class2_selid"); 
	 temp.options.length=1;
	 for (i=1;i<select_class[x].length;i++)
	 {
	  temp.options[i]=new Option(select_class[x][i].text,select_class[x][i].value);
	 }
	 temp.options[0].selected=true;
	
	}
	
	function redirec_class_sel(id,index)
	{
	 var temp = document.getElementById(id); 
	 temp.options[index].selected=true;
	
	}
	
	redirec_class(document.getElementById("it618_class1_selid").options.selectedIndex);
	'.$jstmp.'
	</script>';
	
	showsubmit('it618submit', 'submit', 'del', "<input type=hidden value=$page name=page />", $multipage);
	showtablefooter();
?>