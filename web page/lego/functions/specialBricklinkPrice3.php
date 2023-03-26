<?php
$arr = array(
		'itemType' => 'P',
		'itemNo' => '3001',
		'itemSeq' => '1',
		'colorID' => '11',
		'itemAction' => 'C'
		);
$files = array();
$options = array('cookies' => array('viewCurrencyID' => '1'));
echo http_post_fields('http://www.bricklink.com/mbindex.asp', $arr, $files, $options)
?>