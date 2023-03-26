<?php
function addpiece2()
{
	include "../www/db.php";
	echo "<FORM ACTION = 'index.php?what=addpiece3' METHOD = 'POST'>\n";
	echo "<TABLE BORDER = '1'>\n";
	if ($_POST['submit_design'])
	{
		$SQL = "SELECT description, id, (SELECT dcu.color FROM lego.design_color_user dcu WHERE dcu.design = d.id ORDER BY (dcu.used + dcu.free) DESC LIMIT 1) AS color FROM lego.design d WHERE hide IS FALSE AND primitive IS FALSE AND part_number = ".$_POST['design']." ORDER BY description";
		$lego = pg_fetch_all(pg_query($db, $SQL));
		if ($lego)
		{
			echo "<TR><TD><INPUT TYPE = 'radio' NAME = 'design' ID = 'design' VALUE = '".$lego[0]['id']."'></TD><TD><A HREF = 'picture.php?size=400&amp;design=".$lego[0]['id']."&amp;color=".$lego[0]['color']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$lego[0]['id']."&amp;color=".$lego[0]['color']."'></A><A HREF = 'index.php?what=brick&amp;design=".$lego[0]['id']."'>".utf8_decode($lego[0]['description'])."</A></TD></TR>\n";
		}
	}
	elseif ($_POST['submit_description'])
	{
		$SQL = 	"SELECT d.description, ".
				"d.id, ".
				"(SELECT dcu.color FROM lego.design_color_user dcu WHERE dcu.design = d.id ORDER BY (dcu.used + dcu.free) DESC LIMIT 1) AS color ".
				
				"FROM lego.design d FULL JOIN lego.design_color_user dcu ON d.id = dcu.design ".
				
				"WHERE d.hide IS FALSE AND d.description ILIKE '%".utf8_encode($_POST['description'])."%' ".
				"AND (".
				  "(d.id NOT IN (SELECT obsolete FROM lego.replacing WHERE what = 'design') AND d.id NOT IN (SELECT replacing FROM lego.replacing WHERE what = 'design')) ".
				  "OR (d.id IN (SELECT obsolete FROM lego.replacing WHERE what = 'design') AND free > 0) ".
				  "OR (d.id IN (SELECT replacing FROM lego.replacing WHERE what = 'design') AND d.id NOT IN (SELECT r.replacing FROM lego.design_color_user dcu, lego.replacing r WHERE dcu.design = r.obsolete AND dcu.design IN (SELECT obsolete FROM lego.replacing WHERE what = 'design') AND dcu.free > 0))".
				") ".
				
				"GROUP BY d.id, d.description ".
				"ORDER BY d.description";
		$legos = pg_fetch_all(pg_query($db, $SQL));
		if ($legos)
		{
			foreach ($legos as $lego)
			{
				echo "<TR><TD><INPUT TYPE = 'radio' NAME = 'design' ID = 'design' VALUE = '".$lego['id']."'></TD><TD><A HREF = 'picture.php?size=400&amp;design=".$lego['id']."&amp;color=".$lego['color']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$lego['id']."&amp;color=".$lego['color']."'></A><A HREF = 'index.php?what=brick&amp;design=".$lego['id']."'>".utf8_decode($lego['description'])."</A></TD></TR>\n";
			}
		}
	}
	echo "<TR><TD><INPUT TYPE = 'radio' NAME = 'design' ID = 'design' VALUE = '0' SELECTED></TD><TD>New part</TD></TR>\n";
	echo "</TABLE>\n";
	echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'add'>\n";
	echo "</FORM>\n";
}
?>