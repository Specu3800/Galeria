<article>
<div class='form' style='text-align: center;'>
<?php
	$allPhotos = mysqli_query($connection, "SELECT id_zdjecia,avg(ocena) AS ocena, id_albumu, zdjecia.zaakceptowane FROM zdjecia_oceny JOIN zdjecia ON zdjecia_oceny.id_zdjecia = zdjecia.id WHERE zdjecia.zaakceptowane = 1 GROUP BY id_zdjecia ");
											
											
	//ILOŚĆ ALBUMÓW
	$photosAmount = mysqli_num_rows($allPhotos); //ilosc pobranych albumów 
	$photosPerPage = 10; //tyle na stronie
	$pagesAmount = floor(($photosAmount-1) / $photosPerPage) + 1; // tyle stron



	//STRONNICOWANIE
	if (isset($_GET['strona'])) $pages = $_GET['strona']; else $pages = 1;
	$sqlpage = $photosPerPage*($pages-1);
	
	$allPhotos = mysqli_query($connection, "SELECT id_zdjecia,avg(ocena) AS ocena, id_albumu, zdjecia.zaakceptowane
											FROM zdjecia_oceny 
											JOIN zdjecia ON zdjecia_oceny.id_zdjecia = zdjecia.id 
											WHERE zdjecia.zaakceptowane = 1 
											GROUP BY id_zdjecia 
											ORDER BY ocena DESC
											LIMIT $sqlpage, $photosPerPage");
											
											
	
	while($photoRow = mysqli_fetch_array($allPhotos)){
					$id_zdj = $photoRow['id_zdjecia'];
					$id_albumu = $photoRow['id_albumu'];
					$ocena = $photoRow['ocena']; 
					
	if ($ocena > 0) $selectedPhotoNote = number_format($ocena, 2, '.', ''). "/10";
			else $selectedPhotoNote = "Brak ocen";				
					
		echo "	<div class='image'><a href=index.php?page=foto&id_zdjecia=".$id_zdj."&id_albumu=".$id_albumu.">
					<img class='dymek' src='img/albumy/$id_albumu/$id_zdj' width='180px' height='180px'></a>
					Ocena:<br/>".$selectedPhotoNote."
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
				echo "class='page-button' href='index.php?page=top-foto&strona=$i'>$i</a>-";}
				echo '-
				</div>';
		?>
	</div>
</article>