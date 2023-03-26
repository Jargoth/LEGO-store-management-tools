<?php
function merge2($design1, $design2)
{
	include "../www/db.php";
	
	//kollar vilken som är minst och "prioriterar" denna
	if ($design1 < $design2)
	{
		$d1 = $design1;
		$d2 = $design2;
	}
	else
	{
		$d1 = $design2;
		$d2 = $design1;
	}
	
	//hämtar extra info
	$SQL = "SELECT bricklink, \"type\" FROM lego.bricklink_design WHERE design = ".$design1;
	$res = pg_fetch_all(pg_query($db, $SQL)) or die();
	$b1 = $res[0]['bricklink'];
	$t1 = $res[0]['type'];
	$SQL = "SELECT bricklink, \"type\" FROM lego.bricklink_design WHERE design = ".$design2;
	$res = pg_fetch_all(pg_query($db, $SQL));
	$b2 = $res[0]['bricklink'];
	$t2 = $res[0]['type'];
	
	//Uppdaterar LDraw-filnamn
	$SQL = "UPDATE lego.filename_design_color SET design = ".$d1." WHERE design = ".$d2;
	pg_query($db, $SQL) or die();
	
	//kopierar bilder
	$SQL = "SELECT * FROM lego.design_color WHERE design = ".$d2." AND color NOT IN (SELECT color FROM lego.design_color WHERE design = ".$d1.")";
	$legos = pg_fetch_all(pg_query($db, $SQL));
	if ($legos)
	{
		foreach ($legos as $lego)
		{
			$SQL = "INSERT INTO lego.design_color (design, color, picture_data, picture_size, picture_type, picture_photo, picture_replace, date_added, picture_photo_checked, approved) ".
			"VALUES (".$d1.
			", ".$lego['color'].
			", '".pg_escape_bytea(pg_unescape_bytea($lego['picture_data']))."'".
			", ".$lego['picture_size'].
			", '".$lego['picture_type']."'".
			", '".$lego['picture_photo']."'".
			", '".$lego['picture_replace']."'".
			", now()".
			", now()".
			", '".$lego['approved']."'".
			")";
			pg_query($db, $SQL) or die();
		}
	}
	
	//uppdaterar saldot
	$SQL = "SELECT color, \"user\", free, used FROM lego.design_color_user WHERE design = ".$d1;
	$legos1 = pg_fetch_all(pg_query($db, $SQL));
	$SQL = "SELECT color, \"user\", free, used FROM lego.design_color_user WHERE design = ".$d2;
	$legos2 = pg_fetch_all(pg_query($db, $SQL));
	if ($legos2)
	{
		foreach ($legos2 as $lego2)
		{
			$i = 0;
			if ($legos1)
			{
				foreach ($legos1 as $lego1)
				{
					if ($lego1['color'] == $lego2['color'] and $lego1['user'] == $lego2['user'])
					{
						$SQL = "UPDATE lego.design_color_user SET free = ".($lego1['free']+$lego2['free']).", used = ".($lego1['used']+$lego2['used']).
						" WHERE design = ".$d1." AND color = ".$lego1['color']." AND \"user\" = ".$lego1['user'];
						pg_query($db, $SQL) or die();
						$i = 1;
					}
				}
			}
			if ($i == 0)
			{
				$SQL = "UPDATE lego.design_color_user SET design = ".$d1." WHERE design = ".$d2." AND color = ".$lego2['color']." AND \"user\" = ".$lego2['user'];
				pg_query($db, $SQL) or die();
			}
			$SQL = "DELETE FROM lego.design_color_user WHERE design = ".$d2." AND color = ".$lego2['color']." AND \"user\" = ".$lego2['user'];
			pg_query($db, $SQL) or die();
		}
	}
	
	//tar bort bilder
	$SQL = "DELETE FROM lego.design_color WHERE design = ".$d2;
	pg_query($db, $SQL) or die();
	
	//uppdaterar priset
	$SQL = "UPDATE lego.bricklink_price SET design = '".$b1."', design_type = '".$t1."' ".
			"WHERE design = '".$b2."' AND design_type = '".$t2."'";
	pg_query($db, $SQL) or die();
	
	//uppdaterar inventory
	$SQL = "SELECT * FROM lego.inventory WHERE parent_design = '".$b1."' AND parent_type = '".$t1."'";
	$legos = pg_fetch_all(pg_query($db, $SQL));
	if ($legos)
	{
		$SQL = "DELETE FROM lego.inventory WHERE parent_design = '".$b2."' AND parent_type = '".$t2."'";
	}
	else
	{
		$SQL = "UPDATE lego.inventory SET parent_design = '".$b1."', parent_type = '".$t1."' WHERE parent_design = '".$b2."' AND parent_type = '".$t2."'";
	}
	pg_query($db, $SQL) or die();
	$SQL = "UPDATE lego.inventory SET child_design = '".$b1."', child_type = '".$t1."' WHERE child_design = '".$b2."' AND child_type = '".$t2."'";
	pg_query($db, $SQL) or die();
	
	//tar bort bricklinknamnet
	if ($d1 == $design1)
	{
		$SQL = "DELETE FROM lego.bricklink_design WHERE design = ".$d2;
		pg_query($db, $SQL) or die();
		$SQL = "UPDATE lego.bricklink_design SET obsolete = FALSE WHERE design = ".$d1;
		pg_query($db, $SQL) or die();
	}
	else
	{
		$SQL = "DELETE FROM lego.bricklink_design WHERE design = ".$d1;
		pg_query($db, $SQL) or die();
		$SQL = "UPDATE lego.bricklink_design SET design = ".$d1.", obsolete = FALSE WHERE design = ".$d2;
		pg_query($db, $SQL) or die();
	}
	
	//uppdaterar till salu
	$SQL = "UPDATE lego.bricklink SET design = ".$d1." WHERE design = ".$d2;
	pg_query($db, $SQL) or die();
	
	//ta bort
	$SQL = "DELETE FROM lego.design WHERE id = ".$d2;
	pg_query($db, $SQL) or die();
	
	$r[0] = $d1;
	$r[1] = $d2;
	return $r;
}
?>