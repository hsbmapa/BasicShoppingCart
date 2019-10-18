<html>
<body>

	<?php
	if (isset($_POST['username']) == FALSE) {
		header('Location: ../client/register.html');
	}

	$enteredUsername = $_POST['username'];
	$enteredPassword = $_POST['password'];
	
	if ($enteredPassword < 6 && $enteredUsername != "") {
		echo "Password cannot by less than 5 characters, please click <a href='../client/register.html'>here</a> to try again";
		
	} elseif ($enteredUsername != "" && $enteredPassword != "") {
		$find = 0;

		foreach (file('../database/users.txt') as $line) {
			list($username, $password) = explode(",", $line);
			if ($username == $enteredUsername) {
				$find = 1;
				break;
			}
		}

		if ($find == 1) {
			echo "The user already exist!";
		} else {
			$file = fopen("../database/users.txt", "a");
			fwrite($file, $enteredUsername . "," . $enteredPassword . "\n");
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