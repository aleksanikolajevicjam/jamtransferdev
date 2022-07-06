<?php
/* Smarty version 3.1.32, created on 2022-07-06 10:47:41
  from '/home/jamtestdev/public_html/drivers/jamtransferdev/plugins/Dashboard/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_62c54c2d64e8b6_98056777',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4432b70ae1ed652cbc107b7f262ba1cfcba2d166' => 
    array (
      0 => '/home/jamtestdev/public_html/drivers/jamtransferdev/plugins/Dashboard/templates/index.tpl',
      1 => 1656485342,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:plugins/Dashboard/templates/smallBoxes.tpl' => 1,
    'file:plugins/Dashboard/templates/emptyRow.tpl' => 1,
    'file:plugins/Dashboard/templates/getOrder.tpl' => 1,
    'file:plugins/Dashboard/templates/getUnfinishedPayment.tpl' => 1,
    'file:plugins/Dashboard/templates/actualTransfers.tpl' => 1,
    'file:plugins/Dashboard/templates/todo.tpl' => 1,
    'file:plugins/Dashboard/templates/quickEmail.tpl' => 1,
  ),
),false)) {
function content_62c54c2d64e8b6_98056777 (Smarty_Internal_Template $_smarty_tpl) {
?>
					<?php $_smarty_tpl->_subTemplateRender("file:plugins/Dashboard/templates/smallBoxes.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
					<?php $_smarty_tpl->_subTemplateRender("file:plugins/Dashboard/templates/emptyRow.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?> 			

					<div class="row">
						<section class="col-lg-6 xconnectedSortable"> 
							<?php $_smarty_tpl->_subTemplateRender("file:plugins/Dashboard/templates/getOrder.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?> 			
						</section><!-- /.Left col -->
						<section class="col-lg-6 xconnectedSortable"> 
							<?php $_smarty_tpl->_subTemplateRender("file:plugins/Dashboard/templates/getUnfinishedPayment.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?> 			
						</section>
					</div>	
                    <div class="row">
                        <section class="col-lg-6 xconnectedSortable"> 
							<?php $_smarty_tpl->_subTemplateRender("file:plugins/Dashboard/templates/actualTransfers.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?> 			
                        </section>
                        <section class="col-lg-6 xconnectedSortable">
							<?php $_smarty_tpl->_subTemplateRender("file:plugins/Dashboard/templates/todo.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?> 								
                        </section>
                    </div>		
                    <div class="row">
                        <section class="col-lg-6 xconnectedSortable"> 
							<?php $_smarty_tpl->_subTemplateRender("file:plugins/Dashboard/templates/quickEmail.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?> 
                        </section>
                        <section class="col-lg-6 xconnectedSortable">
                        </section>
                    </div>	        
<?php }
}
