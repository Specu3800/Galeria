<article>	
			
	<div class='form' style='text-align: center;'>
		<form method='post'>
			<input class='button' type='submit' name='showAll' value='Wszystkie' >
			<input class='button' type='submit' name='showPending' value='Do akceptacji'>
		</form>
	</div>
			<?php
			
			if (isset($_POST['showAll'])) $_SESSION['showComments']='all';
			if (isset($_POST['showPending'])) $_SESSION['showComments']='pending';
			if (!isset($_SESSION['showComments'])) $_SESSION['showComments']='all';
			
			if ($_SESSION['showComments']=='all')		$searchForAllComments = @mysqli_query($connection, "SELECT * FROM zdjecia_komentarze");
			if ($_SESSION['showComments']=='pending') 	$searchForAllComments = @mysqli_query($connection, "SELECT * FROM zdjecia_komentarze WHERE zaakceptowany=0");
			
			//ILOŚĆ WSZYSTKICH ALBUMÓW
			
			$searchForNotAcceptedComments = @mysqli_query($connection, "SELECT * FROM zdjecia_komentarze WHERE zaakceptowany=0");
			
			$albumsAmount = mysqli_num_rows($searchForAllComments); //ilosc pobranych albumów 
			$albumsPerPage = 10; //tyle na stronie
			$pagesAmount = floor(($albumsAmount-1) / $albumsPerPage) + 1; // tyle stron
	

			//STRONNICOWANIE
			if (isset($_GET['strona'])) $pages = $_GET['strona']; else $pages = 1;
			$sqlpage = $albumsPerPage*($pages-1);

			
			//WYSWIETLANE ALBUMÓW
			if ($_SESSION['showComments']=='all') {
				$result = @mysqli_query($connection, "SELECT zdjecia_komentarze.id AS id_k, data, komentarz, zaakceptowany, login  FROM zdjecia_komentarze
																JOIN uzytkownicy
																ON uzytkownicy.id = zdjecia_komentarze.id_uzytkownika
																ORDER BY `id_k` desc
																LIMIT $sqlpage, $albumsPerPage");}
			if ($_SESSION['showComments']=='pending') {
				$result = @mysqli_query($connection, "SELECT zdjecia_komentarze.id AS id_k, data, komentarz, zaakceptowany, login  FROM zdjecia_komentarze
																JOIN uzytkownicy
																ON uzytkownicy.id = zdjecia_komentarze.id_uzytkownika
																WHERE zaakceptowany=0
																ORDER BY `id_k` desc
																LIMIT $sqlpage, $albumsPerPage");}
		
			while($row = mysqli_fetch_assoc($result))
			{
				$id_k = $row['id_k'];
				$koment = $row['komentarz'];
				$login = $row['login'];
				$data = $row['data'];
				$accept = $row['zaakceptowany'];
				
				
				if($accept) $acceptShow = "<img src='img/ok.png'>Zaakceptowane"; else $acceptShow = "<img src='img/x.png'>Niezaakceptowane";
				
				echo "
				<div class='form' style='text-align: center;'>
					<table style='width: 100%; text-align: center;'>
						<tr> 
							<td rowspan='3'>
							</td>
							<td>
							</td>						
							<td style='width: 180px;' rowspan='3'>
								<a style='color: #F9FFE9;'>Dodany: $data<br/><br/> Przez: $login<br/><br/>$acceptShow</a>	
							</td>
							<td>
								<form action='index.php?page=admin&admin-page=admin-koment&delete=$id_k' method='post'>
								<input class='button' type='submit' value ='Usuń' onclick=\"return confirm('Jesteś pewny, ze chcesz usunąć ten komentarz?')\">
								</form>
							</td>
						</tr>
						<tr>
							<form action='index.php?page=admin&admin-page=admin-koment&change=$id_k' method='post'>
								<td rowspan='2'>
									<textarea name='editComment' maxlength=255 rows='3' required >$koment</textarea>
								</td>
								<td style='width: 180px;'>				
									<input class='button' type='submit' value ='Zedytuj' name = 'ADD'>
								</td>
							</form>
						</tr>
						<tr>
							<td style='width: 180px; height: 33%'>";
								if (!$accept)echo "
								<form action='index.php?page=admin&admin-page=admin-koment&accept=$id_k' method='post'>
								<input class='button' type='submit' value ='Zaakceptuj'>
								</form>	";
echo"						</td>
						</tr>
					</table>
				</div><br/><br/>";	
			}		
//Usuwanie i zmiana nazwy
	if(isset($_GET['delete'])){
		mysqli_query($connection,"DELETE FROM zdjecia_komentarze WHERE id=".$_GET['delete']);
		header ('location: index.php?page=admin&admin-page=admin-koment');
	}	
	
	if(isset($_GET['change'])){	
		mysqli_query($connection, "UPDATE zdjecia_komentarze SET komentarz='".$_POST['editComment']."'
									WHERE id=".$_GET['change']);
															
		header ('location: index.php?page=admin&admin-page=admin-koment');
	}
	
	if(isset($_GET['accept'])){	
		mysqli_query($connection, "UPDATE zdjecia_komentarze 
															SET `zaakceptowany`=1
															WHERE id=".$_GET['accept']);
															
		header ('location: index.php?page=admin&admin-page=admin-koment');
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
				echo "class='page-button' href='index.php?page=admin&admin-page=admin-koment&strona=$i'>$i</a>-";}
			echo '-</div>';
			
			
		?>
	</div>
</article>

		