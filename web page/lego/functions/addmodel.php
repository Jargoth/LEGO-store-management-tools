<?php
function addmodel()
{
	include "../www/db.php";
	if (!isset($_POST['submit']))
	{
		echo "<FORM ACTION = 'index.php?what=addmodel' METHOD = 'POST' enctype=\"multipart/form-data\">\n";
		echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000'>\n";
		echo "Model file: <INPUT TYPE = 'file' NAME = 'model' ID = 'model'><BR>\n";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'add'>\n";
		echo "</FORM>\n";
	}
	else
	{
		$tmpName  = $_FILES['model']['tmp_name'];
		$fileName  = $_FILES['model']['name'];
		$fp = fopen($tmpName, 'r');
		$lines[0] = addslashes(fgets($fp));
		$header[0] ='false';
		$bricks[0] = 'false';
		$submodel[0] = 'false';
		while (!feof($fp))
		{
			array_push($lines, addslashes(fgets($fp)));
		}
		$row = 0;
		$step = 1;
		foreach ($lines as $line)
		{
			if ($line[0] == 0)
			{
				array_push($header, $line);
			}
		}
		if (strtolower(substr($fileName, -3)) == 'ldr' or strtolower(substr($fileName, -3)) == 'dat')
		{
			$title = substr($lines[0], 2);
		}
		if (strtolower(substr($fileName, -3)) == 'mpd')
		{
			$title = substr($lines[1], 2);
		}
		$SQL = "INSERT INTO lego.model (creator, title, filename) VALUES (".$_COOKIE['user'].", '".utf8_encode($title)."', '".utf8_encode($fileName)."')";
		pg_query($db, $SQL);
		$SQL = "SELECT currval('lego.model_id_seq')";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		$mainmodel = $ans[0]['currval'];
		$newsub = -1;
		foreach ($lines as $line)
		{
			$line = ltrim($line);
			$row++;
			$brick = '';
			$brick = explode(' ', $line);
			$brick = modelLinePrepareInsert($brick);
			$newBricks[0] = 'false';
			if ($brick[0] == '1' and strtolower(substr($brick[14], -5, 3)) == 'dat')
			{
				$SQL = "SELECT filename FROM lego.filename_design_color WHERE filename = '".utf8_encode($brick[14])."'";
				$ans2 = pg_fetch_all(pg_query($db, $SQL));
				if ($ans2)
				{
					$SQL = "INSERT INTO lego.model_bricks(\"row\", step, model, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15) VALUES (".$row.", ".$step.", ".$ans[0]['currval'].", ".$brick['0'].", ".$brick['1'].", ".$brick['2'].", ".$brick['3'].", ".$brick['4'].", ".$brick['5'].", ".$brick['6'].", ".$brick['7'].", ".$brick['8'].", ".$brick['9'].", ".$brick['10'].", ".$brick['11'].", ".$brick['12'].", ".$brick['13'].", '".utf8_encode($brick['14'])."')";
					$SQL2 = "INSERT INTO lego.model_line_error (model, line, \"row\", step) VALUES (".$ans[0]['currval'].", '".utf8_encode($line)."', ".$row.", ".$step.")";
					pg_query($db, $SQL) or pg_query($db, $SQL2);
				}
				else
				{
					array_push($newBricks, array($line, $ans[0]['currval'], $row, $step));
				}
			}
			elseif ($brick[0] == '1' and strtolower(substr($brick[14], -5, 3)) == 'ldr')
			{
				array_push($submodel, array($row, $step, $brick, $ans[0]['currval'], $line));
			}
			elseif ($brick[0] == '0' OR $brick[0] > 1)
			{
				if (substr($brick[1], 0, 4) == 'STEP')
				{
					$step++;
				}
				if (substr($brick[1], 0, 4) == 'FILE')
				{
					$newsub++;
				}
				if ($newsub == 1)
				{
					$newsub--;
					$step = 1;
					$SQL = "INSERT INTO lego.model (creator, filename, submodel) VALUES (".$_COOKIE['user'].", '".utf8_encode($brick['2'])."', ".$mainmodel.")";
					pg_query($db, $SQL);
					$SQL = "SELECT currval('lego.model_id_seq')";
					$ans = pg_fetch_all(pg_query($db, $SQL));
					$SQL = "INSERT INTO lego.model_header(\"row\", model, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15) VALUES (".$row.", ".$ans[0]['currval'].", '".utf8_encode($brick['0'])."', '".utf8_encode($brick['1'])."', '".utf8_encode($brick['2'])."', '".utf8_encode($brick['3'])."', '".utf8_encode($brick['4'])."', '".utf8_encode($brick['5'])."', '".utf8_encode($brick['6'])."', '".utf8_encode($brick['7'])."', '".utf8_encode($brick['8'])."', '".utf8_encode($brick['9'])."', '".utf8_encode($brick['10'])."', '".utf8_encode($brick['11'])."', '".utf8_encode($brick['12'])."', '".utf8_encode($brick['13'])."', '".utf8_encode($brick['14'])."')";
					$SQL2 = "INSERT INTO lego.model_line_error (model, line, \"row\", step) VALUES (".$ans[0]['currval'].", '".utf8_encode($line)."', ".$row.", ".$step.")";
					pg_query($db, $SQL) or pg_query($db, $SQL2);
				}
				else
				{
					$SQL = "INSERT INTO lego.model_header(\"row\", model, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15) VALUES (".$row.", ".$ans[0]['currval'].", '".utf8_encode($brick['0'])."', '".utf8_encode($brick['1'])."', '".utf8_encode($brick['2'])."', '".utf8_encode($brick['3'])."', '".utf8_encode($brick['4'])."', '".utf8_encode($brick['5'])."', '".utf8_encode($brick['6'])."', '".utf8_encode($brick['7'])."', '".utf8_encode($brick['8'])."', '".utf8_encode($brick['9'])."', '".utf8_encode($brick['10'])."', '".utf8_encode($brick['11'])."', '".utf8_encode($brick['12'])."', '".utf8_encode($brick['13'])."', '".utf8_encode($brick['14'])."')";
					$SQL2 = "INSERT INTO lego.model_line_error (model, line, \"row\", step) VALUES (".$ans[0]['currval'].", '".utf8_encode($line)."', ".$row.", ".$step.")";
					pg_query($db, $SQL) or pg_query($db, $SQL2);
				}
			}
			elseif ($brick[0] == '2' or $brick[0] == '3' or $brick[0] == '4' or $brick[0] == '5')
			{
				$SQL = "INSERT INTO lego.model_primitives(\"row\", model, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15, step) VALUES (".$row.", ".$ans[0]['currval'].", '".utf8_encode($brick['0'])."', '".utf8_encode($brick['1'])."', '".utf8_encode($brick['2'])."', '".utf8_encode($brick['3'])."', '".utf8_encode($brick['4'])."', '".utf8_encode($brick['5'])."', '".utf8_encode($brick['6'])."', '".utf8_encode($brick['7'])."', '".utf8_encode($brick['8'])."', '".utf8_encode($brick['9'])."', '".utf8_encode($brick['10'])."', '".utf8_encode($brick['11'])."', '".utf8_encode($brick['12'])."', '".utf8_encode($brick['13'])."', '".utf8_encode($brick['14'])."', ".$step.")";
				pg_query($db, $SQL);
			}
			elseif (!$brick[1])
			{
				$SQL = "INSERT INTO lego.model_header(\"row\", model, val1) VALUES (".$row.", ".$ans[0]['currval'].", '0')";
				pg_query($db, $SQL);
			}
		}
		if ($err)
		{
			$SQL = "UPDATE lego.model SET admin = TRUE WHERE id = ".$mainmodel;
			pg_query($db, $SQL);
		}
		if ($submodel[1])
		{
			foreach($submodel as $sub)
			{
				if ($sub != 'false')
				{
					$SQL = "SELECT id FROM lego.model WHERE filename = '".$sub[2][14]."' ORDER BY date_added DESC LIMIT 1";
					$ans = pg_fetch_all(pg_query($db, $SQL));
					$SQL = "INSERT INTO lego.model_submodel(\"row\", step, submodel, model, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15) VALUES (".$sub[0].", ".$sub[1].", ".$ans[0]['id'].", ".$sub[3].", ".$sub[2]['0'].", ".$sub[2]['1'].", ".$sub[2]['2'].", ".$sub[2]['3'].", ".$sub[2]['4'].", ".$sub[2]['5'].", ".$sub[2]['6'].", ".$sub[2]['7'].", ".$sub[2]['8'].", ".$sub[2]['9'].", ".$sub[2]['10'].", ".$sub[2]['11'].", ".$sub[2]['12'].", ".$sub[2]['13'].", '".utf8_encode($sub[2]['14'])."')";
					$SQL2 = "INSERT INTO lego.model_line_error (model, line, \"row\", step) VALUES (".$sub[3].", '".utf8_encode($sub[4])."', ".$sub[0].", ".$sub[1].")";
					pg_query($db, $SQL) or pg_query($db, $SQL2);
				}
			}
		}
		if ($newBricks[1])
		{
			foreach ($newBricks as $newBrick)
			{
				if ($newBrick != 'false')
				{
					$brick = $newBrick;
					$brick2 = modelLinePrepareInsert(explode(' ', $brick[0]));
					$SQL = "SELECT * FROM lego.model WHERE submodel = ".$brick[1]." AND filename = '".utf8_encode($brick2[14])."'";
					$ans = pg_fetch_all(pg_query($db, $SQL));
					if ($ans)
					{
						$SQL = "INSERT INTO lego.model_submodel(\"row\", step, submodel, model, val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12, val13, val14, val15) VALUES (".$brick[2].", ".$brick[3].", ".$ans[0]['id'].", ".$brick[1].", ".$brick2['0'].", ".$brick2['1'].", ".$brick2['2'].", ".$brick2['3'].", ".$brick2['4'].", ".$brick2['5'].", ".$brick2['6'].", ".$brick2['7'].", ".$brick2['8'].", ".$brick2['9'].", ".$brick2['10'].", ".$brick2['11'].", ".$brick2['12'].", ".$brick2['13'].", '".utf8_encode($brick2['14'])."')";
						$SQL2 = "INSERT INTO lego.model_line_error (model, line, \"row\", step) VALUES (".$ans[0]['currval'].", '".utf8_encode($line)."', ".$row.", ".$step.")";
						pg_query($db, $SQL) or pg_query($db, $SQL2);
					}
					else
					{
						$err = 1;
						$SQL = "INSERT INTO lego.model_bricks_admin (model, brick, \"row\", step) VALUES (".$brick[1].", '".utf8_encode($brick[0])."', ".$brick[2].", ".$brick[3].")";
						$SQL2 = "INSERT INTO lego.model_line_error (model, line, \"row\", step) VALUES (".$brick[1].", '".utf8_encode($brick[0])."', ".$brick[2].", ".$brick[3].")";
						pg_query($db, $SQL) or pg_query($db, $SQL2);
					}
				}
			}
		}
	}
}
?>