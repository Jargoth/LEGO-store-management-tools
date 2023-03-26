<?php
function addpiece4()
{
	include "../www/db.php";
	
	if ($_POST['color'] != 'new')
	{
		$SQL = "SELECT container FROM lego.design_color_user_container dcuc WHERE design = ".$_POST['design'].
				" AND color IN (SELECT color2 FROM lego.similar_colors sc WHERE color1 = ".$_POST['color'].")".
				" AND \"user\" = ".$_COOKIE['user'];
		$similar_colors = pg_fetch_all(pg_query($db, $SQL));
	}
			
	/*displays the picture of the current piece if there is one*/
	echo "<IMG SRC = 'picture.php?size=55&amp;design=".$_POST['design']."&amp;color=".$_POST['color']."'><BR><BR>\n";
		
	echo "<FORM ACTION = 'index.php?what=addpiece5' METHOD = 'POST' enctype=\"multipart/form-data\">\n";
	echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000'>\n";
	if ($_POST['design'] == 0)
	{
		echo "Part number: <INPUT TYPE = 'text' NAME = 'part_number' ID = 'part_number'><BR>\n";
		echo "Part name: <INPUT TYPE = 'text' NAME = 'part_name' ID = 'part_name' SIZE = '75'><BR>\n";
	}
	else
	{
		echo "<INPUT TYPE = 'hidden' NAME = 'design' ID = 'design' VALUE = '".$_POST['design']."'>\n";
	}
	if ($_POST['color'] == 'new')
	{
		$SQL = "SELECT c.\"Name\", c.id, blc.bricklink ".

				"FROM lego.color c LEFT JOIN lego.bricklink_color blc ON blc.color = c.id ".

				"WHERE c.id NOT IN (SELECT color FROM lego.design_color WHERE design = ".$_POST['design'].") ".
				"AND c.id NOT IN (SELECT obsolete FROM lego.replacing WHERE what = 'color') ".
				"AND c.id NOT IN ".
				  "(SELECT replacing FROM lego.replacing WHERE what = 'color' AND obsolete IN ".
				    "(SELECT color FROM lego.design_color_user WHERE free > 0 AND \"user\" = ".$_COOKIE['user']." AND design = ".$_POST['design']." AND color IN ".
				      "(SELECT obsolete FROM lego.replacing WHERE what = 'color')".
				    ")".
				  ") ".

				"ORDER BY c.\"Name\"";
		$colors = pg_fetch_all(pg_query($db, $SQL));
		echo "Color: <SELECT NAME = 'color' ID = 'color' SIZE = '1'>\n";
		foreach ($colors as $color)
		{
##			$SQL = "SELECT ldraw FROM lego.color_ldraw WHERE color = ".$color['id'];
##			$ldraws = pg_fetch_all(pg_query($db, $SQL));
			if ($ldraws)
			{
				$ld = " ( LD:";
				foreach ($ldraws as $ldraw)
				{
					$ld = $ld." ".$ldraw['ldraw'];
				}
				$ld = $ld.")";
			}
			else
			{
				$ld = "";
			}
			if ($color['bricklink'])
			{
				$bricklink = " (BL: ".$color['bricklink'].")";
			}
			else
			{
				$bricklink = "";
			}
			echo "<OPTION VALUE = '".$color['id']."'>".$color['Name'].$bricklink.$ld."\n";
		}
		echo "</SELECT><BR>\n";
		echo "Picture: <INPUT TYPE = 'file' NAME = 'picture' ID = 'picture'><BR>\n";
		echo "<INPUT TYPE = 'hidden' NAME = 'new_color' ID = 'new_color'>\n";
	}
	else
	{
		echo "<INPUT TYPE = 'hidden' NAME = 'color' ID = 'color' VALUE = '".$_POST['color']."'>\n";
	}
	echo "Number used: <INPUT TYPE = 'text' NAME = 'used' ID = 'used'><BR>\n";
	echo "Number free: <INPUT TYPE = 'text' NAME = 'free' ID = 'free'><BR>\n";
	echo "<INPUT TYPE = 'checkbox' NAME = 'new' ID = 'new'>New<BR>\n";
	if ($_COOKIE['user'] == 1)
	{
		$SQL = "SELECT * FROM lego.bricklink_design WHERE design = ".$_POST['design'];
		$res = pg_fetch_all(pg_query($db, $SQL));
		if (!$res)
		{
			echo "Bricklink id: <INPUT TYPE = 'text' NAME = 'bricklink' ID = 'bricklink'><BR>\n";
		}
	}
	if ($_POST['color'] == 'new')
	{
		$SQL = "SELECT id FROM lego.container co ORDER BY id";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			echo "<INPUT TYPE = 'radio' NAME = 'container' ID = 'container' VALUE = 'container_existing'>";
			echo "<SELECT NAME = 'container_existing' ID = 'container_existing'>\n";
			foreach ($ans as $an)
			{
				echo "<OPTION VALUE = '".$an['id']."'>".$an['id']."\n";
			}
			echo "</SELECT><BR>\n";
		}
	}
	else
	{
		$SQL = "SELECT container, bricks, c.\"full\", c.condition FROM lego.design_color_user_container dcuc, lego.container c WHERE dcuc.container = c.id AND dcuc.\"user\" = c.\"user\" AND design = ".$_POST['design']." AND color = ".$_POST['color']." AND dcuc.\"user\" = ".$_COOKIE['user']." ORDER BY container";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			foreach ($ans as $an)
			{
				if ($an['condition'] == 'n')
				{
					$font_color = 'lightgreen';
				}
				else
				{
					$font_color = 'black';
				}
				$SQL = "SELECT FROM lego.design_color_user_container dcuc WHERE dcuc.container = '".$an['container']."' AND dcuc.design = ".$_POST['design']." AND dcuc.color <> ".$_POST['color']." AND dcuc.\"user\" = ".$_COOKIE['user'];
				$ans2 = pg_fetch_all(pg_query($db, $SQL));
				if ($ans2)
				{
					$font_color = 'orange';
				}
				if ($similar_colors)
				{
					foreach ($similar_colors as $similar_color)
					{
						if ($similar_color['container'] == $an['container'])
						{
							$font_color = 'blue';
						}
					}
				}
				if ($an['full'] > 0)
				{
					$font_color = 'red';
				}
				$SQL = "SELECT sum(collected) as collected FROM lego.project_bricks_container pbc, lego.project p WHERE p.\"order\" = pbc.project AND pbc.design = ".$_POST['design']." AND pbc.color = ".$_POST['color']." AND p.\"user\" = ".$_COOKIE['user']." AND container = '".$an['container']."'";
				$ans2 = pg_fetch_all(pg_query($db, $SQL));
				if ($ans2)
				{
					$an['bricks'] = ($an['bricks'] - $ans2[0]['collected']);
				}
				echo "<INPUT TYPE = 'radio' NAME = 'container' ID = 'container' VALUE = '".$an['container']."'>";
				echo "<FONT color = \"".$font_color."\">".$an['container']." (".$an['bricks'].")</FONT><BR>";
			}
		}
		$SQL = "SELECT id, \"full\", condition FROM lego.container co WHERE id NOT IN (SELECT container FROM lego.design_color_user_container WHERE design = ".$_POST['design']." AND color = ".$_POST['color']." AND \"user\" = ".$_COOKIE['user'].") ORDER BY id";
		$ans = pg_fetch_all(pg_query($db, $SQL));
		if ($ans)
		{
			echo "<INPUT TYPE = 'radio' NAME = 'container' ID = 'container' VALUE = 'container_existing'>";
			echo "<SELECT NAME = 'container_existing' ID = 'container_existing'>\n";
			foreach ($ans as $an)
			{
				if ($an['condition'] == 'n')
				{
					$font_color = 'lightgreen';
				}
				else
				{
					$font_color = 'black';
				}
				$SQL = "SELECT FROM lego.design_color_user_container dcuc WHERE dcuc.container = '".$an['id']."' AND dcuc.design = ".$_POST['design']." AND dcuc.color <> ".$_POST['color']." AND dcuc.\"user\" = ".$_COOKIE['user'];
				$ans2 = pg_fetch_all(pg_query($db, $SQL));
				if ($ans2)
				{
					$font_color = 'orange';
				}
				if ($similar_colors)
				{
					foreach ($similar_colors as $similar_color)
					{
						if ($similar_color['container'] == $an['id'])
						{
							$font_color = 'blue';
						}
					}
				}
				if ($an['full'] > 0)
				{
					$font_color = 'red';
				}
				echo "<OPTION style = \"color:".$font_color.";\" VALUE = '".$an['id']."'>".$an['id']."\n";
			}
			echo "</SELECT><BR>\n";
		}
	}
	echo "<INPUT TYPE = 'radio' NAME = 'container' ID = 'container' VALUE = 'container_new'>";
	echo "<INPUT TYPE = 'text' NAME = 'container_new' ID = 'container_new'><BR>";
	echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'add'>\n";
	echo "</FORM>\n";
}
?>