<?php
function marked_for_deletion()
{
	include "../www/db.php";
	$SQL = "SELECT d.description, d.id ".
			"FROM lego.bricklink_design bd, lego.design d, lego.design_color_user dcu ".
			"WHERE bd.deletion IS TRUE ".
			"AND d.id = bd.design ".
			"AND dcu.design = d.id ".
			"GROUP BY d.description, d.id";
	$legos = pg_fetch_all(pg_query($db, $SQL));
	if ($legos)
	{
		foreach ($legos as $lego)
		{
			echo "<A HREF = 'index.php?what=marked_for_deletion&amp;design=".$lego['id']."'>".$lego['description']."</A><BR>\n";
		}
	}
}
?>