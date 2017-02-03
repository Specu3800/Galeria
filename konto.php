<article>
	<div style="width: 800px; text-align: center; margin: auto;">
	<table style="margin: 0 auto;">
		<tr>
			<th style="text-align: center;">
				<a <?php if (@$_GET['konto-page'] == '') echo "style='color: #9EBF6D;'"; ?> 						
				href="index.php?page=konto"> MAIN </a>|
				<a <?php if (@$_GET['konto-page'] == 'konto-dane') echo "style='color: #9EBF6D;'"; ?> 		
				href="index.php?page=konto&konto-page=konto-dane"> MOJE DANE </a>|
				<a <?php if (@$_GET['konto-page'] == 'konto-albumy') echo "style='color: #9EBF6D;'"; ?> 	
				href="index.php?page=konto&konto-page=konto-albumy"> MOJE ALBUMY </a>|
				<a <?php if (@$_GET['konto-page'] == 'konto-foto') echo "style='color: #9EBF6D;'"; ?> 		
				href="index.php?page=konto&konto-page=konto-foto"> MOJE ZDJĘCIA </a>		
			</th>
		</tr>
	</table>
	</div>
</article>


<?php 			
	if (isset($_GET['konto-page'])) include_once $_GET['konto-page'] . '.php';
	else include_once 'konto-main.php';
?>
