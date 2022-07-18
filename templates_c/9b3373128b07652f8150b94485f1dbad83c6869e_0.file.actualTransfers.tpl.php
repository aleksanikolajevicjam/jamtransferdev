<?php
/* Smarty version 3.1.32, created on 2022-07-07 09:12:40
  from 'C:\xampp\htdocs\jamtransferdev\plugins\Dashboard\templates\actualTransfers.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_62c687684b5ad4_01051258',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b3373128b07652f8150b94485f1dbad83c6869e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\jamtransferdev\\plugins\\Dashboard\\templates\\actualTransfers.tpl',
      1 => 1657177505,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c687684b5ad4_01051258 (Smarty_Internal_Template $_smarty_tpl) {
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
