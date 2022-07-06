<?php
/* Smarty version 3.1.32, created on 2022-07-06 10:47:41
  from '/home/jamtestdev/jamtransfer/public/drivers/jamtransferdev/plugins/Dashboard/templates/quickEmail.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_62c54c2d67ef26_81140148',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8e9db4ed87d3da05c052117c8fe2417326cf8db7' => 
    array (
      0 => '/home/jamtestdev/jamtransfer/public/drivers/jamtransferdev/plugins/Dashboard/templates/quickEmail.tpl',
      1 => 1656485342,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c54c2d67ef26_81140148 (Smarty_Internal_Template $_smarty_tpl) {
?>
    <!-- quick email widget -->
    <div class="box box-info">
        <div class="box-header">
            <i class="fa fa-envelope"></i>
            <h3 class="box-title"><?php echo $_smarty_tpl->tpl_vars['QUICK_EMAIL']->value;?>
</h3>
            <!-- tools box -->
            <div class="pull-right box-tools">
                
                <button class="btn btn-info btn-sm" data-widget='collapse' 
                data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                
                <button class="btn btn-info btn-sm" data-widget='remove' 
                data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                
            </div><!-- /. tools -->
        </div>
        <div class="box-body">
            <form id="quickEmail" action="#" method="post">
                <div class="form-group">
                	<?php echo $_smarty_tpl->tpl_vars['FROM']->value;?>
: <?php echo $_SESSION['UserEmail'];?>
<br> 
                    <input type="email" class="form-control" id="emailto" 
                    placeholder="<?php echo $_smarty_tpl->tpl_vars['EMAIL_TO']->value;?>
" value=""/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="subject" 
                    placeholder="<?php echo $_smarty_tpl->tpl_vars['SUBJECT']->value;?>
"/>
                </div>
                <div>
                    <textarea class="textarea" placeholder="<?php echo $_smarty_tpl->tpl_vars['MESSAGE']->value;?>
" id="message"
                    style="width: 100%; height: 125px; font-size: 14px; 
                    line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
            </form>
        </div>
        <div class="box-footer clearfix">
        	
            <button class="pull-right btn btn-default" id="sendEmail">
            	<?php echo $_smarty_tpl->tpl_vars['SEND']->value;?>
 <i class="fa fa-arrow-circle-right"></i>
            	<span id="messageStatus"></span>
            </button>
        </div>
    </div>
                          
<?php echo '<script'; ?>
 type="text/javascript">
	$("#sendEmail").click(function(){
		var url = window.root +'/cms/api/'+
		"testEmailForNewApp.php?to=" + $("#emailto").val() +
		"&subject=" + $("#subject").val() +
		"&message=" + $("#message").val() +
		"&callback=?";
	
		$.ajax({
			type: 'POST',
			url: url,
			async: false,
			contentType: "application/json",
			dataType: 'jsonp',
			success: function(data) {
				$("#messageStatus").html(data);
				$("textarea#message").val('');
				$("#subject").val('');
			}
		});
		return false;
	});
	
<?php echo '</script'; ?>
>                            
<?php }
}
