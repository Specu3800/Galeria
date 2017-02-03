<article>
	<div class='form' style='text-align: center;'><p><a style='color: #9EBF6D;' href='index.php?page=galeria'><img src='img/arrow.png'>Powrót do galerii</a></p></div>
</article>


<article>
	<div class='form' style='text-align: center;'>
		<?php 
			if (isset($_GET['id_albumu'])){$_SESSION['chosenAlbumViewID'] = $_GET['id_albumu']; header('location: index.php?page=album');}
			
			if (isset($_SESSION['chosenAlbumViewID'])){						
				$selectedAlbumPhotos = @mysqli_query($connection, "SELECT albumy.id as id_a, zdjecia.id as id_z, albumy.tytul, zdjecia.zaakceptowane
											FROM albumy 
											JOIN uzytkownicy 
											ON albumy.id_uzytkownika = uzytkownicy.id
											JOIN zdjecia
											ON zdjecia.id_albumu = albumy.id
											WHERE zdjecia.zaakceptowane = 1 
											AND albumy.id =".$_SESSION['chosenAlbumViewID']."");
				
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
											WHERE zdjecia.zaakceptowane = 1 
											AND albumy.id =".$_SESSION['chosenAlbumViewID']."
											LIMIT $sqlpage, $photosPerPage");
				while($photoRow = mysqli_fetch_assoc($selectedAlbumPhotos)){
					$id_albumu = $photoRow['id_a'];
					$id_zdj = $photoRow['id_z'];
					$tytul = $photoRow['tytul'];
					$acpt = $photoRow['zaakceptowane'];		

					echo "
					<div class='image'><a href=index.php?page=foto&id_zdjecia=".$id_zdj.">
					<img class='dymek' src='img/albumy/$id_albumu/$id_zdj' width='180px' height='180px'>
					</a></div>";
				}	
			}
		?>
	</div>
	
	<div class="page">
		<?php
			echo '<div style="text-align: center; margin: 0 auto;">-';
			for ($i = 1; $i<=@$pagesAmount; $i++){
				echo "-<a ";
				if (isset($_GET['strona'])) {if ($_GET['strona'] == $i) echo "style='color: #9EBF6D;' ";}
				else {
				if ($i == 1) echo "style='color: #9EBF6D;' ";}
				echo "class='page-button' href='index.php?page=album&strona=$i'>$i</a>-";}
			echo '-
			</div>';
		?>
	</div>
</article>


<article>
	<div class='form' style='text-align: center;'><p><a style='color: #9EBF6D;' href='index.php?page=galeria'><img src='img/arrow.png'>Powrót do galerii</a></p></div>
</article>

