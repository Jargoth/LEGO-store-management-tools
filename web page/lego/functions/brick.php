<?php
function brick()
{
	include "../www/db.php";
	$SQL = "SELECT d.description, d.part_number, dcu.color, dcu.free, dcu.used, (dcu.free + dcu.used) AS total FROM lego.design d, lego.design_color_user dcu WHERE dcu.design = d.id AND d.id = ".$_GET['design']." AND \"user\" = ".$_COOKIE['user']." ORDER BY (dcu.used + dcu.free) DESC LIMIT 1";
	$brick = pg_fetch_all(pg_query($db, $SQL));
	$SQL = "SELECT sum(dcu.free) AS free, sum(dcu.used) AS used, sum((dcu.free + dcu.used)) AS total FROM lego.design d, lego.design_color_user dcu WHERE dcu.design = d.id AND d.id = ".$_GET['design']." AND \"user\" = ".$_COOKIE['user']." GROUP BY d.id";
	$lego = pg_fetch_all(pg_query($db, $SQL));
	$SQL = "SELECT sum(pb.collected) FROM lego.project_bricks pb, lego.project p WHERE p.\"order\" = pb.project AND pb.design = ".$_GET['design']." AND p.\"user\" = ".$_COOKIE['user']." GROUP BY design";
	$results = pg_fetch_all(pg_query($db, $SQL));
	if ($results)
	{
		$free = $lego[0]['free'] - $results[0]['sum'];
		$used = $lego[0]['used'] + $results[0]['sum'];
	}
	else
	{
		$free = $lego[0]['free'];
		$used = $lego[0]['used'];
	}
	echo "<H3>".$brick[0]['description']."</H3>\n";
	echo "<IMG SRC = 'picture.php?size=400&amp;design=".$_GET['design']."&amp;color=".$brick[0]['color']."'><BR><BR>\n";
	echo "Part number: ".$brick[0]['part_number']."<BR>\n";
	echo "Free bricks: ".$free."<BR>\n";
	echo "Used bricks: ".$used."<BR>\n";
	echo "Total bricks: ".$lego[0]['total']."<BR><BR>\n";
	$SQL = "SELECT dcu.color, c.\"Name\", dcu.free, dcu.used, (dcu.free + dcu.used) AS total FROM lego.design_color_user dcu, lego.color c WHERE c.id = dcu.color AND dcu.design = ".$_GET['design']." AND \"user\" = ".$_COOKIE['user']." ORDER BY c.\"Name\"";
	$legos = pg_fetch_all(pg_query($db, $SQL));
	$SQL = "SELECT pb.color, sum(pb.collected) FROM lego.project_bricks pb, lego.project p WHERE p.\"order\" = pb.project AND pb.design = ".$_GET['design']." AND p.\"user\" = ".$_COOKIE['user']." GROUP BY color";
	$results = pg_fetch_all(pg_query($db, $SQL));
	if ($results)
	{
		foreach($results as $res)
		{
			$project[$res['color']] = $res['sum'];
		}
	}
	echo "<TABLE BORDER = '1'><TR>\n";
	$i = 0;
	foreach ($legos as $lego)
	{
		$i++;
		if ($i == 6)
		{
			$i = 1;
			echo "<TR>\n";
		}
		if ($project[$lego['color']])
		{
			$free = $lego['free'] - $project[$lego['color']];
			$used = $lego['used'] + $project[$lego['color']];
		}
		else
		{
			$free = $lego['free'];
			$used = $lego['used'];
		}
		echo "<TD><IMG SRC = 'picture.php?size=130&amp;design=".$_GET['design']."&amp;color=".$lego['color']."'><BR>\n";
		echo "Color: ".$lego['Name']."<BR>\n";
		echo "Free: ".$free."<BR>\n";
		echo "Used: ".$used."<BR>\n";
		echo "Total: ".$lego['total']."</TD>\n";
	}
	echo "</TR></TABLE>\n";
}
?>