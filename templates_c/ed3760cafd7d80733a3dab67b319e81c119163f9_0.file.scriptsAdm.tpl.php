<?php
/* Smarty version 3.1.32, created on 2022-07-06 06:13:16
  from '/home/jamtestdev/jamtransfer/public/drivers/jamtransfer/plugins/Buking/templates/scriptsAdm.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_62c527fc61fda9_57868435',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ed3760cafd7d80733a3dab67b319e81c119163f9' => 
    array (
      0 => '/home/jamtestdev/jamtransfer/public/drivers/jamtransfer/plugins/Buking/templates/scriptsAdm.tpl',
      1 => 1656489618,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c527fc61fda9_57868435 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="./css/admin.css" media="screen">
<link rel="stylesheet" type="text/css" href="./js/jQuery/jquery-ui-1.8.9.custom.css">
<link rel="stylesheet" type="text/css" href="./js/jQuery/jquery.ui.datepicker.css">
<link rel="stylesheet" type="text/css" href="./js/jQuery/jquery-ui-timepicker.css">
<link rel="stylesheet" href="booking.css">
  


<?php echo '<script'; ?>
 src="./js/jQuery/jquery.ui.core.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="./js/jQuery/jquery.ui.timepicker.js"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 type="text/javascript">

    $(document).ready(function(){


        var currentDate 	= new Date();
        var currentTime 	= currentDate.getHours();
        var dateLimit 		= 0;
        var hourLimit   	= currentTime;

        var limitDate = new Date(currentDate.getFullYear(),currentDate.getMonth(),currentDate.getDate()
            +dateLimit);

        $("#transferDate").datepicker({
            dateFormat: 'yy-mm-dd',
            showOtherMonths: true,
            selectOtherMonths: true,
            minDate: dateLimit,
            onSelect: function(dateStr) {
                $("#returnDate").datepicker('option', 'minDate', dateStr);
                $("#transferTime").val('');
            }
        });

        $("#returnDate").datepicker({
            dateFormat: 'yy-mm-dd',
            showOtherMonths: true,
            selectOtherMonths: true,
            minDate: dateLimit,
            onSelect: function(dateStr) {
                $("#returnTime").val('');
            }
        });

        $('#transferTime').timepicker({
            onHourShow: function( hour ) {
                if ( $('#transferDate').val() == $.datepicker.formatDate ( 'yy-mm-dd', limitDate ) ) {
                    if ( hour <= hourLimit ) {
                        return false;
                    }
                }
                return true;
            }
        });

        $('#returnTime').timepicker({
            onHourShow: function( hour ) {
                if ( $('#returnDate').val() == $.datepicker.formatDate ( 'yy-mm-dd', limitDate ) ) {
                    if ( hour <= hourLimit ) {
                        return false;
                    }
                }
                if( $("#returnDate").val() == $("#transferDate").val() ) {
                    limit = parseInt( $("#transferTime").val() +1, 10);
                    if ( hour <= limit ) {
                        return false;
                    }
                }
                return true;
            }
        });



        var currentDate 	= new Date();
        var currentTime 	= currentDate.getHours();
        var dateLimit 		= -1;
        var hourLimit   	= -24;
        var limitT			= parseInt(currentTime + hourLimit, 10);
        if( limitT > 24 ) {
            dateLimit += 1;
            limitT = limitT - 24;
        }
        var limitDate = new Date(currentDate.getFullYear(),currentDate.getMonth(),currentDate.getDate()
            +dateLimit);

        $("#transferDate").datepicker({
            dateFormat: 'yy-mm-dd',
            showOtherMonths: true,
            selectOtherMonths: true,
            minDate: dateLimit,
            onSelect: function(dateStr) {
                $("#returnDate").datepicker('option', 'minDate', dateStr);
                $("#transferTime").val('');
            }
        });

        $("#returnDate").datepicker({
            dateFormat: 'yy-mm-dd',
            showOtherMonths: true,
            selectOtherMonths: true,
            minDate: dateLimit,
            onSelect: function(dateStr) {
                $("#returnTime").val('');
            }
        });

        $('#transferTime').timepicker({
            onHourShow: function( hour ) {

                if ( $('#transferDate').val() == $.datepicker.formatDate ( 'yy-mm-dd', limitDate ) ) {
                    if ( hour <= limitT ) {

                        return false;
                    }
                }
                return true;
            }
        });

        $('#returnTime').timepicker({
            onHourShow: function( hour ) {
                if ( $('#returnDate').val() == $.datepicker.formatDate ( 'yy-mm-dd', limitDate ) ) {
                    if ( hour <= limit ) {
                        return false;
                    }
                }
                if( $("#returnDate").val() == $("#transferDate").val() ) {
                    limit = parseInt( $("#transferTime").val() +1, 10);
                    if ( hour <= limit ) {
                        return false;
                    }
                }
                return true;
            }
        });



    }); // end doc ready

<?php echo '</script'; ?>
>

<?php }
}
