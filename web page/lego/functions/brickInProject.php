<?php
function brickInProject($design, $color)
{
	include "../www/db.php";
	$SQL = "SELECT p.\"order\", p.\"name\", pb.collected, pb.needed FROM lego.project p, lego.project_bricks pb WHERE p.\"order\" = pb.project AND pb.design = ".$design." AND (pb.color = ".$color." OR pb.color = 0) AND p.\"user\" = ".$_COOKIE['user']." ORDER BY \"order\"";
	$projects = pg_fetch_all(pg_query($db, $SQL));
	if ($projects)
	{
		echo "<br><br>This brick is needed in the following projects:";
		$msg = 1;
		foreach($projects as $project)
		{
			if ($project['collected'] < $project['needed'])
			{
				echo "<br><A TARGET = '_blank' HREF = 'index.php?what=project&amp;id=".$project['order']."'>".$project['name']."</A>\n";
			}
		}
	}
}
?>