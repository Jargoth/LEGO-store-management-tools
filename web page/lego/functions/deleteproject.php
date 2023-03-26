<?php
function deleteproject()
{
	include "../www/db.php";
	if ($_POST['selection'] == 'used')
	{
		$SQL = "SELECT * FROM lego.project_bricks WHERE model = ".$_GET['project']." AND \"user\" = ".$_COOKIE['user'];
		$bricks = pg_fetch_all(pg_query($db, $SQL));
		if ($bricks)
		{
			foreach ($bricks as $brick)
			{
				$SQL = "UPDATE lego.design_color_user SET free = (free - ".$brick['value']."), used = (used + ".$brick['value'].") WHERE design = ".$brick['design']." AND color = ".$brick['color']." AND \"user\" = ".$_COOKIE['user'];
				pg_query($db, $SQL);
				$SQL = "DELETE FROM lego.project_bricks WHERE model = ".$_GET['project']." AND design = ".$brick['design']." AND color = ".$brick['color']." AND \"user\" = ".$_COOKIE['user'];
				pg_query($db, $SQL);
			}
			$SQL = "DELETE FROM lego.project WHERE model = ".$_GET['project']." AND \"user\" = ".$_COOKIE['user'];
			pg_query($db, $SQL);
			$SQL = "UPDATE lego.\"user\" SET regenerate_bricklink = TRUE WHERE id = ".$_COOKIE['user'];
			pg_query($db, $SQL);
		}
	}
	else
	{
		echo "What do you want to do with the collected bricks?<br><br>";
		echo "<FORM ACTION = 'index.php?what=deleteproject&amp;project=".$_GET['project']."' METHOD = 'POST'>\n";
		echo "<INPUT TYPE = 'radio' NAME = 'selection' ID = 'selection' VALUE = 'used'>Move to used<br>";
		echo "<br><INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'submit'>\n";
		echo "</FORM>";
	}
}
?>