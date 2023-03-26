<?php
function addpiece5()
{
	include "../www/db.php";
	if ($_GET['do'] == 'full')
	{
		$SQL = "UPDATE lego.container SET mark_full = TRUE WHERE id = '".$_GET['id']."'";
		pg_query($db, $SQL);
		echo "Marked ".$_GET['id']." as full.";
	}
	else
	{
		if (!isset($_POST['design']))
		{
			if (!$_POST['part_number'])
			{
				$_POST['part_number'] = 0;
			}
			if ($_COOKIE['user'] == 1) //om användaren är admin
			{
				$SQL = "INSERT INTO lego.design (description, part_number) VALUES ('".utf8_encode($_POST['part_name'])."', ".$_POST['part_number'].")";
				pg_query($db, $SQL);
				if ($_POST['bricklink'])
				{
					$SQL = "INSERT INTO lego.bricklink_design (design, bricklink) VALUES (currval('lego.design_id_seq'), '".$_POST['bricklink']."')";
					pg_query($db, $SQL);
				}
			}
			else //för alla andra användare
			{
				$SQL = "INSERT INTO lego.design (description, part_number, approved) VALUES ('".utf8_encode($_POST['part_name'])."', ".$_POST['part_number'].", FALSE)";
				pg_query($db, $SQL);
			}
			$SQL = "SELECT currval('lego.design_id_seq')";
			$design = pg_fetch_all(pg_query($db, $SQL));
			$design = $design[0]['currval'];
		}
		else
		{
			$design = $_POST['design'];
		}
		$_POST['design'] = $design;
		$color = $_POST['color'];
		if (isset($_POST['new_color']))
		{
			$tmpName  = $_FILES['picture']['tmp_name'];
			$fileSize = $_FILES['picture']['size'];
			$fileType = $_FILES['picture']['type'];
			$fp = fopen($tmpName, 'r');
			$content = fread($fp, $fileSize);
			$content = pg_escape_bytea($content);
			if ($_COOKIE['user'] == 1) //om användaren är admin
			{
				$SQL = "INSERT INTO lego.design_color (design, color, picture_data, picture_size, picture_type) VALUES (".$design.", ".$color.", '".$content."', ".$fileSize.", '".$fileType."')";
				pg_query($db, $SQL);
			}
			else //för alla andra användare
			{
				$SQL = "INSERT INTO lego.design_color (design, color, picture_data, picture_size, picture_type, approved) VALUES (".$design.", ".$color.", '".$content."', ".$fileSize.", '".$fileType."', FALSE)";
				pg_query($db, $SQL);
			}
		}
		if (isset($_POST['container']) or !$_POST['free'])
		{
			if ($_POST['container'] and $_POST['free'])
			{
				if ($_POST['container'] == 'container_existing')
				{
					if ($_POST['new'] == 'on')
					{
						$SQL = "INSERT INTO lego.design_color_user_container (design, color, \"user\", bricks, container, condition) VALUES (".$design.", ".$color.", ".$_COOKIE['user'].", ".$_POST['free'].", '".$_POST['container_existing']."', 'n')";
					}
					else
					{
						$SQL = "INSERT INTO lego.design_color_user_container (design, color, \"user\", bricks, container) VALUES (".$design.", ".$color.", ".$_COOKIE['user'].", ".$_POST['free'].", '".$_POST['container_existing']."')";
					}
					pg_query($db, $SQL) or die ('error');
					$SQL = "UPDATE lego.container SET recalculate = TRUE WHERE \"user\" = ".$_COOKIE['user']." AND id = '".$_POST['container_existing']."'";
					pg_query($db, $SQL) or die ('error');
				}
				elseif ($_POST['container'] == 'container_new')
				{
					$SQL = "INSERT INTO lego.container (id, \"user\") VALUES ('".$_POST['container_new']."', ".$_COOKIE['user'].")";
					pg_query($db, $SQL);
					if ($_POST['new'] == 'on')
					{
						$SQL = "INSERT INTO lego.design_color_user_container (design, color, \"user\", bricks, container, condition) VALUES (".$design.", ".$color.", ".$_COOKIE['user'].", ".$_POST['free'].", '".$_POST['container_new']."', 'n')";
					}
					else
					{
						$SQL = "INSERT INTO lego.design_color_user_container (design, color, \"user\", bricks, container) VALUES (".$design.", ".$color.", ".$_COOKIE['user'].", ".$_POST['free'].", '".$_POST['container_new']."')";
					}
					pg_query($db, $SQL) or die ('error');
					$SQL = "UPDATE lego.container SET recalculate = TRUE WHERE \"user\" = ".$_COOKIE['user']." AND id = '".$_POST['container_new']."'";
					pg_query($db, $SQL) or die ('error');
				}
				else
				{
					$SQL = "UPDATE lego.design_color_user_container SET bricks = (bricks + ".$_POST['free'].") WHERE design = ".$design." AND color = ".$color." AND \"user\" = ".$_COOKIE['user']." AND container = '".$_POST['container']."'";
					pg_query($db, $SQL) or die ('error');
					$SQL = "UPDATE lego.container SET recalculate = TRUE WHERE \"user\" = ".$_COOKIE['user']." AND id = '".$_POST['container']."'";
					pg_query($db, $SQL) or die ('error');
				}
			}
			$SQL = "SELECT used, free FROM lego.design_color_user WHERE design = ".$design." AND color = ".$color." AND \"user\" = ".$_COOKIE['user'];
			$lego = pg_fetch_all(pg_query($db, $SQL));
			if ($lego)
			{
				$used = $lego[0]['used'];
				$free = $lego[0]['free'];
				$used = $used + $_POST['used'];
				$free = $free + $_POST['free'];
				$SQL = "UPDATE lego.design_color_user SET used = ".$used.", free = ".$free." WHERE design = ".$design." AND color = ".$color." AND \"user\" = ".$_COOKIE['user'];
				pg_query($db, $SQL) or die ('error');
				echo "Successfully added the piece!";
				$SQL = "UPDATE lego.\"user\" SET regenerate_bricklink = TRUE WHERE id = ".$_COOKIE['user'];
				pg_query($db, $SQL);
			}
			else
			{
				$used = 0;
				$free = 0;
				$used = $used + $_POST['used'];
				$free = $free + $_POST['free'];
				$SQL = "INSERT INTO lego.design_color_user (design, color, \"user\", used, free) VALUES (".$design.", ".$color.", ".$_COOKIE['user'].", ".$used.", ".$free.")";
				pg_query($db, $SQL) or die ('error');
				echo "Successfully added the piece!";
				$SQL = "UPDATE lego.\"user\" SET regenerate_bricklink = TRUE WHERE id = ".$_COOKIE['user'];
				pg_query($db, $SQL);
			}
			if ($_POST['bricklink'] and $_COOKIE['user'] == 1)
			{
				$SQL = "INSERT INTO lego.bricklink_design (design, bricklink) VALUES (".$_POST['design'].", '".$_POST['bricklink']."')";
				pg_query($db, $SQL);
			}
			brickInProject($_POST['design'], $_POST['color']);
			if ($_POST['container'] == 'container_existing')
			{
				echo "<BR><A HREF = 'index.php?what=addpiece5&amp;do=full&amp;id=".$_POST['container_existing']."'>MARK CONTAINER AS FULL</A>";
			}
			elseif ($_POST['container'] == 'container_new')
			{
				echo "<BR><A HREF = 'index.php?what=addpiece5&amp;do=full&amp;id=".$_POST['container_new']."'>MARK CONTAINER AS FULL</A>";
			}
			else
			{
				echo "<BR><A HREF = 'index.php?what=addpiece5&amp;do=full&amp;id=".$_POST['container']."'>MARK CONTAINER AS FULL</A>";
			}
		}
	}
}
?>