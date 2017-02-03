<?php ob_start() ?> 
<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
		
		<link rel="icon" href="img/icon.ico" />
		
		<title>Suliborski</title>
	</head>
	
	<body>
		<?php 
			//POŁĄCZENIE Z BAZĄ, USTAWEINIE POLACZENIA, 
			session_start();
			$connection = mysqli_connect('localhost', 'root', '', 'suliborski');	
			mysqli_query($connection, 'SET NAMES utf-8');
			mysqli_query ($connection, 'SET CHARACTER SET utf8');
			mysqli_query ($connection, "SET collaction_connection = utf-8_polish_ci");

			//DODANIE KLASY IMAGE
			include_once "class.img.php";	
			//WYLOGOWANIE 
			if (@$_GET['logout']) {session_unset(); header ('location: index.php');}
		?>
		
		<!-- CAŁA NAWIGACJA NA STRONIE -->
		<nav>
			<table id="navTable">
				<tr>
					<th>
						<table id="menuTable">
							<tr>
								<th>
									<a <?php if ((@$_GET['page'] == 'galeria')||(@$_GET['page'] == 'album')||(@$_GET['page'] == 'foto')) echo "style='color: #9EBF6D;'" ?> 	href="index.php?page=galeria"> GALERIA </a>|
									<a <?php if (@$_GET['page'] == 'dodaj-album') echo "style='color: #9EBF6D;'"; ?>
									href="index.php?page=dodaj-album"> ZAŁÓŻ ALBUM </a>|
									<a <?php if (@$_GET['page'] == 'dodaj-foto') echo "style='color: #9EBF6D;'"; ?>
									href="index.php?page=dodaj-foto"> DODAJ ZDJĘCIE </a>|
									<a <?php if (@$_GET['page'] == 'top-foto') echo "style='color: #9EBF6D;'"; ?>
									href="index.php?page=top-foto"> NAJLEPIEJ OCENIANE </a>|
									<a <?php if (@$_GET['page'] == 'nowe-foto') echo "style='color: #9EBF6D;'"; ?>
									href="index.php?page=nowe-foto"> NAJNOWSZE </a>
								</th>
							</tr>
						</table>
					</th>
					<th>
						<table id="logTable">
							<tr>
								<th>
									<?php 
										if (@$_SESSION['LOGGEDlogin'] != "" &&  @$_SESSION['LOGGEDemail'] != ""){
											if ((@$_SESSION['LOGGEDpermissions'] == 'administrator') || (@$_SESSION['LOGGEDpermissions'] == 'moderator')){
												if (@$_GET['page'] == 'admin')
													echo "<a style='color: #9EBF6D;' href='index.php?page=admin'> PANEL ADMINISTRACYJNY </a>|";
												else 					
													echo "<a href='index.php?page=admin'> PANEL ADMINISTRACYJNY </a>|";}
												
											echo "<a "; if (@$_GET['page'] == 'konto') echo "style='color: #9EBF6D;'"; echo "href='index.php?page=konto'> MOJE KONTO </a>|";												
											echo "<a href='index.php?logout=true'> WYLOGUJ SIĘ </a>";}

										if (@$_SESSION['LOGGEDlogin'] == "" && @$_SESSION['LOGGEDpassword'] == "" && @$_SESSION['LOGGEDemail'] == ""){
											echo "	<a "; if (@$_GET['page'] == '') echo "style='color: #9EBF6D;'"; echo "href='index.php'> REJESTRACJA </a>|";
											echo "	<a "; if (@$_GET['page'] == '') echo "style='color: #9EBF6D;'"; echo "href='index.php'> ZALOGUJ SIĘ </a>";}
									?>
								</th>
							</tr>
						</table>
					</th>
				</tr>
			</table>
		</nav>

		<section>
			<?php 		
				if (@$_GET['page'] == 'admin')
					include_once "admin/".$_GET['page'] . '.php';
				else {
					if (isset($_GET['page'])) include_once $_GET['page'] . '.php';
					else include_once 'main.php';
				}
			?>
		</section>
		
		<footer>
			Michał Suliborski kl. 4Ta
		</footer>
		
	</body>
</html>

<?php ob_flush () ?>

