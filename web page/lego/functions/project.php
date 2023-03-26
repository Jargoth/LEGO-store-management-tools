<?php
function project()
{
	include "../www/db.php";
	include "warning.php";
	if ($_POST['collect'] == 'collect')
	{
		$SQL = "SELECT collected FROM lego.project_bricks WHERE project = ".$_GET['id']." AND design = ".$_POST['design']." AND color = ".$_POST['color'];
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			$SQL = "UPDATE lego.project_bricks SET collected = ".($_POST['data'] + $ans[0]['collected'])." WHERE project = ".$_GET['id']." AND design = ".$_POST['design']." AND color = ".$_POST['color'];
			pg_query($db, $SQL);
		}
		else
		{
			$SQL = "INSERT INTO lego.project_bricks (project, design, color, collected) VALUES (".$_GET['id'].", ".$_POST['design'].", ".$_POST['color'].", ".$_POST['data'].")";
			pg_query($db, $SQL);
		}
		if ($_POST['generic_color'])
		{
			$SQL = "SELECT collected FROM lego.project_bricks_container WHERE project = ".$_GET['id']." AND design = ".$_POST['design']." AND color = ".$_POST['generic_color']." AND container = '".$_POST['container']."'";
			$ans = pg_fetch_all(pg_query($db, $SQL));
			if ($ans)
			{
				$SQL = "UPDATE lego.project_bricks_container SET collected = ".($_POST['data'] + $ans[0]['collected'])." WHERE project = ".$_GET['id']." AND design = ".$_POST['design']." AND color = ".$_POST['generic_color']." AND container = '".$_POST['container']."'";
				pg_query($db, $SQL);
			}
			else
			{
				$SQL = "INSERT INTO lego.project_bricks_container (project, design, color, collected, container) VALUES (".$_GET['id'].", ".$_POST['design'].", ".$_POST['generic_color'].", ".$_POST['data'].", '".$_POST['container']."')";
				pg_query($db, $SQL);
			}
		}
		else
		{
			$SQL = "SELECT collected FROM lego.project_bricks_container WHERE project = ".$_GET['id']." AND design = ".$_POST['design']." AND color = ".$_POST['color']." AND container = '".$_POST['container']."'";
			$ans = pg_fetch_all(pg_query($db, $SQL));
			if ($ans)
			{
				$SQL = "UPDATE lego.project_bricks_container SET collected = ".($_POST['data'] + $ans[0]['collected'])." WHERE project = ".$_GET['id']." AND design = ".$_POST['design']." AND color = ".$_POST['color']." AND container = '".$_POST['container']."'";
				pg_query($db, $SQL);
			}
			else
			{
				$SQL = "INSERT INTO lego.project_bricks_container (project, design, color, collected, container) VALUES (".$_GET['id'].", ".$_POST['design'].", ".$_POST['color'].", ".$_POST['data'].", '".$_POST['container']."')";
				pg_query($db, $SQL);
			}
		}
		$SQL = "UPDATE lego.project SET regenerate_project_picture = TRUE WHERE \"order\" = ".$_GET['id'];
		pg_query($db, $SQL);
	}
	if ($_POST['retake'] == 'retake')
	{
		$SQL = "SELECT collected FROM lego.project_bricks WHERE project = ".$_GET['id']." AND design = ".$_POST['design']." AND color = ".$_POST['color'];
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			$SQL = "UPDATE lego.project_bricks SET collected = ".($ans[0]['collected'] - $_POST['data'])." WHERE project = ".$_GET['id']." AND design = ".$_POST['design']." AND color = ".$_POST['color'];
			pg_query($db, $SQL);
		}
		else
		{
			$SQL = "INSERT INTO lego.project_bricks (project, design, color, collected) VALUES (".$_GET['id'].", ".$_POST['design'].", ".$_POST['color'].", ".$_POST['data'].")";
			pg_query($db, $SQL);
		}
	}
	if($_GET['action'] == 'collect_bricks')
	{
		$SQL = "SELECT pb.design, pb.color, pb.needed, pb.collected, d.description, c.\"Name\", dcuc.container ".
				"FROM lego.design d, lego.color c, lego.project_bricks pb ".
				"LEFT OUTER JOIN lego.design_color_user_container dcuc ON dcuc.design = pb.design AND dcuc.color = pb.color ".
				"WHERE (dcuc.\"user\" = ".$_COOKIE['user']." OR dcuc.\"user\" IS NULL) ".
				"AND pb.design = d.id ".
				"AND pb.color = c.id ".
				"AND pb.color <> 0 ".
				"AND pb.project = ".$_GET['id']." ".
				"ORDER BY dcuc.container, d.description, c.\"Name\"";
		$bricks = pg_fetch_all(pg_query($db, $SQL));
		if ($bricks)
		{
			foreach ($bricks as $brick)
			{
				if ($brick['collected'] < $brick['needed'])
				{
					echo $brick['description']." (".$brick['Name'].") (".$brick['container'].")";
					echo "<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$brick['design']."&amp;color=".$brick['color']."'>";
					echo $brick['collected'];
					echo " / ".$brick['needed'];
					$SQL = "SELECT bricks FROM lego.design_color_user_container WHERE design = ".$brick['design']." AND color = ".$brick['color']." AND \"user\" = ".$_COOKIE['user']." AND container = '".$brick['container']."'";
					$free = pg_fetch_all(pg_query($db, $SQL));
					echo " free: ";
					if ($free)
					{
						$SQL = "SELECT sum(pb.collected) FROM lego.project_bricks_container pb, lego.project p WHERE p.\"order\" = pb.project AND p.\"user\" = ".$_COOKIE['user']." AND pb.design = ".$brick['design']." AND pb.color = ".$brick['color']." AND container = '".$brick['container']."'";
						$ans = pg_fetch_all(pg_query($db, $SQL));
						if ($ans)
						{
							echo $free[0]['bricks'] - $ans[0]['sum'];
						}
						else
						{
							echo $free[0]['bricks'];
						}
					}
					else
					{
						echo '0';
					}
					echo "<FORM ACTION = 'index.php?what=project&amp;id=".$_GET['id']."&amp;action=collect_bricks' METHOD = 'POST'>";
					echo "<INPUT TYPE = 'text' NAME = 'data' ID = 'data'>";
					echo "<INPUT TYPE = 'submit' NAME = 'collect' ID = 'collect' VALUE = 'collect'>";
					echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$brick['design']."'>";
					echo "<INPUT TYPE = 'hidden' NAME = 'color' ID = 'color' VALUE = '".$brick['color']."'>";
					echo "<INPUT TYPE = 'hidden' NAME = 'container' ID = 'container' VALUE = '".$brick['container']."'>";
					echo "</FORM>";
					echo "<br>";
				}
			}
		}
		$SQL = "SELECT pb.design, pb.color, pb.needed, pb.collected, d.description, c.\"Name\", dcuc.container ".
				"FROM lego.design d, lego.color c, lego.project_bricks pb ".
				"LEFT OUTER JOIN lego.design_color_user_container dcuc ON dcuc.design = pb.design AND dcuc.color = pb.color ".
				"WHERE (dcuc.\"user\" = ".$_COOKIE['user']." OR dcuc.\"user\" IS NULL) ".
				"AND pb.design = d.id ".
				"AND pb.color = c.id ".
				"AND pb.color = 0 ".
				"AND pb.project = ".$_GET['id']." ".
				"ORDER BY dcuc.container, d.description, c.\"Name\"";
		$bricks = pg_fetch_all(pg_query($db, $SQL));
		foreach ($bricks as $brick)
		{
			if ($brick['collected'] < $brick['needed'])
			{
				echo $brick['description']." (".$brick['Name'].")";
				echo "<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$brick['design']."'>";
				echo $brick['collected'];
				echo " / ".$brick['needed'];
				echo "<BR>";
				$SQL = "SELECT dcuc.color, dcuc.container, dcuc.bricks, (SELECT sum(collected) FROM lego.project_bricks_container pbc WHERE pbc.design = dcuc.design AND pbc.color = dcuc.color AND dcuc.container = pbc.container GROUP BY pbc.design, pbc.color, pbc.container) AS project, c.\"Name\" ".
						"FROM lego.design_color_user_container dcuc, lego.color c ".
						"WHERE dcuc.design = ".$brick['design']." ".
						"AND dcuc.\"user\" = 1 ".
						"AND c.id = dcuc.color ".
						"AND bricks > 0 ".
						"ORDER BY bricks DESC";
				$generics = pg_fetch_all(pg_query($db, $SQL));
				if ($generics)
				{
					foreach ($generics as $generic)
					{
						if (($generic['bricks']-$generic['project']) > 0)
						{
							echo $generic['Name']." (".$generic['container'].") free: ".($generic['bricks']-$generic['project'])."<BR>";
							echo "<FORM ACTION = 'index.php?what=project&amp;id=".$_GET['id']."&amp;action=collect_bricks' METHOD = 'POST'>";
							echo "<INPUT TYPE = 'text' NAME = 'data' ID = 'data'>";
							echo "<INPUT TYPE = 'submit' NAME = 'collect' ID = 'collect' VALUE = 'collect'>";
							echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$brick['design']."'>";
							echo "<INPUT TYPE = 'hidden' NAME = 'color' ID = 'color' VALUE = '".$brick['color']."'>";
							echo "<INPUT TYPE = 'hidden' NAME = 'generic_color' ID = 'generic_color' VALUE = '".$generic['color']."'>";
							echo "<INPUT TYPE = 'hidden' NAME = 'container' ID = 'container' VALUE = '".$generic['container']."'>";
							echo "</FORM>";
							echo "<br>";
						}
					}
				}
				echo "<BR><BR>";
			}
		}
	}
	elseif($_GET['action'] == 'end_project')
	{
		echo "This will end the project. What do you want to do with the collected bricks?<BR><BR>";
		echo "<A HREF = 'index.php?what=project&amp;id=".$_GET['id']."&amp;action=end_project_used'>Put the bricks in the used pile.</A>\n";		
		echo "<BR>Put the bricks in the free pile. (not available)\n";				
		echo "<BR>Put the bricks in a new project. (not available)\n";		
	}
	elseif($_GET['action'] == 'end_project_used')
	{
		$SQL = "SELECT design, color, collected, container FROM lego.project_bricks_container WHERE project = ".$_GET['id'];
		$bricks = pg_fetch_all(pg_query($db, $SQL));
		foreach ($bricks as $brick)
		{
			$SQL = "UPDATE lego.design_color_user SET free = (free - ".$brick['collected']."), used = used + ".$brick['collected']." WHERE design = ".$brick['design']." AND color = ".$brick['color']." AND \"user\" = ".$_COOKIE['user'];
			pg_query($db, $SQL);
			$SQL = "UPDATE lego.design_color_user_container SET bricks = (bricks - ".$brick['collected'].") WHERE design = ".$brick['design']." AND color = ".$brick['color']." AND container = '".$brick['container']."' AND \"user\" = ".$_COOKIE['user'];
			pg_query($db, $SQL);
			$SQL = "DELETE FROM lego.project_bricks_container WHERE design = ".$brick['design']." AND color = ".$brick['color']." AND container = '".$brick['container']."' AND project = ".$_GET['id'];
			pg_query($db, $SQL);
		}
		$SQL = "DELETE FROM lego.project_bricks WHERE project = ".$_GET['id'];
		pg_query($db, $SQL);
		$SQL = "DELETE FROM lego.project_model_bricks WHERE project = ".$_GET['id'];
		pg_query($db, $SQL);
		$SQL = "DELETE FROM lego.project_model WHERE project = ".$_GET['id'];
		pg_query($db, $SQL);
		$SQL = "DELETE FROM lego.project WHERE \"order\" = ".$_GET['id'];
		pg_query($db, $SQL);
	}
	elseif($_GET['action'] == 'end_project_free')
	{
		$SQL = "DELETE FROM lego.project_bricks WHERE project = ".$_GET['id'];
		pg_query($db, $SQL);
		$SQL = "DELETE FROM lego.project_model WHERE project = ".$_GET['id'];
		pg_query($db, $SQL);
		$SQL = "DELETE FROM lego.project WHERE \"order\" = ".$_GET['id'];
		pg_query($db, $SQL);
	}
	elseif($_GET['action'] == 'end_project_new')
	{
		if ($_POST['model'])
		{
			//Hämta info om aktuell modell
			$SQL = "SELECT * FROM lego.model WHERE id = ".$_POST['model'];
			$model = pg_fetch_all(pg_query($db, $SQL));
			$model = $model[0];
		
			//Skapa projektet
			$SQL = "INSERT INTO lego.project (\"name\", \"user\") VALUES ('".$model['title']."', ".$_COOKIE['user'].")";
			pg_query($db, $SQL);
		
			//hämta id-numret för projektet som precis skapats
			$SQL = "SELECT currval('lego.project_order_seq')";
			$project_id = pg_fetch_all(pg_query($db, $SQL));
			$project_id = $project_id[0]['currval'];
		
			//länka modellen till projektet
			$SQL = "INSERT INTO lego.project_model (project, model) VALUES (".$project_id.", ".$model['id'].")";
			pg_query($db, $SQL);
		
			//lägga till modellens klossar till projektet som needed
			$SQL = "SELECT submodel, count(submodel), model FROM lego.model_submodel WHERE model = ".$_POST['model']." OR model IN (SELECT id FROM lego.model WHERE submodel = ".$_POST['model'].") GROUP BY submodel, model ORDER BY submodel, model"; 
			$res = pg_fetch_all(pg_query($db, $SQL));
			if ($res)
			{
				foreach ($res as $re)
				{
					if ($re['model'] == $_POST['model'])
					{
						if (isset($submodels[$re['submodel']]))
						{
							$submodels[$re['submodel']]['count'] = $submodels[$re['submodel']]['count'] + $re['count'];
						}
						else
						{
							$submodels[$re['submodel']] = $re;
						}
					}
					else
					{
						if (isset($submodels[$re['submodel']]))
						{
							if (isset($submodels[$re['model']]))
							{
								$submodels[$re['submodel']]['count'] = $submodels[$re['submodel']]['count'] + $submodels[$re['model']]['count']*$re['count'];
							}
							else
							{
								$rest[$re['submodel'].",".$re['model']] = $re;
							}
						}
						else
						{
							if (isset($submodels[$re['model']]))
							{
								$submodels[$re['submodel']] = $re;
								$submodels[$re['submodel']]['count'] = $submodels[$re['model']]['count']*$re['count'];
							}
							else
							{
								$rest[$re['submodel'].",".$re['model']] = $re;
							}
						}
					}
				}
				while ($rest)
				{
					foreach ($rest as $r)
					{
						if (isset($submodels[$r['submodel']]))
						{
							if (isset($submodels[$r['model']]))
							{
								$submodels[$r['submodel']]['count'] = $submodels[$r['submodel']]['count'] + $submodels[$r['model']]['count']*$r['count'];
								unset($rest[$r['submodel'].",".$r['model']]);
							}
						}
						else
						{
							if (isset($submodels[$r['model']]))
							{
								$submodels[$r['submodel']] = $r;
								$submodels[$r['submodel']]['count'] = $submodels[$r['model']]['count']*$r['count'];
								unset($rest[$r['submodel'].",".$r['model']]);
							}
						}
					}
				}
			}
			$SQL = "SELECT mb.model, c.\"Name\", c.id AS color, d.description, fdc.design, count(mb.model), fdc.color AS defcol FROM lego.model_bricks mb, lego.color_ldraw cl, lego.color c, lego.filename_design_color fdc, lego.design d WHERE (mb.model = ".$_POST['model']." OR mb.model IN (SELECT id FROM lego.model WHERE submodel = ".$_POST['model'].")) AND cl.ldraw = mb.val2 AND c.id = cl.color AND fdc.filename = mb.val15 AND d.id = fdc.design AND d.primitive IS FALSE GROUP BY mb.model, c.\"Name\", c.id, fdc.design, d.description, fdc.color ORDER BY d.description, fdc.design, c.\"Name\", c.id";
			$res = pg_fetch_all(pg_query($db, $SQL));
			if ($res)
			{
				foreach ($res as $re)
				{
					if (!$re['defcol'] == 0) //om det finns en specifik färg för den specifika ldraw-filen
					{
						$SQL = "SELECT \"Name\" FROM lego.color WHERE id = ".$re['defcol'];
						$temp = pg_fetch_all(pg_query($db, $SQL));
						$re['color'] = $re['defcol'];
						$re['Name'] = $temp[0]['Name'];
					}
					if ($re['model'] == $_POST['model'])
					{
						if (isset($bricks[$re['design'].",".$re['color']]))
						{
							$bricks[$re['design'].",".$re['color']]['count'] = $bricks[$re['design'].",".$re['color']]['count'] + $re['count'];
						}
						else
						{
							$bricks[$re['design'].",".$re['color']] = $re;
						}
					}
					else
					{
						if (isset($bricks[$re['design'].",".$re['color']]))
						{
							$bricks[$re['design'].",".$re['color']]['count'] = $bricks[$re['design'].",".$re['color']]['count'] + $submodels[$re['model']]['count']*$re['count'];
						}
						else
						{
							$bricks[$re['design'].",".$re['color']] = $re;
							$bricks[$re['design'].",".$re['color']]['count'] = $submodels[$re['model']]['count']*$re['count'];
						}
					}
				}
			}
			foreach ($bricks as $brick)
			{
				if ($brick['design'] == $brick2['design'] and $brick['color'] == $brick2['color'])
				{
					$count = $count + $brick['count'];
				}
				else
				{
					$count = $count + $brick['count'];
					$SQL = "INSERT INTO lego.project_bricks (project, design, color, needed) VALUES (".$project_id.", ".$brick['design'].", ".$brick['color'].", ".$count.")";
					pg_query($db, $SQL);
					$count = 0;
				}
				$brick2 = $brick;
			}
			$SQL = "SELECT design, color, collected FROM lego.project_bricks WHERE project = ".$_GET['id']. " AND collected > 0";
			$olds = pg_fetch_all(pg_query($db, $SQL));
			if ($olds)
			{
				foreach ($olds as $old)
				{
					$SQL = "SELECT collected FROM lego.project_bricks WHERE design = ".$old['design']." AND color = ".$old['color']." AND project = ".$project_id;
					$new = pg_fetch_all(pg_query($db, $SQL));
					if ($new)
					{
						$SQL = "UPDATE lego.project_bricks SET collected = ".($old['collected']+$new['collected'])." WHERE design = ".$old['design']." AND color = ".$old['color']." AND project = ".$project_id;
						pg_query($db, $SQL);
					}
					else
					{
						$SQL = "INSERT INTO lego.project_bricks (project, design, color, collected, needed) VALUES (".$project_id.", ".$old['design'].", ".$old['color'].", ".$old['collected'].", 0)";
						pg_query($db, $SQL);
					}
				}
			}
			$SQL = "DELETE FROM lego.project_bricks WHERE project = ".$_GET['id'];
			pg_query($db, $SQL);
			$SQL = "DELETE FROM lego.project_model WHERE project = ".$_GET['id'];
			pg_query($db, $SQL);
			$SQL = "DELETE FROM lego.project WHERE \"order\" = ".$_GET['id'];
			pg_query($db, $SQL);
		}
		else
		{
			echo "Select a model for the new project.<BR>";
			$SQL = "SELECT title, id FROM lego.model WHERE submodel = 0 ORDER BY title";
			$models = pg_fetch_all(pg_query($db, $SQL));
			echo "<FORM ACTION = 'index.php?what=project&amp;id=".$_GET['id']."&amp;action=end_project_new' METHOD = 'POST'>\n";
			echo "<SELECT NAME = 'model' ID = 'model' SIZE = '1'>\n";
			foreach ($models as $model)
			{
				echo "<OPTION VALUE = '".$model['id']."'>".$model['title']."\n";
			}
			echo "</SELECT>\n";
			echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'submit'>\n";
			echo "</FORM>\n";
		}
	}
	elseif($_GET['action'] == 'too_much')
	{
		$SQL = "SELECT pb.design, pb.color, pb.needed, pb.collected, d.description, c.\"Name\", dcuc.container FROM lego.project_bricks pb, lego.design d, lego.color c, lego.design_color_user_container dcuc WHERE dcuc.design = pb.design AND dcuc.color = pb.color AND dcuc.\"user\" = ".$_COOKIE['user']." AND pb.design = d.id AND pb.color = c.id AND pb.project = ".$_GET['id']." ORDER BY dcuc.container, d.description, c.\"Name\"";
		$bricks = pg_fetch_all(pg_query($db, $SQL));
		foreach ($bricks as $brick)
		{
			if ($brick['collected'] > $brick['needed'])
			{
				echo $brick['description']." (".$brick['Name'].") (".$brick['container'].")";
				echo "<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$brick['design']."&amp;color=".$brick['color']."'>";
				echo $brick['collected'];
				echo " / ".$brick['needed'];
				$SQL = "SELECT free FROM lego.design_color_user WHERE design = ".$brick['design']." AND color = ".$brick['color']." AND \"user\" = ".$_COOKIE['user'];
				$free = pg_fetch_all(pg_query($db, $SQL));
				echo "<FORM ACTION = 'index.php?what=project&amp;id=".$_GET['id']."&amp;action=too_much' METHOD = 'POST'>";
				echo "<INPUT TYPE = 'text' NAME = 'data' ID = 'data'>";
				echo "<INPUT TYPE = 'submit' NAME = 'retake' ID = 'retake' VALUE = 'retake'>";
				echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$brick['design']."'>";
				echo "<INPUT TYPE = 'hidden' NAME = 'color' ID = 'design' VALUE = '".$brick['color']."'>";
				echo "</FORM>";
				echo "<br>";
			}
		}
	}
	else
	{
		$SQL = "SELECT \"name\" FROM lego.project WHERE \"order\" = ".$_GET['id'];
		$res = pg_fetch_all(pg_query($db, $SQL));
		$project_title = $res[0]['name'];
		echo "<H2>".$project_title."</H2>\n";
		echo "<img src=\"picture.php?model=1&amp;id=".$_GET['id']."&amp;project=1\"></BR>";
		echo "<A HREF = 'index.php?what=project&amp;id=".$_GET['id']."&amp;action=collect_bricks'>Collect bricks</A><BR>\n";		
		echo "<A HREF = 'index.php?what=project&amp;id=".$_GET['id']."&amp;action=end_project'>End project</A>\n";		
		$SQL = "SELECT project FROM lego.project_bricks WHERE collected > needed AND project = ".$_GET['id'];
		$res = pg_num_rows(pg_query($db, $SQL));
		if ($res)
		{
			echo "<A HREF = 'index.php?what=project&amp;id=".$_GET['id']."&amp;action=too_much'>";
			warning("Collected to much of $res bricks.");
			echo "</A>\n";
		}
	}
}
?>