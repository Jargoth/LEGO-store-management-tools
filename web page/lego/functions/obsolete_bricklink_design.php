<?php
function obsolete_bricklink_design()
{
	include "../www/db.php";
	include "merge2.php";
	if ($_GET['do'] == 'new' and isset($_GET['design']) and isset($_GET['type']))
	{
		echo "Old bricklink: ".$_GET['design']."<BR>\n";
		echo "Old type: ".$_GET['type']."<BR>\n";
		echo "<FORM ACTION = 'index.php?what=obsolete_bricklink_design&do=update' METHOD = 'POST'>\n";
		echo "New bricklink: <INPUT TYPE = 'text' NAME = 'design' ID = 'design' VALUE = '".$_GET['design']."'><BR>\n";
		echo "New type: <INPUT TYPE = 'text' NAME = 'type' ID = 'type' VALUE = '".$_GET['type']."'><BR>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'olddesign' ID = 'olddesign' VALUE = '".$_GET['design']."'><BR>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'oldtype' ID = 'oldtype' VALUE = '".$_GET['type']."'><BR>\n";
		echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'submit'>\n";
		echo "</FORM>\n";
	}
	else
	{
		if ($_GET['do'] == 'update' and isset($_POST['design']) and isset($_POST['type']))
		{
			$SQL = "SELECT design FROM lego.bricklink_design ".
					"WHERE bricklink = '".$_POST['design']."' ".
					"AND type = '".$_POST['type']."'";
			$res = pg_fetch_all(pg_query($db, $SQL));
			if ($res)
			{
				$SQL = "SELECT design FROM lego.bricklink_design WHERE ".
						"bricklink = '".$_POST['olddesign']."' ".
						"AND \"type\" = '".$_POST['oldtype']."'";
				$res2 = pg_fetch_all(pg_query($db, $SQL));
				echo "Occupied by designID ".$res[0]['design']."<BR>\n";
				echo "Do you wish to merge them? <A HREF = 'index.php?what=obsolete_bricklink_design&amp;do=merge&amp;design1=".$res[0]['design']."&amp;design2=".$res2[0]['design']."'>YES</A><BR><BR>\n";
			}
			else
			{
				$SQL = "DELETE FROM lego.bricklink_price WHERE design = '".$_POST['olddesign']."'".
						"AND design_type = '".$_POST['oldtype']."'";
				pg_query($db, $SQL);
				$SQL = "DELETE FROM lego.inventory_user WHERE design = '".$_POST['olddesign']."' ".
						"AND \"type\" = '".$_POST['oldtype']."'";
				pg_query($db, $SQL);
				$SQL = "DELETE FROM lego.inventory WHERE (parent_design = '".$_POST['olddesign']."' ".
						"AND parent_type = '".$_POST['oldtype']."') OR (child_design = '".$_POST['olddesign']."' ".
						"AND child_type = '".$_POST['oldtype']."')";
				pg_query($db, $SQL);
				$SQL = "UPDATE lego.bricklink_design SET bricklink = '".$_POST['design']."'".
						", \"type\" = '".$_POST['type']."'".
						", obsolete = FALSE ".
						"WHERE bricklink = '".$_POST['olddesign']."' ".
						"AND \"type\" = '".$_POST['oldtype']."'";
				pg_query($db, $SQL);
			}
		}
		if ($_GET['do'] == 'merge' and isset($_GET['design1']) and isset($_GET['design2']))
		{
			$res = merge2($_GET['design1'], $_GET['design2']);
			echo "Successfully merged ".$res[0]." and ".$res[1].".<BR><BR>\n";
		}
		$SQL = "SELECT bd.bricklink, bd.\"type\", d.description ".
				"FROM lego.bricklink_design bd, lego.design d ".
				"WHERE bd.design = d.id ".
				"AND bd.obsolete IS TRUE ".
				"ORDER BY d.description";
		$obsoletes = pg_fetch_all(pg_query($db, $SQL));
		if ($obsoletes)
		{
			foreach ($obsoletes as $obsolete)
			{
				echo "<A HREF = 'index.php?what=obsolete_bricklink_design&amp;do=new&amp;design=".$obsolete['bricklink']."&amp;type=".$obsolete['type']."'>".$obsolete['description']."</A><BR>\n";
			}
		}
	}
}