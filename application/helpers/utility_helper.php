<?php
function format_bytes($bytes, $is_drive_size=true, $beforeunit='<span>', $afterunit='</span>')
{
    $labels = array('B','KB','MB','GB','TB');
    for($x = 0; $bytes >= 1000 && $x < (count($labels) - 1); $bytes /= 1000, $x++); // use 1000 rather than 1024 to simulate HD size not real size
    if($labels[$x] == "TB") return(round($bytes, ($is_drive_size)?1:2).$beforeunit.$labels[$x].$afterunit);
    else return(round($bytes, ($is_drive_size)?0:2).$beforeunit.$labels[$x].$afterunit);
}

function formatram($bytes)    {
    $labels = array('B','KB','MB','GB','TB');
    for($x = 0; $bytes >= 1024 && $x < (count($labels) - 1); $bytes /= 1024, $x++); 
    return round($bytes, 1).$labels[$x];
}


function time_ago($date,$timestamp=false,$diff=true, $granularity=2) {
	$date = $timestamp===true ? $date : strtotime($date);
	$difference = ($diff === true) ? (time() - $date) : $date;
	$retval = '';
	$periods = array('decade' => 315360000,
		'year' => 31536000,
		'month' => 2628000,
		'week' => 604800, 
		'day' => 86400,
		'hour' => 3600,
		'minute' => 60,
		'second' => 1);
								 
	foreach ($periods as $key => $value) {
		if ($difference >= $value) {
			$time = floor($difference/$value);
			$difference %= $value;
			$retval .= ($retval ? ' ' : '').'<span>'.$time.'</span>'.' ';
			$retval .= (($time > 1) ? $key.'s' : $key);
			$granularity--;
		}
		if ($granularity == '0') { break; }
	}
	return $retval;      
}

function time_to_ago($date,$timestamp=false,$diff=true, $granularity=2) {
	//die("here");
	$date = $timestamp===true ? $date : strtotime($date);
	$difference = ($diff === true) ? (time() - $date) : $date;
	$retval = '';
	$periods = array('decade' => 315360000,
		'year' => 31536000,
		'month' => 2628000,
		'week' => 604800, 
		'day' => 86400,
		'hour' => 3600,
		'minute' => 60,
		'second' => 1);
								 
	foreach ($periods as $key => $value) {
		if ($difference >= $value) {
			$time = round($difference/$value);
			$difference %= $value;
			$retval .= ($retval ? ' ' : '').'<span>'.$time.'</span>'.' ';
			$retval .= (($time > 1) ? $key.'s' : $key);
			$granularity--;
		}
		if ($granularity == '0') { break; }
	}
	return $retval;      
}

?>