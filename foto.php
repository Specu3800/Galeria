<article>
	<div class='form' style='text-align: center;'><p><a style='color: #9EBF6D;' href='index.php?page=album'><img src='img/arrow.png'>Powrót do albumu</a></p></div>
</article>

<!--------------------------------------------------- WYŚWIETLANIE ZDJĘCIA -->
<article>
	<div style="text-align: center;">
		<?php 
		
			if ((isset($_GET['id_zdjecia']))){
				$_SESSION['chosenPhotoViewID'] = $_GET['id_zdjecia'];
				if (isset($_GET['id_albumu'])) $_SESSION['chosenAlbumViewID'] = $_GET['id_albumu']; 
				header('location: index.php?page=foto');
			}
			
			if (isset($_SESSION['chosenPhotoViewID'])){						
				$selectedPhoto = @mysqli_query($connection, "SELECT zdjecia.id AS z_ID, uzytkownicy.id AS u_ID, login, zdjecia.data AS z_data, zdjecia.tytul FROM zdjecia 
																						JOIN uzytkownicy ON uzytkownicy.id = zdjecia.id_uzytkownika
																						WHERE zdjecia.id =".$_SESSION['chosenPhotoViewID']);
				$selectedPhotoRow = mysqli_fetch_assoc($selectedPhoto);
				
				echo "<img src='img/albumy/". $_SESSION['chosenAlbumViewID'] ."/".$_SESSION['chosenPhotoViewID']."' style='max-width: 100%;'>";
			}
		?>
	</div>
</article>

<!--------------------------------------------------- WYŚWIETLENIE DANYCH WYBRANEGO ZDJĘCIA -->
<article>
		<?php 
			$selectedPhotoNote = 	mysqli_query($connection, "	SELECT SUM(zdjecia_oceny.ocena) FROM zdjecia_oceny WHERE zdjecia_oceny.id_zdjecia =".$_SESSION['chosenPhotoViewID']);	
			$sum = mysqli_fetch_row($selectedPhotoNote);
			$notesSum = $sum[0];
				
			$selectedPhotoNoters = 	mysqli_query($connection, "SELECT * FROM zdjecia_oceny WHERE zdjecia_oceny.id_zdjecia =".$_SESSION['chosenPhotoViewID']." GROUP BY zdjecia_oceny.id_uzytkownika");
			$notersAmount = mysqli_num_rows($selectedPhotoNoters);		
			
			$loggedUserAlreadyNoted = 0;	
			if (isset($_SESSION['LOGGEDlogin'])){
				$loggedUserAlreadyNotedSQL = @mysqli_query($connection, "SELECT * FROM zdjecia_oceny WHERE zdjecia_oceny.id_uzytkownika =".$_SESSION['LOGGEDid']. " AND zdjecia_oceny.id_zdjecia = ".$_SESSION['chosenPhotoViewID']);
				if (mysqli_num_rows($loggedUserAlreadyNotedSQL) > 0) $loggedUserAlreadyNoted = 1;}		
			
			
			if ($notersAmount != 0) $selectedPhotoNote = number_format($notesSum/$notersAmount, 2, '.', '') . "/10";
			else $selectedPhotoNote = "Brak ocen";	
				
			echo "<table style='margin: 0 auto;'>";
			echo "<tr><th>Zdjęcie dodano: </th>		<th style='font-weight: normal;'>" . $selectedPhotoRow['z_data'] . "</th></tr>";
			echo "<tr><th>Dodał użytkownik: </th>	<th style='font-weight: normal;'>" . $selectedPhotoRow['login'] . "</th></tr>";
			echo "<tr><th>Opis zdjęcia:</th>			<th style='font-weight: normal;'>" . $selectedPhotoRow['tytul'] . "</th></tr>";
			echo "<tr><th>Ocena: </th>					<th style='font-weight: normal;'>" . $selectedPhotoNote. "</th></tr>";
			echo "<tr><th>Ilość oceniających: </th>	<th style='font-weight: normal;'>" . $notersAmount . "</th></tr>";
			echo "</table>";
		?>
</article>


<!--------------------------------------------------- OCENIANIE -->
<?php 
	if (isset($_POST['ADDnote'])){	
		mysqli_query($connection, "INSERT INTO zdjecia_oceny SET 
													id_zdjecia = ".$_SESSION['chosenPhotoViewID'].",
													id_uzytkownika = '".$_SESSION['LOGGEDid']."',
													ocena = ".$_POST['ADDnote']);
																				
		$loggedUserAlreadyNoted = 1;	
		header('location: index.php?page=foto');
	}
?>	
<article <?php if ((isset($_SESSION['LOGGEDlogin'])) && ($loggedUserAlreadyNoted==0)) echo "style='display: block'"; else echo "style='display: none'" ?>>
	<form method='post'>
		<div class="form">
			<table>
				<tr>
					<th class="formTableTh1" >
						Oceń zdjęcie:
					</th>
					<th class="formTableTh2"><div style="margin: 0 auto;">
						<input style="width: 50px;" type="submit" value = "1" name = "ADDnote">
						<input style="width: 50px;" type="submit" value = "2" name = "ADDnote">
						<input style="width: 50px;" type="submit" value = "3" name = "ADDnote">
						<input style="width: 50px;" type="submit" value = "4" name = "ADDnote">
						<input style="width: 50px;" type="submit" value = "5" name = "ADDnote">
						<input style="width: 50px;" type="submit" value = "6" name = "ADDnote">
						<input style="width: 50px;" type="submit" value = "7" name = "ADDnote">
						<input style="width: 50px;" type="submit" value = "8" name = "ADDnote">
						<input style="width: 50px;" type="submit" value = "9" name = "ADDnote">
						<input style="width: 50px;" type="submit" value = "10" name = "ADDnote"></div>
					</th>
				</tr>
			</table>
		</div>
	</form>
	<form method='post'>
	
	</form>
</article>

<article <?php if ((isset($_SESSION['LOGGEDlogin'])) && ($loggedUserAlreadyNoted==1)) echo "style='display: block'"; else echo "style='display: none'" ?>>
	<div class='form' style='text-align: center;'><p><a style='color: #F9FFE9;'>Już oceniłeś to zdjęcie!</a></p></div>
</article>


<!--------------------------------------------------- KOMENTARZE -->
<article <?php if ((isset($_SESSION['LOGGEDlogin']))) echo "style='display: block'"; else echo "style='display: none'" ?>>
	<div class="form">
		<form method='post'>
			<div class="form">
				<table>
					<tr><th class="formTableTh1">Dodaj komentarz:	</th>		<th class="formTableTh2"><textarea name="ADDcomment" placeholder="maks. 255 znaków" maxlength=255 rows="3" required ></textarea></th></tr>
					<tr><th class="formTableTh1"> 	<input class="button" type="submit" value = "Dodaj" name = "COMM"></th></tr>
				</table>
			</div>
		</form>
	</div>
</article>
<?php
	if (isset($_POST['ADDcomment'])){
		$end = mysqli_query($connection, "INSERT INTO `zdjecia_komentarze` SET
													`id_zdjecia`=".$_SESSION['chosenPhotoViewID'].",
													`id_uzytkownika`=".$_SESSION['LOGGEDid'].",
													`data`='".date("Y-m-d")."',
													`komentarz`='".$_POST['ADDcomment']."',
													`zaakceptowany`=0;");
																								
echo "	<article><div class='form' style='text-align: center;'>
				<p><a style='color: #F9FFE9;'>Dodano </a><a style='color: #9EBF6D;'>komentarz!</a><br/>
				<a style='color: #F9FFE9; font-size: 12px;'>Poczekaj na jego zatwierdzenie.</a></p>
			</div></article>";		
	}
?>

<article>
	<div class="form">
		<h2>Komentarze:</h2>
		<?php
			$selectedPhotoComments = @mysqli_query($connection, "SELECT * FROM zdjecia_komentarze 	
																								JOIN uzytkownicy
																								ON uzytkownicy.id = zdjecia_komentarze.id_uzytkownika
																								WHERE zdjecia_komentarze.zaakceptowany = 1 AND zdjecia_komentarze.id_zdjecia =".$_SESSION['chosenPhotoViewID']."
																								ORDER BY data DESC");
					
			if (mysqli_num_rows($selectedPhotoComments) > 0){	
				echo "<table style='table-layout: fixed; text-align: left;'>";
					while($row= mysqli_fetch_array($selectedPhotoComments)){
						echo "<tr><th><a style='font-size: 14px; color: #9EBF6D;'>".$row['login']."</a><a style='font-size: 14px; color: #6f696a;'>(".$row['data'].")</a></th></tr>";
						echo "<tr><td style='font-weight: normal; word-wrap:break-word; max-width: 800px; font-size: 14px; text-align: left;'>".$row['komentarz']."</td></tr>";}					
				echo "</table>";
			}
			else {
				echo"<div class='form' style='text-align: center;'><p><a style='color: #F9FFE9;''>Brak (zaakceptowanych) komentarzy.</a></p></div>";}
		?>
	</div>
</article>


<!--------------------------------------------------- ABY MÓC OCENIAĆ I KOMENTOWAĆ ZALOGUJ SIĘ -->
<article <?php if (isset($_SESSION['LOGGEDlogin'])) echo "style='display: none'"; else echo "style='display: block'" ?>>
	<div class='form' style='text-align: center;'><p><a href='index.php' style='color: #F9FFE9;'>Aby móc </a><a style='color: #9EBF6D;' href='index.php'>oceniać</a><a href='index.php' style='color: #F9FFE9;'> i </a><a style='color: #9EBF6D;' href='index.php'>komentować</a><a href='index.php' style='color: #F9FFE9;'> zdjęcia zaloguj się lub zarejestruj!</a></p></div>
</article>


<article>
	<div class='form' style='text-align: center;'><p><a style='color: #9EBF6D;' href='index.php?page=album'><img src='img/arrow.png'>Powrót do albumu</a></p></div>
</article>