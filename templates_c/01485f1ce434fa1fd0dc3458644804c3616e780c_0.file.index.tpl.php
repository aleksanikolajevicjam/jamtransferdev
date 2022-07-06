<?php
/* Smarty version 3.1.32, created on 2022-07-05 07:52:08
  from '/home/jamtestdev/public_html/drivers/jamtransfer/plugins/Calendar/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_62c3eda8186685_00998666',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '01485f1ce434fa1fd0dc3458644804c3616e780c' => 
    array (
      0 => '/home/jamtestdev/public_html/drivers/jamtransfer/plugins/Calendar/templates/index.tpl',
      1 => 1656485342,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c3eda8186685_00998666 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/home/jamtestdev/jamtransfer/public/drivers/jamtransfer/common/libs/plugins/function.html_options.php','function'=>'smarty_function_html_options',),));
?><div class="row-fluid">
	<div class="">
		<div class="col-md-1" style="width:99% !important;">
			<div class="dp_content">
				<div align="center">
					<select name="cal_month" id="cal_month" onchange="calendar()">
						<?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['month_val']->value,'selected'=>$_smarty_tpl->tpl_vars['month_sel']->value,'output'=>$_smarty_tpl->tpl_vars['month_out']->value),$_smarty_tpl);?>

					</select>
					<select name="cal_year"  id="cal_year" onchange="calendar()">
						<?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['year_val']->value,'selected'=>$_smarty_tpl->tpl_vars['year_sel']->value,'output'=>$_smarty_tpl->tpl_vars['year_out']->value),$_smarty_tpl);?>

					</select>
				</div>
				<div id="cal" align="center">
				</div>
				<br/><br/>
			</div>
		</div>
	</div>
</div>
<?php echo '<script'; ?>
 type="text/javascript">

	calendar();
	function calendar() {
		$.get(
			'plugins/Calendar/calendar.php', 
			{cal_month: $('#cal_month').val(), cal_year: $('#cal_year').val()},
			function(data) {
				$('#cal').html(data);
			}
		);
		$('#xMonth').val($('#cal_month').val());
	}	
		
<?php echo '</script'; ?>
><?php }
}
