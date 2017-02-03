<article>	
		<?php
			//ILOŚĆ WSZYSTKICH ZDJĘĆ
			$searchForPhotos = @mysqli_query($connection, "SELECT zdjecia.id
																							FROM zdjecia 
																							WHERE zdjecia.id_uzytkownika = ". $_SESSION['LOGGEDid']);
			$photosAmount = mysqli_num_rows($searchForPhotos); //ilosc pobranych zdjęć
			$photosPerPage = 10; //tyle na stronie
			$pagesAmount = floor(($photosAmount-1) / $photosPerPage) + 1; // tyle stron
	

			//STRONNICOWANIE
			if (isset($_GET['strona'])) $pages = $_GET['strona']; else $pages = 1;
			$sqlpage = $photosPerPage*($pages-1);
			
			//WYSWIETLANE MINIATUR
			$result = @mysqli_query($connection, "SELECT albumy.id as id_a, zdjecia.id as id_z, zdjecia.tytul, zdjecia.data, uzytkownicy.login, zdjecia.zaakceptowane 
											FROM albumy 
											JOIN uzytkownicy 
											ON albumy.id_uzytkownika = uzytkownicy.id
											JOIN zdjecia
											ON zdjecia.id_albumu = albumy.id
											ORDER BY `id_z` desc
											LIMIT $sqlpage, $photosPerPage");
											
			while($photoRow = mysqli_fetch_assoc($result)){
				$id_albumu = $photoRow['id_a'];
				$id_zdj = $photoRow['id_z'];
				$login = $photoRow['login'];
				$tytul = $photoRow['tytul'];
				$data = $photoRow['data'];	
				$accept = $photoRow['zaakceptowane'];
				
				
				if($tytul != "") $tytulShow = $tytul; else $tytulShow = "(brak nazwy)";
				if($accept) $acceptShow = "<img src='img/ok.png'>Zaakceptowane"; else $acceptShow = "<img src='img/x.png'>Niezaakceptowane";	
				
				
				echo "
				<div class='form' style='text-align: center;'>
					<table style='width: 100%; text-align: center;'>
						<tr > 
							<td style='width: 180px;' rowspan='3'>
								<img src='img/albumy/$id_albumu/$id_zdj' width='180px' height='180px'>	
							</td>
							<td>
								<a style='color: #F9FFE9;'>$tytulShow</a>				
							</td>	
							<td style='width: 180px;' rowspan='3'>
								<a style='color: #F9FFE9;'>Dodany: $data<br/><br/> Użytkownika: $login<br/><br/>$acceptShow</a>	
							</td>							
							<td style='width: 180px;'>
								<form action='index.php?page=admin&admin-page=admin-foto&delete=$id_zdj' method='post'>
								<input class='button' type='submit' value ='Usuń' onclick=\"return confirm('Jesteś pewny, ze chcesz usunąć zdjęcie o nazwie: $tytul?')\">
								</form>			
							</td>
						</tr>
						<tr > 
							<form action='index.php?page=admin&admin-page=admin-foto&change=$id_zdj' method='post'>
								<td>
									<input type='text'	name = 'CHGfotoName' maxlength='99' value='$tytul' required>
								</td>
								<td style='width: 180px; height: 33%''>				
									<input class='button' type='submit' value ='Zmień nazwę' name = 'ADD'>
								</td>
							</form>
						</tr>
						<tr > 
							<td></td>
							<td style='width: 180px; height: 33%'>";
								if (!$accept)echo "
								<form action='index.php?page=admin&admin-page=admin-foto&accept=$id_zdj' method='post'>
								<input class='button' type='submit' value ='Zaakceptuj'>
								</form>	";
echo"						</td>
						</tr>
					</table>
				</div><br/><br/>";	
			}			

//Usuwanie i zmiana nazwy
	if(isset($_GET['delete'])){
		
		mysqli_query($connection,"DELETE from zdjecia_komentarze WHERE id_zdjecia=".$_GET['delete']);
		mysqli_query($connection,"DELETE from zdjecia_oceny WHERE id_zdjecia=".$_GET['delete']);
		
		$result = mysqli_query($connection, "SELECT albumy.id as id_a, zdjecia.id as id_z
											FROM zdjecia 
											JOIN albumy 
											ON albumy.id = zdjecia.id_albumu
											WHERE zdjecia.id =".$_GET['delete']);		
		
		mysqli_query($connection, "DELETE FROM zdjecia WHERE id=".$_GET['delete']);
														
		$photoAlbum = mysqli_fetch_assoc($result)['id_a'];		
		$path = "img/albumy/$photoAlbum/".$_GET['delete'];
		unlink($path);
		
		header ('location: index.php?page=admin&admin-page=admin-foto');
	}	
	
	
	if(isset($_GET['change'])){	
		mysqli_query($connection, "UPDATE zdjecia 
															SET tytul='".$_POST['CHGfotoName']."' 
															WHERE id=".$_GET['change']);
															
		header ('location: index.php?page=admin&admin-page=admin-foto');
	}
	
	
	if(isset($_GET['accept'])){	
		mysqli_query($connection, "UPDATE zdjecia 
															SET `zaakceptowane`=1
															WHERE id=".$_GET['accept']);
															
		header ('location: index.php?page=admin&admin-page=admin-foto');
	}
	
	

?>
	

	<div class="page">
		<?php
			echo '<div style="text-align: center; margin: 0 auto;">-';
			for ($i = 1; $i<=$pagesAmount; $i++){
				echo "-<a ";
				if (isset($_GET['strona'])) {if ($_GET['strona'] == $i) echo "style='color: #9EBF6D;' ";}
				else {
				if ($i == 1) echo "style='color: #9EBF6D;' ";}
				echo "class='page-button' href='index.php?page=admin&admin-page=admin-foto&strona=$i'>$i</a>-";}
			echo '-</div>';
			
		?>
	</div>
</article>

		

									
								