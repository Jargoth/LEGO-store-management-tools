<?php
function myprojects()
{
	include "../www/db.php";
	$SQL = "SELECT \"order\", \"name\" FROM lego.project WHERE \"user\" = ".$_COOKIE['user']." ORDER BY \"order\"";
	$projects = pg_fetch_all(pg_query($db, $SQL));
	if ($projects)
	{
		foreach ($projects as $project)
		{
			echo "<A HREF = 'index.php?what=project&amp;id=".$project['order']."'>".$project['name']."</A><BR>\n";
		}
	}
}
?>