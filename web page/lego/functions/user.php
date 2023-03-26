<?php
function user()
{
	include "../www/db.php";
	if ($_COOKIE['user'])
	{
		$SQL = "SELECT \"user\", fname, lname, \"value\", weight FROM lego.user WHERE id = ".$_COOKIE['user'];
		$ans = pg_fetch_all(pg_query($db, $SQL));
		$price = $ans[0]['value'];
		$weight = $ans[0]['weight'];
		echo "<H3>".$ans[0]['user']."</H3>\n";
		echo "Name: ".$ans[0]['fname']." ".$ans[0]['lname']."<BR>\n";
		$SQL = "SELECT color FROM lego.design_color_user WHERE \"user\" = ".$_COOKIE['user']." GROUP BY color";
		$ans = pg_num_rows(pg_query($db, $SQL));
		echo "Number of colors: ".$ans."<BR>\n";
		$SQL = "SELECT design FROM lego.design_color_user WHERE \"user\" = ".$_COOKIE['user']." GROUP BY design";
		$ans = pg_num_rows(pg_query($db, $SQL));
		echo "Number of designs: ".$ans."<BR>\n";
		$SQL = "SELECT color FROM lego.design_color_user WHERE \"user\" = ".$_COOKIE['user'];
		$ans = pg_num_rows(pg_query($db, $SQL));
		echo "Number of different bricks: ".$ans."<BR>\n";
		$SQL = "SELECT (SELECT sum(dcu.used) FROM lego.design_color_user dcu WHERE \"user\" = ".$_COOKIE['user'].") AS used, (SELECT sum(dcu.free) FROM lego.design_color_user dcu WHERE \"user\" = ".$_COOKIE['user'].") AS free, (SELECT sum(dcu.free) + sum(dcu.used) FROM lego.design_color_user dcu WHERE \"user\" = ".$_COOKIE['user'].") AS total";
		$lego = pg_fetch_all(pg_query($db, $SQL));
		$SQL = "SELECT sum(pb.collected) FROM lego.project_bricks pb, lego.project p WHERE p.\"order\" = pb.project AND p.\"user\" = ".$_COOKIE['user'];
		$res = pg_fetch_all(pg_query($db, $SQL));
		echo "Used pieces: ".($lego[0]['used']+$res[0]['sum'])."<BR>\n";
		echo "Free pieces: ".($lego[0]['free']-$res[0]['sum'])."<BR>\n";
		echo "Total: ".$lego[0]['total']."<BR>\n";
		echo "Value: ".$price." kr<BR>\n";
		echo "Weight: ".$weight."g<BR><BR>\n";
		$SQL = "SELECT id FROM lego.container WHERE id IN
				(SELECT id FROM lego.container WHERE \"full\" IS NULL AND id ILIKE 'G%' AND id <> 'G005' AND id <> 'G102' AND id <> 'G101' ORDER BY weight LIMIT 1)
				OR id IN (SELECT id FROM lego.container WHERE \"full\" IS NULL AND id ILIKE 'S%' ORDER BY weight LIMIT 1)
				OR id IN (SELECT id FROM lego.container WHERE \"full\" IS NULL AND id ILIKE 'I%' ORDER BY weight LIMIT 1)
				ORDER BY id";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		$s = "Least used containers: ";
		if ($ans)
		{
			$i = 0;
			foreach ($ans as $an)
			{
				if ($i == 0)
				{
					$s = $s.$an['id'];
				}
				else
				{
					$s = $s.', '.$an['id'];
				}
				$i++;
			}
			echo $s.'<BR>';
		}
		$SQL = "SELECT id FROM lego.container WHERE id IN
				(SELECT id FROM lego.container WHERE id ILIKE 'G%' ORDER BY weight DESC LIMIT 1)
				OR id IN (SELECT id FROM lego.container WHERE id ILIKE 'S%' ORDER BY weight DESC LIMIT 1)
				OR id IN (SELECT id FROM lego.container WHERE id ILIKE 'I%' ORDER BY weight DESC LIMIT 1)
				ORDER BY id";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		$s = "Most used containers: ";
		if ($ans)
		{
			$i = 0;
			foreach ($ans as $an)
			{
				if ($i == 0)
				{
					$s = $s.$an['id'];
				}
				else
				{
					$s = $s.', '.$an['id'];
				}
				$i++;
			}
			echo $s.'<BR>';
		}
		$SQL = "SELECT design, color, \"Name\", description FROM lego.design_color_user dcu, lego.design d, lego.color c WHERE dcu.design = d.id AND dcu.color = c.id AND \"user\" = ".$_COOKIE['user']." ORDER BY (free + used) DESC LIMIT 1";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		echo "Favorite brick: ".$ans[0]['description']." (".$ans[0]['Name'].") <IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$ans[0]['design']."&amp;color=".$ans[0]['color']."'><BR>\n";
		$SQL = "SELECT design, description, (SELECT dcu.color FROM lego.design_color_user dcu WHERE dcu.design = d.id AND \"user\" = ".$_COOKIE['user']." ORDER BY (dcu.used + dcu.free) DESC LIMIT 1) AS color FROM lego.design_color_user dcu, lego.design d WHERE dcu.design = d.id AND \"user\" = ".$_COOKIE['user']." GROUP BY design, description, d.id ORDER BY sum(free + used) DESC LIMIT 1";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		echo "Favorite design: ".$ans[0]['description']." <IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$ans[0]['design']."&amp;color=".$ans[0]['color']."'><BR>\n";
	}
}
?>