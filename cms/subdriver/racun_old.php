<?

define("NEMPH", "!");
define("NORMAL", "!");
define("BIG", "!1");
define("NUNDERLINED", "!Ĩ");
define("RESET", "@");
define("LINE", "\n________________________________");

require_once $_SERVER['DOCUMENT_ROOT'] . '/cms/subdriver/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/db/v4_Places.class.php';


$op = new v4_Places;
$q  = "SELECT * FROM v4_OrderDetails WHERE DetailsID=".$_REQUEST['id'];
$qr = mysqli_query($con, $q) or die('Error finding Order <br/>' . mysqli_connect_error());
$o = mysqli_fetch_object($qr);

$mokq = "SELECT * FROM v4_OrdersMaster WHERE MOrderID = ".$o->OrderID;
$mokr = mysqli_query($con, $mokq) or die('Error finding MOrder <br/>' . mysqli_connect_error());
$mok = mysqli_fetch_object($mokr);

// dohvacanje engleskih imena lokacija iz v4_Places
// ako je FREEFORM, PickupID i DropID su 0,
// pa se imena dohvacaju iz v4_OrderDetails
if (($o->PickupID != 0) and ($o->DropID != 0)) {
	$op->getRow($o->PickupID);
	$PickupName = $op->getPlaceNameEN();
	$op->getRow($o->DropID);
	$DropName = $op->getPlaceNameEN();
} else {
	$PickupName = $o->PickupName;
	$DropName = $o->DropName;
}
$pay = $o->DetailPrice + $o->ExtraCharge;
$text = 
		NORMAL. "
		Etablissement secondaire \nJ A M GROUP D.O.O. SIRET\n
		81500492400032 Adresse 13 AVENUE BARTHÉLÉMY THIMONNIER - 69300 CALUIRE\n
		Activite Transports de personnes de moins de neuf voyageurs (4619B)\n\n" .
		NEMPH."Reference : \nFacture " .$_REQUEST['id']. " - ". convertTime($o->PickupDate,'%d/%m/%Y') ."\n\n".
		BIG ."Client: ". $o->PaxName."\n" .
		"Facture\n".
		NORMAL. NEMPH.
		"Transfert de: ". $PickupName ."\na\n" . $DropName ."\n" .
		"le ". convertTime($o->PickupDate,'%d/%m/%Y') ."\n" .
		"Montant acompte HT : " . number_format($pay  / 1.1,2) . " EUR\n" .
		"TVA 10%	             : " . number_format($pay  - ($pay  / 1.1),2) . " EUR\n" .
		"Montant acompte TTC: " . number_format($pay ,2) . " EUR\n";
$text .= LINE;

$file = $_REQUEST['id'] . ".txt";
file_put_contents('../racuni/'.$file, $text);


?>

	<div class="container">
		<div class="">
			<em>
			Etablissement secondaire J A M GROUP D.O.O. SIRET<br>
			81500492400032 Adresse 13 AVENUE BARTHÉLÉMY THIMONNIER - 69300 CALUIRE<br>
			Activité Transports de personnes de moins de neuf voyageurs (4619B)
			</em>
		</div>
		<!--	
		<div class="">
			<strong><em>
				Mobitel: 00385 (0)91 537 58 42<br>
				Tel: 00385 (0)21 567 256<br>
				E-mail: office@jam-group.net
			</em></strong>
		</div>
		-->	
		<hr>
		<div align="left">
			Réference : Facture <?= $_REQUEST['id'] ?> -
			<?= convertTime($o->PickupDate,'%d/%m/%Y') ?>
			<br>
			Client: <strong><?= $o->PaxName ?></strong><br>
			<br>
			<h2>Facture</h2>
			<br>
			<strong>Désignation :</strong>
			<br>
			Transfert de: <?= $PickupName ?> à <?= $DropName ?><br>
			le <?= convertTime($o->PickupDate,'%d/%m/%Y') ?>
			<br><br>
			<table>
				<tr>
					<td>
						Montant acompte HT: 
					</td>
					<td align="right">
						<?= number_format($pay / 1.1,2) ?> EUR 
					</td>
				</tr>
				<tr>
					<td>
						TVA 10%	
					</td>
					<td align="right">
						<?= number_format($pay - ($pay / 1.1),2) ?> EUR
					</td>
				</tr>
				<tr>
					<td>
						Montant acompte TTC: 
					</td>
					<td align="right">
						<?= number_format($pay,2) ?> EUR
					</td>
				</tr>
			</table>
		</div>
		<a href="../racuni/<?= $file?>">Download</a>
	</div>
</html>

