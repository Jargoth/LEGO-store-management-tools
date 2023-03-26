<?php
function usepiece()
{
	include "../www/db.php";
	if ($_GET['step'] == '2')
		{
			echo "<FORM ACTION = 'index.php?what=usepiece&amp;step=3' METHOD = 'POST'>\n";
			echo "<TABLE BORDER = '1'>\n";
			$SQL = "SELECT d.description, d.id, (SELECT dcu.color FROM lego.design_color_user dcu WHERE dcu.design = d.id ORDER BY (dcu.used + dcu.free) DESC LIMIT 1) AS color FROM lego.design d, lego.design_color_user dcu WHERE d.id = dcu.design AND \"user\" = ".$_COOKIE['user']." AND description ILIKE '%".$_POST['description']."%' GROUP BY d.description, d.id ORDER BY d.description";
			$legos = pg_fetch_all(pg_query($db, $SQL));
			if ($legos)
			{
				foreach ($legos as $lego)
				{
					echo "<TR><TD><INPUT TYPE = 'radio' NAME = 'design' ID = 'design' VALUE = '".$lego['id']."'></TD><TD><A HREF = 'picture.php?size=400&amp;design=".$lego['id']."&amp;color=".$lego['color']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$lego['id']."&amp;color=".$lego['color']."'></A><A HREF = 'index.php?what=brick&amp;design=".$lego['id']."'>".utf8_decode($lego['description'])."</A></TD></TR>\n";
				}
			}
			echo "</TABLE>\n";
			echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'next'>\n";
			echo "</FORM>\n";
		}
		elseif ($_GET['step'] == '3')
		{
			echo "<FORM ACTION = 'index.php?what=usepiece&amp;step=4' METHOD = 'POST'>\n";
			echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_POST['design']."'>\n";
			if ($_POST['design'] == 0)
			{
			}
			else
			{
				$SQL = "SELECT dcu.color, c.\"Name\" FROM lego.design_color_user dcu, lego.color c WHERE dcu.color = c.id AND design = ".$_POST['design']." AND \"user\" = ".$_COOKIE['user']." ORDER BY c.\"Name\"";
				$legos = pg_fetch_all(pg_query($db, $SQL));
				if ($legos)
				{
					echo "<p>These color exists in your collection</p>";
					foreach ($legos as $lego)
					{
						echo "<INPUT TYPE = 'radio' NAME = 'color' ID = 'color' VALUE = '".$lego['color']."'><IMG SRC = 'picture.php?size=55&amp;design=".$_POST['design']."&amp;color=".$lego['color']."'>".utf8_decode($lego['Name'])."<BR>\n";
					}
				}
				
			}
			echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'add'>\n";
			echo "</FORM>\n";
		}
		elseif ($_GET['step'] == '4')
		{
			
			/*displays the picture of the current piece if there is one*/
			echo "<IMG SRC = 'picture.php?size=55&amp;design=".$_POST['design']."&amp;color=".$_POST['color']."'><BR><BR>\n";
		
			echo "<FORM ACTION = 'index.php?what=usepiece&amp;step=5' METHOD = 'POST' enctype=\"multipart/form-data\">\n";
			echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_POST['design']."'>\n";
			echo "<INPUT TYPE = 'hidden' NAME = 'color' ID = 'color' VALUE = '".$_POST['color']."'>\n";
			echo "Number to use: <INPUT TYPE = 'text' NAME = 'recycle' ID = 'recycle'><BR>\n";
			echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'recycle'>\n";
			echo "</FORM>\n";
		}
		else
		{
			if ($_GET['step'] == '5')
			{
				$SQL = "SELECT free, used FROM lego.design_color_user WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
				$lego = pg_fetch_all(pg_query($db, $SQL));
				$SQL = "UPDATE lego.design_color_user SET free = ".($lego[0]['free']-$_POST['recycle']).", used = ".($lego[0]['used']+$_POST['recycle'])." WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'];
				pg_query($db, $SQL);
				brickInProject($_POST['design'], $_POST['color']);
			}
			echo "<FORM ACTION = 'index.php?what=usepiece&amp;step=2' METHOD = 'POST'>\n";
			echo "Description: <INPUT TYPE = 'text' NAME = 'description' ID = 'design'>\n";
			echo "<INPUT TYPE = 'submit' NAME = 'submit_description' ID = 'submit_description' VALUE = 'search'><BR>\n";
			echo "</FORM>\n";
		}
}
?>