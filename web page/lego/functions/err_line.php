<?php
function err_line()
{
	include "../www/db.php";
	$SQL = "SELECT * FROM lego.model_line_error ORDER BY model, \"row\"";
	$lines = pg_fetch_all(pg_query($db, $SQL));
	foreach ($lines as $line)
	{
		print_r($line);
		echo "<br>";
		$brick = explode(" ", $line['line']);
		if ($brick[0] == 1)
		{
			$SQL = "SELECT design FROM lego.filename_design_color WHERE filename = '".$brick[14]."'";
			$ans = pg_fetch_all(pg_query($db, $SQL));
			if ($ans)
			{
				$SQL = "INSERT INTO lego.model_bricks (model, \"row\", step, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15) VALUES (".$line['model'].", ".$line['row'].", ".$line['step'].", ".$brick[0].", ".$brick[1].", ".$brick[2].", ".$brick[3].", ".$brick[4].", ".$brick[5].", ".$brick[6].", ".$brick[7].", ".$brick[8].", ".$brick[9].", ".$brick[10].", ".$brick[11].", ".$brick[12].", ".$brick[13].", '".$brick[14]."')";
			}
			else
			{
				$SQL = "INSERT INTO lego.model_bricks_admin (model, \"row\", step, brick) VALUES (".$line['model'].", ".$line['row'].", ".$line['step'].", '".$line['line']."')";
			}
			pg_query($db, $SQL) or $err = 1;
			if ($err)
			{
				break;
			}
			$SQL = "DELETE FROM lego.model_step WHERE model = ".$line['model']." OR model IN (SELECT model FROM lego.model WHERE submodel = ".$line['model']." OR model IN (SELECT submodel FROM lego.model WHERE model = ".$line['model']."))";
			pg_query($db, $SQL);
			$SQL = "DELETE FROM lego.model_line_error WHERE model = ".$line['model']." AND \"row\" = ".$line['row'];
			pg_query($db, $SQL) or die();
		}
	}
}
?>