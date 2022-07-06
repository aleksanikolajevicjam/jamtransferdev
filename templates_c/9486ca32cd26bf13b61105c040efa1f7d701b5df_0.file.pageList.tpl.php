<?php
/* Smarty version 3.1.32, created on 2022-07-05 07:52:44
  from '/home/jamtestdev/jamtransfer/public/drivers/jamtransfer/templates/pageList.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_62c3edccf1a893_72872398',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9486ca32cd26bf13b61105c040efa1f7d701b5df' => 
    array (
      0 => '/home/jamtestdev/jamtransfer/public/drivers/jamtransfer/templates/pageList.tpl',
      1 => 1656485342,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c3edccf1a893_72872398 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['isNew']->value) {?>
<div id="ItemWrapperNew" class="editFrame container-fluid" style="display:none" ">
	<div id="inlineContentNew" class="row">
		<div id="new_Item"></div>
	</div>
</div>	
<?php } else { ?>
	<div id="show_Items"><?php echo $_smarty_tpl->tpl_vars['THERE_ARE_NO_DATA']->value;?>
</div>
<?php }
}
}
