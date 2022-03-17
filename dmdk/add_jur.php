<?php
session_start();
include('header.php'); 
$_SESSION['change']='new';
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
		<div class="col-md-12" style="height: 1800px;">
			<h3>Внесення юридичної особи до реєстру суб'єктів господарювання</h3>
			<form style="margin-top: 40px;" method="post" action="add_sub.php">
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-3">
						<label for="income_date">Надані матеріали</label>
						<input type="date" class="form-control" id="income_date" name="income_date">
					</div>
					<div class="form-group col-md-3">
						<label for="income_number">Вх. №</label>
						<input type="text" class="form-control" id="income_number" placeholder="XX-XXXX" name="income_number">
					</div>
				</div>
				<?php
				$sql = "SELECT * FROM person WHERE regno LIKE '____' ORDER BY date_start DESC LIMIT 1";
				$last_regno_query = mysqli_query($link,$sql);
				$last_regno_array = mysqli_fetch_array($last_regno_query);
				$last_regno = $last_regno_array['regno'];
				$last_regno = intval($last_regno);
				$last_regno++;
				//echo $last_regno;
				
				
				?>
				<div class="form-row">
					<div class="form-group col-md-2">
						<label for="regno">Номер у реєстрі</label>
						<input type="number" class="form-control" id="regno" value="<?=$last_regno?>" name="regno" style="letter-spacing: 0.2em;" required >
					</div>
					<div class="form-group col-md-4" >
						<label for="opf_id">Орг-прав форма</label>
						<select name="opf_id" class="form-control">
							<?php
							$link = probirka_db_connect();
							$sql = "SELECT * FROM opf order by opf_name";
							
							$result = mysqli_query($link, $sql);

							while($temp = mysqli_fetch_assoc($result)){
								if ($temp['opf_name']!=='Фізична особа - підприємець') {
									echo "<option >".$temp['opf_name']."</option>";
								}
							}
							?>
						</select>
					</div>
					
					<div class="form-group col-md-2">
						<label for="tax_code">Код платника податків</label>
						<input type="text" class="form-control" id="tax_code" placeholder="Код" name="tax_code" style="letter-spacing: 0.2em;" required>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="name">Найменування суб'єкта господарювання</label>
						<input type="text" class="form-control" id="name" placeholder="Найменування суб'єкта господарювання" name="name" required>
					</div>
				</div>					
				
				
				<h5 style="margin-top: 20px;">Юридична адреса</h5>
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-2">
						<label for="postindex">Поштовий індекс</label>
						<input type="number" class="form-control" id="postindex" placeholder="Індекс" name="postindex" style="letter-spacing: 0.3em;">
					</div>
					<div class="form-group col-md-3">
						<label for="region">Область</label>
						<select name="region" class="form-control">
							<?php
							$link = probirka_db_connect();
							$sql = "SELECT * FROM region";
							$result = mysqli_query($link, $sql);

							while($temp = mysqli_fetch_assoc($result))
							{
								if ($region==$temp['region_name'])
								{
									echo "<option selected>".$temp['region_name'].$t."</option>";
								}
								else
								{
									echo "<option".">".$temp['region_name'].$t."</option>";
								}
							}
							?>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="city">Місто/селище</label>
						<input type="text" class="form-control" id="city" placeholder="Місто/селище" name="city">
					</div>
					<div class="form-group col-md-4">
						<label for="area">Район</label>
						<input type="text" class="form-control" id="area" placeholder="Район" name="area">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-8">
						<label for="adress">Адреса</label>
						<input type="text" class="form-control" id="adress" placeholder="Адреса" name="adress">
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
							<input type="text" class="form-control" id="phone_number" name="phone_number" style="letter-spacing: 0.2em;">
						</div>
					</div>
					<div class="form-group col-md-3">
						<label for="email">E-mail</label>
						<input type="text" class="form-control" id="email" placeholder="E-mail" name="email">
					</div>
				</div>
				<h5 style="margin-top: 20px;">Інформація про державну реєстрацію</h5>
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-3">
						<label for="regdate">Дата реєстрації</label>
						<input type="date" class="form-control" id="regdate"  name="regdate">
						<input type="text" name="type" value="jur"  style="display: none;">
					</div>
					<div class="form-group col-md-7">
						<label for="reg">Номер запису в Єдиному державному реєстрі про проведення державної реєстрації</label>
	<br><input type="text" onkeyup="testJump(this);" maxlength="1" size="1" style="text-align: center; letter-spacing: 0.3em" name="reg1">
    <input type="text" onkeyup="testJump(this);" maxlength="3" size="3" style="text-align: center; letter-spacing: 0.3em" name="reg2">
    <input type="text" onkeyup="testJump(this);" maxlength="3" size="3" style="text-align: center; letter-spacing: 0.3em" name="reg3">
    <input type="text" onkeyup="testJump(this);" maxlength="4" size="6" style="text-align: center; letter-spacing: 0.3em" name="reg4">
	<input type="text" onkeyup="testJump(this);" maxlength="6" size="10" style="text-align: center; letter-spacing: 0.3em" name="reg5">
					</div>
					
				</div>
				<h5 style="margin-top: 20px;">Інформація про види робіт з дорогоцінними металами і дорогоцінним камінням які ведуться за адресою реєстрації юридичної особи</h5>
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-12">
						<label for="worktype">Види робіт: </label>
						<select name="worktype[]" multiple="multiple" style="height: 550px; background-color: #77F496" class="form-control"  onchange="
							if (confirm('Чи дійсно операції здійснюються за юридичною адресою?')){
								selIdx = this.selectedIndex;
								// Do whatever if user says OK
								// ...
							} else {
								// Don't do it and return selection to previous
								this.selectedIndex = selIdx;
							}
						">
							<?php
							$link = probirka_db_connect();
							$sql = "SELECT * FROM works";
							$result = mysqli_query($link, $sql);
							while($temp = mysqli_fetch_assoc($result))
							{
								$workname=$temp['workname'];
								echo "<option title=\"$workname\">".$workname."</option>";
							}
							?>
						</select>
					</div>
					
					
				</div>
				<input type="submit" class="btn btn-warning" value="Внести юридичну особу до реєстру">
			</form>
		</div>
	</div>
</div>
<br><br>
<?php include('footer.php'); ?>