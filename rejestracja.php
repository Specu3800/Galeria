<?php		
	$REGlogin = $_POST['REGlogin'];	
	$REGpassword = $_POST['REGpassword'];
	$REGpasswordConf = $_POST['REGpasswordConf'];
	$REGemail = $_POST['REGemail'];	
	
	$_SESSION['REGerror'] = ""; 
	
	//WALIDACJA LOGINU
	if  ($REGlogin == "")
		{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Musisz podać login. </p>";}
		else{
		if (strlen($REGlogin) < 6 || strlen($REGlogin) >20)
		{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Login musi mieć od 6 do 20 znaków. </p>";}	
		if (!preg_match("/^[A-Za-z0-9]+$/", $REGlogin))
		{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Login powinien zawierać tylko litery i cyfry. </p>";}}
	
	//WALIDACJA HASEŁ
	if  ($REGpassword == "")
		{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Musisz podać hasło. </p>";}
		else {
			if (strlen($REGpassword) < 6 || strlen($REGpassword) >20)
			{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Hasło musi mieć od 6 do 20 znaków. </p>";}	
			if (!preg_match("/^[A-Za-z0-9]+$/", $REGpassword))
			{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Hasło może zawierać tylko litery i cyfry. </p>";}
			if (!preg_match("/[A-Z]/", $REGpassword))
			{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Hasło musi zawierać co najmniej jedną dużą literę. </p>";}
			if (!preg_match("/[a-z]/", $REGpassword))
			{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Hasło musi zawierać co najmniej jedną małą literę. </p>";}
			if (!preg_match("/[0-9]/", $REGpassword))
			{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Hasło musi zawierać co najmniej jedną cyfrę. </p>";}
		}
			
	if ($REGpassword != $REGpasswordConf) {
		$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Podane hasła musza być identyczne. </p>";}

	//WALIDACJA E-MAILA
	if ($REGemail == "")
		{$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Musisz podać E-mail. </p>";}
		else
	if (!filter_var($REGemail, FILTER_VALIDATE_EMAIL)) {
	  $_SESSION['REGerror'] .= "<p><img src='img/error.png'>  Musisz podać poprawny E-mail. </p>";}

	  
	//SPRAWDZANIE, CZY TAKI UZYTKOWNIK JUZ ISTNEIJE
	$searchForLogin = "SELECT * FROM uzytkownicy WHERE login LIKE '".$REGlogin."'";
	if (mysqli_fetch_assoc(mysqli_query($connection, $searchForLogin)) > 0) {
		$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Podany login jest już zajęty. </p>";}
	
	$searchForEmail = "SELECT * FROM uzytkownicy WHERE email LIKE '".$REGemail."'";
	if (mysqli_fetch_assoc(mysqli_query($connection, $searchForEmail)) > 0) {
		$_SESSION['REGerror'] .= "<p><img src='img/error.png'> Podany E-mail jest już zajęty. </p>";}

	//DODANIE UZYTKOWNIKA DO BAZY JESLI JESZCZE NIE ISTNIEJE
	if ($_SESSION['REGerror'] == ""){
		mysqli_query($connection, "	INSERT INTO uzytkownicy SET 
														login='".$REGlogin."', 
														haslo='".md5($REGpassword)."', 
														email='".$REGemail."', 
														zarejestrowany='".date("Y-m-d")."', 
														uprawnienia='uzytkownik', 
														aktywny=true");
														
		$searchForRegisteredUser = mysqli_query($connection, "SELECT * FROM uzytkownicy WHERE login LIKE '".$REGlogin."' AND haslo LIKE '".md5($REGpassword)."'");
		$usersRow = mysqli_fetch_assoc($searchForRegisteredUser);
		
		$_SESSION['LOGGEDid'] = $usersRow['id']; 				
		$_SESSION['LOGGEDlogin'] = $usersRow['login']; 
		$_SESSION['LOGGEDpassword'] = $usersRow['haslo']; 
		$_SESSION['LOGGEDemail'] = $usersRow['email']; 
		$_SESSION['LOGGEDdata'] = $usersRow['zarejestrowany'];
		$_SESSION['LOGGEDpermissions'] = $usersRow['uprawnienia'];
		$_SESSION['LOGGEDactive'] = $usersRow['aktywny'];												

		$REGlogin = "";	
		$REGpassword = "";
		$REGpasswordConf = "";
		$REGemail = "";	
		
		$_SESSION['REGerror'] = "";
		header ('location: index.php?page=rejestracja-ok');}
	else { 
		header ('location: index.php?login='.$REGlogin . '&email=' . $REGemail);	}
?>
