<?php
include "../www/db.php";
$SQL = "SELECT bd.bricklink AS design, bc.bricklink AS color, (sum(pb.needed) - sum(pb.collected)) AS missing ".
		"FROM lego.project_bricks pb, lego.project p, lego.bricklink_design bd, lego.bricklink_color bc ".
		"WHERE p.\"order\" = pb.project ".
		"AND pb.design = bd.design ".
		"AND pb.color = bc.color ".
		"AND p.\"user\" = ".$_COOKIE['user']." ".
		"GROUP BY bd.bricklink, bc.bricklink";
$bricks = pg_fetch_all(pg_query($db, $SQL));
if ($bricks)
{
	echo "<INVENTORY>\n";
	foreach ($bricks as $brick)
	{
		if ($brick['missing'] > 0)
		{
			echo "<ITEM>\n";
			echo "<ITEMTYPE>P</ITEMTYPE>\n";
			echo "<ITEMID>".$brick['design']."</ITEMID>\n";
			if ($brick['color'] != 0)
			{
				echo "<COLOR>".$brick['color']."</COLOR>\n";
			}
			echo "<MINQTY>".$brick['missing']."</MINQTY>\n";
			echo "<NOTIFY>N</NOTIFY>\n";
			echo "</ITEM>\n";
		}
	}
	echo "</INVENTORY>";
}
?>