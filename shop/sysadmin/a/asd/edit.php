<?php
if(!$IWEB_SHOP_IN) {
	die('Hacking attempt');
}

//语言包引入
$a_langpackage=new adminlp;

require_once("../foundation/module_asd.php");
require_once("../foundation/module_admin_logs.php");
//权限管理
$right=check_rights("adv_edit");
if(!$right){
	action_return(0,$a_langpackage->a_privilege_mess,'m.php?app=error');
}
/* post */
$post = array(
	'position_id'	=> intval(get_args('position_id')),
	'media_type'	=> intval(get_args('media_type')),
	'asd_name'		=> short_check(get_args('asd_name')),
	'asd_link'		=> short_check(get_args('asd_link')),
	'remark'		=> long_check(get_args('remark'))
);


$asd_id = intval(get_args('asd_id'));

if(!$asd_id) {
	action_return(0,$a_langpackage->a_error,'-1');
}
if(!$post['asd_name']) {
	action_return(0,$a_langpackage->a_asdname_null,'-1');
}

$wh = explode('X',get_args('asd_wh'));

if($post['media_type']==3) {
	$temp = get_args('content');
	$post['asd_content'] = long_check($temp[0]);
} else {
	$cupload = new upload('jpg|gif|png|swf',2048,'content');
	$cupload->set_dir("../uploadfiles/asd/","{y}/{m}/{d}");
	$file = $cupload->execute();
	if($file) {
		$file[0]['dir'] = str_replace("../","./",$file[0]['dir']);
		$post['asd_content'] = $file[0]['dir'].$file[0]['name'];
	}
}

$post['last_update_time'] = $ctime->long_time();

//数据表定义区
$t_asd_content = $tablePreStr."asd_content";
$t_asd_position = $tablePreStr."asd_position";
$t_admin_log = $tablePreStr."admin_log";
//定义写操作
dbtarget('w',$dbServs);
$dbo=new dbex;

if(update_asd_info($dbo,$t_asd_content,$post,$asd_id)) {
	update_asd_position_file($dbo,$t_asd_content,$t_asd_position,$asd_id);
//	update_asd_file($dbo,$t_asd_content,$t_asd_position,$asd_id);
	admin_log($dbo,$t_admin_log,$a_langpackage->a_modefy_ad."：$asd_id");
	action_return(1,$a_langpackage->a_amend_suc,"m.php?app=asd_edit&id=".$asd_id);
} else {
	action_return(0,$a_langpackage->a_edit_lose_repeat,'-1');
}
?>