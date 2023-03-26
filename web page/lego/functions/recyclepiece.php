<?php
function recyclepiece()
{
	include "../www/db.php";
	if ($_GET['step'] == '2')
		{
			echo "<FORM ACTION = 'index.php?what=recyclepiece&amp;step=3' METHOD = 'POST'>\n";
			echo "<TABLE BORDER = '1'>\n";
			$SQL = "SELECT d.description, d.id, (SELECT dcu.color FROM lego.design_color_user dcu WHERE dcu.design = d.id ORDER BY (dcu.used + dcu.free) DESC LIMIT 1) AS color FROM lego.design d, lego.design_color_user dcu WHERE d.id = dcu.design AND \"user\" = ".$_COOKIE['user']." AND dcu.used > 0 AND description ILIKE '%".$_POST['description']."%' GROUP BY d.description, d.id ORDER BY d.description";
			$legos = pg_fetch_all(pg_query($db, $SQL));
			if ($legos)
			{
				foreach ($legos as $lego)
				{
					echo "<TR><TD><INPUT TYPE = 'radio' NAME = 'design' ID = 'design' VALUE = '".$lego['id']."'></TD><TD><A HREF = 'picture.php?size=400&amp;design=".$lego['id']."&amp;color=".$lego['color']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$lego['id']."&amp;color=".$lego['color']."'></A><A HREF = 'index.php?what=brick&amp;design=".$lego['id']."'>".utf8_decode($lego['description'])."</A></TD></TR>\n";
				}
			}
			echo "</TABLE>\n";
			echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'next'>\n";
			echo "</FORM>\n";
		}
		elseif ($_GET['step'] == '3')
		{
			$SQL = "SELECT d.description, r.replacing FROM lego.replacing r, lego.design d WHERE d.id = r.replacing AND r.what = 'design' AND r.obsolete = ".$_POST['design'];
			$replacing = pg_fetch_all(pg_query($db, $SQL));
			if (!$replacing)
			{
				echo "<FORM ACTION = 'index.php?what=recyclepiece&amp;step=4' METHOD = 'POST'>\n";
				echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_POST['design']."'>\n";
				if ($_POST['design'] == 0)
				{
				}
				else
				{
					$SQL = "SELECT dcu.color, c.\"Name\" FROM lego.design_color_user dcu, lego.color c WHERE dcu.color = c.id AND (design = ".$_POST['design']." OR design IN (SELECT obsolete FROM lego.replacing WHERE replacing = ".$_POST['design']." AND what = 'design')) AND \"user\" = ".$_COOKIE['user']." AND dcu.used > 0 GROUP BY dcu.color, c.\"Name\" ORDER BY c.\"Name\"";
					$legos = pg_fetch_all(pg_query($db, $SQL));
					if ($legos)
					{
						echo "<p>These color exists in your collection</p>";
						foreach ($legos as $lego)
						{
							echo "<INPUT TYPE = 'radio' NAME = 'color' ID = 'color' VALUE = '".$lego['color']."'><IMG SRC = 'picture.php?size=55&amp;design=".$_POST['design']."&amp;color=".$lego['color']."'>".utf8_decode($lego['Name'])."<BR>\n";
						}
					}
				
				}
				echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'add'>\n";
				echo "</FORM>\n";
			}
			else //If the brick is marked obsolete (Change to one of the replacing ones)
			{
				echo "This brick is marked as obsolete. Please select one of these replacing bricks.<br><br>\n";
				echo "<FORM ACTION = 'index.php?what=recyclepiece&amp;step=3' METHOD = 'POST'>\n";
				echo "<TABLE BORDER = '1'>\n";
				foreach ($replacing as $rep)
				{
					echo "<TR><TD><INPUT TYPE = 'radio' NAME = 'design' ID = 'design' VALUE = '".$rep['replacing']."'></TD><TD><A HREF = 'picture.php?size=400&amp;design=".$rep['replacing']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$rep['replacing']."'></A><A HREF = 'index.php?what=brick&amp;design=".$rep['replacing']."'>".utf8_decode($rep['description'])."</A></TD></TR>\n";
				}
				echo "</TABLE>\n";
				echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'next'>\n";
				echo "</FORM>\n";
			}
		}
		elseif ($_GET['step'] == '4')
		{
			$SQL = "SELECT c.\"Name\", r.replacing FROM lego.replacing r, lego.color c WHERE c.id = r.replacing AND r.what = 'color' AND r.obsolete = ".$_POST['color'];
			$replacing = pg_fetch_all(pg_query($db, $SQL));
			if (!$replacing)
			{
				/*displays the picture of the current piece if there is one*/
				echo "<IMG SRC = 'picture.php?size=55&amp;design=".$_POST['design']."&amp;color=".$_POST['color']."'><BR><BR>\n";
		
				echo "<FORM ACTION = 'index.php?what=recyclepiece&amp;step=5' METHOD = 'POST' enctype=\"multipart/form-data\">\n";
				echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_POST['design']."'>\n";
				echo "<INPUT TYPE = 'hidden' NAME = 'color' ID = 'color' VALUE = '".$_POST['color']."'>\n";
				echo "Number to recycle: <INPUT TYPE = 'text' NAME = 'recycle' ID = 'recycle'><BR>\n";
				echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'recycle'>\n";
				echo "</FORM>\n";
			}
			else
			{
				echo "This color is marked as obsolete. Please select one of these replacing colors.<br><br>\n";
				echo "<FORM ACTION = 'index.php?what=recyclepiece&amp;step=4' METHOD = 'POST'>\n";
				echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_POST['design']."'>\n";
				foreach ($replacing as $rep)
				{
					echo "<INPUT TYPE = 'radio' NAME = 'color' ID = 'color' VALUE = '".$rep['replacing']."'><IMG SRC = 'picture.php?size=55&amp;design=".$_POST['design']."&amp;color=".$rep['replacing']."'>".utf8_decode($rep['Name'])."<BR>\n";
				}
				echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'add'>\n";
				echo "</FORM>\n";
			}
		}
		else
		{
			if ($_GET['step'] == '5')
			{
				if ($_POST['submit_picture'])
				{
					$tmpName  = $_FILES['picture']['tmp_name'];
					$fileSize = $_FILES['picture']['size'];
					$fileType = $_FILES['picture']['type'];
					$fp = fopen($tmpName, 'r');
					$content = fread($fp, $fileSize);
					$content = pg_escape_bytea($content);
					$SQL = "INSERT INTO lego.design_color (design, color, picture_data, picture_size, picture_type) VALUES (".$_POST['design'].", ".$_POST['color'].", '".$content."', ".$fileSize.", '".$fileType."')";
					pg_query($db, $SQL);
				}
				$SQL = "SELECT design FROM lego.design_color WHERE design = ".$_POST['design']." AND color = ".$_POST['color'];
				$res = pg_fetch_all(pg_query($db, $SQL));
				if ($res)
				{
					$SQL = "SELECT design FROM lego.design_color_user WHERE  design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
					$res = pg_fetch_all(pg_query($db, $SQL));
					if (!$res)
					{
						$SQL = "INSERT INTO lego.design_color_user (design, color, \"user\", free, used) VALUES (".$_POST['design'].", ".$_POST['color'].", ".$_COOKIE['user'].", 0, 0)";
						pg_query($db, $SQL);
					}
					$SQL = "SELECT obsolete FROM lego.replacing WHERE replacing = ".$_POST['color']." AND what = 'color'";
					$obsolete_color = pg_fetch_all(pg_query($db, $SQL));
					$SQL = "SELECT obsolete FROM lego.replacing WHERE replacing = ".$_POST['design']." AND what = 'design'";
					$obsolete_design = pg_fetch_all(pg_query($db, $SQL));
				
					$SQL = "SELECT free, used FROM lego.design_color_user WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
					$lego = pg_fetch_all(pg_query($db, $SQL));
					if ($obsolete_color)
					{
						$first = 1;
						$obs_color;
						foreach ($obsolete_color as $obs)
						{
							if ($first)
							{
								$obs_color = $obs_color."color = ".$obs['obsolete'];
								$first = 0;
							}
							else
							{
								$obs_color = $obs_color." OR color = ".$obs['obsolete'];
							}
						}
						$SQL = "SELECT free, used, color FROM lego.design_color_user WHERE design = ".$_POST['design']." AND (".$obs_color.") AND \"user\" = ".$_COOKIE['user'];
						$lego_obsolete_color = pg_fetch_all(pg_query($db, $SQL));
					}
					if ($obsolete_design)
					{
						$first = 1;
						$obs_design;
						foreach ($obsolete_design as $obs)
						{
							if ($first)
							{
								$obs_design = $obs_design."design = ".$obs['obsolete'];
								$first = 0;
							}
							else
							{
								$obs_design = $obs_design." OR design = ".$obs['obsolete'];
							}
						}
						$SQL = "SELECT free, used, design FROM lego.design_color_user WHERE (".$obs_design.") AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
						$lego_obsolete_design = pg_fetch_all(pg_query($db, $SQL));
					}
					if ($obsolete_design and $obsolete_color)
					{
						$SQL = "SELECT free, used, design, color FROM lego.design_color_user WHERE (".$obs_design.") AND (".$obs_color.") AND \"user\" = ".$_COOKIE['user'];
						$lego_obsolete_design_color = pg_fetch_all(pg_query($db, $SQL));
					}
					$used = 0;
					if ($lego)
					{
						$used += $lego[0]['used'];
					}
					if ($lego_obsolete_color)
					{
						foreach ($lego_obsolete_color as $obsolete_color_used)
						{
							$used += $obsolete_color_used['used'];
						}
					}
					if ($lego_obsolete_design)
					{
						foreach ($lego_obsolete_design as $obsolete_design_used)
						{
							$used += $obsolete_design_used['used'];
						}
					}
					if ($lego_obsolete_design_color)
					{
						foreach ($lego_obsolete_design_color as $obsolete_design_color_used)
						{
							$used += $obsolete_design_color_used['used'];
						}
					}
					$recycle = $_POST['recycle'];
					$free = 0;
					if ($used >= $recycle)
					{
						if ($lego[0]['used'] >= $recycle)
						{
							$SQL = "UPDATE lego.design_color_user SET free = ".($lego[0]['free'] + $recycle).", used = ".($lego[0]['used']-$recycle)." WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
							pg_query($db, $SQL);
						}
						else
						{
							$free = $free + $lego[0]['free'] + $lego[0]['used'];
							$SQL = "UPDATE lego.design_color_user SET free = ".($free).", used = ".($lego[0]['used']-$lego[0]['used'])." WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
							pg_query($db, $SQL);
							$recycle -= $lego[0]['used'];
							if ($lego_obsolete_color)
							{
								foreach ($lego_obsolete_color as $data)
								{
									if ($recycle > 0)
									{
										if ($data['used'] >= $recycle)
										{
											$SQL = "UPDATE lego.design_color_user SET free = ".($free+$recycle)." WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
											$SQL = "UPDATE lego.design_color_user SET used = ".($data['used']-$recycle)." WHERE design = ".$_POST['design']." AND color = ".$data['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
										}
										else
										{
											$free = $free + $data['used'];
											$SQL = "UPDATE lego.design_color_user SET free = ".($free)." WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
											$SQL = "UPDATE lego.design_color_user SET used = ".($data['used']-$data['used'])." WHERE design = ".$_POST['design']." AND color = ".$data['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
											$recycle -= $data['used'];
										}
									}
								}
							}
							if ($lego_obsolete_design)
							{
								foreach ($lego_obsolete_design as $data)
								{
									if ($recycle > 0)
									{
										if ($data['used'] >= $recycle)
										{
											$SQL = "UPDATE lego.design_color_user SET free = ".($free+$recycle)." WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
											$SQL = "UPDATE lego.design_color_user SET used = ".($data['used']-$recycle)." WHERE design = ".$data['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
										}
										else
										{
											$free = $free + $data['used'];
											$SQL = "UPDATE lego.design_color_user SET free = ".($free)." WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
											$SQL = "UPDATE lego.design_color_user SET used = ".($data['used']-$data['used'])." WHERE design = ".$data['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
											$recycle -= $data['used'];
										}
									}
								}
							}
							if ($lego_obsolete_design_color)
							{
								foreach ($lego_obsolete_design_color as $data)
								{
									if ($recycle > 0)
									{
										if ($data['used'] >= $recycle)
										{
											$SQL = "UPDATE lego.design_color_user SET free = ".($free+$recycle)." WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
											$SQL = "UPDATE lego.design_color_user SET used = ".($data['used']-$recycle)." WHERE design = ".$data['design']." AND color = ".$data['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
										}
										else
										{
											$free = $free + $data['used'];
											$SQL = "UPDATE lego.design_color_user SET free = ".($free)." WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
											$SQL = "UPDATE lego.design_color_user SET used = ".($data['used']-$data['used'])." WHERE design = ".$data['design']." AND color = ".$data['color']." AND \"user\" = ".$_COOKIE['user'];
											pg_query($db, $SQL);
											$recycle -= $data['used'];
										}
									}
								}
							}
						}
					}
					else
					{
						echo "<H2>error!!! you only have used ".$used." bricks</H2><br>\n";
					}
					brickInProject($_POST['design'], $_POST['color']);
				}
				else
				{
					echo "We are missing a picture of that design/color. Please provide one.<br><br>\n";
					echo "<FORM ACTION = 'index.php?what=recyclepiece&amp;step=5' METHOD = 'POST' enctype=\"multipart/form-data\">\n";
					echo "<input type='hidden' name='design' value='".$_POST['design']."'>\n";
					echo "<input type='hidden' name='color' value='".$_POST['color']."'>\n";
					echo "<input type='hidden' name='recycle' value='".$_POST['recycle']."'>\n";
					echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000'>\n";
					echo "Picture: <INPUT TYPE = 'file' NAME = 'picture' ID = 'picture'><BR>\n";
					echo "<INPUT TYPE = 'submit' NAME = 'submit_picture' ID = 'submit_picture' VALUE = 'submit'><BR>\n";
					echo "</FORM>\n";
				}
				$SQL = "UPDATE lego.\"user\" SET regenerate_bricklink = TRUE WHERE id = ".$_COOKIE['user'];
				pg_query($db, $SQL);
			}
			echo "<FORM ACTION = 'index.php?what=recyclepiece&amp;step=2' METHOD = 'POST'>\n";
			echo "Description: <INPUT TYPE = 'text' NAME = 'description' ID = 'design'>\n";
			echo "<INPUT TYPE = 'submit' NAME = 'submit_description' ID = 'submit_description' VALUE = 'search'><BR>\n";
			echo "</FORM>\n";
		}
}
?>