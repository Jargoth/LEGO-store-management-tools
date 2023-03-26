<?php
function build()
{
	include "../www/db.php";
	$gets = array_keys($_GET);
	krsort($gets);
	$submodel = 0;
	foreach ($gets as $get)
	{
		if (substr($get, 0, 8) == 'submodel' and $submodel < substr($get, 8))
		{
			$submodel = substr($get, 8);
		}
	}
	$SQL = "SELECT title FROM lego.model WHERE id = ".$_GET['model'];
	$model = pg_fetch_all(pg_query($db, $SQL));
	echo "<H4>".$model[0]['title']." - Step# ".$_GET['step']."</H4><BR>\n";
	echo "<TABLE><TR>\n";
	if (!$submodel)
	{
		$SQL = "SELECT submodel FROM lego.model_submodel WHERE model = ".$_GET['model']." AND step = ".(string)(((int)$_GET['step']) + 1);
	}
	else
	{
		$SQL = "SELECT submodel FROM lego.model_submodel WHERE model = ".$_GET['submodel'.$submodel]." AND step = ".(string)(((int)$_GET['substep'.$submodel]) + 1);
	}
	$hassub = pg_fetch_all(pg_query($db, $SQL));
	$SQL = "SELECT model, step FROM lego.model_step WHERE model = ".$_GET['model']." AND step = ".(string)(((int)$_GET['step']) - 1);
	$last = pg_fetch_all(pg_query($db, $SQL));
	if ($last)
	{
		echo "<TD><A HREF = 'index.php?what=build&amp;model=".$last[0]['model']."&amp;step=".$last[0]['step']."'>Previous step</A></TD>\n";
	}
		
	if ($hassub)
	{
		$SQL = "SELECT model FROM lego.model_step WHERE model = ".$hassub[0]['submodel']." AND step = 1";
		$newsub = pg_fetch_all(pg_query($db, $SQL));
	}
	if ($submodel)
	{
		$SQL = "SELECT model, step FROM lego.model_step WHERE model = ".$_GET['submodel'.$submodel]." AND step = ".(string)(((int)$_GET['substep'.$submodel]) + 1);
		$nextsub = pg_fetch_all(pg_query($db, $SQL));
	}
	if (1)
	{
		$SQL = "SELECT model, step FROM lego.model_step WHERE model = ".$_GET['model']." AND step = ".(string)(((int)$_GET['step']) + 1);
		$next = pg_fetch_all(pg_query($db, $SQL));
	}
	if ($next)
	{
		echo "<TD><A HREF = 'index.php?what=build&amp;model=".$next[0]['model']."&amp;step=";
		if (!$newsub and !$nextsub)
		{
			echo $next[0]['step'];
		}
		else
		{
			echo $_GET['step'];
		}
		if ($newsub)
		{
			$i = 0;
			while ($i < $submodel)
			{
				$i++;
				echo "&amp;submodel".$i."=".$_GET['submodel'.$i]."&amp;substep".$i."=".$_GET['substep'.$i];
			}
			echo "&amp;submodel".($submodel + 1)."=".$newsub[0]['model']."&amp;substep".($submodel + 1)."=1";
		}
		if ($nextsub)
		{
			$i = 0;
			while ($i < ($submodel - 1))
			{
				$i++;
				echo "&amp;submodel".$i."=".$_GET['submodel'.$i]."&amp;substep".$i."=".$_GET['substep'.$i];
			}
			echo "&amp;submodel".($submodel)."=".$nextsub[0]['model']."&amp;substep".($submodel)."=";
			if (!$newsub)
			{
				echo $nextsub[0]['step'];
			}
			else
			{
				echo $_GET['substep'.$submodel];
			}
		}
		echo "'>Next step</A></TD>\n";
	}
		
	echo "</TABLE></TR>\n";
	if (!$submodel)
	{
		echo "<IMG SRC = 'picture.php?model=1&size=800&id=".$_GET['model']."&step=".$_GET['step']."'>\n";
		echo "<TABLE BORDER = '1'>\n";
		echo "<TR><TD>Brick</TD><TD>Numbers</TD></TR>\n";
		$SQL = "SELECT d.description, c.\"Name\", dc.design, dc.color, count(dc.design) as numbers FROM lego.design_color dc, lego.model_bricks mb, lego.color c, lego.filename_design_color fdc, lego.design d WHERE c.id = dc.color AND c.ldraw_number = mb.val2 AND fdc.filename = mb.val15 AND fdc.design = dc.design AND d.id = dc.design AND mb.model = ".$_GET['model']." AND mb.step = ".$_GET['step']." GROUP BY dc.design, dc.color, d.description, c.\"Name\" ORDER BY d.description, c.\"Name\"";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			foreach ($ans as $an)
			{
				$bricks[$an['design'].".".$an['color']] = $an;
			}
		}
		$SQL = "SELECT submodel, count(submodel) as numbers FROM lego.model_submodel WHERE model = ".$_GET['model']." AND step = ".$_GET['step']." GROUP by submodel";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			foreach ($ans as $an)
			{
				$bricks[$an['submodel']] = $an;
			}
		}
		foreach ($bricks as $brick)
		{
			if ($brick['description'])
			{
				echo "<TR><TD>".$brick['description']." (".$brick['Name'].")<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$brick['design']."&amp;color=".$brick['color']."'></TD><TD>".$brick['numbers']."</TD></TR>\n";
			}
			else
			{
				$SQL = "SELECT step FROM lego.model_step WHERE model = ".$brick['submodel']." ORDER BY step DESC LIMIT 1";
				$step = pg_fetch_all(pg_query($db,$SQL));
				echo "<TR><TD><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;model=1&amp;id=".$brick['submodel']."&amp;step=".$step[0]['step']."'></TD><TD>".$brick['numbers']."</TD></TR>\n";
			}
		}
		echo "</TABLE>\n";
	}
	else
	{
		echo "<IMG SRC = 'picture.php?model=1&size=800&id=".$_GET['submodel'.$submodel]."&step=".$_GET['substep'.$submodel]."'>\n";
		echo "<TABLE BORDER = '1'>\n";
		echo "<TR><TD>Brick</TD><TD>Numbers</TD></TR>\n";
		$SQL = "SELECT d.description, c.\"Name\", dc.design, dc.color, count(dc.design) as numbers FROM lego.design_color dc, lego.model_bricks mb, lego.color c, lego.filename_design_color fdc, lego.design d WHERE c.id = dc.color AND c.ldraw_number = mb.val2 AND fdc.filename = mb.val15 AND fdc.design = dc.design AND d.id = dc.design AND mb.model = ".$_GET['submodel'.$submodel]." AND mb.step = ".$_GET['substep'.$submodel]." GROUP BY dc.design, dc.color, d.description, c.\"Name\" ORDER BY d.description, c.\"Name\"";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			foreach ($ans as $an)
			{
				$bricks[$an['design'].".".$an['color']] = $an;
			}
		}
		$SQL = "SELECT submodel, count(submodel) as numbers FROM lego.model_submodel WHERE model = ".$_GET['submodel'.$submodel]." AND step = ".$_GET['substep'.$submodel]." GROUP by submodel";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			foreach ($ans as $an)
			{
				$bricks[$an['submodel']] = $an;
			}
		}
		foreach ($bricks as $brick)
		{
			if ($brick['description'])
			{
				echo "<TR><TD>".$brick['description']." (".$brick['Name'].")<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$brick['design']."&amp;color=".$brick['color']."'></TD><TD>".$brick['numbers']."</TD></TR>\n";
			}
			else
			{
				$SQL = "SELECT step FROM lego.model_step WHERE model = ".$brick['submodel']." ORDER BY step DESC LIMIT 1";
				$step = pg_fetch_all(pg_query($db,$SQL));
				echo "<TR><TD><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;model=1&amp;id=".$brick['submodel']."&amp;step=".$step[0]['step']."'></TD><TD>".$brick['numbers']."</TD></TR>\n";
			}
		}
		echo "</TABLE>\n";
	}
}
?>