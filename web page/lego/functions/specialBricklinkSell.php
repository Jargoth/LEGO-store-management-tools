<?php
function specialBricklinkSell()
{
	include "../www/db.php";
	if ($_COOKIE['user'] == 1)
	{
		if ($_GET['design'] and $_GET['color'] and !$_POST['number'])
		{
			echo "Number to sell:";
			echo "<FORM ACTION = 'index.php?what=specialBricklinkSell&amp;design=".$_GET['design']."&amp;color=".$_GET['color']."' METHOD = 'POST'>";
			echo "<INPUT TYPE = 'text' NAME = 'number' ID = 'number'>";
			echo "<INPUT TYPE = 'submit' NAME = 'sell' ID = 'sell' VALUE = 'sell'>";
			echo "</FORM>";
		}
		if ($_GET['design'] and $_GET['color'] and $_POST['number'])
		{
			$SQL = "SELECT sell FROM lego.bricklink WHERE design = ".$_GET['design']." AND color = ".$_GET['color']." AND \"user\" = ".$_COOKIE['user'];
			$res = pg_fetch_all(pg_query($db, $SQL));
			if ($res)
			{
				$SQL = "UPDATE lego.bricklink SET sell = ".($res[0]['sell'] + $_POST['number'])." WHERE design = ".$_GET['design']." AND color = ".$_GET['color']." AND \"user\" = ".$_COOKIE['user'];
				pg_query($db, $SQL);
			}
			else
			{
				$SQL = "INSERT INTO lego.bricklink (design, color, \"user\", sell) VALUES (".$_GET['design'].", ".$_GET['color'].", ".$_COOKIE['user'].", ".$_POST['number'].")";
				pg_query($db, $SQL);
			}
		}
		$SQL = "SELECT d.id AS design, d.description, c.id AS color, c.\"Name\", dcu.free, bc.bricklink AS bricklink_color, bd.bricklink AS bricklink_design FROM lego.design d, lego.color c, lego.design_color_user dcu LEFT OUTER JOIN lego.bricklink_color bc ON bc.color = dcu.color LEFT OUTER JOIN lego.bricklink_design bd On bd.design = dcu.design WHERE c.id = dcu.color AND d.id = dcu.design AND dcu.\"user\" = ".$_COOKIE['user']." AND free > ".$_COOKIE['bricklinkmax']." ORDER BY dcu.date_added";
		$results = pg_fetch_all(pg_query($db, $SQL));
		if ($results)
		{
			foreach ($results as $result)
			{
				$SQL = "SELECT sum(pb.collected) FROM lego.project_bricks pb, lego.project p WHERE p.\"order\" = pb.project AND pb.design = ".$result['design']." AND pb.color = ".$result['color']." AND p.\"user\" = ".$_COOKIE['user']." GROUP BY pb.design, pb.color";
				$res = pg_fetch_all(pg_query($db, $SQL));
				if ($res)
				{
					$result['free'] = $result['free'] - $res[0]['sum'];
				}
				$SQL = "SELECT sell FROM lego.bricklink WHERE design = ".$result['design']." AND color = ".$result['color']." AND \"user\" = ".$_COOKIE['user'];
				$res = pg_fetch_all(pg_query($db, $SQL));
				if ($res)
				{
					$result['free'] = $result['free'] - $res[0]['sell'];
				}
				if ($result['free'] > $_COOKIE['bricklinkmax'])
				{
					$items[$result['free'].",".$result['design'].",".$result['color']] = $result;
				}
			}
			//krsort($items);
			echo "<TABLE BORDER = 1>";
			foreach ($items as $item)
			{
				echo "<TR>";
				echo "<TD><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$item['design']."&amp;color=".$item['color']."'><A HREF = 'index.php?what=specialBricklinkSell&amp;design=".$item['design']."&amp;color=".$item['color']."'>".$item['description']." (".$item['Name'].")</A>(".$item['bricklink_design'].":".$item['bricklink_color'].")</TD>";
				echo "<TD>".$item['free']."</TD>";
				echo "</TR>";
			}
			echo "</TABLE>";
		}
		else
		{
			echo "Nothing to sell";
		}
	}
}
?>