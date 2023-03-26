<?php
function search()
{
	include "../www/db.php";
	if (isset($_POST['search1']))
	{
		//Models
		$SQL = "SELECT title, id FROM lego.model WHERE submodel = 0 AND title ILIKE '%".$_POST['search1']."%' ORDER BY title";
		$models = pg_fetch_all(pg_query($db, $SQL));		
		if ($models)
		{
			echo "Models";
			echo "<TABLE BORDER = 1>\n";
			foreach ($models as $model)
			{
				$SQL = "SELECT step FROM lego.model_step WHERE model = ".$model['id']." ORDER BY step DESC LIMIT 1";
				$step = pg_fetch_all(pg_query($db, $SQL));
				echo "<TR>\n";
				echo "<TD><A HREF = 'index.php?what=model&amp;id=".$model['id']."'>".$model['title']."</A><IMG SRC = 'picture.php?model=1&size=55&id=".$model['id']."&amp;step=".$step[0]['step']."'></TD>\n";
				echo "</TR>\n";
			}
			echo "<TABLE>\n";
		}
		
		//Bricks
		$SQL = "SELECT description, id, (SELECT dcu.color FROM lego.design_color_user dcu WHERE dcu.design = d.id ORDER BY (dcu.used + dcu.free) DESC LIMIT 1) AS color FROM lego.design d WHERE hide IS FALSE AND primitive IS FALSE AND description ILIKE '%".utf8_encode($_POST['search1'])."%' ORDER BY description";
		$bricks = pg_fetch_all(pg_query($db, $SQL));
		if ($bricks)
		{
			echo "Bricks";
			echo "<TABLE BORDER = 1>\n";
			foreach ($bricks as $brick)
			{
				echo "<TR>\n";
				echo "<TD><A HREF = 'picture.php?size=400&amp;design=".$brick['id']."&amp;color=".$brick['color']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$brick['id']."&amp;color=".$brick['color']."'></A><A HREF = 'index.php?what=brick&amp;design=".$brick['id']."'>".utf8_decode($brick['description'])."</A></TD>";
				echo "</TR>\n";
			}
			echo "<TABLE>\n";
		}
		
		//Colors
		$SQL = "SELECT \"Name\", id FROM  lego.color WHERE \"Name\" ILIKE '%".$_POST['search1']."%' ORDER BY \"Name\"";
		$colors = pg_fetch_all(pg_query($db, $SQL));
		if ($colors)
		{
			echo "Colors";
			echo "<TABLE BORDER = 1>\n";
			foreach ($colors as $color)
			{
				$SQL = "SELECT design, color FROM lego.design_color WHERE design = 12 AND color = ".$color['id'];
				$res = pg_fetch_all(pg_query($db, $SQL));
				echo "<TR>\n";
				echo "<TD>";
				if ($res)
				{
					echo "<IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$res[0]['design']."&amp;color=".$res[0]['color']."'>";
				}
				echo $color['Name']."</TD>\n";
				echo "</TR>\n";
			}
			echo "<TABLE>\n";
		}
		
		//Model Categories
		$SQL = "SELECT title, id, parent FROM lego.modelcat WHERE title ILIKE '%".$_POST['search1']."%' ORDER BY title";
		$modelcats = pg_fetch_all(pg_query($db, $SQL));
		if ($modelcats)
		{
			$SQL = "SELECT title, id, parent FROM lego.modelcat";
			$result = pg_fetch_all(pg_query($db, $SQL));
			foreach ($result as $res)
			{
				$mc[$res['id']] = $res;
			}
			echo "Model Categories";
			echo "<TABLE BORDER = 1>\n";
			foreach ($modelcats as $modelcat)
			{
				$lastParent = -1;
				unset($s);
				unset($mc2);
				while ($lastParent != 0)
				{
					if (isset($mc2))
					{
						$mc2[] = $mc[$lastParent];
						$lastParent = $mc[$lastParent]['parent'];
					}
					else
					{
						$mc2[] = $modelcat;
						$lastParent = $modelcat['parent'];
					}
				}
				$mc2 = array_reverse($mc2);
				foreach($mc2 as $mc3)
				{
					$s = $s."\\".$mc3['title'];
				}
				echo "<TR>\n";
				echo "<TD><A HREF = 'index.php?what=list_model&amp;cat=".$modelcat['id']."'>".$s."</A></TD>\n";
				echo "</TR>\n";
			}
			echo "<TABLE>\n";
		}
	}
}
?>