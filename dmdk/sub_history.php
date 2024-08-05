<?php include_once("header.php"); ?>
<?php
$link = probirka_db_connect();
$id = $_GET['id'];
$sql = "SELECT * FROM person WHERE person_id = (SELECT person_id FROM person WHERE id = $id) order by id desc";
$full_history_result = mysqli_query($link, $sql);
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div style="margin: 2%; border: 3px solid grey; background-color: white; min-height: 500px; border-radius: 5px; padding: 10px;">
				<?php 
				foreach ($full_history_result as $value): ?>
					<h5><?=$value['name'];?></h5>
					<h5><?=show_opf_name($value['opf_id'])?></h5>
					<h6>Код платника податків: <?=$value['tax_code'];?></h6>
					<h6>Дата реєстрації: <?=date("d.m.Y", strtotime($value['regdate']))?></h6>
					<h6>Свідоцтво про реєстрацію: <?=$value['reg'];?></h6>
					<h6>Дата та час зміни: <?=date("d.m.Y H:i:s", strtotime($value['date_start']))?></h6>
					<hr>
				<?php endforeach; ?>
				<a href="info_menu.php?id=<?=$id?>" class="btn btn-warning">Повернутися</a>
			</div>

		</div>
	</div>
</div>
<div style="margin-top: 5%"></div>
<?php include_once("footer.php"); ?>
