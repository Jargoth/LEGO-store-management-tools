<?php
function brick_no_price()
{
	include "../www/db.php";
	if ($_GET['do'] == 'add')
	{
		$SQL = "SELECT d.description, c.\"Name\" FROM lego.design d, lego.color c, lego.design_color_user dcu ".
				"WHERE d.id = dcu.design AND c.id = dcu.color AND dcu.design = ".$_GET['design']." ".
				"AND dcu.color = ".$_GET['color'];
		$brick = pg_fetch_all(pg_query($db, $SQL));
		echo "<h2>".$brick[0]['description']." (".$brick[0]['Name'].")</h2><br>\n";
		echo "<FORM ACTION = 'index.php?what=brick_no_price&do=add2' METHOD = 'POST'>\n";
		echo "What should it cost in $: <INPUT TYPE = 'text' NAME = 'price' ID = 'price'><BR>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_GET['design']."'><BR>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'color' ID = 'color' VALUE = '".$_GET['color']."'><BR>\n";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'submit'>\n";
		echo "</FORM>\n";
	}
	elseif ($_GET['do'] == 'add2')
	{
		$SQL = "SELECT bd.bricklink AS design, bd.\"type\", bc.bricklink AS color FROM lego.design_color dc, lego.bricklink_design bd, ".
				"lego.bricklink_color bc WHERE dc.design = bd.design AND dc.color = bc.color ".
				"AND dc.design = ".$_POST['design']." AND dc.color = ".$_POST['color'];
		$brick = pg_fetch_all(pg_query($db, $SQL));
		$SQL = "INSERT INTO lego.bricklink_price (design, design_type, color, last6_used_usd) ".
				"VALUES ('".$brick[0]['design']."', '".$brick[0]['type']."', ".$brick[0]['color'].", '".
				$_POST['price']."')";
		pg_query($db, $SQL);
		echo 'SUCCESS!<br><br>';
	}
	$SQL = "SELECT dcu.design, d.description, dcu.color, c.\"Name\" FROM lego.design_color_user dcu, ".
			"lego.bricklink_design bd, lego.bricklink_color bc, ".
			"lego.design d, lego.color c ".
			"WHERE d.id = dcu.design ".
			"AND c.id = dcu.color ".
			"AND bd.design = dcu.design ".
			"AND bc.color = dcu.color ".
			"AND (bd.bricklink, bd.\"type\", bc.bricklink) NOT IN (SELECT design, design_type, color FROM lego.bricklink_price) ".
			"ORDER BY d.description, c.\"Name\"";
	$legos = pg_fetch_all(pg_query($db, $SQL));
	if ($legos)
	{
		foreach ($legos as $lego)
		{
			echo "<A HREF = 'index.php?what=brick_no_price&amp;do=add&amp;design=".$lego['design']."&amp;color=".$lego['color']."'>".$lego['description']." (".$lego['Name'].")</A><br>";
		}
	}
}