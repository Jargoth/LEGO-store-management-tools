<?php
function list_model()
{
	include "../www/db.php";
	if (!$_GET['cat'])
	{
		$cate = 0;
	}
	else
	{
		$cate = $_GET['cat'];
	}
	$SQL = "SELECT title, id FROM lego.modelcat WHERE parent = ".$cate." ORDER BY title";
	$cats = pg_fetch_all(pg_query($db, $SQL));
	if ($cats)
	{
		foreach ($cats as $cat)
		{
			echo "<A HREF = 'index.php?what=list_model&amp;cat=".$cat['id']."'>".$cat['title']."</A><BR>\n";
		}
	}
	if ($cate == 0)
	{
		if ($_GET['my'])
		{
			$SQL = "SELECT m.id, m.title, max(ms.step) as step FROM lego.model m FULL JOIN lego.model_step ms ON m.id = ms.model WHERE m.id NOT IN (SELECT model FROM lego.model_modelcat) AND m.submodel = 0 AND creator = ".$_COOKIE['user']." GROUP BY m.id, m.title ORDER BY m.title, max(ms.step)";
		}
		else
		{
			$SQL = "SELECT m.id, m.title, max(ms.step) as step FROM lego.model m FULL JOIN lego.model_step ms ON m.id = ms.model WHERE m.id NOT IN (SELECT model FROM lego.model_modelcat) AND m.submodel = 0 GROUP BY m.id, m.title ORDER BY m.title, max(ms.step)";
		// $SQL = "SELECT m.id, m.title, max(ms.step) as step FROM lego.model m FULL JOIN lego.model_step ms ON m.id = ms.model WHERE m.submodel = 0 GROUP BY m.id, m.title ORDER BY m.title, max(ms.step)";
		}
	}
	else
	{
		if ($_GET['my'])
		{
			$SQL = "SELECT m.id, m.title, max(ms.step) as step FROM lego.model m FULL JOIN lego.model_step ms ON m.id = ms.model WHERE m.id IN (SELECT model FROM lego.model_modelcat WHERE modelcat = ".$cate.") AND m.submodel = 0 AND creator = ".$_COOKIE['user']." GROUP BY m.id, m.title ORDER BY m.title, max(ms.step)";
		}
		else
		{
			$SQL = "SELECT m.id, m.title, max(ms.step) as step FROM lego.model m FULL JOIN lego.model_step ms ON m.id = ms.model WHERE m.id IN (SELECT model FROM lego.model_modelcat WHERE modelcat = ".$cate.") AND m.submodel = 0 GROUP BY m.id, m.title ORDER BY m.title, max(ms.step)";
		}
	}
	$models = pg_fetch_all(pg_query($db, $SQL));
	if ($models)
	{
		foreach ($models as $model)
		{
			echo "<A HREF = 'index.php?what=model&amp;id=".$model['id']."'>".$model['title']."</A><IMG SRC = 'picture.php?model=1&size=55&id=".$model['id']."&amp;step=".$model['step']."'><BR>\n";
		}
	}
}
?>