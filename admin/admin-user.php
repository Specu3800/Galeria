<article>	
	<div class='form' style='text-align: center;'>
		<form method='post'>
			<input class='button' type='submit' name='showAll' value='Wszyscy' >
			<input class='button' type='submit' name='showA' value='Administratorzy' >
			<input class='button' type='submit' name='showM' value='Moderatorzy'>
			<input class='button' type='submit' name='showU' value='Użytkownicy'>
		</form>
	</div>

		<?php	
			if (isset($_POST['showAll'])) $_SESSION['showUsers']='all';
			if (isset($_POST['showA'])) $_SESSION['showUsers']='a';
			if (isset($_POST['showM'])) $_SESSION['showUsers']='m';
			if (isset($_POST['showU'])) $_SESSION['showUsers']='u';
			if (!isset($_SESSION['showUsers'])) $_SESSION['showUsers']='all';
		
			//ILOŚĆ WSZYSTKICH ALBUMÓW
			if ($_SESSION['showUsers']=='all')	$searchForAllUsers = @mysqli_query($connection, "SELECT * FROM uzytkownicy");
			if ($_SESSION['showUsers']=='a')		$searchForAllUsers = @mysqli_query($connection, "SELECT * FROM uzytkownicy WHERE uprawnienia='administrator'");
			if ($_SESSION['showUsers']=='m')		$searchForAllUsers = @mysqli_query($connection, "SELECT * FROM uzytkownicy WHERE uprawnienia='moderator'");
			if ($_SESSION['showUsers']=='u')		$searchForAllUsers = @mysqli_query($connection, "SELECT * FROM uzytkownicy WHERE uprawnienia='uzytkownik'");
			
			$albumsAmount = mysqli_num_rows($searchForAllUsers); //ilosc pobranych albumów 
			$albumsPerPage = 10; //tyle na stronie
			$pagesAmount = floor(($albumsAmount-1) / $albumsPerPage) + 1; // tyle stron
	

			//STRONNICOWANIE
			if (isset($_GET['strona'])) $pages = $_GET['strona']; else $pages = 1;
			$sqlpage = $albumsPerPage*($pages-1);

			
			//WYSWIETLANE ALBUMÓW
			if ($_SESSION['showUsers']=='all'){
				$result = @mysqli_query($connection, "SELECT * FROM uzytkownicy
														ORDER BY `login` asc
														LIMIT $sqlpage, $albumsPerPage");}
			if ($_SESSION['showUsers']=='a'){
				$result = @mysqli_query($connection, "SELECT * FROM uzytkownicy
														WHERE uprawnienia='administrator'
														ORDER BY `login` asc
														LIMIT $sqlpage, $albumsPerPage");}
			if ($_SESSION['showUsers']=='m'){
				$result = @mysqli_query($connection, "SELECT * FROM uzytkownicy
														WHERE uprawnienia='moderator'
														ORDER BY `login` asc
														LIMIT $sqlpage, $albumsPerPage");}
			if ($_SESSION['showUsers']=='u'){
				$result = @mysqli_query($connection, "SELECT * FROM uzytkownicy
														WHERE uprawnienia='uzytkownik'
														ORDER BY `login` asc
														LIMIT $sqlpage, $albumsPerPage");}
														
			while($row = mysqli_fetch_assoc($result))
			{
				$id = $row['id'];
				$login = $row['login'];
				$email= $row['email'];
				$zarejestrowany= $row['zarejestrowany'];
				$uprawnienia = $row['uprawnienia'];
				$aktywny = $row['aktywny'];
				
				if($aktywny) $aktywnyShow = "<img src='img/ok.png'>Aktywny"; else $aktywnyShow = "<img src='img/x.png'>Nieaktywny";
				
				
				echo "
				<div class='form' style='text-align: center;'>
					<table style='width: 100%; text-align: center;'>
						<tr> 
							<td rowspan='2'>
								$login </br> $email	
							</td>						
							<td style='width: 180px;' rowspan='2'>
								<a style='color: #F9FFE9;'>Dołączył: $zarejestrowany<br/><br/> Grupa: $uprawnienia<br/><br/>$aktywnyShow</a>	
							</td>
							<td style='width: 180px;' rowspan='2'>
								<form action='index.php?page=admin&admin-page=admin-user&change=$id' method='post'>
									Zmień<br/>uprawnienia: <br/>
									<input style='width: 50px;' class='button' type='submit' name='perm' value ='A'>
									<input style='width: 50px;' class='button' type='submit' name='perm' value ='M'>
									<input style='width: 50px;' class='button' type='submit' name='perm' value ='U'>
								</form>
							</td>
							<td style='width: 180px; height: 33%'>
								<form action='index.php?page=admin&admin-page=admin-user&delete=$id' method='post'>
								<input class='button' type='submit' value ='Usuń' onclick=\"return confirm('Jesteś pewny, ze chcesz usunąć konto użytkownika: $login?')\">
								</form>
							</td>
						</tr>
						<tr>
								<td style='width: 180px;'>	
									<form action='index.php?page=admin&admin-page=admin-user&block=$id' method='post'>
									<input class='button' type='submit' value ='Zablokuj/odblokuj'>
									</form>
								</td>
						</tr>
					</table>
				</div><br/><br/>";	
			}		
//Usuwanie i zmiana nazwy
	if(isset($_GET['delete'])){									
		$result = mysqli_query($connection, "SELECT id FROM albumy WHERE id_uzytkownika=".$_GET['delete']);
		while($album =mysqli_fetch_assoc($result)){
			$result2 = mysqli_query($connection, "SELECT id FROM zdjecia WHERE id_albumu=".$album['id']);
			while($row =mysqli_fetch_assoc($result2)){
				mysqli_query($connection,"DELETE from zdjecia_komentarze WHERE id_zdjecia=".$row['id']);
				mysqli_query($connection,"DELETE from zdjecia_oceny WHERE id_zdjecia=".$row['id']);	
			}
			mysqli_query($connection, "DELETE FROM zdjecia WHERE id_albumu=".$album['id']);
			mysqli_query($connection, "DELETE FROM albumy WHERE id=".$album['id']);
			
			$path = "img/albumy/".$album['id'];				
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
				exec(sprintf("rd /s /q %s", escapeshellarg($path)));}
			else{
				exec(sprintf("rm -rf %s", escapeshellarg($path)));}	}
		mysqli_query($connection,"DELETE FROM uzytkownicy WHERE id=".$_GET['delete']);
		header ('location: index.php?page=admin&admin-page=admin-user');		
	}	
	
	if(isset($_GET['change'])){	
		switch ($_POST['perm']){
			case 'A':{mysqli_query($connection, "UPDATE uzytkownicy SET uprawnienia='administrator' WHERE id=".$_GET['change']);break;}
			case 'M':{mysqli_query($connection, "UPDATE uzytkownicy SET uprawnienia='moderator' WHERE id=".$_GET['change']);break;}
			case 'U':{mysqli_query($connection, "UPDATE uzytkownicy SET uprawnienia='uzytkownik' WHERE id=".$_GET['change']);break;}
		}
		header ('location: index.php?page=admin&admin-page=admin-user');
	}
	
	if(isset($_GET['block'])){	
		$result = mysqli_query($connection, "SELECT * FROM uzytkownicy WHERE id =".$_GET['block'] );
		$status = mysqli_fetch_assoc($result)['aktywny'];
				
		if ($status == 1) 
			mysqli_query($connection, "UPDATE uzytkownicy SET `aktywny`=0 WHERE id=".$_GET['block']);
		else 
			mysqli_query($connection, "UPDATE uzytkownicy SET `aktywny`=1 WHERE id=".$_GET['block']);
															
		header ('location: index.php?page=admin&admin-page=admin-user');
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
				echo "class='page-button' href='index.php?page=admin&admin-page=admin-user&strona=$i'>$i</a>-";}
			echo '-</div>';
			
			
		?>
	</div>
</article>

		