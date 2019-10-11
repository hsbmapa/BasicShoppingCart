<?php
session_start();
?>

<html>

<body>

	<?php
	unset($_SESSION['user']);
	header('Location: ../client/');
	?>

</body>

</html>