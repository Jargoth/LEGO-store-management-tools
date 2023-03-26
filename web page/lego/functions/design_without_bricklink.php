<?php
function design_without_bricklink()
{
	include "../www/db.php";
	include "merge2.php";
	if ($_COOKIE['user'] == 1)
	{
		if ($_POST['submit'] == 'next')
		{
			$SQL = "UPDATE lego.design SET bricklink = now() WHERE id = ".$_GET['design'];
			pg_query($db, $SQL);
		}
		if ($_POST['submit'] == 'exclude from bricklink')
		{
			$SQL = "UPDATE lego.design SET exclude_from_bricklink = TRUE WHERE id = ".$_GET['design'];
			pg_query($db, $SQL);
		}
		if ($_POST['submit'] == 'submit')
		{
			$SQL = "SELECT design FROM lego.bricklink_design ".
					"WHERE bricklink = '".$_POST['bricklink']."' ".
					"AND type = 'P'";
			$res = pg_fetch_all(pg_query($db, $SQL));
			if ($res)
			{
				echo "Occupied by designID ".$res[0]['design']."<BR>\n";
				echo "Do you wish to merge them? <A HREF = 'index.php?what=design_without_bricklink&amp;do=merge&amp;design1=".$res[0]['design']."&amp;design2=".$_GET['design']."'>YES</A><BR><BR>\n";
			}
			else
			{
				$SQL = "INSERT INTO lego.bricklink_design (bricklink, design) VALUES ('".$_POST['bricklink']."', ".$_GET['design'].")";
				pg_query($db, $SQL);
			}
		}
		if ($_GET['do'] == 'merge' and isset($_GET['design1']) and isset($_GET['design2']))
		{
			$res = merge2($_GET['design1'], $_GET['design2']);
			echo "Successfully merged ".$res[0]." and ".$res[1].".<BR><BR>\n";
		}
		$SQL = "SELECT d.description, d.id, d.part_number, dc.color FROM lego.design d, lego.design_color dc WHERE d.exclude_from_bricklink IS FALSE AND d.id NOT IN (SELECT obsolete FROM lego.replacing WHERE what = 'design') AND d.primitive IS FALSE AND d.id = dc.design AND d.id NOT IN (SELECT design FROM lego.bricklink_design) AND (date_part('month', age(bricklink)) >= 3 OR date_part('year', age(bricklink)) >= 1) ORDER BY bricklink LIMIT 1";
		$res = pg_fetch_all(pg_query($db, $SQL));
		$description = $res[0]['description'];
		$part_number = $res[0]['part_number'];
		echo "<IMG SRC = 'picture.php?size=130&design=".$res[0]['id']."&color=".$res[0]['color']."'>";
		echo $description." (".$part_number.")<BR>";
		echo "<FORM ACTION = 'index.php?what=design_without_bricklink&amp;design=".$res[0]['id']."' METHOD = 'POST'>";
		echo "<INPUT TYPE = 'text' NAME = 'bricklink' ID = 'bricklink'>";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'submit'>";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'next'>";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'exclude from bricklink'>";
		echo "</FORM>";
	}
	
}
?>