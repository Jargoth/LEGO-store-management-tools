<?php
function model()
{
	include "../www/db.php";
	if ($_GET['action'] == 'startproject')
	{
		//Hämta info om aktuell modell
		$SQL = "SELECT * FROM lego.model WHERE id = ".$_GET['id'];
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
		$SQL = "SELECT submodel, count(submodel), model FROM lego.model_submodel WHERE model = ".$_GET['id']." OR model IN (SELECT id FROM lego.model WHERE submodel = ".$_GET['id'].") GROUP BY submodel, model ORDER BY submodel, model"; 
		$res = pg_fetch_all(pg_query($db, $SQL));
		if ($res)
		{
			foreach ($res as $re)
			{
				if ($re['model'] == $_GET['id'])
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
		$SQL = "SELECT mb.model, c.\"Name\", c.id AS color, d.description, fdc.design, count(mb.model), fdc.color AS defcol FROM lego.model_bricks mb, lego.color_ldraw cl, lego.color c, lego.filename_design_color fdc, lego.design d WHERE (mb.model = ".$_GET['id']." OR mb.model IN (SELECT id FROM lego.model WHERE submodel = ".$_GET['id'].")) AND cl.ldraw = mb.val2 AND c.id = cl.color AND fdc.filename = mb.val15 AND d.id = fdc.design AND d.primitive IS FALSE GROUP BY mb.model, c.\"Name\", c.id, fdc.design, d.description, fdc.color ORDER BY d.description, fdc.design, c.\"Name\", c.id";
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
				if ($re['model'] == $_GET['id'])
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
	}
	$SQL = "SELECT submodel, count(submodel), model FROM lego.model_submodel WHERE model = ".$_GET['id']." OR model IN (SELECT id FROM lego.model WHERE submodel = ".$_GET['id'].") GROUP BY submodel, model ORDER BY submodel, model"; 
	$res = pg_fetch_all(pg_query($db, $SQL));
	if ($res)
	{
		foreach ($res as $re)
		{
			if ($re['model'] == $_GET['id'])
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
	$SQL = "SELECT mb.model, c.\"Name\", c.id AS color, d.description, fdc.design, count(mb.model), fdc.color AS defcol FROM lego.model_bricks mb, lego.color_ldraw cl, lego.color c, lego.filename_design_color fdc, lego.design d WHERE (mb.model = ".$_GET['id']." OR mb.model IN (SELECT id FROM lego.model WHERE submodel = ".$_GET['id'].")) AND cl.ldraw = mb.val2 AND c.id = cl.color AND fdc.filename = mb.val15 AND d.id = fdc.design AND d.primitive IS FALSE GROUP BY mb.model, c.\"Name\", c.id, fdc.design, d.description, fdc.color ORDER BY d.description, fdc.design, c.\"Name\", c.id";
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
			if ($re['model'] == $_GET['id'])
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
	$SQL = "SELECT step FROM lego.model_step WHERE model = ".$_GET['id']." ORDER BY step DESC LIMIT 1";
	$bricks2 = pg_fetch_all(pg_query($db, $SQL));
	echo "<IMG SRC = 'picture.php?model=1&size=400&id=".$_GET['id']."&step=".$bricks2[0]['step']."'><BR><BR>\n";
	echo "<A HREF = 'index.php?what=modelfile&amp;id=".$_GET['id']."&amp;step=".$bricks2[0]['step']."'>Download model</A><BR>\n";
	echo "<A HREF = 'index.php?what=build&amp;model=".$_GET['id']."&amp;step=1'>Building instructions</A><BR>\n";
	echo "<p><A HREF = 'index.php?what=model&amp;id=".$_GET['id']."&amp;action=startproject'>Start new project</a></p>";
	$SQL = "SELECT p.order FROM lego.project p, lego.project_model pm WHERE p.order = pm.project AND pm.model = ".$_GET['id']." AND p.\"user\" = ".$_COOKIE['user'];
	$projects = pg_fetch_all(pg_query($db, $SQL));
	if ($projects)
	{
		foreach($projects as $project)
		{
			echo "<p><A HREF = 'index.php?what=project&amp;project=".$project['order']."'>";
			$SQL = "SELECT sum(needed) as needed, sum(collected) as collected FROM lego.project_bricks WHERE project = ".$project['order']." GROUP BY project ORDER BY project";
			$res = pg_fetch_all(pg_query($db, $SQL));
			if ($res)
			{
				$needed = $res[0]['needed'];
				$collected = $res[0]['collected'];
			}
			else
			{
				$needed = 0;
				$collected = 0;
			}
			echo $collected." / ".$needed." bricks collected.</a></p>";
		}
	}
	echo "<h4>Missing</h4>\n";
	echo "<TABLE BORDER = 1>\n";
	echo "<TR>\n";
	echo "<TD>Brick</TD>\n";
	echo "<TD>Needed</TD>\n";
	echo "<TD>Free</TD>";
	echo "<TD>Total</TD>";
	echo "</TR>\n";
	$count = 0;
	foreach ($bricks as $brick)
	{
		if ($brick['design'] == $brick2['design'] and $brick['color'] == $brick2['color'])
		{
			$count = $count + $brick['count'];
		}
		else
		{
			$count = $count + $brick['count'];
			$SQL = "SELECT free, (free + used) AS total FROM lego.design_color_user WHERE \"user\" = ".$_COOKIE['user']." AND design = ".$brick['design']." AND color = ".$brick['color'];
			$ans = pg_fetch_all(pg_query($db, $SQL));
			$SQL = "SELECT sum(pb.collected) FROM lego.project_bricks pb, lego.project p WHERE p.order = pb.project AND p.\"user\" = ".$_COOKIE['user']." AND pb.design = ".$brick['design']." AND pb.color = ".$brick['color'];
			$ans2 = pg_fetch_all(pg_query($db, $SQL));
			if (!$ans)
			{
				$ans[0]['free'] = 0;
				$ans[0]['total'] = 0;
			}
			if ($ans2)
			{
				$ans[0]['free'] = $ans[0]['free'] - $ans2[0]['sum'];
			}
			if ($count > $ans[0]['free'])
			{
				echo "<TR><TD>".$brick['description']." (".$brick['Name'].")<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$brick['design']."&amp;color=".$brick['color']."'></TD><TD>".$count."</TD><TD>".$ans[0]['free']."</TD><TD>".$ans[0]['total']."</TD</TR>\n";
				$err = 1;
			}
			$count = 0;
		}
		$brick2 = $brick;
	}
	echo "</TABLE>\n";
	if (!$err)
	{
		echo "You can build this model.\n";
	}
}
?>