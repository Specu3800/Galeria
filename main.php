<?php
	if (@$_SESSION['LOGGEDlogin'] != "" &&  @$_SESSION['LOGGEDemail'] != "") header ('location: index.php?page=galeria'); 
			
	//WYSWIETLENIE NAVERROR (GDY KTOŚ NIEZALOGOWANY WRACA ZE STRONY DO KTÓREJ NIE MA DOSTĘPU)
	if (@$_SESSION['NAVerror'] != "") {
		echo "<article> <div class='form'>".@$_SESSION['NAVerror']."</div></article>";
		@$_SESSION['NAVerror'] = "";}
?>
	
<article>
	<div class="form">
		<form method="post" action="index.php?page=rejestracja">
			<h2>Rejestracja:</h2>
			<table style='margin: 0 auto;'>
				<tr><th class="formTableTh1" >Login:</th>					<th class="formTableTh2" ><input type="text"			name = "REGlogin" 				placeholder="Login" 				pattern="[A-Za-z0-9]{6,20}" 	value="<?php echo @$_GET['login']; ?>" required></th></tr>
				<tr><th class="formTableTh1" >Hasło:</th>					<th class="formTableTh2" ><input type="password" 	name = "REGpassword" 		placeholder="Hasło" 				pattern="^\S{6,}$" 				required></th></tr>
				<tr><th class="formTableTh1" >Potwierdź hasło:</th>	<th class="formTableTh2" ><input type="password" 	name = "REGpasswordConf" 	placeholder="Potwierdź hasło" 	pattern="^\S{6,}$" 				required></th></tr>
				<tr><th class="formTableTh1" >E-mail:</th>					<th class="formTableTh2" ><input type="email"			name = "REGemail" 				placeholder="E-mail" 				pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}${5,128}" value="<?php echo @$_GET['email']; ?>" required></th></tr>
				<tr><th class="formTableTh1" ><input class="button" type="submit" value = "Zarejestruj" name = "REG"></th></th>	
			</table>
		</form> 
	</div>
</article>
	
<?php 	//WYSWIETLENIE REGERROR (BLAD REJESTRACJI)
	if (@$_SESSION['REGerror'] != "") {
		echo "<article> <div class='form'>".@$_SESSION['REGerror']."</div></article>";
		@$_SESSION['REGerror'] = "";}
?>
		
<article>
	<div class="form">
		<form method="post">
			<h2 >Logowanie:</h2>
			<table style='margin: 0 auto;'>
				<tr><th class="formTableTh1">Login:</th>					<th class="formTableTh2"> <input type="text" 		name = "LOGlogin"  				placeholder="Login" 		pattern="[A-Za-z0-9]{6,20}" 	required></th></tr>
				<tr><th class="formTableTh1">Hasło:</th>					<th class="formTableTh2"> <input type="password"	name = "LOGpassword" 		placeholder="Hasło" 	 	pattern="^\S{6,}$"  				required></th></tr>
				<tr><th class="formTableTh1"><input type="submit" name = "LOG" value = "Zaloguj" class="button"></th></tr>
			</table>
		</form> 
	</div>
</article>
	
<?php

	if (isset($_POST['LOGlogin'])){
		$searchForUser = mysqli_query($connection, "SELECT * FROM uzytkownicy WHERE login LIKE '".$_POST['LOGlogin']."' AND haslo LIKE '".md5($_POST['LOGpassword'])."'");
		$usersRow = mysqli_fetch_assoc($searchForUser);
		if ($_POST['LOGlogin'] != ""){
			if ($usersRow > 0) {
				if ($usersRow['aktywny'] == true){
					$_SESSION['LOGGEDid'] = $usersRow['id']; 				
					$_SESSION['LOGGEDlogin'] = $usersRow['login']; 
					$_SESSION['LOGGEDpassword'] = $usersRow['haslo']; 
					$_SESSION['LOGGEDemail'] = $usersRow['email']; 
					$_SESSION['LOGGEDdata'] = $usersRow['zarejestrowany'];
					$_SESSION['LOGGEDpermissions'] = $usersRow['uprawnienia'];
					$_SESSION['LOGGEDactive'] = $usersRow['aktywny'];
					header ('location: index.php?page=galeria');
				}
				else @$_SESSION['LOGerror'] .= "<p><img src='img/error.png'> Konto jest nieaktywne.</p>";
			}
			else @$_SESSION['LOGerror'] .= "<p><img src='img/error.png'> Zły login lub hasło.</p>";
		}
		if (@$_SESSION['LOGerror'] != "") {
			echo "<article> <div class='form'>".@$_SESSION['LOGerror']."</div></article>";
			$_SESSION['LOGerror'] = "";}
	}
?>
		
<article>
	<h2>Dane logowania</h2>
	<p style='color: #9EBF6D; font-size: 14px; text-align: center;'> TEN WPIS SŁUŻY WYŁĄCZNIE W CELU SPRAWDZANIA DZIAŁANIA STRONY</p>
	<p style='color: #9EBF6D; font-size: 12px; text-align: center;'> (w kodzie HTML, poniżej tego tekstu, znjaduje się również zakomentowana tabela <br/>wyświetlająca wszystkie rekordy użytkowników z bazy danych)</p>
			
	<table style='margin: 0 auto; text-align: left;'>
		<tr><th style='font-weight: 500;'>		LOGIN						</th>		<th style='font-weight: 500;'>HASŁO</th></tr>
		<tr><th style='font-weight: normal;'>	uzytkownik				</th>		<th style='font-weight: normal;'>zaq12WSX</th></tr>
		<tr><th style='font-weight: normal;'>	uzytkownikNieaktywny</th>		<th style='font-weight: normal;'>zaq12WSX</th></tr>
		<tr><th style='font-weight: normal;'>	moderator					</th>		<th style='font-weight: normal;'>zaq12WSX</th></tr>
		<tr><th style='font-weight: normal;'>	administrator				</th>		<th style='font-weight: normal;'>zaq12WSX</th></tr>
	<table> <br/>
	
	<!-- WYŚWIETLENIE DANYCH UŻYTKOWNIKÓW W TABELI
	<table style="margin: auto;" border="1px solid black">
		<tr>
			<th>id</th>
			<th>login</th>
			<th>haslo</th>
			<th>email</th>
			<th>zarejestrowany</th>
			<th>uprawnienia</th>
			<th>aktywny</th>
		</tr>
		<?php 
			$result = @mysqli_query($connection, "SELECT * FROM uzytkownicy");
			while($row= mysqli_fetch_assoc($result)){
				echo '<tr>';
				echo "<th>".$row['id']."</th>";
				echo "<th>".$row['login']."</th>";
				echo "<th>".$row['haslo']."</th>";
				echo "<th>".$row['email']."</th>";
				echo "<th>".$row['zarejestrowany']."</th>";
				echo "<th>".$row['uprawnienia']."</th>";
				echo "<th>".$row['aktywny']."</th>";
				echo '</tr>';
			}
		?>
		-->
	</table>
</article>

