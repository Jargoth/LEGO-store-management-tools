<?php
function modelfile()
{
	include "../www/db.php";
	$SQL = "SELECT filename FROM lego.model WHERE id = ".$_GET['id'];
	$header = pg_fetch_all(pg_query($db, $SQL));
	if (!isset($_GET['submodel1']))
	{
		if ($_GET['step'])
		{
			$SQL = "SELECT * FROM lego.model_bricks WHERE (model = ".$_GET['id']." AND step <= ".$_GET['step'].") OR model IN (SELECT id FROM lego.model WHERE submodel = ".$_GET['id'].") OR model IN (SELECT submodel FROM lego.model_submodel WHERE model = (SELECT submodel FROM lego.model WHERE id = ".$_GET['id'].") AND submodel <> ".$_GET['id'].")";
		}
		else
		{
			$SQL = "SELECT * FROM lego.model_bricks WHERE (model = ".$_GET['id'].") OR model IN (SELECT id FROM lego.model WHERE submodel = ".$_GET['id'].") OR model IN (SELECT submodel FROM lego.model_submodel WHERE model = (SELECT submodel FROM lego.model WHERE id = ".$_GET['id'].") AND submodel <> ".$_GET['id'].")";
		}
		if ($_GET['project'])
		{
			$SQL = "SELECT * FROM lego.project_model_bricks WHERE project = ".$_GET['id']." AND used IS TRUE";
		}
		$models = pg_fetch_all(pg_query($db, $SQL));
	}
	else
	{
		$SQL = "SELECT * FROM lego.model_bricks WHERE (model = ".$_GET['id']." AND step <= ".$_GET['step'].")";
		$models = pg_fetch_all(pg_query($db, $SQL));
		$ok = 1;
		$i = 1;
		while ($ok)
		{
			if (isset($_GET['submodel'.$i]))
			{
				if (isset($_GET['substep'.$i]))
				{
					$SQL = "SELECT * FROM lego.model_bricks WHERE (model = ".$_GET['submodel'.$i]." AND step <= ".$_GET['substep'.$i].")";
				}
				else
				{
					$SQL = "SELECT * FROM lego.model_bricks WHERE model = ".$_GET['submodel'.$i];
				}
				$submodels = pg_fetch_all(pg_query($db, $SQL));
				foreach ($submodels as $submodel)
				{
					$bricks[$submodel['row']] = $submodel;
				}
			}
			else
			{
				$ok = 0;
			}
			$i++;
		}
	}
	if (!isset($_GET['project']))
	{
		$SQL = "SELECT * FROM lego.model_header WHERE model = ".$_GET['id']." OR model IN (SELECT id FROM lego.model WHERE submodel = ".$_GET['id'].") OR model IN (SELECT submodel FROM lego.model_submodel WHERE model = (SELECT submodel FROM lego.model WHERE id = ".$_GET['id'].") AND submodel <> ".$_GET['id'].")";
		$heads = pg_fetch_all(pg_query($db, $SQL));
		if ($_GET['step'])
		{
			$SQL = "SELECT * FROM lego.model_submodel WHERE (model = ".$_GET['id']." AND step <= ".$_GET['step'].") OR model IN (SELECT id FROM lego.model WHERE submodel = ".$_GET['id'].")";
		}
		else
		{
			$SQL = "SELECT * FROM lego.model_submodel WHERE (model = ".$_GET['id'].") OR model IN (SELECT id FROM lego.model WHERE submodel = ".$_GET['id'].")";
		}
		$subs = pg_fetch_all(pg_query($db, $SQL));
	}
	foreach ($models as $model)
	{
		if ($model['model'] == $_GET['id'])
		{
			$bricksfirst[$model['row']] = $model;
		}
		else
		{
			$bricks[$model['row']] = $model;
		}
	}
	if (!isset($_GET['project']))
	{
		foreach ($heads as $head)
		{
			$head['val2'] = utf8_decode($head['val2']);
			$head['val3'] = utf8_decode($head['val3']);
			$head['val4'] = utf8_decode($head['val4']);
			$head['val5'] = utf8_decode($head['val5']);
			$head['val6'] = utf8_decode($head['val6']);
			$head['val7'] = utf8_decode($head['val7']);
			$head['val8'] = utf8_decode($head['val8']);
			$head['val9'] = utf8_decode($head['val9']);
			$head['val10'] = utf8_decode($head['val10']);
			$head['val11'] = utf8_decode($head['val11']);
			$head['val12'] = utf8_decode($head['val12']);
			$head['val13'] = utf8_decode($head['val13']);
			$head['val14'] = utf8_decode($head['val14']);
			$head['val15'] = utf8_decode($head['val15']);
			if ($head['model'] == $_GET['id'])
			{
				$bricksfirst[$head['row']] = $head;
			}
			else
			{
				$bricks[$head['row']] = $head;
			}
		}
	}
	if ($subs)
	{
		foreach ($subs as $sub)
		{
			if ($sub['model'] == $_GET['id'])
			{
				$bricksfirst[$sub['row']] = $sub;
			}
			else
			{
				$bricks[$sub['row']] = $sub;
			}
		}
	}
	
	if (!isset($_GET['project']))
	{
		header("Content-Disposition: attachment; filename=".$header[0]['filename']);
	}
	else
	{
		header("Content-Disposition: attachment; filename=model.ldr");
	}
	ksort($bricksfirst);
	foreach ($bricksfirst as $brick)
	{
		echo $brick['val1']." ".$brick['val2']." ".$brick['val3']." ".$brick['val4']." ".$brick['val5']." ".$brick['val6']." ".$brick['val7']." ".$brick['val8']." ".$brick['val9']." ".$brick['val10']." ".$brick['val11']." ".$brick['val12']." ".$brick['val13']." ".$brick['val14']." ".$brick['val15']."\n";
	}
	ksort($bricks);
	foreach ($bricks as $brick)
	{
		echo $brick['val1']." ".$brick['val2']." ".$brick['val3']." ".$brick['val4']." ".$brick['val5']." ".$brick['val6']." ".$brick['val7']." ".$brick['val8']." ".$brick['val9']." ".$brick['val10']." ".$brick['val11']." ".$brick['val12']." ".$brick['val13']." ".$brick['val14']." ".$brick['val15']."\n";
	}
	echo "0";
}
?>