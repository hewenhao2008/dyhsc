<?php
if(!$IWEB_SHOP_IN) {
	die('Hacking attempt');
}

//引入模块公共方法文件
require("foundation/module_shop.php");

//语言包引入
$m_langpackage=new moduleslp;

//数据库操作
dbtarget('w',$dbServs);
$dbo=new dbex();

//定义文件表
$t_shop_info = $tablePreStr."shop_info";
$t_word= $tablePreStr."word";
// 处理post变量
$post['shop_name'] = short_check1(get_args('shop_name'));
//判断商铺名称是否重复
$sql = "select * from $t_shop_info where shop_name='{$post['shop_name']}'";
$array = $dbo->getRs($sql);
if($array){
	action_return(0, $m_langpackage->m_shop_yes,'-1');
}
//判断是否有敏感词
$sql="select * from $t_word";
$row = $dbo->getRs($sql);

$post['shop_address'] = long_check(get_args('shop_address'));
$post['shop_template'] = short_check(get_args('shop_template'));
$post['shop_country'] = intval(get_args('country'));
$post['shop_province'] = intval(get_args('province'));
$post['shop_city'] = intval(get_args('city'));
$post['shop_district'] = intval(get_args('district'));
$post['shop_intro'] = big_check(get_args('shop_intro'));
if($row){
	foreach ($row as $v){
		if(stristr($post['shop_name'],$v['word_name'])){
			action_return(0, $m_langpackage->m_shop_no.$v['word_name'],'-1');
		}
		if(stristr($post['shop_intro'],$v['word_name'])){
			action_return(0, $m_langpackage->m_shop_intro_no.$v['word_name'].$m_langpackage->m_shop_intro_no_back,'-1');
		}
	}
}
$post['shop_management'] = short_check(get_args('shop_management'));
$post['user_id'] = $user_id;
$post['shop_id'] = $user_id;
$post['shop_creat_time'] = $ctime->long_time();
$post['shop_categories'] =short_check(get_args('categories'));
if($post['shop_categories']==0)
	$post['shop_categories'] =short_check(get_args('categories_parent'));

if(insert_shop_info($dbo,$t_shop_info,$post)) {
	set_sess_shop_id($post['shop_id']);
	set_session('shop_lock',0);
	set_session('shop_open',0);
	action_return(1,$m_langpackage->m_shopcreate_success,'modules.php?app=shop_request');
} else {
	action_return(0,$m_langpackage->m_shopcreate_fail,'-1');
}
exit;
?>