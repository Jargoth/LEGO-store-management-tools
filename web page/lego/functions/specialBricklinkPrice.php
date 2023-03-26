<?php
function specialBricklinkPrice($design, $color, $condition, $time, $what, $type)
{
	if ($condition == 'used' and $time == 'past' and $what == 'avg')
	{
		
		$files = array();
		$options = array('cookies' => array('viewCurrencyID' => '1'));
		$data = http_post_fields('http://www.bricklink.com/mbindex.asp', $arr, $files, $options);
		echo $data;
		$datas = explode("<", $data);
		$i = 0;
		foreach($datas as $data)
		{
			if ($data == 'FONT FACE="Verdana" SIZE="-2" COLOR="#FFFFFF">&nbsp;Past 6 Months Sales')
			{
				$past6 = 1;
			}
			elseif ($data == 'FONT FACE="Verdana" SIZE="-2" COLOR="#FFFFFF">&nbsp;Current Items for Sale')
			{
				$past6 = 0;
			}
			if ($past6)
			{
				if ($data == 'B>Used')
				{
					$used = 1;
				}
				elseif ($data == 'B>New')
				{
					$used = 0;
				}
				if ($used)
				{
					if ($data == 'FONT FACE="Verdana" SIZE="-2">Average&nbsp;')
					{
						$average = 1;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">Total Lots&nbsp;')
					{
						$average = 0;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">Total Qty&nbsp;')
					{
						$average = 0;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">Lowest&nbsp;')
					{
						$average = 0;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">By Qty Avg&nbsp;
')
					{
						$average = 0;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">Highest&nbsp;')
					{
						$average = 0;
					}
					if ($average)
					{
						$i++;
						if ($i == 5)
						{
							$data = split('&nbsp;', $data);
							return trim($data[2], '$');
						}
					}
				}
			}
		}
	}
	elseif ($condition == 'new' and $time == 'past' and $what == 'avg')
	{
		$arr = array(
		'itemType' => $type,
		'itemNo' => $design,
		'itemSeq' => '1',
		'colorID' => $color,
		'itemAction' => 'C'
		);
		$files = array();
		$options = array('cookies' => array('viewCurrencyID' => '1'));
		$data = http_post_fields('http://www.bricklink.com/mbindex.asp', $arr, $files, $options);
		$datas = split("<", $data);
		$i = 0;
		foreach($datas as $data)
		{
			if ($data == 'FONT FACE="Verdana" SIZE="-2" COLOR="#FFFFFF">&nbsp;Past 6 Months Sales')
			{
				$past6 = 1;
			}
			elseif ($data == 'FONT FACE="Verdana" SIZE="-2" COLOR="#FFFFFF">&nbsp;Current Items for Sale')
			{
				$past6 = 0;
			}
			if ($past6)
			{
				if ($data == 'B>Used')
				{
					$used = 1;
				}
				elseif ($data == 'B>New')
				{
					$used = 0;
				}
				if ($used == 0)
				{
					if ($data == 'FONT FACE="Verdana" SIZE="-2">Average&nbsp;')
					{
						$average = 1;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">Total Lots&nbsp;')
					{
						$average = 0;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">Total Qty&nbsp;')
					{
						$average = 0;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">Lowest&nbsp;')
					{
						$average = 0;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">By Qty Avg&nbsp;
')
					{
						$average = 0;
					}
					elseif ($data == 'FONT FACE="Verdana" SIZE="-2">Highest&nbsp;')
					{
						$average = 0;
					}
					if ($average)
					{
						$i++;
						if ($i == 5)
						{
							$data = split('&nbsp;', $data);
							return trim($data[2], '$');
						}
					}
				}
			}
		}
	}
	return 'error';
}
echo specialBrickLinkPrice($_GET['design'], $_GET['color'], $_GET['condition'], 'past', 'avg', $_GET['type']);
?>