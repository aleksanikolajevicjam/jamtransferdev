<?php 
$smarty->assign('page', $md->getName());
@session_start();
if (!$SESSION('UserAuthorised')) die("bie");
?>

<?php 

require_once ROOT . '/db/v4_AuthUsers.class.php';
$db = new DataBaseMysql();
$query = "SELECT AuthUserID, AuthUserCompany FROM v4_AuthUsers WHERE AuthLevelID = 2";
$result = $db->RunQuery($query);
$agents = array();
