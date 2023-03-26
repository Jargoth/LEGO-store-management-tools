<?php
function add_file()
{
	include "../www/db.php";
	if (isset($_POST['submit']))
	{
		$brick = explode(" ", $_POST['brick']);
		$SQL = "INSERT INTO lego.filename_design_color (filename, design, color) VALUES ('".utf8_encode($brick[14])."', ".$_POST['design'].", ".$_POST['color'].")";
		pg_query($db, $SQL) or die();
		$SQL = "INSERT INTO lego.model_bricks(\"row\", step, model, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15) VALUES (".$_POST['row'].", ".$_POST['step'].", ".$_POST['model'].", ".$brick['0'].", ".$brick['1'].", ".$brick['2'].", ".$brick['3'].", ".$brick['4'].", ".$brick['5'].", ".$brick['6'].", ".$brick['7'].", ".$brick['8'].", ".$brick['9'].", ".$brick['10'].", ".$brick['11'].", ".$brick['12'].", ".$brick['13'].", '".utf8_encode($brick['14'])."')";
		$SQL2 = "INSERT INTO lego.model_line_error (model, line, \"row\", step) VALUES (".$_POST['model'].", '".utf8_encode($_POST['brick'])."', ".$_POST['row'].", ".$_POST['step'].")";
		pg_query($db, $SQL) or pg_query($db, $SQL2);
		$SQL = "SELECT submodel FROM lego.model WHERE id = ".$_POST['model'];
		$sub = pg_fetch_all(pg_query($db, $SQL));
		if ($sub[0]['submodel'] == 0)
		{
			$model = $_POST['model'];
		}
		else
		{
			$model = $sub[0]['submodel'];
		}
		$SQL = "SELECT model FROM lego.regenerate_modelpic WHERE model = ".$model;
		$regenerate = pg_fetch_all(pg_query($db, $SQL));
		if (!$regenerate)
		{
			$SQL = "INSERT INTO lego.regenerate_modelpic (model) VALUES (".$model.")";
			pg_query($db, $SQL);
		}
		$SQL = "DELETE FROM lego.model_bricks_admin WHERE model = ".$_POST['model']." AND brick = '".utf8_encode($_POST['brick'])."'";
		pg_query($db, $SQL);
		$SQL = "SELECT model FROM lego.model_bricks_admin WHERE model = ".$_POST['model']." OR model IN (SELECT submodel FROM lego.model m, lego.model_bricks_admin mba WHERE m.id = mba.model)";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if (!$ans)
		{
			$SQL = "UPDATE lego.model SET admin = FALSE WHERE id = ".$_POST['model'];
			pg_query($db, $SQL);
		}
	}
	while (!$i)
	{
		$SQL = "SELECT model, brick, \"row\", step FROM lego.model_bricks_admin ORDER BY model, brick LIMIT 1";
		$lego = pg_fetch_all(pg_query($db, $SQL));
		$brick = explode(" ", utf8_decode($lego[0]['brick']));
		$brick[14] = addslashes($brick[14]);
		$SQL = "SELECT filename FROM lego.filename_design_color WHERE filename = '".utf8_encode($brick[14])."'";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			$SQL = "INSERT INTO lego.model_bricks(\"row\", step, model, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15) VALUES (".$lego[0]['row'].", ".$lego[0]['step'].", ".$lego[0]['model'].", ".$brick['0'].", ".$brick['1'].", ".$brick['2'].", ".$brick['3'].", ".$brick['4'].", ".$brick['5'].", ".$brick['6'].", ".$brick['7'].", ".$brick['8'].", ".$brick['9'].", ".$brick['10'].", ".$brick['11'].", ".$brick['12'].", ".$brick['13'].", '".utf8_encode($brick['14'])."')";
			$SQL2 = "INSERT INTO lego.model_line_error (model, line, \"row\", step) VALUES (".$lego[0]['model'].", '".utf8_encode($lego[0]['brick'])."', ".$lego[0]['row'].", ".$lego[0]['step'].")";
			pg_query($db, $SQL) or pg_query($db, $SQL2);
			$SQL = "SELECT submodel FROM lego.model WHERE id = ".$lego[0]['model'];
			$sub = pg_fetch_all(pg_query($db, $SQL));
			if ($sub[0]['submodel'] == 0)
			{
				$model = $lego[0]['model'];
			}
			else
			{
				$model = $sub[0]['submodel'];
			}
			$SQL = "SELECT model FROM lego.regenerate_modelpic WHERE model = ".$model;
			$regenerate = pg_fetch_all(pg_query($db, $SQL));
			if (!$regenerate)
			{
				$SQL = "INSERT INTO lego.regenerate_modelpic (model) VALUES (".$model.")";
				pg_query($db, $SQL);
			}
			$SQL = "DELETE FROM lego.model_bricks_admin WHERE model = ".$lego[0]['model']." AND brick = '".addslashes($lego[0]['brick'])."'";
			pg_query($db, $SQL);
		}
		else
		{
			$SQL = "SELECT filename FROM lego.model WHERE (id = ".$lego[0]['model']." OR submodel = ".$lego[0]['model']." OR submodel IN (SELECT submodel FROM lego.model WHERE id = ".$lego[0]['model'].")) AND filename = '".utf8_encode($brick[14])."'";
			$ans = pg_fetch_all(pg_query($db, $SQL));
			if ($ans)
			{
				$SQL = "INSERT INTO lego.model_submodel(\"row\", step, model, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15) VALUES (".$lego[0]['row'].", ".$lego[0]['step'].", ".$lego[0]['model'].", ".$brick['0'].", ".$brick['1'].", ".$brick['2'].", ".$brick['3'].", ".$brick['4'].", ".$brick['5'].", ".$brick['6'].", ".$brick['7'].", ".$brick['8'].", ".$brick['9'].", ".$brick['10'].", ".$brick['11'].", ".$brick['12'].", ".$brick['13'].", '".utf8_encode($brick['14'])."')";
				$SQL2 = "INSERT INTO lego.model_line_error (model, line, \"row\", step) VALUES (".$lego[0]['model'].", '".utf8_encode($lego[0]['brick'])."', ".$lego[0]['row'].", ".$lego[0]['step'].")";
				pg_query($db, $SQL) or pg_query($db, $SQL2);
				$SQL = "DELETE FROM lego.model_bricks_admin WHERE model = ".$lego[0]['model']." AND brick = '".addslashes($lego[0]['brick'])."'";
				pg_query($db, $SQL);
				$SQL = "SELECT submodel FROM lego.model WHERE id = ".$lego[0]['model'];
				$sub = pg_fetch_all(pg_query($db, $SQL));
				if ($sub[0]['submodel'] == 0)
				{
					$model = $lego[0]['model'];
				}
				else
				{
					$model = $sub[0]['submodel'];
				}
				$SQL = "SELECT model FROM lego.regenerate_modelpic WHERE model = ".$model;
				$regenerate = pg_fetch_all(pg_query($db, $SQL));
				if (!$regenerate)
				{
					$SQL = "INSERT INTO lego.regenerate_modelpic (model) VALUES (".$model.")";
					pg_query($db, $SQL);
				}
				$SQL = "SELECT model FROM lego.model_bricks_admin WHERE model = ".$lego[0]['model'];
				$ans = pg_fetch_all(pg_query($db, $SQL));
				if (!$ans)
				{
					$SQL = "UPDATE lego.model SET admin = FALSE WHERE id = ".$lego[0]['model'];
					pg_query($db, $SQL);
				}
			}
			else
			{
				$i = 1;
			}
		}
	}
	if ($lego)
	{
		$SQL = "SELECT id, \"Name\" FROM lego.color ORDER BY \"Name\"";
		$colors = pg_fetch_all(pg_query($db, $SQL));
		$SQL = "SELECT id, description FROM lego.design ORDER BY description";
		$designs = pg_fetch_all(pg_query($db, $SQL));
		echo $lego[0]['brick']."<BR><BR>\n";
		echo "<FORM ACTION = 'index.php?what=add_file' METHOD = 'POST' enctype=\"multipart/form-data\">\n";
		echo "Color: <SELECT NAME = 'color' ID = 'color' SIZE = '1'>\n";
		foreach ($colors as $color)
		{
			echo "<OPTION VALUE = '".$color['id']."'>".$color['Name']."\n";
		}
		echo "</SELECT><BR>\n";
		echo "Design: <SELECT NAME = 'design' ID = 'design' SIZE = '1'>\n";
		foreach ($designs as $design)
		{
			echo "<OPTION VALUE = '".$design['id']."'>".$design['description']."\n";
		}
		echo "</SELECT><BR>\n";
		echo "<input type='hidden' name='model' value='".$lego[0]['model']."'>\n";
		echo "<input type='hidden' name='brick' value='".$lego[0]['brick']."'>\n";
		echo "<input type='hidden' name='row' value='".$lego[0]['row']."'>\n";
		echo "<input type='hidden' name='step' value='".$lego[0]['step']."'>\n";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'add'>\n";
		echo "</FORM>\n";
	}
}
?>