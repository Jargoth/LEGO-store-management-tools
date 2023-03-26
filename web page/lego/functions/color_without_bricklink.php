<?php
function color_without_bricklink()
{
	include "../www/db.php";
	if ($_COOKIE['user'] == 1)
	{
		if ($_POST['submit'] == 'next')
		{
			$SQL = "UPDATE lego.color SET bricklink = now() WHERE id = ".$_GET['color'];
			pg_query($db, $SQL);
		}
		if ($_POST['submit'] == 'exclude')
		{
			$SQL = "UPDATE lego.color SET exclude_from_bricklink = TRUE WHERE id = ".$_GET['color'];
			pg_query($db, $SQL);
		}
		if ($_POST['submit'] == 'submit')
		{
			$SQL = "INSERT INTO lego.bricklink_color (bricklink, color) VALUES ('".$_POST['bricklink']."', ".$_GET['color'].")";
			pg_query($db, $SQL);
		}
		$SQL = "SELECT c.\"Name\", c.id FROM lego.color c WHERE exclude_from_bricklink IS FALSE AND c.id NOT IN (SELECT color FROM lego.bricklink_color) AND date_part('month', age(bricklink)) >= 3 LIMIT 1";
		$res = pg_fetch_all(pg_query($db, $SQL));
		$description = $res[0]['Name'];
		echo $description."<BR>";
		echo "<FORM ACTION = 'index.php?what=color_without_bricklink&amp;color=".$res[0]['id']."' METHOD = 'POST'>";
		echo "<INPUT TYPE = 'text' NAME = 'bricklink' ID = 'bricklink'>";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'submit'>";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'next'>";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'exclude'>";
		echo "</FORM>";
	}
	
}
?>