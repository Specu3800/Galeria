<article>
	<div style="width: 800px; text-align: center; margin: auto;">
		<?php
			if (@$_SESSION['LOGGEDlogin'] == "" &&  @$_SESSION['LOGGEDemail'] == "") {
				@$_SESSION['NAVerror'] .= "<p><img src='img/error.png'> Najpierw musisz się zalogować. </p>";
				header ('location: index.php');}
				
			$searchForLoggedUserData = @mysqli_query($connection, "SELECT * FROM uzytkownicy WHERE login LIKE '".@$_SESSION['LOGGEDlogin']."' AND haslo LIKE '".@$_SESSION['LOGGEDpassword']."'");
			$userDataRow = mysqli_fetch_assoc($searchForLoggedUserData);
			
			echo "<h2> Witaj <a style='color: #9EBF6D; font-size: 24px'>" . $userDataRow['login'] . "</a>! Oto dane twojego konta:</h2>";
			echo "<table style='margin: 0 auto;'>";
			echo "<tr><th>Login: </th>				<th style='font-weight: normal;'>" . $userDataRow['login'] . "</th></tr>";
			echo "<tr><th>E-mail: </th>				<th style='font-weight: normal;'>" . $userDataRow['email'] . "</th></tr>";
			echo "<tr><th>Data rejestracji:</th>	<th style='font-weight: normal;'>" . $userDataRow['zarejestrowany'] . "</th></tr>";
			echo "<tr><th>Uprawnienia: </th>		<th style='font-weight: normal;'>" . $userDataRow['uprawnienia'] . "</th></tr>";
			echo "</table>";
		?>
	</div>
</article>