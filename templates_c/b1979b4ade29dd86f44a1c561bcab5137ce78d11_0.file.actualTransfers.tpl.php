<?php
/* Smarty version 3.1.32, created on 2022-07-06 10:47:41
  from '/home/jamtestdev/jamtransfer/public/drivers/jamtransferdev/plugins/Dashboard/templates/actualTransfers.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_62c54c2d6753b7_19128215',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b1979b4ade29dd86f44a1c561bcab5137ce78d11' => 
    array (
      0 => '/home/jamtestdev/jamtransfer/public/drivers/jamtransferdev/plugins/Dashboard/templates/actualTransfers.tpl',
      1 => 1656485342,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c54c2d6753b7_19128215 (Smarty_Internal_Template $_smarty_tpl) {
?>	<style>
		table {
			border: 1px solid black;
		}


		td, th {
			border: 1px solid black;
			text-align: center;
		}	
	</style>
    <div class="box box-info">
        <div class="box-header">
            <i class="fa fa-road"></i>
            <h3 class="box-title">Actual transfers <?php echo $_smarty_tpl->tpl_vars['timeStart']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['timeEnd']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['today']->value;?>
)</h3>
		</div>	
	<div class="box-body">	
		<?php echo $_smarty_tpl->tpl_vars['data']->value;?>

	</div>
<?php echo '<script'; ?>
>

	$(".mytooltip").popover({trigger:'hover', html:true, placement:'bottom'});

<?php echo '</script'; ?>
><?php }
}
