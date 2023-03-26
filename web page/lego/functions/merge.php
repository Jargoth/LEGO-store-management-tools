<?php
function merge()
{
	include "../www/db.php";
	if ($_GET['do'] == 'spec')
	{
		$SQL = "SELECT d.description, c.\"Name\", d.id AS design, c.id AS color ".
				"FROM lego.bricklink_design bd, lego.bricklink_color bc, ".
				"lego.design d, lego.color c ".
				"WHERE bd.bricklink = '".$_GET['design']."' ".
				"AND bd.\"type\" = '".$_GET['type']."' ".
				"AND bc.bricklink = ".$_GET['color']." ".
				"AND bd.design = d.id ".
				"AND bc.color = c.id ";
		$parent = pg_fetch_all(pg_query($db, $SQL));
		echo "<H2>".$parent[0]['Name']." ".$parent[0]['description']."</H2>\n";
		echo "<IMG BORDER = '0' SRC = 'picture.php?size=400&amp;design=".$parent[0]['design']."&amp;color=".$parent[0]['color']."'><br><br>\n";
		$SQL = "SELECT count, free, d.description, c.\"Name\", dcu.design, dcu.color, dcuc.container ".
				"FROM lego.inventory i, lego.bricklink_design bd, lego.bricklink_color bc, ".
				"lego.design_color_user dcu, lego.design d, lego.color c, lego.design_color_user_container dcuc ".
				"WHERE i.parent_design = '".$_GET['design']."' ".
				"AND i.parent_type = '".$_GET['type']."' ".
				"AND i.parent_color = ".$_GET['color']." ".
				"AND i.child_design = bd.bricklink ".
				"AND i.child_type = bd.\"type\" ".
				"AND i.child_color = bc.bricklink ".
				"AND bd.design = dcu.design ".
				"AND bc.color = dcu.color ".
				"AND dcu.\"user\" = ".$_COOKIE['user']." ".
				"AND d.id = dcu.design ".
				"AND c.id = dcu.color ".
				"AND dcu.\"user\" = dcuc.\"user\" ".
				"AND dcuc.design = dcu.design ".
				"AND dcuc.color = dcu.color";
		$childs = pg_fetch_all(pg_query($db, $SQL));
		if ($childs)
		{
			foreach ($childs as $child)
			{
				$SQL = "SELECT sum(pb.collected) ".
						"FROM lego.project_bricks pb, lego.project p ".
						"WHERE p.\"order\" = pb.project ".
						"AND pb.design = ".$child['design']." ".
						"AND pb.color = ".$child['color']." ".
						"AND p.\"user\" = ".$_COOKIE['user']." ".
						"GROUP BY pb.design, pb.color";
				$projects = pg_fetch_all(pg_query($db, $SQL));
				if ($projects)
				{
					$child['free'] = $child['free'] - $projects[0]['sum'];
				}
				echo "<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$child['design']."&amp;color=".$child['color']."'>".$child['Name']." ".$child['description']." (needed: ".$child['count']." free: ".$child['free'].")(".$child['container'].")<br>\n";
			}
			echo "<FORM ACTION = 'index.php?what=merge&do=merge' METHOD = 'POST'>\n";
			$SQL = "SELECT dcuc.container FROM lego.design_color_user_container dcuc ".
					"WHERE dcuc.design = ".$parent[0]['design']." AND dcuc.color = ".$parent[0]['color']." AND dcuc.\"user\" = ".$_COOKIE['user'];
			$ans = pg_fetch_all(pg_query($db, $SQL));
			if ($ans)
			{
				echo $ans[0]['container']."<br>";
			}
			else
			{
				$SQL = "SELECT id FROM lego.container WHERE \"user\" = ".$_COOKIE['user']." ORDER BY id";
				$ans = pg_fetch_all(pg_query($db, $SQL));
				echo "<SELECT NAME = 'container' ID = 'container'>\n";
				foreach ($ans as $container)
				{
					echo "<OPTION VALUE = '".$container['id']."'>".$container['id']."\n";
				}
				echo "</SELECT><BR>\n";
			}
			echo "Merge into: <INPUT TYPE = 'text' NAME = 'antal' ID = 'antal'>\n";
			echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_GET['design']."'>\n";
			echo "<INPUT TYPE = 'hidden' NAME = 'type' ID = 'type' VALUE = '".$_GET['type']."'>\n";
			echo "<INPUT TYPE = 'hidden' NAME = 'color' ID = 'color' VALUE = '".$_GET['color']."'>\n";
			echo "<INPUT TYPE = 'submit' NAME = 'merge' ID = 'merge' VALUE = 'merge'>\n";
			echo "</FORM>\n";
		}
		echo "<FORM ACTION = 'index.php?what=merge&do=delete_inventory' METHOD = 'POST'>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_GET['design']."'>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'type' ID = 'type' VALUE = '".$_GET['type']."'>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'color' ID = 'color' VALUE = '".$_GET['color']."'>\n";
		echo "<INPUT TYPE = 'submit' NAME = 'delete_inventory' ID = 'delete_inventory' VALUE = 'delete inventory'>\n";
		echo "</FORM>\n";
	}
	else
	{
		if ($_GET['do'] == 'merge' and isset($_POST['design']) and isset($_POST['type']) and isset($_POST['color']))
		{
			$SQL = "SELECT bd.design, bc.color FROM lego.bricklink_design bd, lego.bricklink_color bc ".
					"WHERE bd.bricklink = '".$_POST['design']."' ".
					"AND bd.\"type\" = '".$_POST['type']."' ".
					"AND bc.bricklink = ".$_POST['color'];
			$parent = pg_fetch_all(pg_query($db, $SQL));
			$SQL = "UPDATE lego.design SET hide = FALSE WHERE id = ".$parent[0]['design'];
			pg_query($db, $SQL);
			$SQL = "SELECT free FROM lego.design_color_user ".
					"WHERE design = ".$parent[0]['design']." ".
					"AND color = ".$parent[0]['color']." ".
					"AND \"user\" = ".$_COOKIE['user'];
			$res = pg_fetch_all(pg_query($db, $SQL));
			if ($res)
			{
				$SQL = "UPDATE lego.design_color_user SET free = ".($_POST['antal']+$res[0]['free'])." ".
						"WHERE design = ".$parent[0]['design']." ".
						"AND color = ".$parent[0]['color']." ".
						"AND \"user\" = ".$_COOKIE['user'];
			}
			else
			{
				$SQL = "INSERT INTO lego.design_color_user (design, color, \"user\", free) ".
						"VALUES (".$parent[0]['design'].", ".$parent[0]['color'].", ".$_COOKIE['user'].
						", ".$_POST['antal'].")";
			}
			pg_query($db, $SQL);
			if ($_POST['container'])
			{
				$SQL = "INSERT INTO lego.design_color_user_container (design, color, \"user\", container, bricks) VALUES (".$parent[0]['design'].", ".$parent[0]['color'].", ".$_COOKIE['user'].", '".$_POST['container']."', ".$_POST['antal'].")";
				pg_query($db, $SQL);
			}
			$SQL = "SELECT bd.design, bc.color, count ".
					"FROM lego.inventory, lego.bricklink_design bd, lego.bricklink_color bc ".
					"WHERE parent_design = '".$_POST['design']."' ".
					"AND parent_type = '".$_POST['type']."' ".
					"AND parent_color = ".$_POST['color']." ".
					"AND bd.bricklink = child_design ".
					"AND bd.type = child_type ".
					"AND bc.bricklink = child_color";
			$childs = pg_fetch_all(pg_query($db, $SQL));
			foreach($childs as $child)
			{
				$SQL = "SELECT free FROM lego.design_color_user ".
						"WHERE design = ".$child['design']." ".
						"AND color = ".$child['color']." ".
						"AND \"user\" = ".$_COOKIE['user'];
				$res = pg_fetch_all(pg_query($db, $SQL));
				if ($res)
				{
					$SQL = "UPDATE lego.design_color_user SET free = ".($res[0]['free']-($_POST['antal']*$child['count']))." ".
							"WHERE design = ".$child['design']." ".
							"AND color = ".$child['color']." ".
							"AND \"user\" = ".$_COOKIE['user'];
				}
				else
				{
					$SQL = "INSERT INTO lego.design_color_user (design, color, \"user\", free) ".
							"VALUES (".$child['design'].", ".$child['color'].", ".$_COOKIE['user'].
							", ".($_POST['antal']*$child['count']).")";
				}
				pg_query($db, $SQL);
				$SQL = "SELECT bricks, container FROM lego.design_color_user_container ".
						"WHERE design = ".$child['design']." ".
						"AND color = ".$child['color']." ".
						"AND \"user\" = ".$_COOKIE['user'];
				$res = pg_fetch_all(pg_query($db, $SQL));
				if ($res)
				{
					$SQL = "UPDATE lego.design_color_user_container SET bricks = ".($res[0]['bricks']-($_POST['antal']*$child['count']))." ".
							"WHERE design = ".$child['design']." ".
							"AND color = ".$child['color']." ".
							"AND \"user\" = ".$_COOKIE['user']." ".
							"AND container = '".$res[0]['container']."'";
				}
				pg_query($db, $SQL);
			}
			$SQL = "UPDATE lego.\"user\" SET regenerate_bricklink = TRUE WHERE id = ".$_COOKIE['user'];
			pg_query($db, $SQL);
		}
		if ($_GET['do'] == 'delete_inventory' and isset($_POST['design']) and isset($_POST['type']) and isset($_POST['color']))
		{
			$SQL = "DELETE FROM lego.inventory_user WHERE design = '".$_POST['design']."' AND \"type\" = '".$_POST['type']."' AND color = ".$_POST['color'];
			pg_query($db, $SQL);
			$SQL = "DELETE FROM lego.inventory WHERE parent_design = '".$_POST['design']."' AND parent_type = '".$_POST['type']."' AND parent_color = ".$_POST['color'];
			pg_query($db, $SQL);
		}
		$SQL = "SELECT iu.design, iu.type, iu.color, d.id AS did, d.description, ".
				"c.\"Name\", c.id AS cid, last6_used_usd ".
				"FROM lego.design d, lego.bricklink_design bd, lego.color c, lego.bricklink_color bc, ".
				"lego.inventory_user iu LEFT OUTER JOIN  ".
					"lego.bricklink_price bp ON iu.design = bp.design AND iu.type = bp.design_type ".
					"AND iu.color = bp.color ".
				"WHERE iu.\"user\" = ".$_COOKIE['user']." ".
				"AND iu.design = bd.bricklink ".
				"AND iu.type = bd.type ".
				"AND d.id = bd.design ".
				"AND iu.color = bc.bricklink ".
				"AND c.id = bc.color ".
				"AND (d.id, c.id) NOT IN (SELECT design, color FROM lego.design_color_user WHERE \"user\" = ".$_COOKIE['user'].") ".
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
						$res = pg_fetch_all(pg_query($db, $SQL));
						if (!$res)
						{
							$errorinv = 1;
						}
						else
						{
							$SQL = "SELECT sum(pb.collected) ".
									"FROM lego.project_bricks pb, lego.project p, ".
									"lego.bricklink_design bd, lego.bricklink_color bc ".
									"WHERE p.\"order\" = pb.project ".
									"AND bd.design = pb.design ".
									"AND bc.color = pb.color ".
									"AND bd.bricklink = '".$child['child_design']."' ".
									"AND bd.type = '".$child['child_type']."' ".
									"AND bc.bricklink = ".$child['child_color']." ".
									"AND p.\"user\" = ".$_COOKIE['user']." ".
									"GROUP BY pb.design, pb.color";
							$projects = pg_fetch_all(pg_query($db, $SQL));
							if ($projects)
							{
								if ($projects[0]['sum'] >= $child['count'])
								{
									$errorinv = 1;
								}
							}
						}
					}
					if (!$errorinv and is_numeric($parent['last6_used_usd']))
					{
						echo "<A HREF = 'index.php?what=merge&do=spec&design=".$parent['design']."&type=".$parent['type']."&color=".$parent['color']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$parent['did']."&amp;color=".$parent['cid']."'>".$parent['Name']." ".$parent['description']." ($".$parent['last6_used_usd'].")</A><br>";
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
		$SQL = "SELECT iu.design, iu.type, iu.color, d.id AS did, d.description, ".
				"c.\"Name\", c.id AS cid, last6_used_usd ".
				"FROM lego.design d, lego.bricklink_design bd, lego.color c, lego.bricklink_color bc, ".
				"lego.inventory_user iu RIGHT OUTER JOIN  ".
					"lego.bricklink_price bp ON iu.design = bp.design AND iu.type = bp.design_type ".
					"AND iu.color = bp.color ".
				"WHERE iu.\"user\" = ".$_COOKIE['user']." ".
				"AND iu.design = bd.bricklink ".
				"AND iu.type = bd.type ".
				"AND d.id = bd.design ".
				"AND iu.color = bc.bricklink ".
				"AND c.id = bc.color ".
				"AND (d.id, c.id) IN (SELECT design, color FROM lego.design_color_user WHERE \"user\" = ".$_COOKIE['user'].") ".
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
						$res = pg_fetch_all(pg_query($db, $SQL));
						if (!$res)
						{
							$errorinv = 1;
						}
						else
						{
							$SQL = "SELECT sum(pb.collected) ".
									"FROM lego.project_bricks pb, lego.project p, ".
									"lego.bricklink_design bd, lego.bricklink_color bc ".
									"WHERE p.\"order\" = pb.project ".
									"AND bd.design = pb.design ".
									"AND bc.color = pb.color ".
									"AND bd.bricklink = '".$child['child_design']."' ".
									"AND bd.type = '".$child['child_type']."' ".
									"AND bc.bricklink = ".$child['child_color']." ".
									"AND p.\"user\" = ".$_COOKIE['user']." ".
									"GROUP BY pb.design, pb.color";
							$projects = pg_fetch_all(pg_query($db, $SQL));
							if ($projects)
							{
								if ($projects[0]['sum'] >= $child['count'])
								{
									$errorinv = 1;
								}
							}
						}
					}
					if (!$errorinv and is_numeric($parent['last6_used_usd']))
					{
						echo "<A HREF = 'index.php?what=merge&do=spec&design=".$parent['design']."&type=".$parent['type']."&color=".$parent['color']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$parent['did']."&amp;color=".$parent['cid']."'>".$parent['Name']." ".$parent['description']." ($".$parent['last6_used_usd'].")</A><br>";
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
}
?>