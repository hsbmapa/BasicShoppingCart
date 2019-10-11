<html>

<body>

	<?php
	if (isset($_POST['username']) == FALSE) {
		header('Location: ../client/register.html');
	}

	$entered_username = $_POST['username'];
	$entered_password = $_POST['password'];
	if ($entered_password < 6 && $entered_username != 0) {
		echo "Password cannot by less than 5 characters, please click <a href='../client/register.html'>here</a> to try again";
	} elseif ($entered_username != "" && $entered_password != "") {
		$find = 0;

		foreach (file('../database/users.txt') as $line) {
			list($username, $password) = explode(",", $line);
			if ($username == $entered_username) {
				$find = 1;
				break;
			}
		}

		if ($find == 1) {
			echo "The user already exist!";
		} else {
			$file = fopen("../database/users.txt", "a");
			fwrite($file, $entered_username . "," . $entered_password . "\n");
			fclose($file);
			echo "The user has been added to the database/users.txt";
		}

		echo "<br/>Go <a href='../client/'>back</a> to register, login or check the users.txt";
	} else {
		echo "Username and Password cannot be empty!";
		echo "<br/>Go <a href='../client/'>back</a> to register, login or check the users.txt";
	}
	?>


</body>

</html>