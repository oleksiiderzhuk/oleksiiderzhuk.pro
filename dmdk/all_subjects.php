<html>
<head>
<meta charset="win-1251">
</head>
<body>
<a href="/public/dmdk">Назад</a>
<?php
$dir = 'reports';
$files_array = scandir($dir);
$count = 0;
echo "<h2>Сформовані звіти</h2>";
$files_reversed = array_reverse($files_array);
foreach ($files_reversed as $single_file) {
	$file = $dir."/$single_file";
	$tim = filemtime($file);
	$mtime = date("d.m.Y  H:i", $tim);
}
//echo 'Інформація станом на '.$mtime.'<br><br>';
echo "<table>";
foreach ($files_reversed as $single_file) {
	if ($single_file!=='.gitignore'){
		echo "<tr><td width=50%><div style='line-height: 1.5em'><a href='reports/$single_file' download>$single_file</a></div></td></tr>";
		}
}
echo "</table>";
//echo $time."<br>";


//include 'stat_askod_faq.php';


?>

