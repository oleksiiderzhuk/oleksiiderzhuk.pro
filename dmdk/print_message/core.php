<?php
if($_POST['type']=='0')
{
	if(isset($_POST['letter']))
	{

		header("Location: add_letter.php?id=".$_POST['id']);
	}
	if(isset($_POST['message']))
	{
		header("Location: add_message.php?id=".$_POST['id']);
	}
	if(isset($_POST['envelope']))
	{
		header("Location: envelope.php?id=".$_POST['id']);
	}
}

if($_POST['type']==1)
{

	if(isset($_POST['letter']))
	{

		header("Location: edit_letter.php?id=".$_POST['id']);
	}
		if(isset($_POST['letter_operations']))
	{

		header("Location: edit_operations_letter.php?id=".$_POST['id']);
	}
	if(isset($_POST['message']))
	{
		header("Location: edit_message.php?id=".$_POST['id']);
	}
	if(isset($_POST['adr+']))
	{
		header("Location: plus_adress.php?id=".$_POST['id']);
	}
	if(isset($_POST['opera+']))
	{
		header("Location: plus_operations.php?id=".$_POST['id']);
	}
	if(isset($_POST['adr-']))
	{
		header("Location: minus_adress.php?id=".$_POST['id']);
	}
	if(isset($_POST['envelope']))
	{
		header("Location: envelope.php?id=".$_POST['id']);
	}

}

if($_POST['type']==2)
{

	if(isset($_POST['letter']))
	{
		header("Location: remove_letter.php?id=".$_POST['id']."&ino=".$_POST['income_number']."&indate=".$_POST['income_date']);
	}
	if(isset($_POST['message']))
	{
		header("Location: remove_message.php?id=".$_POST['id']."&ino=".$_POST['income_number']."&indate=".$_POST['income_date']);
	}
	if(isset($_POST['envelope']))
	{
		header("Location: envelope.php?id=".$_POST['id']);
	}
	if(isset($_POST['delete']))
	{
		header("Location: ../del_sub.php?id=".$_POST['id']."&income_number=".$_POST['income_number']."&income_date=".$_POST['income_date']);
	}

}
?>