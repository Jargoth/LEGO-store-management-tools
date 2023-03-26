<?php
function addpiecexml()
{
	include "../www/db.php";
	if ($_POST['submit'] == 'submit')
	{
		$tmpName  = $_FILES['csv']['tmp_name'];
		$fileSize = $_FILES['csv']['size'];
		$lines = file($tmpName, FILE_SKIP_EMPTY_LINES);
		$BrickStoreXML = 0;
		$Inventory = 0;
		$item = 0;
		foreach ($lines as $line)
		{
			$line = ltrim($line);
			$line = rtrim($line);
			if ($BrickStoreXML == 0 and $Inventory == 0 and $item == 0)
			{
				if ($line == '<BrickStockXML>')
				{
					$BrickStoreXML = 1;
				}
			}
			elseif ($BrickStoreXML == 1 and $Inventory == 0 and $item == 0)
			{
				if ($line == '<Inventory>')
				{
					$Inventory = 1;
				}
			}
			elseif ($BrickStoreXML == 1 and $Inventory == 1 and $item == 0)
			{
				if ($line == '<Item>')
				{
					$item = 1;
				}
			}
			elseif ($BrickStoreXML == 1 and $Inventory == 1 and $item == 1)
			{
				$line = explode('>', $line);
				if ($line[0] == '<ItemID')
				{
					$line = explode('<', $line[1]);
					$design = $line[0];
				}
				if ($line[0] == '<ItemTypeID')
				{
					$line = explode('<', $line[1]);
					$type = $line[0];
				}
				if ($line[0] == '<ColorID')
				{
					$line = explode('<', $line[1]);
					$color = $line[0];
				}
				if ($line[0] == '<Qty')
				{
					$line = explode('<', $line[1]);
					$qty = $line[0];
				}
				if ($line[0] == '<Remarks')
				{
					$line = explode('<', $line[1]);
					$container = $line[0];
				}
				if ($line[0] == '<Condition')
				{
					$line = explode('<', $line[1]);
					$condition = $line[0];
					if ($condition == 'N')
					{
						$condition = 'n';
					}
				}
				if ($line[0] == '</Item')
				{
					$SQL = "SELECT design FROM lego.bricklink_design WHERE bricklink = '".$design."' AND \"type\" = '".$type."'";
					$ans = pg_fetch_all(pg_query($db, $SQL));
					if ($ans)
					{
						$design1 = $ans[0]['design'];
						$SQL = "UPDATE lego.design SET hide = false WHERE id = ".$design1;
						pg_query($db, $SQL);
					}
					else
					{
						$SQL = "INSERT INTO lego.design (description) VALUES ('new item')";
						pg_query($db, $SQL);
						$SQL = "SELECT currval('lego.design_id_seq')";
						$ans = pg_fetch_all(pg_query($db, $SQL));
						$design1 = $ans[0]['currval'];
						$SQL = "INSERT INTO lego.bricklink_design (design, bricklink, \"type\") VALUES (".$design1.", '".$design."', '".$type."')";
						pg_query($db, $SQL);
					}
					
					$SQL = "SELECT color FROM lego.bricklink_color WHERE bricklink = ".$color;
					$ans = pg_fetch_all(pg_query($db, $SQL));
					if ($ans)
					{
						$color1 = $ans[0]['color'];
					}
					else
					{
						print($color+"\n");
						$SQL = "INSERT INTO lego.color (\"Name\") VALUES ('new item')";
						pg_query($db, $SQL);
						$SQL = "SELECT currval('lego.color_id_seq')";
						$ans = pg_fetch_all(pg_query($db, $SQL));
						$color1 = $ans[0]['currval'];
						$SQL = "INSERT INTO lego.bricklink_color (color, bricklink) VALUES (".$color1.", ".$color.")";
						pg_query($db, $SQL);
					}
					if ($_POST['mode'] == 'free')
					{
						$SQL = "SELECT free FROM lego.design_color_user WHERE design = ".$design1." AND color = ".$color1." AND \"user\" = ".$_COOKIE['user'];
						$ans = pg_fetch_all(pg_query($db, $SQL));
						if ($ans)
						{
							$SQL = "UPDATE lego.design_color_user SET free = ".($ans[0]['free']+$qty)." WHERE design = ".$design1." AND color = ".$color1." AND \"user\" = ".$_COOKIE['user'];
							pg_query($db, $SQL);
						}
						else
						{
							$SQL = "INSERT INTO lego.design_color_user (design, color, \"user\", free) VALUES (".$design1.", ".$color1.", ".$_COOKIE['user'].", ".$qty.")";
							pg_query($db, $SQL);
						}		
						
						
						$SQL = "SELECT design, color, \"user\", container FROM lego.design_color_user_container WHERE design = ".$design1." AND color = ".$color1." AND \"user\" = ".$_COOKIE['user']." AND container = '".$container."'";
						$ans = pg_fetch_all(pg_query($db, $SQL));
						if ($ans)
						{
							$SQL = "UPDATE lego.design_color_user_container SET bricks = (bricks+".$qty.") WHERE design = ".$design1." AND color = ".$color1." AND \"user\" = ".$_COOKIE['user']." AND container = '".$container."'";
							pg_query($db, $SQL);
						}
						else
						{
							$SQL = "SELECT id FROM lego.container WHERE id = '".$container."' AND \"user\" = ".$_COOKIE['user'];
							$ans = pg_fetch_all(pg_query($db, $SQL));
							if (!$ans)
							{
								$SQL = "INSERT INTO lego.container (id, \"user\") VALUES ('".$container."', ".$_COOKIE['user'].")";
								pg_query($db, $SQL);
							}
							$SQL = "INSERT INTO lego.design_color_user_container (design, color, \"user\", bricks, container, condition) VALUES (".$design1.", ".$color1.", ".$_COOKIE['user'].", ".$qty.", '".$container."', '".$condition."')";
							pg_query($db, $SQL);
						}
						$SQL = "UPDATE lego.container SET recalculate = 't' WHERE id = '".$container."'";
						pg_query($db, $SQL);
					}
					elseif ($_POST['mode'] == 'used')
					{
						$SQL = "SELECT used FROM lego.design_color_user WHERE design = ".$design1." AND color = ".$color1." AND \"user\" = ".$_COOKIE['user'];
						$ans = pg_fetch_all(pg_query($db, $SQL));
						if ($ans)
						{
							$SQL = "UPDATE lego.design_color_user SET used = ".($ans[0]['used']+$qty)." WHERE design = ".$design1." AND color = ".$color1." AND \"user\" = ".$_COOKIE['user'];
							pg_query($db, $SQL);
						}
						else
						{
							$SQL = "INSERT INTO lego.design_color_user (design, color, \"user\", used) VALUES (".$design1.", ".$color1.", ".$_COOKIE['user'].", ".$qty.")";
							pg_query($db, $SQL);
						}
					}
					
					$item = 0;
				}
			}
		}
		$SQL = "UPDATE lego.\"user\" SET regenerate_bricklink = TRUE WHERE id = ".$_COOKIE['user'];
		pg_query($db, $SQL);
	}
	else
	{
		echo "<FORM ACTION = 'index.php?what=addpiecexml' METHOD = 'post' enctype='multipart/form-data'>\n";
		echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000'>\n";
		echo "<INPUT ID = 'csv' NAME = 'csv' TYPE = 'file'><BR>\n";
		echo "<INPUT TYPE = 'radio' NAME = 'mode' ID = 'mode' VALUE = 'free'>free";
		echo "<INPUT TYPE = 'radio' NAME = 'mode' ID = 'mode' VALUE = 'used'>used<br>";
		echo "<INPUT ID = 'submit' NAME = 'submit' TYPE = 'submit' VALUE = 'submit'>\n";
		echo "</FORM>\n";
	}
}