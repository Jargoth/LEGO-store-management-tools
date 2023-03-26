<?php
function lista()
{
	include "../www/db.php";
	if ($_GET['list'] == 'brick') /*list bricks*/
	{
		if ($_GET['sort'] == 1)
		{
			$lb1 = $_GET['lb1'];
			$lb2 = $_GET['lb2'];
			$lb3 = $_GET['lb3'];
			$lb4 = $_GET['lb4'];
			$lb5 = $_GET['lb5'];
			$SQL = "SELECT lb1, lb2, lb3, lb4, lb5 FROM lego.sort WHERE \"user\" = ".$_COOKIE['user'];
			$ans = pg_fetch_all(pg_query($db, $SQL));
			if ($ans)
			{
				$SQL = "UPDATE lego.sort SET lb1 = '".$lb1."', lb2 = '".$lb2."', lb3 = '".$lb3."', lb4 = '".$lb4."', lb5 = '".$lb5."' WHERE \"user\" = ".$_COOKIE['user'];
				pg_query($db, $SQL);
			}
			else
			{
				$SQL = "INSERT INTO lego.sort (lb1, lb2, lb3, lb4, lb5, \"user\") VALUES ('".$lb1."', '".$lb2."', '".$lb3."', '".$lb4."', '".$lb5."', ".$_COOKIE['user'].")";
				pg_query($db, $SQL);
			}
		}
		else
		{
			$SQL = "SELECT lb1, lb2, lb3, lb4, lb5 FROM lego.sort WHERE \"user\" = ".$_COOKIE['user']." AND lb1 IS NOT NULL";
			$ans = pg_fetch_all(pg_query($db, $SQL));
			if ($ans)
			{
				$lb1 = $ans[0]['lb1'];
				$lb2 = $ans[0]['lb2'];
				$lb3 = $ans[0]['lb3'];
				$lb4 = $ans[0]['lb4'];
				$lb5 = $ans[0]['lb5'];
			}
			else
			{
				$lb1 = 'd.description';
				$lb2 = 'colors DESC';
				$lb3 = 'used DESC';
				$lb4 = 'free DESC';
				$lb5 = 'total DESC';
			}
		}
		$sort = $lb1.", ".$lb2.", ".$lb3.", ".$lb4.", ".$lb5;
			
		if ($lb1 == "d.description") {$aDescription[1] = "d.description DESC";}
		else {$aDescription[1] = "d.description";}
		if ($lb1 != "d.description" AND $lb1 != "d.description DESC") {array_push($aDescription, $lb1);}
		if ($lb2 != "d.description" AND $lb2 != "d.description DESC") {array_push($aDescription, $lb2);}
		if ($lb3 != "d.description" AND $lb3 != "d.description DESC") {array_push($aDescription, $lb3);}
		if ($lb4 != "d.description" AND $lb4 != "d.description DESC") {array_push($aDescription, $lb4);}
		if ($lb5 != "d.description" AND $lb5 != "d.description DESC") {array_push($aDescription, $lb5);}
			
		if ($lb1 == "colors DESC") {$aColors[1] = "colors";}
		else {$aColors[1] = "colors DESC";}
		if ($lb1 != "colors" AND $lb1 != "colors DESC") {array_push($aColors, $lb1);}
		if ($lb2 != "colors" AND $lb2 != "colors DESC") {array_push($aColors, $lb2);}
		if ($lb3 != "colors" AND $lb3 != "colors DESC") {array_push($aColors, $lb3);}
		if ($lb4 != "colors" AND $lb4 != "colors DESC") {array_push($aColors, $lb4);}
		if ($lb5 != "colors" AND $lb5 != "colors DESC") {array_push($aColors, $lb5);}
			
		if ($lb1 == "used DESC") {$aUsed[1] = "used";}
		else {$aUsed[1] = "used DESC";}
		if ($lb1 != "used" AND $lb1 != "used DESC") {array_push($aUsed, $lb1);}
		if ($lb2 != "used" AND $lb2 != "used DESC") {array_push($aUsed, $lb2);}
		if ($lb3 != "used" AND $lb3 != "used DESC") {array_push($aUsed, $lb3);}
		if ($lb4 != "used" AND $lb4 != "used DESC") {array_push($aUsed, $lb4);}
		if ($lb5 != "used" AND $lb5 != "used DESC") {array_push($aUsed, $lb5);}
			
		if ($lb1 == "free DESC") {$aFree[1] = "free";}
		else {$aFree[1] = "free DESC";}
		if ($lb1 != "free" AND $lb1 != "free DESC") {array_push($aFree, $lb1);}
		if ($lb2 != "free" AND $lb2 != "free DESC") {array_push($aFree, $lb2);}
		if ($lb3 != "free" AND $lb3 != "free DESC") {array_push($aFree, $lb3);}
		if ($lb4 != "free" AND $lb4 != "free DESC") {array_push($aFree, $lb4);}
		if ($lb5 != "free" AND $lb5 != "free DESC") {array_push($aFree, $lb5);}
			
		if ($lb1 == "total DESC") {$aTotal[1] = "total";}
		else {$aTotal[1] = "total DESC";}
		if ($lb1 != "total" AND $lb1 != "total DESC") {array_push($aTotal, $lb1);}
		if ($lb2 != "total" AND $lb2 != "total DESC") {array_push($aTotal, $lb2);}
		if ($lb3 != "total" AND $lb3 != "total DESC") {array_push($aTotal, $lb3);}
		if ($lb4 != "total" AND $lb4 != "total DESC") {array_push($aTotal, $lb4);}
		if ($lb5 != "total" AND $lb5 != "total DESC") {array_push($aTotal, $lb5);}
			
		$SQL = "SELECT d.id, d.description, (SELECT count(dcu.color) FROM lego.design_color_user dcu WHERE d.id = dcu.design AND \"user\" = ".$_COOKIE['user'].") AS colors, (SELECT sum(dcu.used) FROM lego.design_color_user dcu WHERE dcu.design = d.id AND \"user\" = ".$_COOKIE['user'].") AS used, (SELECT sum(dcu.free) FROM lego.design_color_user dcu WHERE dcu.design = d.id AND \"user\" = ".$_COOKIE['user'].") AS free, (SELECT dcu.color FROM lego.design_color_user dcu WHERE dcu.design = d.id AND \user\" = ".$_COOKIE['user']." ORDER BY (dcu.used + dcu.free) DESC LIMIT 1) AS color,(SELECT sum(dcu.free + dcu.used) FROM lego.design_color_user dcu WHERE dcu.design = d.id AND \"user\" = ".$_COOKIE['user'].") AS total FROM lego.design d, lego.design_color_user dcu WHERE d.id = dcu.design AND dcu.\"user\" = ".$_COOKIE['user']." ORDER BY ".$sort;
		$legos = pg_fetch_all(pg_query($db, $SQL));
		echo "<TABLE BORDER = '1'>\n";
		echo "<TR>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=brick&amp;sort=1&amp;lb1=".$aDescription[1]."&amp;lb2=".$aDescription[2]."&amp;lb3=".$aDescription[3]."&amp;lb4=".$aDescription[4]."&amp;lb5=".$aDescription[5]."'>Part Name</A></TD>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=brick&amp;sort=1&amp;lb1=".$aColors[1]."&amp;lb2=".$aColors[2]."&amp;lb3=".$aColors[3]."&amp;lb4=".$aColors[4]."&amp;lb5=".$aColors[5]."'>Number of Colors</A></TD>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=brick&amp;sort=1&amp;lb1=".$aUsed[1]."&amp;lb2=".$aUsed[2]."&amp;lb3=".$aUsed[3]."&amp;lb4=".$aUsed[4]."&amp;lb5=".$aUsed[5]."'>Used Pieces</A></TD>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=brick&amp;sort=1&amp;lb1=".$aFree[1]."&amp;lb2=".$aFree[2]."&amp;lb3=".$aFree[3]."&amp;lb4=".$aFree[4]."&amp;lb5=".$aFree[5]."'>Free Pieces</A></TD>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=brick&amp;sort=1&amp;lb1=".$aTotal[1]."&amp;lb2=".$aTotal[2]."&amp;lb3=".$aTotal[3]."&amp;lb4=".$aTotal[4]."&amp;lb5=".$aTotal[5]."'>Total Pieces</A></TD>\n";
		echo "</TR>\n";
		foreach ($legos as $lego)
		{
			echo "<TR>\n";
			echo "<TD><A HREF = 'picture.php?size=400&amp;design=".$lego['id']."&amp;color=".$lego['color']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$lego['id']."&amp;color=".$lego['color']."'></A><A HREF = 'index.php?what=brick&amp;design=".$lego['id']."'>".utf8_decode($lego['description'])."</A></TD>\n";
			echo "<TD>".$lego['colors']."</TD>\n";
			echo "<TD>".$lego['used']."</TD>\n";
			echo "<TD>".$lego['free']."</TD>\n";
			echo "<TD>".$lego['total']."</TD>\n";
			echo "</TR>\n";
		}
		echo "</TABLE>\n";
	}
	if ($_GET['list'] == 'color') /*list colors*/
	{
		if ($_GET['sort'] == 1)
		{
			$lc1 = $_GET['lc1'];
			$lc2 = $_GET['lc2'];
			$lc3 = $_GET['lc3'];
			$lc4 = $_GET['lc4'];
			$lc5 = $_GET['lc5'];
			$SQL = "SELECT lc1, lc2, lc3, lc4, lc5 FROM lego.sort WHERE \"user\" = ".$_COOKIE['user'];
			$ans = pg_fetch_all(pg_query($db, $SQL));
			if ($ans)
			{
				$SQL = "UPDATE lego.sort SET lc1 = '".$lc1."', lc2 = '".$lc2."', lc3 = '".$lc3."', lc4 = '".$lc4."', lc5 = '".$lc5."' WHERE \"user\" = ".$_COOKIE['user'];
				pg_query($db, $SQL);
			}
			else
			{
				$SQL = "INSERT INTO lego.sort (lc1, lc2, lc3, lc4, lc5, \"user\") VALUES ('".$lc1."', '".$lc2."', '".$lc3."', '".$lc4."', '".$lc5."', ".$_COOKIE['user'].".)";
				pg_query($db, $SQL);
			}
		}
		else
		{
			$SQL = "SELECT lc1, lc2, lc3, lc4, lc5 FROM lego.sort WHERE \"user\" = ".$_COOKIE['user']." AND lc1 IS NOT NULL";
			$ans = pg_fetch_all(pg_query($db, $SQL));
			if ($ans)
			{
				$lc1 = $ans[0]['lc1'];
				$lc2 = $ans[0]['lc2'];
				$lc3 = $ans[0]['lc3'];
				$lc4 = $ans[0]['lc4'];
				$lc5 = $ans[0]['lc5'];
			}
			else
			{
				$lc1 = 'name';
				$lc2 = 'designs DESC';
				$lc3 = 'used DESC';
				$lc4 = 'free DESC';
				$lc5 = 'total DESC';
			}
		}
		$sort = $lc1.", ".$lc2.", ".$lc3.", ".$lc4.", ".$lc5;
			
		if ($lc1 == "name") {$aName[1] = "name DESC";}
		else {$aName[1] = 'name';}
		if ($lc1 != "name" AND $lc1 != "name DESC") {array_push($aName, $lc1);}
		if ($lc2 != "name" AND $lc2 != "name DESC") {array_push($aName, $lc2);}
		if ($lc3 != "name" AND $lc3 != "name DESC") {array_push($aName, $lc3);}
		if ($lc4 != "name" AND $lc4 != "name DESC") {array_push($aName, $lc4);}
		if ($lc5 != "name" AND $lc5 != "name DESC") {array_push($aName, $lc5);}
			
		if ($lc1 == "designs DESC") {$aDesigns[1] = "designs";}
		else {$aDesigns[1] = 'designs DESC';}
		if ($lc1 != "designs" AND $lc1 != "designs DESC") {array_push($aDesigns, $lc1);}
		if ($lc2 != "designs" AND $lc2 != "designs DESC") {array_push($aDesigns, $lc2);}
		if ($lc3 != "designs" AND $lc3 != "designs DESC") {array_push($aDesigns, $lc3);}
		if ($lc4 != "designs" AND $lc4 != "designs DESC") {array_push($aDesigns, $lc4);}
		if ($lc5 != "designs" AND $lc5 != "designs DESC") {array_push($aDesigns, $lc5);}
			
		if ($lc1 == "used DESC") {$aUsed[1] = "used";}
		else {$aUsed[1] = 'used DESC';}
		if ($lc1 != "used" AND $lc1 != "used DESC") {array_push($aUsed, $lc1);}
		if ($lc2 != "used" AND $lc2 != "used DESC") {array_push($aUsed, $lc2);}
		if ($lc3 != "used" AND $lc3 != "used DESC") {array_push($aUsed, $lc3);}
		if ($lc4 != "used" AND $lc4 != "used DESC") {array_push($aUsed, $lc4);}
		if ($lc5 != "used" AND $lc5 != "used DESC") {array_push($aUsed, $lc5);}
			
		if ($lc1 == "free DESC") {$aFree[1] = "free";}
		else {$aFree[1] = 'free DESC';}
		if ($lc1 != "free" AND $lc1 != "free DESC") {array_push($aFree, $lc1);}
		if ($lc2 != "free" AND $lc2 != "free DESC") {array_push($aFree, $lc2);}
		if ($lc3 != "free" AND $lc3 != "free DESC") {array_push($aFree, $lc3);}
		if ($lc4 != "free" AND $lc4 != "free DESC") {array_push($aFree, $lc4);}
		if ($lc5 != "free" AND $lc5 != "free DESC") {array_push($aFree, $lc5);}
			
		if ($lc1 == "total DESC") {$aTotal[1] = "total";}
		else {$aTotal[1] = 'total DESC';}
		if ($lc1 != "total" AND $lc1 != "total DESC") {array_push($aTotal, $lc1);}
		if ($lc2 != "total" AND $lc2 != "total DESC") {array_push($aTotal, $lc2);}
		if ($lc3 != "total" AND $lc3 != "total DESC") {array_push($aTotal, $lc3);}
		if ($lc4 != "total" AND $lc4 != "total DESC") {array_push($aTotal, $lc4);}
		if ($lc5 != "total" AND $lc5 != "total DESC") {array_push($aTotal, $lc5);}
			
		$SQL = "SELECT c.id, c.\"Name\" AS name, (SELECT count(dcu.design) FROM lego.design_color_user dcu WHERE c.id = dcu.color AND \"user\" = ".$_COOKIE['user'].") AS designs, (SELECT sum(dcu.used) FROM lego.design_color_user dcu WHERE dcu.color = c.id AND \"user\" = ".$_COOKIE['user'].") AS used, (SELECT sum(dcu.free) FROM lego.design_color_user dcu WHERE dcu.color = c.id AND \"user\" = ".$_COOKIE['user'].") AS free, (SELECT dcu.design FROM lego.design_color_user dcu WHERE dcu.color = c.id AND \"user\" = ".$_COOKIE['user']." ORDER BY (dcu.used + dcu.free) DESC LIMIT 1) AS design, (SELECT sum(dcu.used + dcu.free) FROM lego.design_color_user dcu WHERE dcu.color = c.id AND \"user\" = ".$_COOKIE['user'].") AS total FROM lego.color c, lego.design_color_user dcu WHERE c.id = dcu.color AND dcu.\"user\" = ".$_COOKIE['user']." ORDER BY ".$sort;
		$legos = pg_fetch_all(pg_query($db, $SQL));
		echo "<TABLE BORDER = '1'>\n";
		echo "<TR>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=color&amp;sort=1&amp;lc1=".$aName[1]."&amp;lc2=".$aName[2]."&amp;lc3=".$aName[3]."&amp;lc4=".$aName[4]."&amp;lc5=".$aName[5]."'>Color</A></TD>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=color&amp;sort=1&amp;lc1=".$aDesigns[1]."&amp;lc2=".$aDesigns[2]."&amp;lc3=".$aDesigns[3]."&amp;lc4=".$aDesigns[4]."&amp;lc5=".$aDesigns[5]."'>Number of Designs</A></TD>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=color&amp;sort=1&amp;lc1=".$aUsed[1]."&amp;lc2=".$aUsed[2]."&amp;lc3=".$aUsed[3]."&amp;lc4=".$aUsed[4]."&amp;lc5=".$aUsed[5]."'>Used Pieces</A></TD>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=color&amp;sort=1&amp;lc1=".$aFree[1]."&amp;lc2=".$aFree[2]."&amp;lc3=".$aFree[3]."&amp;lc4=".$aFree[4]."&amp;lc5=".$aFree[5]."'>Free Pieces</A></TD>\n";
		echo "<TD><A HREF = 'index.php?what=list&amp;list=color&amp;sort=1&amp;lc1=".$aTotal[1]."&amp;lc2=".$aTotal[2]."&amp;lc3=".$aTotal[3]."&amp;lc4=".$aTotal[4]."&amp;lc5=".$aTotal[5]."'>Total Pieces</A></TD>\n";
		echo "</TR>\n";
		foreach ($legos as $lego)
		{
			echo "<TR>\n";
			echo "<TD><A HREF = 'picture.php?size=400&amp;design=".$lego['design']."&amp;color=".$lego['id']."'><IMG BORDER = '0' SRC = 'picture.php?size=55&amp;design=".$lego['design']."&amp;color=".$lego['id']."'></A>".utf8_decode($lego['name'])."</TD>\n";
			echo "<TD>".$lego['designs']."</TD>\n";
			echo "<TD>".$lego['used']."</TD>\n";
			echo "<TD>".$lego['free']."</TD>\n";
			echo "<TD>".$lego['total']."</TD>\n";
			echo "</TR>\n";
		}
		echo "</TABLE>\n";
	}
}
?>