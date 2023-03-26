<?php
function modelLinePrepareInsert($brick)
{
	if ($brick[0] == '0')
	{
		if ($brick[1] == 'FILE')
		{
			$tmpbrick[0] = $brick[0];
			$tmpbrick[1] = $brick[1];
			$tmpbrick[2] = '';
			unset($brick[0]);
			unset($brick[1]);
			$first = 1;
			foreach ($brick as $b)
			{
				if (!$first)
				{
					$tmpbrick[2] = $tmpbrick[2].' ';
				}
				$first = 0;
				$tmpbrick[2] = $tmpbrick[2].$b;
			}
			$brick = $tmpbrick;
		}
		if ($brick[1] == 'WRITE')
		{
			$tmpbrick[0] = $brick[0];
			$tmpbrick[1] = $brick[1];
			$tmpbrick[2] = '';
			unset($brick[0]);
			unset($brick[1]);
			$first = 1;
			foreach ($brick as $b)
			{
				if (!$first)
				{
					$tmpbrick[2] = $tmpbrick[2].' ';
				}
				$first = 0;
				$tmpbrick[2] = $tmpbrick[2].$b;
			}
			$brick = $tmpbrick;
		}
	}
	if ($brick[0] == '1')
	{
		$tmpbrick[0] = $brick[0];
		$tmpbrick[1] = $brick[1];
		$tmpbrick[2] = $brick[2];
		$tmpbrick[3] = $brick[3];
		$tmpbrick[4] = $brick[4];
		$tmpbrick[5] = $brick[5];
		$tmpbrick[6] = $brick[6];
		$tmpbrick[7] = $brick[7];
		$tmpbrick[8] = $brick[8];
		$tmpbrick[9] = $brick[9];
		$tmpbrick[10] = $brick[10];
		$tmpbrick[11] = $brick[11];
		$tmpbrick[12] = $brick[12];
		$tmpbrick[13] = $brick[13];
		$tmpbrick[14] = '';
		unset($brick[0]);
		unset($brick[1]);
		unset($brick[2]);
		unset($brick[3]);
		unset($brick[4]);
		unset($brick[5]);
		unset($brick[6]);
		unset($brick[7]);
		unset($brick[8]);
		unset($brick[9]);
		unset($brick[10]);
		unset($brick[11]);
		unset($brick[12]);
		unset($brick[13]);
		$first = 1;
		foreach ($brick as $b)
		{
			if (!$first)
			{
				$tmpbrick[14] = $tmpbrick[14].' ';
			}
			$first = 0;
			$tmpbrick[14] = $tmpbrick[14].$b;
		}
		$brick = $tmpbrick;
	}
	return $brick;
}
?>