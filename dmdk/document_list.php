<?php 
session_start();
error_reporting(1); 
include("header.php");
$sql = "SELECT * FROM documents WHERE person_id = ".$_GET['person_id']." AND del = 0";
$documents = mysqli_query($link, $sql);

$sql = "SELECT * FROM documents WHERE person_id = ".$_GET['person_id']." AND del = 1";
$del_documents = mysqli_query($link, $sql);
?>
<div class="container" style="background-color:#ffffffb3; min-height: 70vh;">

	<div class="row" style="margin-top: 10px; padding:20px;">
		<h4 style="width:100%">Список документів 
		<?php
		if ($_SESSION['can_edit'] == '1') {
			echo '
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#addWork" style="float:right; margin-right:19px;">Завантажити новий документ</button>';
		}
		?>
		
		</h4>
		
		<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delList" style="float:right; margin-right:19px;">Корзина</button>
	</div>

	<hr>

	<?php

	foreach ($documents as $document) {
		if($document['id']>=96){
			$document_name = str_replace("3_3".explode(".",explode("3_3", $document['name'])[1])[0], "", $document['name']);
		}
		else {
			$document_name = $document['name'];
		}
		echo "<div class='row'><div class='col-md-12'>";
		echo "<a href='uploads/documents/".$document['name']."' download>".$document_name."</a> ";
		if ($_SESSION['can_edit'] == '1') {
			echo "<a href='../uploads/documents/un.php?id=".$document['id']."&person_id=".$_GET['person_id']."&file_name=".$document['name']."' style='color:red; float:right;'>X</a><hr><br>";
		}
		echo "</div></div>";
	}

	?>
</div>
<div class="modal fade" id="addWork" tabindex="-1" role="dialog" aria-labelledby="addWorkLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addWorkLabel">Додати новий документ</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="system/system_add_document.php" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-row">
								<div class="form-group col-md-12">
									<label for="name">Назва документу</label>
									<input type="text" class="form-control" id="name" name="name">
									<input type="hidden" name="person_id" value="<?=$_GET['person_id']?>">
								</div>
							</div>
							<div class="form-row" style="margin-top: 20px;">
								<div class="form-group col-md-12">
									<label for="document">Документ</label>
									<input type="file" id="document" name="document">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
					<input type="submit" class="btn btn-info" value="Завантажити">
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="delList" tabindex="-1" role="dialog" aria-labelledby="delListLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="delListLabel">Відновити документ</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<?php

						foreach ($del_documents as $document) {
							if($document['id']>=96){
								$document_name = str_replace("3_3".explode(".",explode("3_3", $document['name'])[1])[0], "", $document['name']);
							}
							else {
								$document_name = $document['name'];
							}
							echo "<div class='row'><div class='col-md-12'>";
							echo "<a href='uploads/documents/".$document['name']."' download>".$document_name."</a> <a href='../uploads/documents/restore.php?id=".$document['id']."&person_id=".$_GET['person_id']."&file_name=".$document['name']."' style='color:green; float:right;'>";
							if ($_SESSION['can_edit'] == '1') {	
								echo "Відновити</a><hr><br>";
							}
							echo "</div></div>";
						}

						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="margin-top:90px"></div>
<?php include("footer.php"); ?>