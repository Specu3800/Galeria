<article>	
	<div class="form" style="text-align: center;">
		<?php		
			//ILOŚĆ WSZYSTKICH ALBUMÓW
			$searchForAlbums = @mysqli_query($connection, "SELECT albumy.id
																							FROM albumy 
																							JOIN zdjecia 
																							ON albumy.id = zdjecia.id_albumu
																							WHERE zdjecia.zaakceptowane
																							GROUP BY albumy.id
																							HAVING SUM(zdjecia.zaakceptowane)>0");
			$albumsAmount = mysqli_num_rows($searchForAlbums); //ilosc pobranych albumów 
			$albumsPerPage = 10; //tyle na stronie
			$pagesAmount = floor(($albumsAmount-1) / $albumsPerPage) + 1; // tyle stron
	

			//STRONNICOWANIE
			if (isset($_GET['strona'])) $pages = $_GET['strona']; else $pages = 1;
			$sqlpage = $albumsPerPage*($pages-1);

			//SORTOWANIE
			if(!isset($_SESSION['sort'])) $_SESSION['sort']="tytul";
			
			if (@$_GET['sort'] !== null){
				switch (@$_GET['sort'])
				{
					case 'login':	{$_SESSION['sort'] = "login"; break;}
					case 'data':	{$_SESSION['sort'] = "data"; break;}
					case 'tytul':	{$_SESSION['sort'] = "tytul"; break;}
				}
				header ('location: index.php?page=galeria');
			}

			$sortPattern = "`".$_SESSION['sort']."`";
			
			//WYSWIETLANE MINIATUR
			$result = @mysqli_query($connection, "SELECT albumy.id as id_a, zdjecia.id as id_z, albumy.tytul, uzytkownicy.login, albumy.data, zdjecia.zaakceptowane
																			FROM albumy 
																			JOIN uzytkownicy 
																			ON albumy.id_uzytkownika = uzytkownicy.id
																			JOIN zdjecia
																			ON albumy.id = zdjecia.id_albumu AND zdjecia.zaakceptowane = 1
																			GROUP BY albumy.id
																			ORDER BY $sortPattern
																			LIMIT $sqlpage, $albumsPerPage");
		
			while($row = mysqli_fetch_assoc($result))
			{
				$id_albumu = $row['id_a'];
				$id_zdj = $row['id_z'];
				$tytul = $row['tytul'];
				$login = $row['login'];
				$data = $row['data'];
				$acpt = $row['zaakceptowane'];
				
				echo "
				<a href='index.php?page=album&id_albumu=$id_albumu'><div class='image'>
				<img class='dymek' src='img/albumy/$id_albumu/$id_zdj' width='180px' height='180px'>
				<div class='text'><div class='innerText'>Tytuł galerii: $tytul<br>Autor: $login<br>Data utworzenia:<br/>$data</div></div>
				</div></a>";
			}		
		?>
	</div>

	<div class="page">
		<?php
			echo '<div style="text-align: center; margin: 0 auto;">-';
			for ($i = 1; $i<=$pagesAmount; $i++){
				echo "-<a ";
				if (isset($_GET['strona'])) {if ($_GET['strona'] == $i) echo "style='color: #9EBF6D;' ";}
				else {
				if ($i == 1) echo "style='color: #9EBF6D;' ";}
				echo "class='page-button' href='index.php?page=galeria&strona=$i'>$i</a>-";}
			echo '-</div>';
			
			echo '<div style="width:' . 300 . 'px; align: center; margin: 0 auto;">';			
			echo "Sortuj według: 	<a "; if (@$_SESSION['sort'] == 'login') echo "style='color: #9EBF6D;'"; echo " href='index.php?page=galeria&sort=login'> LOGIN </a> | 
												<a "; if (@$_SESSION['sort'] == 'data') echo "style='color: #9EBF6D;'"; echo " href='index.php?page=galeria&sort=data'> DATA </a> | 
												<a "; if (@$_SESSION['sort'] == 'tytul') echo "style='color: #9EBF6D;'"; echo " href='index.php?page=galeria&sort=tytul'> TYTUŁ </a>";
			echo '</div>';
		?>
	</div>
</article>

		