<?php

// Run this script every minute using cron
// Adjust the '/path/to/' entries in this file (3 occurences)
// and the name of your twitter account and password and
// the location of your tides CSV

$in = fopen('/path/to/tides.csv', 'r');
run($in, 'twitter-user-name', 'twitter-password', 'Name of tidal region');
fclose($in);

function run($in, $twitteruser, $twitterpassword, $tide) {
	$day = date('j');
	$month = date('n');
	$year = date('Y');
	$time = date('H:i');
	while (($data = fgetcsv($in)) !== FALSE) {
		if (($data[0] == $day) &&
		    ($data[1] == $month) &&
		    ($data[2] == $year) &&
		    ($data[3] == $time)) {
			// We have a match
			$m = date('M');
			$next = fgetcsv($in);
			$nextday = $next[0];
			$nextt = $next[3];
			$nexth = $next[4];
			if ($data[5] == 'h') {
				$body = "{$tide}: High tide @ $time {$day}-{$m}-{$year} ({$data[4]}m) Low tide due at $nextt ({$nexth}m)";
			} else {
				$body = "{$tide}: Low tide @ $time {$day}-{$m}-{$year} ({$data[4]}m) High tide due at $nextt ({$nexth}m)";
			}
			if ($nextday > $day) {
				$body .= ' tomorrow';
			}
		
			// Set things up
			set_include_path('/path/to/Arc90_Service_Twitter/lib');
			require_once('/path/to/Arc90_Service_Twitter/lib/Arc90/Service/Twitter.php');
			$twitter = new Arc90_Service_Twitter($twitteruser, $twitterpassword);
			$result = $twitter->updateStatus($body, 0);
			
			// If an error occured (usually API is too busy), sleep 10 seconds, and try again
			if ($result->http_code != 200) {
				sleep(10);
				$twitter = new Arc90_Service_Twitter($twitteruser, $twitterpassword);
				$result = $twitter->updateStatus($body, 0);
			}
			if ($result->http_code != 200) {
				sleep(10);
				$twitter = new Arc90_Service_Twitter($twitteruser, $twitterpassword);
				$result = $twitter->updateStatus($body, 0);
			}
			if ($result->http_code != 200) {
				sleep(10);
				$twitter = new Arc90_Service_Twitter($twitteruser, $twitterpassword);
				$result = $twitter->updateStatus($body, 0);
			}
		}
	}
}
?>
