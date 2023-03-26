<?php
function no_lotid()
{
	include "../www/db.php";
	if ($_POST['submit'] == 'submit')
	{
		$tmpName  = $_FILES['xml']['tmp_name'];
		$fileSize = $_FILES['xml']['size'];
		$fp = fopen($tmpName, 'r');
		$lotid = '';
		$color = '';
		$type = '';
		$design = '';
		$container = '';
		while (($line = fgets($fp, $fileSize)) != false)
		{
			$line = ltrim($line);
			$line = rtrim($line);
			if ($line == '<INVENTORY>')
			{
				$inventory = 1;
			}
			if ($line == '</INVENTORY>')
			{
				$inventory = 0;
			}
			if ($line == '<ITEM>' and $inventory = 1)
			{
				$item = 1;
			}
			if ($line == '</ITEM>' and $inventory = 1)
			{
				$item = 0;
				$SQL = "UPDATE lego.bricklink SET lotid = '".$lotid."' WHERE color = (SELECT color FROM lego.bricklink_color WHERE bricklink = ".$color.") AND design = (SELECT design FROM lego.bricklink_design WHERE bricklink = '".$design."' AND \"type\" = '".$type."') AND container = '".$container."' AND \"user\" = ".$_COOKIE['user'];
				pg_query($db, $SQL);
			}
			if (substr($line, 0, 7) == '<LOTID>' and $inventory and $item)
			{
				$lotid = substr($line, 7, -8);
			}
			if (substr($line, 0, 7) == '<COLOR>' and $inventory and $item)
			{
				$color = substr($line, 7, -8);
			}
			if (substr($line, 0, 10) == '<ITEMTYPE>' and $inventory and $item)
			{
				$type = substr($line, 10, -11);
			}
			if (substr($line, 0, 8) == '<ITEMID>' and $inventory and $item)
			{
				$design = substr($line, 8, -9);
			}
			if (substr($line, 0, 9) == '<REMARKS>' and $inventory and $item)
			{
				$container = substr($line, 9, -10);
			}
		}
	}
	echo "<FORM ACTION = 'index.php?what=no_lotid' METHOD = 'POST' enctype=\"multipart/form-data\">\n";
	echo 'Submit new XML-file.';
	echo "<input type='hidden' name='MAX_FILE_SIZE' value='1000000000'>\n";
	echo "<INPUT TYPE = 'file' NAME = 'xml' ID = 'xml'><BR>\n";
	echo "<INPUT TYPE = 'submit' NAME = 'submit' ID = 'submit' VALUE = 'submit'>\n";
}
?>