<?php

// Utility script to convert CSV files created by the Land Information
// New Zealand tidal service into one entry per line CSv for the run.php
// script

$in = fopen('data.csv', 'r');
$out = fopen('out.csv', 'w');

// First day of the file
$day = 1;
$month = 1;
$year = 2009;

// Is the first tide high?
$isHigh = true;

while (($data = fgetcsv($in)) !== FALSE) {
	$day = $data[0];
	$month = $data[2];
	$year = $data[3];
	
	// First tide of the day
	$time = $data[4];
	$height = $data[5];
	fwrite($out, $day.','.$month.','.$year.','.$time.','.$height);
	if ($isHigh) { fwrite($out, ',h'); } else { fwrite($out, ',l'); }
	fwrite($out, "\n");
	$isHigh = !$isHigh;

	// Second tide of the day
	$time = $data[6];
	$height = $data[7];
	fwrite($out, $day.','.$month.','.$year.','.$time.','.$height);
	if ($isHigh) { fwrite($out, ',h'); } else { fwrite($out, ',l'); }
	fwrite($out, "\n");
	$isHigh = !$isHigh;

	// Optional third tide of the day
	$time = $data[8];
	$height = $data[9];
	fwrite($out, $day.','.$month.','.$year.','.$time.','.$height);
	if ($isHigh) { fwrite($out, ',h'); } else { fwrite($out, ',l'); }
	fwrite($out, "\n");
	$isHigh = !$isHigh;
	
	// Optional fourth tide of the day
	if ($data[10] != '') {
		$time = $data[10];
		$height = $data[11];
		fwrite($out, $day.','.$month.','.$year.','.$time.','.$height);
		if ($isHigh) { fwrite($out, ',h'); } else { fwrite($out, ',l'); }
		fwrite($out, "\n");
		$isHigh = !$isHigh;
	}
}

fclose($in);
fclose($out);

?>
