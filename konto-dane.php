<article>
	<div style="width: 800px; text-align: center; margin: auto;">
		<form method="post" action="index.php?page=konto&konto-page=konto-dane">
			<h2>Zmień hasło:</h2>
			<table style='margin: 0 auto;'>
				<tr><th class="formTableTh1">Stare hasło:</th>		<th class="formTableTh2"><input type="password" 	name = "CHPoldPassword" 		placeholder="Stare hasło" 		pattern="^\S{6,}$" 	required></th></tr>
				<tr><th class="formTableTh1">Nowe hasło:</th>		<th class="formTableTh2"><input type="password" 	name = "CHPnewPassword" 		placeholder="Nowe hasło" 		pattern="^\S{6,}$" 	required></th></tr>
				<tr><th class="formTableTh1">Potwierdź hasło:</th>	<th class="formTableTh2"><input type="password" 	name = "CHPnewPasswordConf" placeholder="Potwierdź hasło" 	pattern="^\S{6,}$" 	required></th></tr>
				<tr><th class="formTableTh1"><input class="button" type="submit" value = "Zmień hasło" name = "CHP"><th></th>	
			</table>
		</form> 
	</div>
</article>
<?php
	if (isset($_POST['CHPnewPassword'])){
		if ((md5($_POST['CHPoldPassword']) == $_SESSION['LOGGEDpassword']) && ($_POST['CHPnewPassword'] == $_POST['CHPnewPasswordConf'])){
			
			///@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ DODAJ! kiedy nie moge zmienić hasla?
			//nie mozna zmienic hasla, kiedy haslo nei spelnia warunkow
			//nie moge zmienic hasla gdy haslo jest taie samo jak aktualne
			
			mysqli_query($connection, "UPDATE uzytkownicy SET haslo='".md5($_POST['CHPnewPassword'])."' WHERE uzytkownicy.id = ".$_SESSION['LOGGEDid']);
			
			$_SESSION['LOGGEDpassword'] = md5($_POST['CHPnewPassword']);
			echo "Zmieniono haslo!";
		}
	}
?>

<article>
	<div style="width: 800px; text-align: center; margin: auto;">
		<form method="post" action="index.php?page=konto&konto-page=konto-dane">
			<h2>Zmień E-mail:</h2>
			<table style='margin: 0 auto;'>
				<tr><th class="formTableTh1">Nowy e-mail:</th>		<th class="formTableTh2"><input type="email"		name = "CHEemail" 					placeholder="Nowy e-mail" 		pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}${5,128}" required></th></tr>
				<tr><th class="formTableTh1">Hasło:</th>				<th class="formTableTh2"><input type="password" 	name = "CHEpassword" 			placeholder="Hasło" 				pattern="^\S{6,}$" 	required></th></tr>
				<tr><th class="formTableTh1"><input class="button" type="submit" value = "Zmień e-mail" name = "CHE"><br/></th></th>	
			</table>
		</form> 	
	</div>
</article>
<?php
	if (isset($_POST['CHEemail'])){
		if (md5($_POST['CHEpassword']) == $_SESSION['LOGGEDpassword']){
			///@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ DODAJ! k kiedy nie moge zmienic maila? 
			//dodaj, ze nie mozna zmienic maila gdy ktos juz taki ma
			//dodaj ze nie mozna gdy jest on taki sam jak aktualny
			mysqli_query($connection, "UPDATE uzytkownicy SET email='".$_POST['CHEemail']."' WHERE uzytkownicy.id = ".$_SESSION['LOGGEDid']);
			$_SESSION['LOGGEDemail'] = $_POST['CHEemail'];
			echo "Zmieniono maila!";
		}
		
	}
?>