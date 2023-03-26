<?php
function specialBricklink()
{
	include "../www/db.php";
	if ($_COOKIE['user'])
	{
		$SQL = "SELECT * FROM lego.bricklink_xml_generate WHERE \"user\" = ".$_COOKIE['user'];
		$res = pg_fetch_all(pg_query($db, $SQL));
		$SQL = "SELECT * FROM lego.bricklink_xml_update_generate WHERE \"user\" = ".$_COOKIE['user'];
		$res2 = pg_fetch_all(pg_query($db, $SQL));
		if ($res)
		{
			echo "XML generation in progress. Please come back later.";
		}
		elseif ($res2)
		{
			echo "XML generation in progress. Please come back later.";
		}
		else
		{
			$SQL = "SELECT \"read\" FROM lego.bricklink_xml WHERE \"user\" = ".$_COOKIE['user'];
			$new_xml = pg_fetch_all(pg_query($db, $SQL));
			$SQL = "SELECT \"read\" FROM lego.bricklink_xml_update WHERE \"user\" = ".$_COOKIE['user'];
			$update_xml = pg_fetch_all(pg_query($db, $SQL));
			echo "<A HREF = 'index.php?what=specialBricklinkSell'>Sell</A>";
			$SQL = "SELECT bl.design, bl.color, (dcu.bricks-bl.sell) as free, bl.container FROM lego.bricklink bl LEFT JOIN lego.design_color_user_container dcu ON dcu.design = bl.design AND dcu.color = bl.color AND dcu.\"user\" = bl.\"user\" AND dcu.container = bl.container WHERE bl.\"user\" = ".$_COOKIE['user'];
			$results = pg_fetch_all(pg_query($db, $SQL));
			$number = 0;
 			foreach ($results as $result)
			{
				$SQL = "SELECT sum(pb.collected) FROM lego.project_bricks_container pb, lego.project p WHERE p.\"order\" = pb.project AND pb.design = ".$result['design']." AND pb.color = ".$result['color']." AND p.\"user\" = ".$_COOKIE['user']." AND pb.container = '".$result['container']."' GROUP BY pb.design, pb.color, p.\"user\"";
				$res = pg_fetch_all(pg_query($db, $SQL));
				if ($res)
				{
					$result['free'] = $result['free'] - $res[0]['sum'];
				}
				if ($result['free'] < $_COOKIE['bricklinkmin'])
				{
					$number++;
				}
			}
 			if ($number)
			{
				echo "<BR><A HREF = 'index.php?what=specialBricklinkCancel'>Cancel Sell (".$number.")</A>";
			}
			echo "<BR><A HREF = 'index.php?what=specialBricklinkVariables'>Change Variables</A>";
			if ($new_xml[0]['read'] == 'f')
			{
				echo "<BR><BR><A HREF = 'functions/specialBricklinkXMLDownload.php'>DOWNLOAD NEW XML</A>";
			}
			elseif ($update_xml[0]['read'] == 'f')
			{
				echo "<BR><BR><A HREF = 'functions/specialBricklinkXMLupdateDownload.php'>DOWNLOAD NEW XML</A>";
			}
			else
			{
				echo "<BR><A HREF = 'functions/specialBricklinkXMLDownload.php'>Download last XML</A>";
				echo "<BR><A HREF = 'functions/specialBricklinkXMLupdateDownload.php'>Download last XML (update)</A>";
				echo "<BR><A HREF = 'index.php?what=specialBricklinkXML'>Generate XML (new items)</A>";
				echo "<BR><A HREF = 'index.php?what=specialBricklinkXMLupdate'>Generate XML (updated items)</A>";
			}
			echo "<BR><A HREF = 'index.php?what=specialBricklinkOrder'>Complete order</A>";
		}
	}
}
?>