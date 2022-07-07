<?
$smarty->assign('page', $md->getName());
@session_start();
if (!$_SESSION['UserAuthorized']) die('Bye, bye');
?>

<?php
require_once ROOT . '/db/v4_AuthUsers.class.php';

$db = new DataBaseMysql();
$query = "SELECT AuthUserID, AuthUserCompany FROM v4_AuthUsers where AuthLevelID = 2;";
$result = $db->RunQuery($query);
$agents = array();
while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $agents[] = $row;
}
$smarty->assign('agents', $agents);
?>

<?php if (s('returnTransfer') == '1') $checked = "checked";
$smarty->assign('checked', $checked); ?>
<?
require_once ROOT . '/m/getRoutePrices.php';
$car = getRoutePrices(s('FromID'), s('ToID'));
//$cells = count($car);
/*
switch ($cells) {
    case 1:
        $box = 'l4';
        $offset = 'offset-l2';
        break;
    case 2:
        $box = 'l3';
        $offset = 'offset-l3';
        break;
    case 3:
        $box = 'l4';
        $offset = '';
        break;
    case 4:
        $box = 'l3';
        $offset = '';
        break;
    case 5:
        $box = 'l2';
        $offset = 'offset-l1';
        break;
    case 6:
        $box = 'l2';
        $offset = '';
        break;
    case 7:
        $box = 'l3';
        $offset = '';
        break;
    case 8:
        $box = 'l3';
        $offset = '';
        break;
    case 9:
        $box = 'l3';
        $offset = '';
        break;
    case 10:
        $box = 'l2';
        $offset = '';
        break;
    case 11:
        $box = 'l3';
        $offset = '';
        break;
    case 12:
        $box = 'l2';
        $offset = '';
        break;
}
?>

<div class="col s12 <?= $offset ?>">

    <?/*
    foreach ($car as $VehicleCapacity =>
        $price) {
        $VehicleImageRoot = "https://" .
            $_SERVER['HTTP_HOST'];

        if ($VehicleCapacity <= 3) $vehicleImageFile =
            '/i/cars/sedan.png';
        else if ($VehicleCapacity <= 4)
            $vehicleImageFile = '/i/cars/sedan.png';
        else if ($VehicleCapacity <= 8)
            $vehicleImageFile = '/i/cars/minivan.png';
        else if ($VehicleCapacity <= 15)
            $vehicleImageFile = '/i/cars/minibusl.png';
        else if ($VehicleCapacity > 15)
            $vehicleImageFile = '/i/cars/bus.png';
        $VehicleImage =
            $VehicleImageRoot . $vehicleImageFile;
    ?>
        <div class="col s12 <?= $box ?> card l">
            <br>
            <i class="fa fa-user"></i>
            <?= $VehicleCapacity ?><br>
            <img src="<?= $VehicleImage ?>" class="responsive-img" alt="taxi">

            <div class="card-action">
                <i class="fa fa-tags red-text"></i>
                <?= $price ?>
                <?= s('Currency') ?>
            </div>
        </div>

    <?
    } ?>
</div>