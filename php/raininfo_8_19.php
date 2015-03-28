<?php
error_reporting(E_ALL &~E_NOTICE &~E_WARNING &~E_DEPRECATED);

	$url = "https://api.forecast.io/forecast/713a2259fbd56cbc71cbcc0037eae14b/28.552299,-81.179489";

	$json = file_get_contents($url);
	$obj = json_decode($json, true);
	$rain = $obj[currently][precipIntensity];

	date_default_timezone_set('America/New_York');
	$time = date('h:i a');
	$date = date('m/d/Y');

	$unix;

	$minintense = array();
	$minprob = array();
	$mintime = array();
	$break;

	$hourintense = array();
	$hourprob = array();
	$hourtime = array();

	for($x=0; $x<sizeOf($obj[minutely][data]); $x++){
		$mintensity = round($obj[minutely][data][$x][precipIntensity], 3);
		$mprobability = $obj[minutely][data][$x][precipProbability]*100;
		$mtime = date('h:i a', $obj[minutely][data][$x][time]);
		$break = $x;

		array_push($mintime, $mtime);
		array_push($minintense, $mintensity);
		array_push($minprob, $mprobability);

		//Find where the rain will stop and exit loop
		if($mintensity <= .002 && $mprobability <= 15 && $x>5){
			$unix=$obj[minutely][data][$x][time];
			break;
		}
	}
	echo $unix;

	for($x=0; $x<24; $x++){
		$hintensity = round($obj[hourly][data][$x][precipIntensity], 3);
		$hprobability = $obj[hourly][data][$x][precipProbability]*100;
		$htime = date('h:i a', $obj[hourly][data][$x][time]);

		array_push($hourtime, $htime);
		array_push($hourintense, $hintensity);
		array_push($hourprob, $hprobability);
	}

	$changetime = $obj[minutely][data][$break][time];
	$stoptime = date('h:i a', $changetime);
	$stopdate = date('m/d/Y', $changetime);
	$tilchange = $changetime-time();

	//var_dump($obj[currently]);

	if($rain < .002){
		$yesno = "No";
	} else if($rain <= .017 && $rain > .002) {
		$yesno = "Kinda";
	} else if($rain <= .1 && $rain > .017) {
		$yesno = "A little";
	} else if($rain <= .4 && $rain > .4) {
		$yesno = "Yes";
	} else {
		$yesno = "A Shit Load";
	}

	// .002  = very light
	// .017  = light
	// .1  = moderate
	// .4  = heavy

	echo("<h1>Is it Raining : $yesno</h1>");
	echo("Current Time : $time<br>");
	echo("The Rain will stop at : $stoptime on $stopdate in ");
	echo(round($tilchange/60)." minutes");
	/*echo("<h2>Minute Data</h2><table><tr>");

	for($x=1; $x<sizeOf($mintime); $x++){
		echo("<td style='padding:10px'><b>$mintime[$x]</b><br>i=$minintense[$x]<br>p=$minprob[$x]%</td>");
		if($x==10 | $x==20 | $x==30 | $x==40 | $x==50){
			echo("</tr><tr>");
		}
	}

	echo("</tr></table>");
	echo("<br><h2>Hour Data</h2><table><tr>");
	for($x=0; $x<24; $x++){
		echo("<td style='padding:10px'><b>$hourtime[$x]</b><br>i=$hourintense[$x]<br>p=$hourprob[$x]%</td>");
		if($x==7 | $x==15){
			echo("</tr><tr>");
		}
	}
	echo("</tr></table>");

	//var_dump($hourtime);
	//var_dump($hourprob);
	//var_dump($hourintense);
*/
	var_dump($mintime);
	//var_dump($obj[minutely]);
?>