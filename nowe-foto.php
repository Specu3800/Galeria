<article>
<div class='form' style='text-align: center;'>
<?php
	$allPhotos = mysqli_query($connection, "SELECT * FROM zdjecia WHERE zaakceptowane=1");
											
											
	//ILOŚĆ ALBUMÓW
	$photosAmount = mysqli_num_rows($allPhotos); //ilosc pobranych albumów 
	$photosPerPage = 10; //tyle na stronie
	$pagesAmount = floor(($photosAmount-1) / $photosPerPage) + 1; // tyle stron



	//STRONNICOWANIE
	if (isset($_GET['strona'])) $pages = $_GET['strona']; else $pages = 1;
	$sqlpage = $photosPerPage*($pages-1);
	
	$allPhotos = mysqli_query($connection, "SELECT albumy.id as id_a, zdjecia.id as id_z, albumy.tytul, zdjecia.zaakceptowane, zdjecia.data AS z_data
											FROM albumy 
											JOIN uzytkownicy 
											ON albumy.id_uzytkownika = uzytkownicy.id
											JOIN zdjecia
											ON zdjecia.id_albumu = albumy.id
											WHERE zdjecia.zaakceptowane = 1 
											ORDER BY zdjecia.data DESC
											LIMIT $sqlpage, $photosPerPage");
	
	while($photoRow = mysqli_fetch_array($allPhotos)){
					$id_zdj = $photoRow['id_z'];
					$id_albumu = $photoRow['id_a'];
					$tytul = $photoRow['tytul'];
					$data = $photoRow['z_data'];
					$acpt = $photoRow['zaakceptowane'];	
					
					
		echo "	<div class='image'><a href=index.php?page=foto&id_zdjecia=".$id_zdj."&id_albumu=".$id_albumu.">
					<img class='dymek' src='img/albumy/$id_albumu/$id_zdj' width='180px' height='180px'></a>
					Data utworzenia:<br/>$data
					</div>";}
					
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
				echo "class='page-button' href='index.php?page=nowe-foto&strona=$i'>$i</a>-";}
				echo '-
				</div>';
		?>
	</div>
</article>