<?php
function color_ldraw()
{
	include "../www/db.php";
	
	if ($_POST['submit'] == 'add' and isset($_POST['ldraw']) and isset($_POST['color']))
	{
		$SQL = "INSERT INTO lego.color_ldraw (color, ldraw) VALUES (".$_POST['color'].", '".$_POST['ldraw']."')";
		pg_query($db, $SQL);
	}
	
	$SQL = "SELECT mb.val2 FROM lego.model_bricks mb WHERE mb.val2 NOT IN (SELECT ldraw FROM lego.color_ldraw) GROUP BY mb.val2 ORDER BY mb.val2";
	$ans = pg_query($db, $SQL);
	$lego = pg_fetch_all($ans);
	if ($lego)
	{
		echo "<FORM ACTION = 'index.php?what=color_ldraw' METHOD = 'POST' enctype=\"multipart/form-data\">\n";
		echo "<P>LDraw: ".$lego[0][val2]."</P>";
		echo "<input type='hidden' name='ldraw' value='".$lego[0]['val2']."'>\n";
		$SQL = "SELECT id, \"Name\" FROM lego.color ORDER BY \"Name\"";
		$colors = pg_fetch_all(pg_query($db, $SQL));
		echo "Color: <SELECT NAME = 'color' ID = 'color' SIZE = '1'>\n";
		foreach ($colors as $color)
		{
			echo "<OPTION VALUE = '".$color['id']."'>".$color['Name']."\n";
		}
		echo "</SELECT><BR>\n";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'add'>\n";
		echo "</FORM>\n";
	}
	else
	{
		echo "<P>All done!</P>";
	}
}
?>