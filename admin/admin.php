<article>
	<div style="width: 800px; text-align: center; margin: auto;">
	<table style="margin: 0 auto;">
		<tr>
			<th style="text-align: center;">
				
				<?php if ((@$_SESSION['LOGGEDpermissions'] == 'administrator')){ 
					echo "<a ";
						if ((@$_GET['admin-page'] == 'admin-albumy') || @($_GET['admin-page'] == "")) 
							echo "style='color: #9EBF6D;' ";
				echo "href='index.php?page=admin&admin-page=admin-albumy'> ALBUMY </a>|";}?> 
						
				<a <?php if (@$_GET['admin-page'] == 'admin-foto') echo "style='color: #9EBF6D;'"; ?> 	
				href="index.php?page=admin&admin-page=admin-foto"> ZDJĘCIA </a>|
				
				<a <?php if (@$_GET['admin-page'] == 'admin-koment') echo "style='color: #9EBF6D;'"; ?> 		
				href="index.php?page=admin&admin-page=admin-koment"> KOMENTARZE </a>|

				<?php if ((@$_SESSION['LOGGEDpermissions'] == 'administrator')){ 
					echo "<a ";
						if ((@$_GET['admin-page'] == 'admin-user') || @($_GET['admin-page'] == "")) 
							echo "style='color: #9EBF6D;' ";
				echo "href='index.php?page=admin&admin-page=admin-user'> UŻYTKOWNICY </a>|";}?> 
			
				<a <?php if (@$_GET['admin-page'] == 'galeria') echo "style='color: #9EBF6D;'"; ?>
				href="index.php?page=galeria"> POWRÓT </a>	
				
			</th>
		</tr>
	</table>
	</div>
</article>


<?php 			
	if (isset($_GET['admin-page'])) include_once "admin/".$_GET['admin-page'] . '.php';
	else include_once 'admin/admin-albumy.php';
?>
