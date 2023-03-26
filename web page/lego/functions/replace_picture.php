<?php
function replace_picture()
{
	include "../www/db.php";
	if ($_COOKIE['user'] == 1)
	{
		if ($_POST['submit'] == 'submit')
		{
			$tmpName  = $_FILES['picture']['tmp_name'];
			$fileSize = $_FILES['picture']['size'];
			$fileType = $_FILES['picture']['type'];
			$fp = fopen($tmpName, 'r');
			$content = fread($fp, $fileSize);
			$content = pg_escape_bytea($content);
			$SQL = "UPDATE lego.design_color SET picture_data = '".$content."', picture_size = ".$fileSize.", picture_type = '".$fileType."', picture_replace = FALSE, picture_55_size = 0 WHERE design = ".$_GET['design']." AND color = ".$_GET['color'];
			pg_query($db, $SQL);
		}
		elseif (isset($_GET['design']) AND isset($_GET['color'])) /*displays a form for replacing the brickpicture i question*/
		{
			echo "<IMG SRC = 'picture.php?size=55&amp;design=".$_GET['design']."&amp;color=".$_GET['color']."'><BR><BR>\n";
			echo "<FORM ACTION = 'index.php?what=replace_picture&amp;design=".$_GET['design']."&amp;color=".$_GET['color']."' METHOD = 'post' enctype='multipart/form-data'>\n";
			echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000'>\n";
			echo "<INPUT ID = 'picture' NAME = 'picture' TYPE = 'file'><BR>\n";
			echo "<INPUT ID = 'submit' NAME = 'submit' TYPE = 'submit' VALUE = 'submit'>\n";
			echo "</FORM>\n";
		}
		else /*lists all pictures markt as "picture_replace"*/
		{
			$SQL = "SELECT dc.design, dc.color, c.\"Name\" AS color_name, d.description AS design_name FROM lego.design_color dc, lego.design d, lego.color c WHERE dc.design = d.id AND dc.color = c.id AND dc.picture_replace IS TRUE ORDER BY d.description, c.\"Name\"";
			$legos = pg_fetch_all(pg_query($db, $SQL));
			foreach ($legos as $lego)
			{
				echo "<A HREF = 'index.php?what=replace_picture&amp;design=".$lego['design']."&amp;color=".$lego['color']."'>".$lego['design_name']." (".$lego['color_name'].")</A><BR>\n";
			}
		}
	}
}
?>