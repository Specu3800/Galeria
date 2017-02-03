<article>	
		<?php		
			//ILOŚĆ WSZYSTKICH ALBUMÓW
			$searchForAlbums = @mysqli_query($connection, "SELECT albumy.id
																							FROM albumy 
																							JOIN zdjecia 
																							ON albumy.id = zdjecia.id_albumu
																							WHERE zdjecia.zaakceptowane AND albumy.id_uzytkownika = ". $_SESSION['LOGGEDid'] ."
																							GROUP BY albumy.id");
			$albumsAmount = mysqli_num_rows($searchForAlbums); //ilosc pobranych albumów 
			$albumsPerPage = 10; //tyle na stronie
			$pagesAmount = floor(($albumsAmount-1) / $albumsPerPage) + 1; // tyle stron
	

			//STRONNICOWANIE
			if (isset($_GET['strona'])) $pages = $_GET['strona']; else $pages = 1;
			$sqlpage = $albumsPerPage*($pages-1);

			
			//WYSWIETLANE ALBUMÓW
			$result = @mysqli_query($connection, "SELECT albumy.id as id_a, albumy.tytul, uzytkownicy.login, albumy.data
																			FROM albumy 
																			JOIN uzytkownicy 
																			ON albumy.id_uzytkownika = uzytkownicy.id
																			WHERE albumy.id_uzytkownika = ". $_SESSION['LOGGEDid'] ."
																			GROUP BY albumy.id
																			ORDER BY `id_a` desc
																			LIMIT $sqlpage, $albumsPerPage");
		
			while($row = mysqli_fetch_assoc($result))
			{
				$id_albumu = $row['id_a'];
				$tytul = $row['tytul'];
				$login = $row['login'];
				$data = $row['data'];
				
				if($row['tytul'] != "") $tytulShow = $row['tytul']; else $tytulShow = "(brak nazwy)";
				
				echo "
				<div class='form' style='text-align: center;'>
					<table style='width: 100%; text-align: center;'>
						<tr> 
							<td rowspan='3'>
							</td>
							<td>
								<a style='color: #F9FFE9;'>$tytulShow</a>				
							</td>							
							<td style='width: 180px;' rowspan='3'>
								<a style='color: #F9FFE9;'>Dodany: $data<br/><br/> Użytkownika: $login</a>	
							</td>
							<td>
								<form action='index.php?page=konto&konto-page=konto-albumy&delete=$id_albumu' method='post'>
								<input class='button' type='submit' value ='Usuń' onclick=\"return confirm('Jesteś pewny, ze chcesz usunąć album o nazwie: $tytul?')\">
								</form>
							</td>
						</tr>
						<tr>
							<form action='index.php?page=konto&konto-page=konto-albumy&change=$id_albumu' method='post'>
								<td>
									<input type='text'	name = 'CHGalbumName' maxlength='99' value='$tytul' required>
								</td>
								<td style='width: 180px;'>				
									<input class='button' type='submit' value ='Zmień nazwę' name = 'ADD'>
								</td>
							</form>
						</tr>
						<tr>
							<td></td>
							<td style='width: 180px; height: 33%'>
								
							</td>
						</tr>
					</table>
				</div><br/><br/>";	
			}		
//Usuwanie i zmiana nazwy
	if(isset($_GET['delete'])){
		//z tabeli komentarze i oceny											
		$result = mysqli_query($connection, "SELECT id FROM zdjecia WHERE id_albumu=".$_GET['delete']);
		while($row =mysqli_fetch_assoc($result)){
			mysqli_query($connection,"DELETE from zdjecia_komentarze WHERE id_zdjecia=".$row['id']);
			mysqli_query($connection,"DELETE from zdjecia_oceny WHERE id_zdjecia=".$row['id']);	
		}
		
		//z tabeli zdjecia i albumy
		mysqli_query($connection, "DELETE FROM zdjecia WHERE id_albumu=".$_GET['delete']);
		mysqli_query($connection, "DELETE FROM albumy WHERE id=".$_GET['delete']);
		
		
		//z dysku	
		$path = "img/albumy/".$_GET['delete'];				
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
			exec(sprintf("rd /s /q %s", escapeshellarg($path)));}
		else{
			exec(sprintf("rm -rf %s", escapeshellarg($path)));}		
		header ('location: index.php?page=konto&konto-page=konto-albumy');
	}	
	
	if(isset($_GET['change'])){	
		mysqli_query($connection, "UPDATE albumy SET tytul='".$_POST['CHGalbumName']."' 
															WHERE id=".$_GET['change']);
															
		header ('location: index.php?page=konto&konto-page=konto-albumy');
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
				echo "class='page-button' href='index.php?page=konto&konto-page=konto-albumy&strona=$i'>$i</a>-";}
			echo '-</div>';
			
			
		?>
	</div>
</article>

		