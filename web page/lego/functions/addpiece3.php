<?php
function addpiece3()
{
	include "../www/db.php";
	$rep[] = '';
	if ($_POST['bricklink'])
	{
		$SQL = "SELECT design FROM lego.bricklink_design WHERE bricklink = '".$_POST['bricklink']."'";
		$legos = pg_fetch_all(pg_query($db, $SQL));
		if ($legos)
		{
			$_POST['design'] = $legos[0]['design'];
			$SQL = "UPDATE lego.design SET hide = FALSE WHERE id = ".$_POST['design'];
			pg_query($db, $SQL);
		}
	}
	if ($_POST['design'] == 0 AND !$_POST['bricklink'])
	{
		echo "<FORM ACTION = 'index.php?what=addpiece3' METHOD = 'POST'>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_POST['design']."'>\n";
		echo "Bricklink: <INPUT TYPE = 'text' NAME = 'bricklink' ID = 'bricklink'><BR>\n";
	}
	else
	{
		echo "<FORM ACTION = 'index.php?what=addpiece4' METHOD = 'POST'>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_POST['design']."'>\n";
		$SQL = "SELECT obsolete, replacing FROM lego.replacing WHERE what = 'color'";
		$res = pg_fetch_all(pg_query($db, $SQL));
		if ($res)
		{
			foreach ($res as $re)
			{
				$obsoletes[$re['obsolete']][] = $re['replacing'];
			}
		}
		$SQL = "SELECT dcu.color, c.\"Name\", dcu.free, blc.bricklink FROM lego.design_color_user dcu, lego.color c LEFT JOIN lego.bricklink_color blc ON blc.color = c.id WHERE dcu.color = c.id AND design = ".$_POST['design']." AND \"user\" = ".$_COOKIE['user']." ORDER BY c.\"Name\"";
		$legos = pg_fetch_all(pg_query($db, $SQL));
		if ($legos)
		{
			echo "<p>These color exists in your collection</p>";
			foreach ($legos as $lego)
			{
				if (isset($obsoletes[$lego['color']]) AND $lego['free'] > 0)
				{
					foreach ($obsoletes[$lego['color']] as $obsolete)
					{
						$rep[] = $obsolete;
					}
				}
			}
			foreach ($legos as $lego)
			{
				if (!array_search($lego['color'], $rep) AND (!isset($obsoletes[$lego['color']]) OR $lego['free'] > 0))
				{
					echo array_search($lego['color'], $rep);
					if ($lego['bricklink'])
					{
						$bricklink = ' (BL: '.$lego['bricklink'].")";
					}
					else
					{
						$bricklink = '';
					}
					echo "<INPUT TYPE = 'radio' NAME = 'color' ID = 'color' VALUE = '".$lego['color']."'><IMG SRC = 'picture.php?size=55&amp;design=".$_POST['design']."&amp;color=".$lego['color']."'>".utf8_decode($lego['Name']).$bricklink."<BR>\n";
				}
			}
		}
		$SQL = "SELECT dc.color, c.\"Name\", blc.bricklink FROM lego.design_color dc, lego.color c LEFT JOIN lego.bricklink_color blc ON blc.color = c.id WHERE dc.color = c.id AND design = ".$_POST['design']." AND dc.color NOT IN (SELECT dcu.color FROM lego.design_color_user dcu, lego.color c WHERE dcu.color = c.id AND design = ".$_POST['design']." AND \"user\" = ".$_COOKIE['user'].") ORDER BY c.\"Name\"";
		$legos = pg_fetch_all(pg_query($db, $SQL));
		if ($legos)
		{
			echo "<p>...and these exists in the database.</p>";
			foreach ($legos as $lego)
			{
				if (!array_search($lego['color'], $rep))
				{
					if ($lego['bricklink'])
					{
						$bricklink = ' (BL: '.$lego['bricklink'].")";
					}
					else
					{
						$bricklink = '';
					}
					echo "<INPUT TYPE = 'radio' NAME = 'color' ID = 'color' VALUE = '".$lego['color']."'><IMG SRC = 'picture.php?size=55&amp;design=".$_POST['design']."&amp;color=".$lego['color']."'>".utf8_decode($lego['Name']).$bricklink."<BR>\n";
				}
			}
		}
	}
	echo "<INPUT TYPE = 'radio' NAME = 'color' ID = 'color' VALUE = 'new' SELECTED>New color<BR>\n";
	echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'add'>\n";
	echo "</FORM>\n";
}
?>