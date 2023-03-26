<?php
function needed()
{
	include "../www/db.php";
	$SQL = "SELECT pb.design, pb.color, sum(pb.collected) as collected, sum(pb.needed) as needed, d.description, c.\"Name\" FROM lego.project p, lego.project_bricks pb, lego.design d, lego.color c WHERE pb.design = d.id AND pb.color = c.id AND p.order = pb.project AND p.\"user\" = ".$_COOKIE['user']." GROUP BY pb.design, pb.color, d.description, c.\"Name\" ORDER BY d.description, c.\"Name\"";
	$bricks = pg_fetch_all(pg_query($db, $SQL));
	echo "<TABLE BORDER = 1>";
	foreach ($bricks as $brick)
	{
		$missing = $brick['needed'] - $brick['collected'];
		if ($missing > 0)
		{
			$SQL = "SELECT free FROM lego.design_color_user WHERE design = ".$brick['design']." AND color = ".$brick['color']." AND \"user\" = ".$_COOKIE['user'];
			$res = pg_fetch_all(pg_query($db, $SQL));
/*			if ($res)
			{
				$missing = $missing - $res[0]['free'];
			}
*/
			if ($missing > 0)
			{
				$count = $count + $missing;
				echo "<TR><TD>".$brick['description']." (".$brick['Name'].")<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$brick['design']."&amp;color=".$brick['color']."'></TD><TD>".$missing."</TD</TR>\n";
			}
		}
	}
	echo "</TABLE>";
	echo "Total number of parts needed: ".$count."\n";
	echo "<A HREF = 'generate_missing_XML.php' TARGET = '_blank'>GENERATE XML</A>";
}
?>