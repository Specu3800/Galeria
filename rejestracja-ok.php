<article>
	<div style="width: 800px; text-align: center; margin: auto;">
		<?php
			echo "<p><a>Witaj </a><a style='color: #9EBF6D;'>" . @$_SESSION['LOGGEDlogin'] . "</a><a>!</a><br/>";
		?>
	</div>
	<div  style="width: 800px; text-align: center; margin: auto;">
		Kliknij poniżej aby przejść do galerii: </p>
		<form method="post" action="index.php?page=galeria"><input class="button" type="submit" value = "Galeria" name = "LOG"></form><br/>
	</div>
</article>

