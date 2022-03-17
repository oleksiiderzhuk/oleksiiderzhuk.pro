<?php
include('header.php'); 
$link = probirka_db_connect();
$person_id = $_GET['person_id'];
?>
<div class="container">
	<div class="row" style="margin-top: 60px;">
		<div class="col-md-12">
			<form action="system/next_imen_add.php?person_id=<?=$person_id?>" method="post" style="min-height: 750px;">
				<div class="form-row">

					<div class="form-group col-md-2">			
						<input class="form-control" type="text" name="cipher" placeholder="Шифр іменника" style="letter-spacing: 0.2em">
					</div>
				</div>
				<input type="submit" name="submit" value="Додати іменник" class="btn btn-warning">
			</form>
		</div>
	</div>
</div>
<?php include("footer.php"); ?>
