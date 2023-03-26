<?php
include "../www/db.php";
if (!isset($_GET['create']))
{
	if (isset($_GET['model']))
	{
		if (isset($_GET['project']))
		{
			$SQL = "SELECT picture_data, picture_size, picture_type FROM lego.project WHERE \"order\" = ".$_GET['id'];
			$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
			if ($arr[0]['picture_type'] == 'image/bmp')
			{
				$name = "image.bmp";
			}
			else
			{
				$name = "image.jpg";
			}
			$size = $arr[0]['picture_size'];
			$type = $arr['picture_type'];
			$name = pg_unescape_bytea($name);
			header("Content-Disposition: attachment; filename=$name");
			header("Content-length: $size");
			header("Content-type: $type");
			$content = pg_unescape_bytea($arr[0]['picture_data']);
			echo $content;
		}
		else
		{
			if (!isset($_GET['size']))
			{
				$SQL = "SELECT picture_data, picture_size, picture_type FROM lego.model_step WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				if ($arr[0]['picture_type'] == 'image/bmp')
				{
					$name = "image.bmp";
				}
				else
				{
					$name = "image.jpg";
				}
				$size = $arr[0]['picture_size'];
				$type = $arr['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			elseif ($_GET['size'] == '55')
			{
				$SQL = "SELECT picture_55_data AS picture_data, picture_55_size AS picture_size, picture_55_type AS picture_type FROM lego.model_step WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			elseif ($_GET['size'] == '130')
			{
				$SQL = "SELECT picture_130_data AS picture_data, picture_130_size AS picture_size, picture_130_type AS picture_type FROM lego.model_step WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			elseif ($_GET['size'] == '400')
			{
				$SQL = "SELECT picture_400_data AS picture_data, picture_400_size AS picture_size, picture_400_type AS picture_type FROM lego.model_step WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			elseif ($_GET['size'] == '800')
			{
				$SQL = "SELECT picture_800_data AS picture_data, picture_800_size AS picture_size, picture_800_type AS picture_type FROM lego.model_step WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
		}
	}
	else
	{
		if (!isset($_GET['size']))
		{
			$SQL = "SELECT picture_data, picture_size, picture_type, approved FROM lego.design_color WHERE approved IS TRUE AND design = ".$_GET['design']." AND color = ".$_GET['color'];
			$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
			if ($arr[0]['picture_type'] == 'image/bmp')
			{
				$name = "image.bmp";
			}
			elseif ($arr[0]['picture_type'] == 'image/gif')
			{
				$name = "image.gif";
			}
			else
			{
				$name = "image.jpg";
			}
			$size = $arr[0]['picture_size'];
			$type = $arr[0]['picture_type'];
			$name = pg_unescape_bytea($name);
			header("Content-Disposition: attachment; filename=$name");
			header("Content-length: $size");
			header("Content-type: $type");
			$content = pg_unescape_bytea($arr[0]['picture_data']);
			echo $content;
		}
		elseif($_GET['size'] == '55')
		{
			if ($_GET['color'] == 'most')
			{
				$SQL = "SELECT picture_55_data AS picture_data, picture_55_size AS picture_size, picture_55_type AS picture_type FROM lego.design_color dc, lego.design_color_user dcu WHERE approved IS TRUE AND design = ".$_GET['design']." AND dc.design = dcu.design AND dc.color = dcu.color ORDER BY (dcu.used + dcu.free) DESC";
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr[0]['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			elseif (is_numeric($_GET['color']))
			{
				$SQL = "SELECT picture_55_data AS picture_data, picture_55_size AS picture_size, picture_55_type AS picture_type FROM lego.design_color WHERE approved IS TRUE AND design = ".$_GET['design']." AND color = ".$_GET['color'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr[0]['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			else
			{
				$SQL = "SELECT picture_55_data AS picture_data, picture_55_size AS picture_size, picture_55_type AS picture_type FROM lego.design_color WHERE approved IS TRUE AND design = ".$_GET['design'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr[0]['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
		}
		elseif($_GET['size'] == '130')
		{
			if ($_GET['color'] == 'most')
			{
				$SQL = "SELECT picture_130_data AS picture_data, picture_130_size AS picture_size, picture_130_type AS picture_type FROM lego.design_color dc, lego.design_color_user dcu WHERE approved IS TRUE AND design = ".$_GET['design']." AND dc.design = dcu.design AND dc.color = dcu.color ORDER BY (dcu.used + dcu.free) DESC";
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr[0]['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			elseif (is_numeric($_GET['color']))
			{
				$SQL = "SELECT picture_130_data AS picture_data, picture_130_size AS picture_size, picture_130_type AS picture_type FROM lego.design_color WHERE approved IS TRUE AND design = ".$_GET['design']." AND color = ".$_GET['color'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr[0]['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			else
			{
				$SQL = "SELECT picture_130_data AS picture_data, picture_130_size AS picture_size, picture_130_type AS picture_type FROM lego.design_color WHERE approved IS TRUE AND design = ".$_GET['design'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr[0]['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
		}
		elseif($_GET['size'] == '400')
		{
			if ($_GET['color'] == 'most')
			{
				$SQL = "SELECT picture_400_data AS picture_data, picture_400_size AS picture_size, picture_400_type AS picture_type FROM lego.design_color dc, lego.design_color_user dcu WHERE approved IS TRUE AND design = ".$_GET['design']." AND dc.design = dcu.design AND dc.color = dcu.color ORDER BY (dcu.used + dcu.free) DESC";
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr[0]['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			elseif (is_numeric($_GET['color']))
			{
				$SQL = "SELECT picture_400_data AS picture_data, picture_400_size AS picture_size, picture_400_type AS picture_type FROM lego.design_color WHERE approved IS TRUE AND design = ".$_GET['design']." AND color = ".$_GET['color'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr[0]['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
			else
			{
				$SQL = "SELECT picture_400_data AS picture_data, picture_400_size AS picture_size, picture_400_type AS picture_type FROM lego.design_color WHERE approved IS TRUE AND design = ".$_GET['design'];
				$arr  = pg_fetch_all(pg_query($db, $SQL)) or die('Error, query failed');
				$name = "image.jpg";
				$size = $arr[0]['picture_size'];
				$type = $arr[0]['picture_type'];
				$name = pg_unescape_bytea($name);
				header("Content-Disposition: attachment; filename=$name");
				header("Content-length: $size");
				header("Content-type: $type");
				$content = pg_unescape_bytea($arr[0]['picture_data']);
				echo $content;
			}
		}
	}
}
else
{
	if (!$_GET['model'] == '1')
	{
		if ($_GET['size'] == '55')
		{
			$filename = 'C:\Program Files\Ampps\www\lego\55.jpg';
			$fp = fopen($filename, 'rb') or die('Cannot open file');
			$content = fread($fp, filesize($filename));
			$size = filesize($filename);

			$content = pg_escape_bytea($content);
			$SQL = "UPDATE lego.design_color SET picture_55_data = '".$content."', picture_55_type = '".$type."', picture_55_size = ".$size." WHERE design = ".$_GET['design']." AND color = ".$_GET['color'];
			pg_query($db, $SQL) or die('Error, query failed');
			echo 'ok';
		}
		if ($_GET['size'] == '130')
		{
			$filename = 'C:\Program Files\Ampps\www\lego\130.jpg';
			$fp = fopen($filename, 'rb') or die('Cannot open file');
			$content = fread($fp, filesize($filename));
			$size = filesize($filename);

			$content = pg_escape_bytea($content);
			$SQL = "UPDATE lego.design_color SET picture_130_data = '".$content."', picture_130_type = '".$type."', picture_130_size = ".$size." WHERE design = ".$_GET['design']." AND color = ".$_GET['color'];
			pg_query($db, $SQL) or die('Error, query failed');
			echo 'ok';
		}
		if ($_GET['size'] == '400')
		{
			$filename = 'C:\Program Files\Ampps\www\lego\400.jpg';
			$fp = fopen($filename, 'rb') or die('Cannot open file');
			$content = fread($fp, filesize($filename));
			$size = filesize($filename);

			$content = pg_escape_bytea($content);
			$SQL = "UPDATE lego.design_color SET picture_400_data = '".$content."', picture_400_type = '".$type."', picture_400_size = ".$size." WHERE design = ".$_GET['design']." AND color = ".$_GET['color'];
			pg_query($db, $SQL) or die('Error, query failed');
			echo 'ok';
		}
		if (!isset($_GET['size']))
		{
			$design = $_GET['design'];
			$color = $_GET['color'];
			$filename = 'C:\Program Files (x86)\Ampps\www\lego/bricklink.jpg';
			$fp = fopen($filename, 'rb') or die('Cannot open file');
			$content = fread($fp, filesize($filename));
			$size = filesize($filename);
			$content = pg_escape_bytea($content);
			$SQL = "INSERT INTO lego.design_color (design, color, picture_data, picture_size, picture_type) VALUES (".$design.", ".$color.", '".$content."', ".$size.", '".$type."')";
			pg_query($db, $SQL) or die('Error, query failed');
			echo 'ok';
		}
	}
	else
	{
		if (isset($_GET['project']))
		{
			$filename = "C:\Program Files (x86)\Ampps\www/lego/model.bmp";
			$fp = fopen($filename, 'rb') or die('Cannot open file');
			$content = fread($fp, filesize($filename));
			$size = filesize($filename);
	
			$content = pg_escape_bytea($content);
			$SQL = "UPDATE lego.project SET picture_data = '".$content."', picture_type = '".$type."', picture_size = ".$size." WHERE \"order\" = ".$_GET['id'];
			pg_query($db, $SQL) or die('Error, query failed');
			echo 'ok';
		}
		else
		{
			if (!isset($_GET['size']))
			{
				$filename = "C:/temp/model.bmp";
				$fp = fopen($filename, 'rb') or die('Cannot open file');
				$content = fread($fp, filesize($filename));
				$size = filesize($filename);
	
				$content = pg_escape_bytea($content);
				$SQL = "SELECT model, step FROM lego.model_step WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				$ans = pg_fetch_all(pg_query($db, $SQL));
				if ($ans)
				{
					$SQL = "UPDATE lego.model_step SET picture_data = '".$content."', picture_type = '".$type."', picture_size = ".$size." WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				}
				else
				{
					$SQL = "INSERT INTO lego.model_step (model, step, picture_data, picture_size, picture_type) VALUES (".$_GET['id'].", ".$_GET['step'].", '".$content."', ".$size.", '".$type."')";
				}
				pg_query($db, $SQL) or die('Error, query failed');
				echo 'ok';
			}
			if ($_GET['size'] == '55')
			{
				$filename = "C:/temp/model_55.jpg";
				$fp = fopen($filename, 'rb') or die('Cannot open file');
				$content = fread($fp, filesize($filename));
				$size = filesize($filename);
	
				$content = pg_escape_bytea($content);
				$SQL = "UPDATE lego.model_step SET picture_55_data = '".$content."', picture_55_type = '".$type."', picture_55_size = ".$size." WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				pg_query($db, $SQL) or die('Error, query failed');
				echo 'ok';
			}
			if ($_GET['size'] == '130')
			{
				$filename = "C:/temp/model_130.jpg";
				$fp = fopen($filename, 'rb') or die('Cannot open file');
				$content = fread($fp, filesize($filename));
				$size = filesize($filename);

				$content = pg_escape_bytea($content);
				$SQL = "UPDATE lego.model_step SET picture_130_data = '".$content."', picture_130_type = '".$type."', picture_130_size = ".$size." WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				pg_query($db, $SQL) or die('Error, query failed');
				echo 'ok';
			}
			if ($_GET['size'] == '400')
			{
				$filename = "C:/temp/model_400.jpg";
				$fp = fopen($filename, 'rb') or die('Cannot open file');
				$content = fread($fp, filesize($filename));
				$size = filesize($filename);

				$content = pg_escape_bytea($content);
				$SQL = "UPDATE lego.model_step SET picture_400_data = '".$content."', picture_400_type = '".$type."', picture_400_size = ".$size." WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				pg_query($db, $SQL) or die('Error, query failed');
				echo 'ok';
			}
			if ($_GET['size'] == '800')
			{
				$filename = "C:/temp/model_800.jpg";
				$fp = fopen($filename, 'rb') or die('Cannot open file');
				$content = fread($fp, filesize($filename));
				$size = filesize($filename);

				$content = pg_escape_bytea($content);
				$SQL = "UPDATE lego.model_step SET picture_800_data = '".$content."', picture_800_type = '".$type."', picture_800_size = ".$size." WHERE model = ".$_GET['id']." AND step = ".$_GET['step'];
				pg_query($db, $SQL) or die('Error, query failed');
				echo 'ok';
			}
		}
	}
}
?>