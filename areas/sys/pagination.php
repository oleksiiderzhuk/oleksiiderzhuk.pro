<?php
$path = __FILE__;
echo '<table border="0" bordercolor="black" width="80%"><tr><td width="20%" align="center">';
echo '<a href="../"><b><< Home</b></a></td><td align="center">';
for ($i=2; $i<=$height_floors; $i++) {

	$href = $i.'.php' ;


	echo "<a style=\"margin-left: 25px\" href=\"$href\" >";
	if ($i==$_SESSION['floor']) { 
		echo " <b style=\"font-size: 24pt\">$i</b>";
	}
	else {
		echo $i;
	}
	echo '</a>&nbsp;';	
}
echo '</td></tr></table>';

/*if ( $_SESSION['print_view'] == '1' ) {
	echo "</td><td>" . $link ;
}
*/

?>	