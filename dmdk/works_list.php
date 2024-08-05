<?php 
session_start();
error_reporting(0); 
include("header.php");
$sql = "SELECT * FROM works";
$works_result = mysqli_query($link, $sql);
?>
<div class="container" style="background-color:#ffffffb3; min-height: 70vh;">
	<div class="row" style="margin-top: 10px; padding:20px;">
		<h4 style="width:100%">Види діяльності: <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addWork" style="float:right; margin-right:19px;">Додати новий вид діяльності</button></h4>
		<?php foreach($works_result as $single_work): ?>
		<form style="width:100%" action="system/system_edit_works.php" method="post">
			<input type="hidden" name="workid" value="<?=$single_work['workid']?>">
			<textarea style="width:88%; margin-top:7px;" name="workname"><?=$single_work['workname']?></textarea>
			<input type="submit" value="Змінити" class="btn btn-info" style="width:10%; margin-bottom:40px;">
		</form>
		<?php endforeach; ?>
	</div>
</div>
<div class="modal fade" id="addWork" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Додати новий вид діяльності</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  <form action="system/system_add_work.php" method="post">
		  <div class="modal-body">
			<input type="text" name="workname" class="form-control">
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