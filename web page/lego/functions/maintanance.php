<?php
function maintanance()
{
	include "../www/db.php";
	$SQL = "SELECT mb.val2 FROM lego.model_bricks mb WHERE mb.val2 NOT IN (SELECT ldraw FROM lego.color_ldraw) GROUP BY mb.val2 ORDER BY mb.val2";
	$ans = pg_query($db, $SQL);
	$lego = pg_fetch_all($ans);
	if ($lego)
	{
		echo "<a href = 'index.php?what=color_ldraw'>\n";
		echo "<p>LDraw color missing (".pg_num_rows($ans).")</p></A>\n";
	}
	
	$SQL = "SELECT design, color FROM lego.design_color WHERE picture_replace IS TRUE";
	$ans = pg_query($db, $SQL);
	$lego = pg_fetch_all($ans);
	if ($lego)
	{
		echo "<a href = 'index.php?what=replace_picture'>\n";
		echo "<p>replace picture (".pg_num_rows($ans).")</p></A>\n";
	}
	
	$SQL = "SELECT model FROM lego.model_bricks_admin";
	$ans = pg_query($db, $SQL);
	$lego = pg_fetch_all($ans);
	if ($lego)
	{
		echo "<a href = 'index.php?what=add_file'>\n";
		echo "<p>add file (".pg_num_rows($ans).")</p></A>\n";
	}
	
	$SQL = "SELECT model FROM lego.model_line_error";
	$ans = pg_query($db, $SQL);
	$lego = pg_fetch_all($ans);
	if ($lego)
	{
		echo "<a href = 'index.php?what=err_line'>\n";
		echo "<p>fix error (".pg_num_rows($ans).")</p></A>\n";
	}

	$SQL = "SELECT d.description, d.id AS design, c.id AS color, c.\"Name\" FROM lego.model_bricks mb, lego.color_ldraw cl, lego.color c, lego.filename_design_color fdc, lego.design d WHERE d.id NOT IN (SELECT obsolete FROM lego.replacing WHERE what = 'design') AND c.id <> 0 AND d.primitive = FALSE AND fdc.color = 0 AND mb.val2 = cl.ldraw AND cl.color = c.id AND mb.val15 = fdc.filename AND d.id = fdc.design AND mb.id NOT IN (SELECT mb.id FROM lego.model_bricks mb, lego.color_ldraw cl, lego.color c, lego.filename_design_color fdc, lego.design_color dc WHERE mb.val2 = cl.ldraw AND cl.color = c.id AND mb.val15 = fdc.filename AND c.id = dc.color AND fdc.design = dc.design) GROUP BY d.description, d.id, c.id, c.\"Name\" ORDER BY d.description, c.\"Name\"";
	$ans = pg_query($db, $SQL);
	$lego = pg_fetch_all($ans);
	if ($lego)
	{
		echo "<a href = 'index.php?what=brick_no_picture'>\n";
		echo "<p>bricks without pictures (".pg_num_rows($ans).")</p></A>\n";
	}
	
	$SQL = "SELECT id FROM lego.design d, lego.design_color dc WHERE d.exclude_from_bricklink IS FALSE AND d.id NOT IN (SELECT obsolete FROM lego.replacing WHERE what = 'design') AND d.id = dc.design AND (date_part('month', age(bricklink)) >= 3 OR date_part('year', age(bricklink)) >= 1) AND primitive IS FALSE AND id NOT IN (SELECT design FROM lego.bricklink_design)";
	$ans = pg_query($db, $SQL);
	if (pg_num_rows($ans))
	{
		echo "<a href = 'index.php?what=design_without_bricklink'>\n";
		echo "<p>Design without BrickLink (".pg_num_rows($ans).")</p></A>\n";
	}
	
	$SQL = "SELECT id FROM lego.color WHERE exclude_from_bricklink IS FALSE AND date_part('month', age(bricklink)) >= 3 AND id NOT IN (SELECT color FROM lego.bricklink_color)";
	$ans = pg_query($db, $SQL);
	if (pg_num_rows($ans))
	{
		echo "<a href = 'index.php?what=color_without_bricklink'>\n";
		echo "<p>Color without BrickLink (".pg_num_rows($ans).")</p></A>\n";
	}
	
	$SQL = "SELECT obsolete FROM lego.bricklink_design WHERE obsolete IS TRUE";
	$ans = pg_query($db, $SQL);
	if (pg_num_rows($ans))
	{
		echo "<a href = 'index.php?what=obsolete_bricklink_design'>\n";
		echo "<p>Obsolete Bricklink Designs (".pg_num_rows($ans).")</p></A>\n";
	}
	
	$SQL = "SELECT dcu.design, dcu.color FROM lego.design_color_user dcu, ".
			"lego.bricklink_design bd, lego.bricklink_color bc ".
			"WHERE bd.design = dcu.design ".
			"AND bc.color = dcu.color ".
			"AND (bd.bricklink, bd.\"type\", bc.bricklink) NOT IN (SELECT design, design_type, color FROM lego.bricklink_price) ".
			"ORDER BY dcu.design, dcu.color";
	$ans = pg_query($db, $SQL);
	if (pg_num_rows($ans))
	{
		echo "<a href = 'index.php?what=brick_no_price'>\n";
		echo "<p>Bricks without price (".pg_num_rows($ans).")</p></A>\n";
	}
	
	$SQL = "SELECT bd.deletion, bd.design FROM lego.bricklink_design bd, lego.design_color_user dcu WHERE dcu.design = bd.design AND bd.deletion IS TRUE GROUP BY bd.design, bd.deletion";
	$ans = pg_query($db, $SQL);
	if (pg_num_rows($ans))
	{
		echo "<a href = 'index.php?what=marked_for_deletion'>\n";
		echo "<p>Bricks marked for deletion (".pg_num_rows($ans).")</p></A>\n";
	}
	
	$SQL = "SELECT * FROM lego.bricklink WHERE lotid IS NULL";
	$ans = pg_query($db, $SQL);
	if (pg_num_rows($ans))
	{
		echo "<a href = 'index.php?what=no_lotid'>\n";
		echo "<p>Lots without lotid (".pg_num_rows($ans).")</p></A>\n";
	}
}
?>