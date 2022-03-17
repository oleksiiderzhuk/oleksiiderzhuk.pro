<?php
include('header.php'); 
$link = probirka_db_connect();
$selected_year = $_POST['year'];
if ($selected_year == '2020'){
	$year_cip = '0:';
}
else {
	$year_cip = substr(trim($selected_year), -1);
}
?>
<div style="text-align: center;">
	<div class="container" >
		<br><h3>Зареєстровані іменники</h3>
	</div>
	<table width=80% align="center"><tr><td align=center>
	<div class="form-group col-md-2" style="text-align: center;">			
		<label for="cipher">Рік</label>
		<form method="post" action="imens_selected.php">
		<select class="form-control" name="year" style="letter-spacing: 0em" selected="<?=$selected_year?>">
			<option></option>
			<option <?php if ($selected_year=='2018'){echo 'selected';} ?>>2018</option>
			<option <?php if ($selected_year=='2019'){echo 'selected';} ?>>2019</option>
			<option  <?php if ($selected_year=='2020'){echo 'selected';} ?>>2020</option>
			<option <?php if ($selected_year=='2017'){echo 'selected';} ?>>2017</option>
		</select>

		<input type="hidden" name="person_id" value="$person_id">
		<input type="submit" name="submit" value="Вибрати" class="btn btn-warning">
		</form>
		</td></tr></table>
</div>
						
</div>
<?php
$sql = "SELECT * FROM imen where cipher like \"%$year_cip\" and is_valid = '1' order by reg_date desc";
//echo $sql;
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_assoc($result)){

	$cip = $row['cipher'];
	$person_id = $row['person_id'];
	$sql = "SELECT * FROM person WHERE person_id = $person_id";
	$person_result = mysqli_query($link, $sql);
	$person = mysqli_fetch_array($person_result);
	$reg_date = date ("d.m.Y", strtotime($row['reg_date']));
	$id = $person['id'];
	echo "<table width=80% border=0 align=center cellpadding=20px>
			<tr>
				<td align=left width=50%>
					<div style='line-height: 2em; text-align: center'>
					<a href=\"/info_menu.php?id=$id\"
					style='font-weight: bold; color: red'>". 
				$cip 
					. "</a></div>
				</td><td align=left>" . 
				$reg_date 
				. "</td>
			</tr>
		</table>";
} 

?>


<?php include("footer.php"); ?>
