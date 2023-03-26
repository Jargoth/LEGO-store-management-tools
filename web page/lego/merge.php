<?php
function merge()
{
	include "../www/db.php";
	$SQL = "SELECT iu.design, iu.type, iu.color, d.id AS did, d.description, c.\"Name\", c.id AS cid, last6_used_usd ".
			"FROM lego.inventory_user iu, lego.bricklink_price bp, lego.design d, lego.bricklink_design bd, ".
			"lego.color c, lego.bricklink_color bc ".
			"WHERE iu.\"user\" = ".$_COOKIE['user']." ".
			"AND iu.design = bp.design ".
			"AND iu.type = bp.design_type ".
			"AND iu.color = bp.color ".
			"AND iu.design = bd.bricklink ".
			"AND iu.type = bd.type ".
			"AND d.id = bd.design ".
			"AND iu.color = bc.bricklink ".
			"AND c.id = bc.color ".
			"GROUP BY iu.design, iu.type, iu.color, d.id, d.description, last6_used_usd, c.\"Name\", c.id ".
			"ORDER BY last6_used_usd DESC";
	$parents = pg_fetch_all(pg_query($db, $SQL));
	if ($parents)
	{
		foreach ($parents as $parent)
		{
			$errorinv = 0;
			if ($lastparent['design'] != $parent['design'] or $lastparent['type'] != $parent['type'] or $lastparent['color'] != $parent['color'])
			{
				$SQL = "SELECT * FROM lego.inventory ".
						"WHERE parent_design = '".$parent['design']."' ".
						"AND parent_type = '".$parent['type']."' ".
						"AND parent_color = ".$parent['color'];
				$childs = pg_fetch_all(pg_query($db, $SQL));
				echo $SQL."<br>";
				foreach ($childs as $child)
				{
					$SQL = "SELECT dcu.free ".
							"FROM lego.design_color_user dcu, lego.bricklink_design bd, lego.bricklink_color bc ".
							"WHERE bd.design = dcu.design ".
							"AND bc.color = dcu.color ".
							"AND bd.bricklink = '".$child['child_design']."' ".
							"AND bd.type = '".$child['child_type']."' ".
							"AND bc.bricklink = ".$child['child_color']." ".
							"AND dcu.\"user\" = ".$_COOKIE['user']." ".
							"AND dcu.free >= ".$child['count'];
					echo $SQL."<br>";
					$res = pg_fetch_all(pg_query($db, $SQL));
					if (!$res)
					{
						$errorinv = 1;
					}
				}
				if (!$errorinv)
				{
					echo "<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$parent['did']."&amp;color=".$parent['cid']."'>".$parent['Name']." ".$parent['description']." ($".$parent['last6_used_usd'].")<br>";
				}
				else
				{
					$SQL = "DELETE FROM lego.inventory_user ".
							"WHERE design = '".$parent['design']."' ".
							"AND color = ".$parent['color']." ".
							"AND \"type\" = '".$parent['type']."' ".	
							"AND \"user\" = ".$_COOKIE['user'];
							pg_query($db, $SQL);
				}
			}
			$lastparent = $parent;
		}
	}
	else
	{
		echo "Nothing to merge.";
	}
}
?>