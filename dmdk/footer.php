<table width=100% style="
	background-color: white; vertical-align: bottom; position: fixed; /* Фиксированное положение */
    left: 0; bottom: 0; /* Левый нижний угол */
    padding: 10px; /* Поля вокруг текста */
    background: white; /* Цвет фона */
    width: 100%; /* Ширина слоя */" height=50px>
<tr>
<td align=left width=15%>	<form align=center method="post" action="">
		<div>© Головфінтех, 2018</div>
</td><td align=center width=60%>
		<?php 
			if (!isset($_SESSION['user'])) {
		?>					
				<input type="text" name="user" placeholder="Прізвище" required autofocus>&nbsp;
				<input type="password" name="pass" placeholder="Пароль">&nbsp;
				<input type="submit" name="submit" value="Увійти" />
					
		<?php 
			}
			else {
		?>
				<input style="display: none" type="text" name="user" placeholder="Прізвище" required autofocus>&nbsp;
				<input style="display: none" type="password" name="pass" placeholder="Пароль">&nbsp;
				<input style="display: none" type="submit" name="submit" value="Увійти" />
		<?php 
			 } 
		?>
</td><td align=right>
		<div style="margin-right: 10%;"></div>
		</form>
</td>
</tr>
</table>
	
</footer>
<script src="js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>