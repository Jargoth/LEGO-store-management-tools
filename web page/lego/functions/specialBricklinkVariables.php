<?php
function specialBricklinkVariables()
{
	include "../www/db.php";
	if ($_COOKIE['user'])
	{
		if ($_POST['change'])
		{
			$SQL = "UPDATE lego.user_parameters SET \"value\" = '".$_POST['bricklinkmax']."' WHERE \"user\" = ".$_COOKIE['user']." AND parameter = 'bricklinkmax'";
			pg_query($db, $SQL);
			$SQL = "UPDATE lego.user_parameters SET \"value\" = '".$_POST['bricklinkmin']."' WHERE \"user\" = ".$_COOKIE['user']." AND parameter = 'bricklinkmin'";
			pg_query($db, $SQL);
		}
		$SQL = "SELECT \"value\" FROM lego.user_parameters WHERE \"user\" = ".$_COOKIE['user']." AND (parameter = 'bricklinkmax' OR parameter = 'bricklinkmin') ORDER BY parameter";
		$res = pg_fetch_all(pg_query($db, $SQL));
		echo "<FORM ACTION = 'index.php?what=specialBricklinkVariables' METHOD = 'POST'>";
		echo "<BR>bricklinkmax: <INPUT TYPE = 'text' NAME = 'bricklinkmax' ID = 'bricklinkmax' VALUE = '".$res[0]['value']."'>";
		echo "<BR>bricklinkmin: <INPUT TYPE = 'text' NAME = 'bricklinkmin' ID = 'bricklinkmin' VALUE = '".$res[1]['value']."'>";
		echo "<BR><INPUT TYPE = 'submit' NAME = 'change' ID = 'change' VALUE = 'change'>";
		echo "</FORM>";
	}
}
?>