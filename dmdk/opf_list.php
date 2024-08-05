<?php 
session_start();
error_reporting(0); 
include("header.php");
$sql = "SELECT * FROM opf";
$opf_result = mysqli_query($link, $sql);
?>
<div class="container" style="background-color:#ffffffb3; min-height: 70vh;">
	<div class="row" style="margin-top: 10px; padding:20px;" align="center">
		<h4 style="width:100%">Організаційно-правова форма<button type="button" class="btn btn-info" data-toggle="modal" data-target="#addOpf" style="float:right; margin-right:19px;">Додати нову ОПФ</button></h4>
		<?php foreach($opf_result as $single_opf): ?>
		<form style="width:100%" action="system/system_edit_opf.php" method="post">
			<input type="hidden" name="opf_id" value="<?=$single_opf['opf_id']?>">
			<input style="width:60%; margin-top:7px;" name="opf_name" value="<?=$single_opf['opf_name']?>" class="form-control">
			<input style="width:60%; margin-top:7px;" name="alias" value="<?=$single_opf['alias']?>"  class="form-control"><br>
			<input type="submit" value="Змінити" class="btn btn-info" style="width:10%; margin-bottom:40px;">
		</form>
		<?php endforeach; ?>
	</div>
</div>
<div class="modal fade" id="addOpf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Додати нову ОПФ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  <form action="system/system_add_opf.php" method="post">
		  <div class="modal-body">
			<input type="text" name="opf_name" class="form-control" placeholder="Назва"><br>
			<input type="text" name="alias" class="form-control" placeholder="Скорочено">
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
			<input type="submit" class="btn btn-info" value="Додати">
		  </div>
	  </form>
    </div>
  </div>
</div>
<div style="margin-top:90px"></div>
<?php include("footer.php"); ?>