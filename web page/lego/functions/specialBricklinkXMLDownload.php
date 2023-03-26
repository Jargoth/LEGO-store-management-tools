<?php
function specialBricklinkXMLDownload()
{
	include "../../www/db.php";
	if ($_COOKIE['user'])
	{
		$SQL = "SELECT data FROM lego.bricklink_xml WHERE \"user\" = ".$_COOKIE['user'];
		$res = pg_fetch_all(pg_query($db, $SQL));
		$SQL = "UPDATE lego.bricklink_xml SET \"read\" = TRUE WHERE \"user\" = ".$_COOKIE['user'];
		pg_query($db, $SQL);
		header("Content-Disposition: attachment; filename=bricklink.xml");
		echo "<INVENTORY>\n";
		foreach ($res as $re)
		{
			echo $re['data']."\n";
		}
		echo "</INVENTORY>";
	}
}
specialBricklinkXMLDownload();
?>