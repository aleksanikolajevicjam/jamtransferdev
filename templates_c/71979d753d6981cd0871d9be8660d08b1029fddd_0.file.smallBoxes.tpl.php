<?php
/* Smarty version 3.1.32, created on 2022-07-07 09:12:40
  from 'C:\xampp\htdocs\jamtransferdev\plugins\Dashboard\templates\smallBoxes.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_62c68768286af9_01503899',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '71979d753d6981cd0871d9be8660d08b1029fddd' => 
    array (
      0 => 'C:\\xampp\\htdocs\\jamtransferdev\\plugins\\Dashboard\\templates\\smallBoxes.tpl',
      1 => 1657177505,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c68768286af9_01503899 (Smarty_Internal_Template $_smarty_tpl) {
?>   

                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-2 col-xs-6">
                            <!-- small box -->
                            <a href="orders/newTransfers">
                                <div class="small-box xblue xwhite-text">
                                    <div class="inner">
                                        <h3>
                                            <?php echo $_smarty_tpl->tpl_vars['todayBooking']->value;?>

                                        </h3>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['NNEW']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['TODAY']->value;?>

                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cloud-download"></i>
                                    </div>
                                    
                                        <span  class="small-box-footer">
                                            More info <i class="fa fa-arrow-circle-right"></i>
                                        </span>
                                    
                                </div>
                            </a>
                        </div><!-- ./col -->
                                                <div class="col-lg-2 col-xs-6">
                            <!-- small box -->
                            <a href="orders/notConfirmed">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>
                                            <?php echo $_smarty_tpl->tpl_vars['notConfirmedOrders']->value;?>

                                        </h3>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['NOT_CONFIRMED']->value;?>
 All
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-ios7-alarm"></i>
                                    </div>
                                     <span class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </span>
                                </div>
                            </a>
                        </div><!-- ./col -->      					
						<div class="col-lg-2 col-xs-6">
                            <!-- small box -->
                            <a href="orders/notConfirmedToday">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>
                                            <?php echo $_smarty_tpl->tpl_vars['notConfirmedOrdersToday']->value;?>

                                        </h3>
                                        <p>
                                            Today unconfirmed/declined 
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-ios7-alarm"></i>
                                    </div>
                                     <span class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </span>
                                </div>
                            </a>
                        </div><!-- ./col -->						
						<div class="col-lg-2 col-xs-6">
                            <!-- small box -->
                            <a href="orders/notConfirmedTomorrow">
                                <div class="small-box bg-orange">
                                    <div class="inner">
                                        <h3>
                                            <?php echo $_smarty_tpl->tpl_vars['notConfirmedOrdersTomorrow']->value;?>

                                        </h3>
                                        <p>
                                            Tomorrow unconfirmed/declined 
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-ios7-alarm"></i>
                                    </div>
                                     <span class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </span>
                                </div>
                            </a>
                        </div><!-- ./col -->
                        <div class="col-lg-2 col-xs-6">
                            <!-- small box -->
                            <a href="orders/declined">
                                <div class="small-box red darken-2 xwhite-text">
                                    <div class="inner">
                                        <h3>
                                            <?php echo $_smarty_tpl->tpl_vars['declined']->value;?>

                                        </h3>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['DECLINED']->value;?>

                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-nuclear"></i>
                                    </div>
                                     <span class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </span>
                                </div>
                            </a>
                        </div><!-- ./col -->
                        <div class="col-lg-2 col-xs-6">
                            <!-- small box -->
                            <a href="orders/tomorrow">
                                <div class="small-box teal darken-2 xwhite-text">
                                    <div class="inner">
                                        <h3>
                                            <?php echo $_smarty_tpl->tpl_vars['tomorrowTransfers']->value;?>

                                        </h3>
                                        <p>
                                            <?php echo $_smarty_tpl->tpl_vars['TOMORROW']->value;?>

                                        </p>
                                    </div>
                                    <div class="icon ">
                                        <i class="fa fa-car"></i>
                                    </div>
                                     <span class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </span>
                                </div>
                            </a>
                        </div><!-- ./col -->						
                    </div><!-- /.row -->
<?php }
}
