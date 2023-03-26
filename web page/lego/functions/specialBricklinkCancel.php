<?php
function specialBricklinkCancel()
{
	include "../www/db.php";
	if ($_COOKIE['user'] == 1)
	{
		if ($_POST['number'])
		{
			$SQL = "SELECT sell FROM lego.bricklink WHERE design = ".$_GET['design']." AND color = ".$_GET['color']." AND \"user\" = ".$_COOKIE['user']." AND container = '".$_GET['container']."'";
			$res = pg_fetch_all(pg_query($db, $SQL));
			if ($_POST['number'] < $res[0]['sell'])
			{
				$SQL = "UPDATE lego.bricklink SET sell = sell - ".$_POST['number']." WHERE design = ".$_GET['design']." AND color = ".$_GET['color']." AND \"user\" = ".$_COOKIE['user']." AND container = '".$_GET['container']."'";
				pg_query($db, $SQL);
			}
			elseif ($_POST['number'] == $res[0]['sell'])
			{
				$SQL = "DELETE FROM lego.bricklink WHERE design = ".$_GET['design']." AND color = ".$_GET['color']." AND \"user\" = ".$_COOKIE['user']." AND container = '".$_GET['container']."'";
				pg_query($db, $SQL);
			}
			else
			{
				echo "<P>Please enter a value between 1 and ".$res[0]['sell'].", which you are selling.</P>";
			}
		}
		$SQL = "SELECT bl.design, bl.color, (dcu.bricks-bl.sell) as free, bl.sell, d.description, c.\"Name\", dcu.container FROM lego.bricklink bl LEFT JOIN lego.design_color_user_container dcu ON dcu.design = bl.design AND dcu.color = bl.color AND dcu.\"user\" = bl.\"user\" AND dcu.container = bl.container , lego.design d, lego.color c WHERE bl.design = d.id AND bl.color = c.id AND bl.\"user\" = ".$_COOKIE['user']." ORDER BY d.description, c.\"Name\", dcu.container";
		$results = pg_fetch_all(pg_query($db, $SQL));
		$number = 0;
		foreach ($results as $result)
		{
			if (!is_numeric($result['free']))
			{
				$result['free'] = 0 - $result['sell'];
			}
			$SQL = "SELECT sum(pb.collected) FROM lego.project_bricks_container pb, lego.project p WHERE p.\"order\" = pb.project AND pb.design = ".$result['design']." AND pb.color = ".$result['color']." AND p.\"user\" = ".$_COOKIE['user']." AND pb.container = '".$result['container']."' GROUP BY pb.design, pb.color, p.\"user\"";
			$res = pg_fetch_all(pg_query($db, $SQL));
			if ($res)
			{
				$result['free'] = $result['free'] - $res[0]['sum'];
			}
			if ($result['free'] < $_COOKIE['bricklinkmin'])
			{
				echo "<BR>";
				echo "<FORM ACTION = 'index.php?what=specialBricklinkCancel&amp;design=".$result['design']."&amp;color=".$result['color']."&amp;container=".$result['container']."' METHOD = 'POST'>";
				echo "<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$result['design']."&amp;color=".$result['color']."'>".$result['description']." (".$result['Name'].")(".$result['container'].")(Available: ".$result['free'].")";
				echo "<INPUT TYPE = 'text' NAME = 'number' ID = 'number'>";
				echo "<INPUT TYPE = 'submit' NAME = 'retake' ID = 'retake' VALUE = 'retake'>";
				echo "</FORM>";
			}
		}
	}
}
?>