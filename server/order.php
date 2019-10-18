<?php
session_start();
include("php_api_folder/rsa.php");
include('php_api_folder/des.php');
?>
<html>

<head>
	<style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 400px;
		}

		td,
		th {
			width: 100px;
			text-align: center;
			padding: 8px;
		}

		th {
			background-color: #4CAF50;
			color: white;
		}
	</style>
</head>

<body>
	<?php
	if (!isset($_POST['DESKey']) or !isset($_POST['cardNumber'])) {
		header('Location: ../client/shopping_cart.php');
	}
	$ServerRSAPrivateKey = get_rsa_privatekey('RSA_keys/private.key');
	$CustomerDESKey = rsa_decryption($_POST['DESKey'], $ServerRSAPrivateKey);
	$DecryptedCCNumber = php_des_decryption($CustomerDESKey, $_POST['cardNumber']);

	$orderInformation = "---------------------------------------------------\nClient: " . $_SESSION['user'] . "\n";
	$orderInformation = $orderInformation . "Ordered quantity information: \n";
	if ($_POST["ProductAquantity"] > 0) {
		$orderInformation = $orderInformation . $_POST["ProductA"] . ": " . $_POST["ProductAquantity"] . " ($" . $_POST["ProductAprice"] . " each)\n";
	}
	if ($_POST["ProductBquantity"] > 0) {
		$orderInformation = $orderInformation . $_POST["ProductB"] . ": " . $_POST["ProductBquantity"] . " ($" . $_POST["ProductBprice"] . " each)\n";
	}
	if ($_POST["ProductCquantity"] > 0) {
		$orderInformation = $orderInformation . $_POST["ProductC"] . ": " . $_POST["ProductCquantity"] . " ($" . $_POST["ProductCprice"] . " each)\n";
	}
	$orderInformation = $orderInformation . "Total price: " . $_POST["totalPrice"] . "\n";
	$orderInformation = $orderInformation . "Credit card number: " . $DecryptedCCNumber . "\n\n\n";
	?>

	<h2>Confirmation of Order</h2>

	<table>
		<tr>
			<th>Products</th>
			<th>Price</th>
			<th>Quantity</th>
			<th>Subtotal</th>
		</tr>
		<tr>
			<td><?php echo $_POST["ProductA"]; ?></td>
			<td><?php echo $_POST["ProductAprice"]; ?></td>
			<td><?php echo $_POST["ProductAquantity"]; ?></td>
			<td><?php echo $_POST["ProductAtotal"]; ?></td>
		</tr>
		<tr>
			<td><?php echo $_POST["ProductB"]; ?></td>
			<td><?php echo $_POST["ProductBprice"]; ?></td>
			<td><?php echo $_POST["ProductBquantity"]; ?></td>
			<td><?php echo $_POST["ProductBtotal"]; ?></td>
		</tr>
		<tr>
			<td><?php echo $_POST["ProductC"]; ?></td>
			<td><?php echo $_POST["ProductCprice"]; ?></td>
			<td><?php echo $_POST["ProductCquantity"]; ?></td>
			<td><?php echo $_POST["ProductCtotal"]; ?></td>
		</tr>
		<tr>
			<th></th>
			<th>Total</th>
			<th><?php echo $_POST["totalQuantity"]; ?></th>
			<th><?php echo $_POST["totalPrice"]; ?></th>
		</tr>
	</table>

	<?php

	echo "<br/><br/>Received encrypted DES key:<br/>";
	echo "<textarea rows='6' cols='50'>" . $_POST['DESKey'] . "</textarea>";

	echo "<br/><br/>Recovered DES key: " . $CustomerDESKey;

	echo "<br/><br/>Received encrypted credit card number: " . $_POST['cardNumber'];

	echo "<br/><br/>Decrypted credit card number: " . $DecryptedCCNumber;

	$myfile = fopen("../database/orders.txt", "a") or die("<br/><br/>Unable to open file!");
	fwrite($myfile, $orderInformation);
	fclose($myfile);
	echo "<br/><br/>Order has been added, go to  <a href='../database/'>database</a> to check this order information has been added to orders.txt file"
	?>
</body>

</html>