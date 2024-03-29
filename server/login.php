<?php
session_start();

include("php_api_folder/rsa.php");
?>

<html>

<body>

	<?php

	if (isset($_POST['username']) == FALSE) {
		header('Location: ../client/login.html');
	}

	$receivedUsername = $_POST['username'];
	$receivedPassword = $_POST['password'];


	if ($receivedUsername != "" && $receivedPassword != "") {
		$find = 0;

		foreach (file('../database/users.txt') as $line) {
			list($username, $hashed_password) = explode(",", $line);
			if ($username == $receivedUsername) {
				$find = 1;

				$privateKey = get_rsa_privatekey('RSA_keys/private.key');
				$decrypted = rsa_decryption($receivedPassword, $privateKey);

				$split_value = explode("&", $decrypted);

				$timestamp = time();

				if ($timestamp - (int) $split_value[1] <= 150) {
					if ($hashed_password == $split_value[0] . "\n") {

						echo "login successful.";
						$_SESSION['user'] = $username;
						$login = 1;
						echo "<br/><br/>Now you can access to the <a href='../client/shopping_cart.php'>shopping cart</a><br/>";
					} else {
						echo "Wrong Password.";
					}
				} else {
					echo "<br/><br/>The difference between the client-side submitted timestamp and the current server-side timestamp is greater than 150 seconds. Invalid login request!<br/><br/>";
				}
				break;
			}
		}

		if ($find == 0) {
			echo "<br/>Cannot find the username ->" . $receivedUsername . "<- in the database<br/>";
		}

		echo "<br/>Go <a href='../client/'>back</a> to register, login or check the users.txt";
	} else {
		echo "Username and Password cannot be empty!";
		echo "<br/>Go <a href='../client/'>back</a> to register, login or check the users.txt";
	}
	?>


</body>

</html>