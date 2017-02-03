<article>
	<div class="form">
		<form method="post">
			<h2>Dodaj album:</h2>
			<table style='margin: 0 auto;'>
				<tr><th class="formTableTh1">Nazwa albumu:</th> 	<th class="formTableTh2"><input type="text"	name = "ADDalbumName" maxlength="99" placeholder="Nazwa albumu" required></th></tr>
				<tr><th class="formTableTh1"><input class="button" type="submit" value = "Dodaj album" name = "ADD"></th></th>	
			</table>
		</form> 
	</div>
</article>

<?php
	if (isset($_POST['ADDalbumName'])) {
		$searchForLoggedUserData = mysqli_query($connection, "SELECT * FROM uzytkownicy WHERE login LIKE '".$_SESSION['LOGGEDlogin']."' AND haslo LIKE '".$_SESSION['LOGGEDpassword']."'");
		$userDataRow = mysqli_fetch_assoc($searchForLoggedUserData);
					
		if ($_SESSION['LOGGEDlogin'] == "" &&  $_SESSION['LOGGEDemail'] == "") {
			$_SESSION['NAVerror'] .= "<p><img src='img/error.png'> Najpierw musisz się zalogować. </p>";
			header ('location: index.php');}		
			
		$_SESSION['ADDalbumError'] = ""; 
				
		if  (@$_POST['ADDalbumName'] == ""){
			@$_SESSION['ADDalbumError'] .= "<p><img src='img/error.png'> Musisz podać nazwę albumu. </p>";}
		else {
			if (strlen($_POST['ADDalbumName']) >99)
				{$_SESSION['ADDalbumError'] .= "<p><img src='img/error.png'> Nazwa albumu może mieć maksymalnie 99 znaków. </p>";}			
			if (trim($_POST['ADDalbumName']) == "" )
				{$_SESSION['ADDalbumError'] .= "<p><img src='img/error.png'> Nazwa albumu nie może skąłdać się z samych białych znaków. </p>";}
			if (trim(htmlspecialchars((strip_tags($_POST['ADDalbumName'])), ENT_QUOTES)) == "")
				{$_SESSION['ADDalbumError'] .= "<p><img src='img/error.png'> Podałeś nieprawidłową nazwę.</p>";}
		}
							
		if (@$_SESSION['ADDalbumError'] == ""){
			$newComment = trim(htmlspecialchars((strip_tags($_POST['ADDalbumName'])),ENT_QUOTES));
			mysqli_query($connection, "	INSERT INTO albumy SET 
															tytul='".$newComment."', 
															data='".date("Y-m-d")."', 
															id_uzytkownika=".$userDataRow['id']);
			
			$_SESSION['ADDalbumError'] = "";
			$_SESSION['chosenAlbum'] = mysqli_insert_id($connection);
			header ('location: index.php?page=dodaj-foto');
			}
		else {
			echo "<article> <div class='form'>".$_SESSION['ADDalbumError']."</div></article>";
			$_SESSION['ADDalbumError'] = "";
		}
	}
?>

