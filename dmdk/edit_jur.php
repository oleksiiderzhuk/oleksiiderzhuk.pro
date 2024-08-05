<?php
session_start();
error_reporting(0);
include('header.php'); echo '<br>';
$_SESSION['change']='new';
$id=$_GET['id'];
$sql = "SELECT * FROM person WHERE id = $id";
$person_result = mysqli_query($link, $sql);
$person = mysqli_fetch_array($person_result);
$person_id = $person['person_id'];
$regno = $person['regno'];
$name = $person['name'];
$name=htmlspecialchars($name,ENT_QUOTES);
$name=str_replace (' ','&nbsp;',$name);
$edrpou = $person['tax_code'];
$person_opf_id = $person['opf_id'];
$opf=show_work_name($opf_id);
$reg_info = $person['reg'];

$reg_info_1 = substr($reg_info, 0, 1);
$reg_info_2 =  substr($reg_info, 1, 3);
$reg_info_3 =  substr($reg_info, 4, 3);
$reg_info_4 =  substr($reg_info, 7, 4);
$reg_info_5 =  substr($reg_info, 11, 6);

$reg_date = $person['regdate'];
$reg_date=date("Y-m-d", strtotime($reg_date));

$sql = "SELECT * FROM adress WHERE person_id = $person_id and is_main='1' AND current = 1";
$main_adress_result = mysqli_query($link, $sql);
$main_adress = mysqli_fetch_array($main_adress_result);
$income_date=$main_adress['income_date'];
$income_date=date("Y-m-d", strtotime($income_date));
$income_number=$main_adress['income_number'];

$sql = "SELECT * FROM contact WHERE person_id = $person_id AND current = 1";
$contact_result = mysqli_query($link, $sql);
$contact = mysqli_fetch_array($contact_result);
$tel=$contact['phone_number'];
$email=$contact['email'];

$sql = "SELECT MAX(ID) FROM activity_finish WHERE person_id = $person_id";
$max_result = mysqli_query($link, $sql);
$max_array = mysqli_fetch_array($max_result);
$maxid=$max_array['MAX(ID)'];

$sql = "SELECT * FROM activity_finish WHERE id=$maxid";
$activity_finish_result = mysqli_query($link, $sql);
$activity_finish_array = mysqli_fetch_array($activity_finish_result);
$date_stopped=$activity_finish_array['date_stopped'];
$date_stopped=date("Y-m-d", strtotime($date_stopped));
$reg_stopped=$activity_finish_array['regno_stopped'];
$reg_stopped1 = substr($reg_stopped, 0, 1);
$reg_stopped2 =  substr($reg_stopped, 1, 3);
$reg_stopped3 =  substr($reg_stopped, 4, 3);
$reg_stopped4 =  substr($reg_stopped, 7, 4);
$reg_stopped5 =  substr($reg_stopped, 11, 6);
$date_stopping=$activity_finish_array['date_stopping'];
$date_stopping=date("Y-m-d", strtotime($date_stopping));
?>
<script>
function testJump(x){
    var ml = ~~x.getAttribute('maxlength');
    if(ml && x.value.length >= ml){
        do{
            x = x.nextSibling;
        }
        while(x && !(/text/.test(x.type)));
        if(x && /text/.test(x.type)){
            x.focus();
        }
    }
}
</script>

<style>
input[type='number'] {
    -moz-appearance:textfield;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}
</style>
<div class="container">
	<div class="row" style="margin-top: 60px; ">
		<div class="col-md-12" style="height: 1300px;">
			<h3>Редагування юридичної особи в реєстрі суб'єктів господарювання</h3>
			<form style="margin-top: 40px;" method="post" action="edit_sub.php">
				<div class="form-row">
					<div class="form-group col-md-2">
						<label for="regno">Номер в реєстрі</label>
						<input type="number" class="form-control" id="regno" placeholder="№" name="regno" value="<?=$regno?>" required style="letter-spacing: 0.3em;">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
					<div class="form-group col-md-2" >
						<label for="opf_id">Орг-прав форма</label>
						<select name="opf_id" class="form-control">
							<?php
							$link = probirka_db_connect();
							$sql = "SELECT * FROM opf order by opf_name";
							
							$result = mysqli_query($link, $sql);

							while($temp = mysqli_fetch_assoc($result)){
								$opf_id=$temp['opf_id'];
								if ($temp['opf_id']!=='0') {
								
									if ($person_opf_id==$opf_id) {
									echo "<option selected>".$temp['opf_name']."</option>";
									}
									else {
										echo "<option >".$temp['opf_name']."</option>";
									}
								}
							}
							?>
						</select>
					</div>
					<div class="form-group col-md-2">
						<label for="tax_code">Код платника податків</label>
						<input type="number" class="form-control" id="tax_code" placeholder="Код" name="tax_code"  value="<?=$edrpou?>" required style="letter-spacing: 0.3em;">
					</div>
				</div>
				<div class="form-row" style="margin-top: 0px;">
				
					<div class="form-group col-md-12">
						<label for="name">Найменування суб'єкта господарювання</label><br>
						
						<input type="text" class="form-control" id="name" placeholder="Найменування суб'єкта господарювання" name="name" value=<?=$name?> required>
					</div>
					
				</div>	
				
				<h5 style="margin-top: 20px;">Контакти</h5>
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-4">
						<label for="phone_number">Телефон</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">+380</div>
							</div>
							<input type="text" class="form-control" id="phone_number" name="phone_number" value="<?=$tel?>" style="letter-spacing: 0.2em;">
						</div>
					</div>
					<div class="form-group col-md-3">
						<label for="email">E-mail</label>
						<input type="text" class="form-control" id="email" placeholder="E-mail" name="email" value="<?=$email?>">
					</div>
				</div>
				<h5 style="margin-top: 20px;">Інформація про державну реєстрацію</h5>
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-3">
						<label for="regdate">Дата реєстрації</label>
						<input type="date" class="form-control" id="regdate"  name="regdate" value="<?php if($reg_date!=='1970-01-01') echo $reg_date; ?>">
						<input type="text" name="type" value="jur"  style="display: none;">
					</div>
					<div class="form-group col-md-7">
						<label for="reg">Номер запису в Єдиному державному реєстрі про проведення державної реєстрації</label>
						<br><input type="text" onkeyup="testJump(this);" maxlength="1" size="1" style="text-align: center; letter-spacing: 0.3em" name="reg1" value="<?=$reg_info_1?>">
						<input type="text" onkeyup="testJump(this);" maxlength="3" size="3" style="text-align: center; letter-spacing: 0.3em" name="reg2" value="<?=$reg_info_2?>">
						<input type="text" onkeyup="testJump(this);" maxlength="3" size="3" style="text-align: center; letter-spacing: 0.3em" name="reg3" value="<?=$reg_info_3?>">
						<input type="text" onkeyup="testJump(this);" maxlength="4" size="6" style="text-align: center; letter-spacing: 0.3em" name="reg4" value="<?=$reg_info_4?>">
						<input type="text" onkeyup="testJump(this);" maxlength="6" size="10" style="text-align: center; letter-spacing: 0.3em" name="reg5" value="<?=$reg_info_5?>">
					</div>
				</div>
				
								<h5 style="margin-top: 20px;">Інформація про державну реєстрацію припинення</h5>
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-3">
						<label for="stopdate">Дата запису</label>
						<input type="date" class="form-control" name="datestopped"  value="<?php if($date_stopped!=='1970-01-01') echo $date_stopped; ?>">
					</div>
					<div class="form-group col-md-7">
						<label for="stop">Номер запису в Єдиному державному реєстрі про припинення</label>
						<br><input type="text" onkeyup="testJump(this);" maxlength="1" size="1" style="text-align: center; letter-spacing: 0.3em" name="regstopped1" value="<?=$reg_stopped1?>">
						<input type="text" onkeyup="testJump(this);" maxlength="3" size="3" style="text-align: center; letter-spacing: 0.3em" name="regstopped2" value="<?=$reg_stopped2?>">
						<input type="text" onkeyup="testJump(this);" maxlength="3" size="3" style="text-align: center; letter-spacing: 0.3em" name="regstopped3" value="<?=$reg_stopped3?>">
						<input type="text" onkeyup="testJump(this);" maxlength="4" size="6" style="text-align: center; letter-spacing: 0.3em" name="regstopped4" value="<?=$reg_stopped4?>">
						<input type="text" onkeyup="testJump(this);" maxlength="6" size="10" style="text-align: center; letter-spacing: 0.3em" name="regstopped5" value="<?=$reg_stopped5?>">
					</div>
				</div>
				
				<h5 style="margin-top: 20px;">Інформація про перебування у стані припинення</h5>
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-3">
						<label for="stop">Дата запису</label>
						<input type="date" class="form-control"  name="datestopping"  value="<?php if($date_stopping!=='1970-01-01') echo $date_stopping; ?>">
					</div>

				</div>
				<input type="submit" class="btn btn-warning" value="Зберегти">
			</form>
		</div>
	</div>
</div>

<?php include('footer.php'); ?>