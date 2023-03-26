<?php
function specialBricklinkOrder()
{
	include "../www/db.php";
	if ($_POST['submit'] == 'submit')
	{
		$tmpName  = $_FILES['csv']['tmp_name'];
		$fileSize = $_FILES['csv']['size'];
		$lines = file($tmpName, FILE_SKIP_EMPTY_LINES);
		$orders = 0;
		$order = 0;
		$item = 0;
		foreach ($lines as $line)
		{
			$line = ltrim($line);
			$line = rtrim($line);
			if ($orders == 0 and $order == 0 and $item == 0)
			{
				if ($line == '<ORDERS>')
				{
					$orders = 1;
				}
			}
			elseif ($orders == 1 and $order == 0 and $item == 0)
			{
				if ($line == '</ORDERS>')
				{
					echo "done";
					$orders = 0;
				}
				elseif ($line == '<ORDER>')
				{
					$order = 1;
				}
			}
			elseif ($orders == 1 and $order == 1 and $item == 0)
			{
				if ($line == '</ORDER>')
				{
					$order = 0;
				}
				elseif ($line == '<ITEM>')
				{
					$item = 1;
				}
			}
			elseif ($orders == 1 and $order == 1 and $item == 1)
			{
				$line = explode('>', $line);
				if ($line[0] == '</ITEM')
				{
					$SQL = "SELECT design FROM lego.bricklink_design WHERE bricklink = '".$itemid."' AND \"type\" = '".$itemtype."'";
					$ans = pg_fetch_all(pg_query($db, $SQL));
					$design = $ans[0]['design'];
					$SQL = "SELECT color FROM lego.bricklink_color WHERE bricklink = ".$color;
					$ans = pg_fetch_all(pg_query($db, $SQL));
					$color = $ans[0]['color'];
					$SQL = "UPDATE lego.design_color_user SET free = (free - ".$qty.") WHERE design = ".$design." AND color = ".$color." AND \"user\" = ".$_COOKIE['user'];
					pg_query($db, $SQL);
					$SQL = "UPDATE lego.design_color_user_container SET bricks = (bricks - ".$qty.") WHERE design = ".$design." AND color = ".$color." AND \"user\" = ".$_COOKIE['user']." AND container = '".$container."'";
					pg_query($db, $SQL);
					$SQL = "UPDATE lego.bricklink SET sell = (sell - ".$qty.") WHERE design = ".$design." AND color = ".$color." AND \"user\" = ".$_COOKIE['user']." AND container = '".$container."'";
					pg_query($db, $SQL);
					$SQL = "UPDATE lego.container SET recalculate = TRUE WHERE \"user\" = ".$_COOKIE['user']." AND id = '".$container."'";
					pg_query($db, $SQL);
					$item = 0;
					$itemid = '';
					$color = '';
					$itemtype = '';
					$qty = '';
					$container = '';
				}
				if ($line[0] == '<ITEMID')
				{
					$line = explode('<', $line[1]);
					$itemid = $line[0];
				}
				if ($line[0] == '<COLOR')
				{
					$line = explode('<', $line[1]);
					$color = $line[0];
				}
				if ($line[0] == '<ITEMTYPE')
				{
					$line = explode('<', $line[1]);
					$itemtype = $line[0];
				}
				if ($line[0] == '<QTY')
				{
					$line = explode('<', $line[1]);
					$qty = $line[0];
				}
				if ($line[0] == '<REMARKS')
				{
					$line = explode('<', $line[1]);
					$container = $line[0];
				}
			}
		}
		$SQL = "UPDATE lego.\"user\" SET regenerate_bricklink = TRUE WHERE id = ".$_COOKIE['user'];
		pg_query($db, $SQL);
	}
	else
	{
		echo "<FORM ACTION = 'index.php?what=specialBricklinkOrder' METHOD = 'post' enctype='multipart/form-data'>\n";
		echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000'>\n";
		echo "<INPUT ID = 'csv' NAME = 'csv' TYPE = 'file'><BR>\n";
		echo "<INPUT ID = 'submit' NAME = 'submit' TYPE = 'submit' VALUE = 'submit'>\n";
		echo "</FORM>\n";
	}
}