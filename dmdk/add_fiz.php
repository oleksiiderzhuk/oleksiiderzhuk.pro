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
		<div class="col-md-12" style="height: 1075px;">
			<?php
				if($_GET['error']==1)
				{
					echo "<h5 style='color: #db3830;'>Суб'єкт з таким єдрпоу вже існує!</h5>";
				}
			?>
			<h3>Внесення фізичної особи-підприємця до реєстру суб'єктів господарювання</h3>
			<form style="margin-top: 40px;" method="post" action="add_sub.php">
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-3">
						<label for="income_date">Надані матеріали</label>
						<input type="date" class="form-control" id="income_date" name="income_date">
					</div>
					<div class="form-group col-md-3">
						<label for="income_number">Вх. №</label>
						<input type="text" class="form-control" id="income_number" placeholder="XX-XXXX" name="income_number" style="letter-spacing: 0.2em;">
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
						<input type="number" class="form-control" id="regno" name="regno" style="letter-spacing: 0.2em;" value="<?=$last_regno?>" required >
					</div>
					<div class="form-group col-md-8">
						<label for="name">Прізвище ім'я по-батькові</label>
						<input type="text" class="form-control" id="name" placeholder="Прізвище ім'я по-батькові" name="name" required>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="tax_code">Ідентифікаційний номер</label>
						<input type="text" class="form-control" id="tax_code" placeholder="Ідентифікаційний номер" name="tax_code" style="letter-spacing: 0.2em;" required >
					</div>
					<div class="form-group col-md-8">
						<label for="passport_info">Серія, номер паспорта або УНЗР, ким коли виданий</label>
						<input type="text" class="form-control" id="passport_info" placeholder="Серія, номер паспорта або УНЗР" name="passport_info" >
					</div>
				</div>
				<h5 style="margin-top: 20px;">Адреса проживання</h5>
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
							<input type="text" class="form-control" id="phone_number" name="phone_number" style="letter-spacing: 0.2em;" >
						</div>
					</div>
					<div class="form-group col-md-3">
						<label for="email">E-mail</label>
						<input type="text" class="form-control" id="email" placeholder="E-mail" name="email" >
					</div>
				</div>
				<h5 style="margin-top: 20px;">Інформація про державну реєстрацію</h5>
				<div class="form-row" style="margin-top: 20px;">
					<div class="form-group col-md-3">
						<label for="regdate">Дата реєстрації</label>
						<input type="date" class="form-control" id="regdate"  name="regdate" >
					</div>
					<div class="form-group col-md-8">
						<label for="reg">Номер запису про проведення державної реєстрації фізичної особи-підприємця</label>
	<br><input type="text" onkeyup="testJump(this);" maxlength="1" size="1" style="text-align: center; letter-spacing: 0.3em" name="reg1">
    <input type="text" onkeyup="testJump(this);" maxlength="3" size="3" style="text-align: center; letter-spacing: 0.3em" name="reg2">
    <input type="text" onkeyup="testJump(this);" maxlength="3" size="3" style="text-align: center; letter-spacing: 0.3em" name="reg3">
    <input type="text" onkeyup="testJump(this);" maxlength="4" size="6" style="text-align: center; letter-spacing: 0.3em" name="reg4">
	<input type="text" onkeyup="testJump(this);" maxlength="6" size="10" style="text-align: center; letter-spacing: 0.3em" name="reg5">
					</div>
					
				</div>
				<input type="submit" class="btn btn-warning" value="Внести фізичну особу до реєстру">
			</form>
		</div>
	</div>
</div>

<?php include('footer.php'); ?>