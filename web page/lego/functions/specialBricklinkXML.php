<?php
function specialBricklinkXML()
{
	include "../www/db.php";
	if ($_COOKIE['user'])
	{
		$SQL = "INSERT INTO lego.bricklink_xml_generate (\"user\") VALUES (".$_COOKIE['user'].")";
		pg_query($db, $SQL);
		$SQL = "DELETE FROM lego.bricklink_xml WHERE \"user\" = ".$_COOKIE['user'];
		pg_query($db, $SQL);
		echo "Generation of the XML will start within 10 minutes. Please come back later.";
	}
}
?>