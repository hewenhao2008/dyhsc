<?php
/*
 * 注意：此文件由itpl_engine编译型模板引擎编译生成。
 * 如果您的模板要进行修改，请修改 templates/default/modules/shop/payment_add.html
 * 如果您的模型要进行修改，请修改 models/modules/shop/payment_add.php
 *
 * 修改完成之后需要您进入后台重新编译，才会重新生成。
 * 如果您开启了debug模式运行，那么您可以省去上面这一步，但是debug模式每次都会判断程序是否更新，debug模式只适合开发调试。
 * 如果您正式运行此程序时，请切换到service模式运行！
 *
 * 如您有问题请到官方论坛（http://tech.jooyea.com/bbs/）提问，谢谢您的支持。
 */
?><?php
/*
 * 此段代码由debug模式下生成运行，请勿改动！
 * 如果debug模式下出错不能再次自动编译时，请进入后台手动编译！
 */
/* debug模式运行生成代码 开始 */
if(!function_exists("tpl_engine")) {
	require("foundation/ftpl_compile.php");
}
if(filemtime("templates/default/modules/shop/payment_add.html") > filemtime(__file__) || (file_exists("models/modules/shop/payment_add.php") && filemtime("models/modules/shop/payment_add.php") > filemtime(__file__)) ) {
	tpl_engine("default","modules/shop/payment_add.html",1);
	include(__file__);
} else {
/* debug模式运行生成代码 结束 */
?><?php
if(!$IWEB_SHOP_IN) {
	trigger_error('Hacking attempt');
}

require("foundation/acheck_shop_creat.php");
require("foundation/module_payment.php");

//引入语言包
$m_langpackage=new moduleslp;
$i_langpackage=new indexlp;

//数据表定义区
$t_payment = $tablePreStr."payment";
$t_shop_payment = $tablePreStr."shop_payment";

$pay_id = intval(get_args('pay_id'));
if(!$pay_id) {
	trigger_error($m_langpackage->m_handle_err);
}

//读写分离定义方法
$dbo=new dbex;
dbtarget('r',$dbServs);

$payment = get_one_payment($dbo,$t_payment,$pay_id);
if(!$payment) {
	trigger_error($m_langpackage->m_handle_err);
}

$payment_config = $payment['config'];
$line = explode("\n",$payment_config);
$config_arr = array();
foreach($line as $value) {
	$v = explode("|",$value);
	if($v[0] && $v[1]) {
		$config_arr[$v[0]] = $v[1];
		$config_select[$v[0]] = '';
		if(isset($v[2])){
			$config_select[$v[0]] = unserialize($v[2]);
		}
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo  $m_langpackage->m_u_center;?></title>
<link rel="stylesheet" type="text/css" href="skin/<?php echo  $SYSINFO['templates'];?>/css/modules.css">
<link rel="stylesheet" type="text/css" href="skin/<?php echo  $SYSINFO['templates'];?>/css/layout.css">
<link rel="stylesheet" type="text/css" href="skin/<?php echo  $SYSINFO['templates'];?>/css/style.css">
<script type="text/javascript" src="skin/<?php echo  $SYSINFO['templates'];?>/js/changeStyle.js"></script>
<script type="text/javascript" src="skin/<?php echo  $SYSINFO['templates'];?>/js/userchangeStyle.js"></script>

<style type="text/css">
.red{color:red;}
</style>
</head>
<body onload="menu_style_change('shop_payment');changeMenu();">
<?php  require("shop/index_header.php");?>
	<div class="site_map">
	  <?php echo $m_langpackage->m_current_position;?><A href="index.php"><?php echo $SYSINFO['sys_name'];?></A>/<a href="modules.php"><?php echo $m_langpackage->m_u_center;?></a>/&nbsp;&nbsp;<?php echo  $m_langpackage->m_payment_setting;?>
	</div>
    <div class="clear"></div>
    <?php  require("modules/left_menu.php");?>
    <div class="main_right">
		<div class="cont">
		<div class="title_uc"><h3><?php echo  $m_langpackage->m_payment_setting;?></h3></div><hr />
		<form action="do.php?act=shop_payment_update" method="post" name="form_shop_pay" onsubmit="return checkForm();">
		<table width="100%" class="form_table">
			<tr>
				<th class="textright" width="200px;"><?php echo  $m_langpackage->m_payment_name;?>：</td>
				<th class="textleft"><?php echo $payment['pay_name'];?><input type="hidden" name="pay_id" value="<?php echo $payment['pay_id'];?>" /></td>
			</tr>
			<tr>
				<td class="textright"><?php echo  $m_langpackage->m_payment_desc;?>：</td>
				<td class="textleft"><textarea name="pay_desc" rows="5" cols="40"><?php echo strip_tags($payment['pay_desc']);?></textarea> <span class="red">*</span><?php echo  $m_langpackage->m_payment_showuser_pay;?></td>
			</tr>
			<tr>
				<td class="textright"><?php echo  $m_langpackage->m_payment_enable;?>：</td>
				<td class="textleft"><input type="radio" name="enabled" value="1" checked><?php echo  $m_langpackage->m_yes;?> <input type="radio" name="enabled" value="0"><?php echo  $m_langpackage->m_no;?></td>
			</tr>
			<?php foreach($config_arr as $k=>$v){?>
			<tr>
				<td class="textright"><?php echo $v;?>：</td>
				<td class="textleft">
				<?php if($config_select[$k]){?>
				<select name="pay_config[<?php echo $k;?>]">
					<?php foreach($config_select[$k] as $sk=>$sv){?>
					<option value="<?php echo $sk;?>"><?php echo $sv;?></option>
					<?php }?>
				</select>
				<?php  }else {?>
				<input type="text" name="pay_config[<?php echo $k;?>]" value="" maxlength="40" style="width:200px;" />
				<?php }?>
				</td>
			</tr>
			<?php }?>
			<tr>
				<td colspan="2" class="center"><input type="submit" value="<?php echo  $m_langpackage->m_payment_install;?>" /></td>
			</tr>
		</table>
		</form>
        </div>
    </div>
<div class="clear"></div>
<?php  require("shop/index_footer.php");?>
</body>
</html><?php } ?>