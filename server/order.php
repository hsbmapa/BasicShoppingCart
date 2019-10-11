<?php

session_start();

include("php_api_folder/rsa.php");

include('php_api_folder/des.php');
?>


<html>

<head>

</head>

<body>
	<?php
	if (!isset($_POST['DES_key']) or !isset($_POST['cardNumber'])) {
		header('Location: ../client/shopping_cart.php');
	}

	$Server_RSA_PrivateKey = get_rsa_privatekey('RSA_keys/private.key');  // retrieve Server's RSA private key
	$Customer_DES_key = rsa_decryption($_POST['DES_key'], $Server_RSA_PrivateKey); // recover user's DES key
	$recovered_credit_card_number = php_des_decryption($Customer_DES_key, $_POST['cardNumber']); // recover credit card number

	$order_information = "-----------------------------------------------\nClient: " . $_SESSION['user'] . "\n";
	$order_information = $order_information . "Ordered quantity information: \n";
	if ($_POST["ProductAquantity"] > 0) {
		$order_information = $order_information . $_POST["ProductA"] . ": " . $_POST["ProductAquantity"] . " ($" . $_POST["ProductAprice"] . " each)\n";
	}
	if ($_POST["ProductBquantity"] > 0) {
		$order_information = $order_information . $_POST["ProductB"] . ": " . $_POST["ProductBquantity"] . " ($" . $_POST["ProductBprice"] . " each)\n";
	}
	if ($_POST["ProductCquantity"] > 0) {
		$order_information = $order_information . $_POST["ProductC"] . ": " . $_POST["ProductCquantity"] . " ($" . $_POST["ProductCprice"] . " each)\n";
	}
	$order_information = $order_information . "Total price: " . $_POST["totalPrice"] . "\n";
	$order_information = $order_information . "Credit card number: " . $recovered_credit_card_number . "\n\n\n";

	?>
	<h1>Confirmation of your order</h1>

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
	echo "<textarea rows='7' cols='60'>" . $_POST['DES_key'] . "</textarea>";

	echo "<br/><br/>Recovered DES key: " . $Customer_DES_key;

	echo "<br/><br/>Received encrypted credit card number: " . $_POST['cardNumber'];

	echo "<br/><br/>Recovered credit card number: " . $recovered_credit_card_number;

	$myfile = fopen("../database/orders.txt", "a") or die("<br/><br/>Unable to open file!");
	fwrite($myfile, $order_information);
	fclose($myfile);
	echo "Order has been added, go to  <a href='../database/'>database</a> to check this order information has been added to orders.txt file"
	?>
</body>

</html>