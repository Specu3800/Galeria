<article>
	<?php
		if (@$_SESSION['LOGGEDlogin'] == "" &&  @$_SESSION['LOGGEDemail'] == "") {
			@$_SESSION['NAVerror'] .= "<p><img src='img/error.png'> Najpierw musisz się zalogować. </p>";
			header ('location: index.php');}
			
		$searchForLoggedUserData = @mysqli_query($connection, "SELECT * FROM uzytkownicy WHERE login LIKE '".@$_SESSION['LOGGEDlogin']."' AND haslo LIKE '".@$_SESSION['LOGGEDpassword']."'");
		$userDataRow = mysqli_fetch_assoc($searchForLoggedUserData);
			
		$searchForAlbums = @mysqli_query($connection, "SELECT albumy.id AS albumID, albumy.tytul, uzytkownicy.id, albumy.id_uzytkownika FROM albumy 
															JOIN uzytkownicy 
															ON albumy.id_uzytkownika =" . $userDataRow['id'] . "
															GROUP BY albumy.id");

	?>
	<div class="form">
		<form method="post">
			<h2>Dodaj zdjęcie:</h2>
			<table style='margin: 0 auto;'>
				<tr>
					<th class="formTableTh1">
						Wybierz album:
					</th>
					<th class="formTableTh2"> 
						<select  name = "chosenAlbum" > 
							<?php 
								while(@$albumRow = mysqli_fetch_assoc($searchForAlbums)){
									echo "<option value=". $albumRow['albumID']. " ";
									if (@$albumRow['albumID'] == @$_SESSION['chosenAlbum']) echo "selected";
									echo " >" . $albumRow['tytul'] . "</option>";
								}	
							?>
						</select>	
					</th>
				</tr>
				<tr>
					<th class="formTableTh1">
						<input class="button" type="submit" value = "Wybierz" name = "CHOSE">
					</th>
				</tr>
				</th>
			</table>
		</form> 
	</div>
</article>

<?php
	if (mysqli_num_rows($searchForAlbums) == 0) {
		echo "<article><div class='form' style='text-align: center;'>";
		echo "<p> <a style='color: #F9FFE9;'> Brak albumów! <br/>Kliknij</a><a href='index.php?page=dodaj-album'> tutaj</a>, aby go stworzyć.</p>";			
		echo "</div></article>";}
?>

	
<?php 
	if (@$_POST['chosenAlbum'] !== null){$_SESSION['chosenAlbum'] = @$_POST['chosenAlbum']; header('location: index.php?page=dodaj-foto');}
	
	if (isset($_SESSION['chosenAlbum'])){ 
	
		echo "<article><div class='form' style='text-align: center;'>";
			
		$selectedAlbumPhotos = @mysqli_query($connection, "SELECT albumy.id as id_a, zdjecia.id as id_z, albumy.tytul, zdjecia.zaakceptowane
									FROM albumy 
									JOIN uzytkownicy 
									ON albumy.id_uzytkownika = uzytkownicy.id
									JOIN zdjecia
									ON zdjecia.id_albumu = albumy.id
									AND albumy.id =".$_SESSION['chosenAlbum']."");
		
		//ILOŚĆ ZDJĘĆ
		$photosAmount = mysqli_num_rows($selectedAlbumPhotos); //ilosc pobranych albumów 
		$photosPerPage = 10; //tyle na stronie
		$pagesAmount = floor(($photosAmount-1) / $photosPerPage) + 1; // tyle stron
	


		//STRONNICOWANIE
		if (isset($_GET['strona'])) $pages = $_GET['strona']; else $pages = 1;
		$sqlpage = $photosPerPage*($pages-1);

		
		$selectedAlbumPhotos = @mysqli_query($connection, "SELECT albumy.id as id_a, zdjecia.id as id_z, albumy.tytul, zdjecia.zaakceptowane
									FROM albumy 
									JOIN uzytkownicy 
									ON albumy.id_uzytkownika = uzytkownicy.id
									JOIN zdjecia
									ON zdjecia.id_albumu = albumy.id
									AND albumy.id =".$_SESSION['chosenAlbum']."
									LIMIT $sqlpage, $photosPerPage");
		while($photoRow = mysqli_fetch_assoc($selectedAlbumPhotos)){
			$id_albumu = $photoRow['id_a'];
			$id_zdj = $photoRow['id_z'];
			$tytul = $photoRow['tytul'];
			$acpt = $photoRow['zaakceptowane'];		
			echo "
			<div class='image'>
			<img class='dymek' src='img/albumy/$id_albumu/$id_zdj' width='180px' height='180px'>";
			if ($acpt) echo "<img src='img/ok.png'>Zaakceptowane"; else echo "<img src='img/x.png'>Niezaakceptowane";
			echo "</div>";
		}	
		if ($photosAmount == 0) echo "<p> <a style='color: #F9FFE9;'> Brak zdjęć w albumie </a> </p>";
		echo "</div>";
	}
?>

	

<?php
	if (isset($_SESSION['chosenAlbum'])){
		echo "<div class='page'>";
			echo '<div style="text-align: center; margin: 0 auto;">';
			for ($i = 1; $i<=@$pagesAmount; $i++){
				if ($i == 1) echo "-";
				echo "-<a ";
				if (isset($_GET['strona'])) {if ($_GET['strona'] == $i) echo "style='color: #9EBF6D;' ";}
				else {
				if ($i == 1) echo "style='color: #9EBF6D;' ";}
				echo "class='page-button' href='index.php?page=dodaj-foto&strona=$i'>$i</a>-";
				if ($i == @$pagesAmount) echo "-</div>";}
		echo "</div></article>";
	}
?>



<article <?php if (isset($_SESSION['chosenAlbum'])) echo "style='display: block'"; else echo "style='display: none'" ?>>
	<div class="form">
		<form method="post" enctype="multipart/form-data">
			<h2>Dodaj zdjęcie:</h2>
			<table style='margin: 0 auto;'>
				<tr><th class="formTableTh1">Zdjęcie:</th>	<th class="formTableTh2"> <input type="file"	class='button'		name = "ADDEDphoto"></th></tr>
				<tr><th class="formTableTh1">Opis:</th>			<th class="formTableTh2"> <textarea name="ADDEDphotoDesc" placeholder="opcjonalnie..." maxlength="255" rows="3"></textarea></th></tr>
				<tr><th class="formTableTh1"><input class="button" type="submit" value = "Dodaj" name = "ADD"></th></th>	
			</table>
		</form> 
	</div>
</article>
<?php 
	if (isset($_FILES['ADDEDphoto'])){ //DO BAZY
			if ($_FILES['ADDEDphoto']['error'] == UPLOAD_ERR_OK){			
				if(is_array(getimagesize($_FILES['ADDEDphoto']['tmp_name']))){
								
					$searchForChosenAlbum = @mysqli_query($connection, "SELECT * FROM albumy WHERE albumy.id = ". $_SESSION['chosenAlbum']);
																
					$uploadedImageWidth = getimagesize($_FILES['ADDEDphoto']['tmp_name'])[0]; //DANE OBRAZU + KLASA
					$uploadedImageHeight = getimagesize($_FILES['ADDEDphoto']['tmp_name'])[1];
					$uploadedImage = new Image ($_FILES['ADDEDphoto']['tmp_name']);
					
					$description = trim(htmlspecialchars((strip_tags($_POST['ADDEDphotoDesc'])),ENT_QUOTES));
					mysqli_query($connection, "	INSERT INTO zdjecia SET 
																		tytul='".$description."', 
																		data='".date("Y-m-d")."', 
																		id_albumu='".$_SESSION['chosenAlbum']."', 
																		id_uzytkownika=".$_SESSION['LOGGEDid'].",
																		zaakceptowane = 0");	
					
					if ($uploadedImageWidth > $uploadedImageHeight) { //ZMIANA ROZDZIELCZOŚCI, JEŚLI JEST TAKA POTRZEBA
						if ($uploadedImageWidth > 1200) {$uploadedImage->SetWidth(1200);}}
					else {
						if ($uploadedImageHeight > 1200) {$uploadedImage->SetHeight(1200);}}
					
					if (!file_exists("img/albumy/" . $_SESSION['chosenAlbum'])) { //TWORZYMY KATALOG Z ALBUMEM, JESLI GO JESZCZE NIE MA
						mkdir("img/albumy/" . $_SESSION['chosenAlbum'], 0777, true);}
					
					$uploadedImage->Save($_FILES['ADDEDphoto']['tmp_name']); //ZAPISUJEMY
					
					move_uploaded_file($_FILES['ADDEDphoto']['tmp_name'], "img/albumy/" . $_SESSION['chosenAlbum'] . "/" . mysqli_insert_id($connection));//PRZENOSIMY
					
					echo $_FILES['ADDEDphoto']['tmp_name'] . "                 do             " . "img/albumy/" . $_SESSION['chosenAlbum'] . "/" . mysqli_insert_id($connection);
					
					$_SESSION['ADDphotoError'] = "<div class='form' style='text-align: center;'>
								<p>Dodano zdjęcie do albumu <a style='color: #9EBF6D;'>".mysqli_fetch_array($searchForChosenAlbum)['tytul']."</a>!<br/>
								<a style='font-size: 12px; color: #F9FFE9;'>Poczekaj na jego zatwierdzenie.</a></p>
								</div>";		//KOMUNIKAT POMYSLNEGO DODANIA ZDJECIA              //$albumRow['tytul'] - pochodzi z <select> do wyboru albumu
						
					header ('location: index.php?page=dodaj-foto'); exit(); //Bez exit nie wyświetla komunikatu

				}
				else {$_SESSION['ADDphotoError'] = "<p><img src='img/error.png'>Wysłany plik nie jest zdjęciem!</p>";}
			}
			elseif ($_FILES['ADDEDphoto']['error'] == UPLOAD_ERR_NO_FILE){
				$_SESSION['ADDphotoError'] = "<p><img src='img/error.png'> Nie wysłałeś żadnego pliku!</p>";}
			else {$_SESSION['ADDphotoError'] = "<p><img src='img/error.png'> Błąd przesyłania pliku. Spróbuj ponownie.</p>";}
	}
	
	if (@$_SESSION['ADDphotoError'] != "") {
		echo "<article> <div class='form'>".@$_SESSION['ADDphotoError']."</div></article>";
		@$_SESSION['ADDphotoError'] = "";}
	 ?>
	 





