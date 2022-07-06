<?php
/* Smarty version 3.1.32, created on 2022-07-05 09:47:12
  from '/home/jamtestdev/jamtransfer/public/drivers/jamtransfer/plugins/Dashboard/templates/getOrder.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_62c3ec807890a5_88466448',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '29b5d9ea28db921ccc843e9cbeb0f04555b6abee' => 
    array (
      0 => '/home/jamtestdev/jamtransfer/public/drivers/jamtransfer/plugins/Dashboard/templates/getOrder.tpl',
      1 => 1656485342,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c3ec807890a5_88466448 (Smarty_Internal_Template $_smarty_tpl) {
?>    <!-- get transfer  widget -->
    <div class="box box-info">
        <div class="box-header">
            <i class="fa fa-car"></i>
            <h3 class="box-title">GET TRANSFER ORDER</h3>
            <!-- tools box -->
            <div class="pull-right box-tools">
                
                <button class="btn btn-info btn-sm" data-widget='collapse' 
                data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                
                <button class="btn btn-info btn-sm" data-widget='remove' 
                data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                
            </div><!-- /. tools -->
        </div>
        <div class="box-body">
			<form action="orders/order" method="post"> 
				<div class="row">
					<div class="col-md-4">Transfer order number: </div>
					<div class="col-md-3">
						<input class="form-control" type="text" name="orderid" size="5"
						 id="orderid" value=""> 
					</div>
					<div class="col-md-3">
						<button class="pull-right btn btn-default" type="submit" name="Confirm">
							<i class="fa fa-check l"></i> View
						</button>	
					</div>		
				</div>
			</form>
        </div>
    </div><?php }
}
