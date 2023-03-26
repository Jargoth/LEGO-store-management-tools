<?php
function brick_no_picture()
{
	include "../www/db.php";
	if ($_POST['submit'] == 'submit')
	{
		$tmpName  = $_FILES['picture']['tmp_name'];
		$fileSize = $_FILES['picture']['size'];
		$fileType = $_FILES['picture']['type'];
		$fp = fopen($tmpName, 'r');
		$content = fread($fp, $fileSize);
		$content = pg_escape_bytea($content);
		$SQL = "INSERT INTO lego.design_color (design, color, picture_data, picture_size, picture_type) VALUES (".$_GET['design'].", ".$_GET['color'].", '".$content."', ".$fileSize.", '".$fileType."')";
		pg_query($db, $SQL);
		$SQL = "INSERT INTO lego.design_color_user (design, color, \"user\") VALUES (".$_GET['design'].", ".$_GET['color'].", ".$_COOKIE['user'].")";
		pg_query($db, $SQL);
	}
	$SQL = "SELECT d.description, d.id AS design, c.id AS color, c.\"Name\", cl.ldraw FROM lego.model_bricks mb, lego.color_ldraw cl, lego.color c, lego.filename_design_color fdc, lego.design d WHERE d.id NOT IN (SELECT obsolete FROM lego.replacing WHERE what = 'design') AND c.id <> 0 AND d.primitive = FALSE AND fdc.color = 0 AND mb.val2 = cl.ldraw AND cl.color = c.id AND mb.val15 = fdc.filename AND d.id = fdc.design AND mb.id NOT IN (SELECT mb.id FROM lego.model_bricks mb, lego.color_ldraw cl, lego.color c, lego.filename_design_color fdc, lego.design_color dc WHERE mb.val2 = cl.ldraw AND cl.color = c.id AND mb.val15 = fdc.filename AND c.id = dc.color AND fdc.design = dc.design) GROUP BY d.description, d.id, c.id, c.\"Name\", cl.ldraw ORDER BY d.description, c.\"Name\" LIMIT 1";
	$brick = pg_fetch_all(pg_query($db, $SQL));
	echo $brick[0]['description']." (".$brick[0]['Name']." (LD: ".$brick[0]['ldraw']."))<BR>";
	echo "<FORM ACTION = 'index.php?what=brick_no_picture&amp;design=".$brick[0]['design']."&amp;color=".$brick[0]['color']."' METHOD = 'post' enctype='multipart/form-data'>\n";
	echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000'>\n";
	echo "<INPUT ID = 'picture' NAME = 'picture' TYPE = 'file'><BR>\n";
	echo "<INPUT ID = 'submit' NAME = 'submit' TYPE = 'submit' VALUE = 'submit'>\n";
	echo "</FORM>\n";
}
?>