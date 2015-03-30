<?php
error_reporting(E_ALL &~E_NOTICE &~E_WARNING &~E_DEPRECATED);

	$url = "https://api.forecast.io/forecast/";
	$key = "713a2259fbd56cbc71cbcc0037eae14b";
	$lat = $_GET["latitude"];
	$long = $_GET["longitude"];
	//$lat = "28.5421";
	//$long = "-81.155943";
	$location = $_GET["address"];
	$url.=$key."/".$lat.",".$long;

	$json = file_get_contents($url);
	$obj = json_decode($json, true);
	$rain = $obj[currently][precipIntensity];

	date_default_timezone_set('America/New_York');
	$time = date('h:i a');
	$date = date('m/d/Y');

	$minintense = array();
	$minprob = array();
	$mintime = array();
	$break;
	$runhour = false;

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
		if($mintensity <= .002 && $mprobability <= 15){
			break;
		}/*
		else if($x==sizeOf($obj[minutely][data])-1){
			$runhour = true;
		}*/
	}

	/*if($runhour==true){
		for($x=1; $x<24; $x++){
			$hintensity = round($obj[hourly][data][$x][precipIntensity], 3);
			$hprobability = $obj[hourly][data][$x][precipProbability]*100;
			$htime = date('h:i a', $obj[hourly][data][$x][time]);
			$break = $x;

			array_push($hourtime, $htime);
			array_push($hourintense, $hintensity);
			array_push($hourprob, $hprobability);

			if($hintensity <= .002 && $hprobability <= 15){
				break;
			}
		}
	}*/

	if($runhour==false){
		$changetime = $obj[minutely][data][$break][time];
		$tilchange = round(($changetime-time())/60)." minutes";
		if($tilchange<=0){ $tilchange = "0 minutes";}
	}
	else{
		$changetime = $obj[hourly][data][$break][time];
		$tilchange = "about ".round((round(($changetime-time())/60)/60), 1)." hours";
	}
	$stoptime = date('h:i a', $changetime);
	$stopdate = date('m/d/Y', $changetime);

	//var_dump($obj[currently]);

	/*if($rain < .002){
		$yesno = "It is not raining";
	} else if($rain <= .017 && $rain > .002) {
		$yesno = "There is some light rain";
	} else if($rain <= .1 && $rain > .017) {
		$yesno = "There is some rain";
	} else if($rain <= .4 && $rain > .4) {
		$yesno = "Yup, it's raining";
	} else {
		$yesno = "Holy balls it's a monsoon";
	}*/

	if($rain < .002){
		if($location){
			$yesno = "It's not raining in <span>$location</span>.";
		}
		else{
			$yesno = "It's not raining in your area.";
		}
	} else {
		if($runhour==false){
			$yesno = "The rain will stop in <span>$tilchange</span> around <span>$stoptime</span>";
		}
		else{
			$yesno = "The rain will end in over an hour.";
		}
	}

	//echo("Current Time : $time<br>");
	echo($yesno);

	// .002  = very light
	// .017  = light
	// .1  = moderate
	// .4  = heavy

	/*echo("<h1>Is it Raining : $yesno</h1>");
	echo("Current Time : $time<br>");
	echo("The Rain will stop at : $stoptime on $stopdate in ");
	echo(round($tilchange/60)." minutes");
	echo("<h2>Minute Data</h2><table><tr>");

	for($x=1; $x<sizeOf($mintime); $x++){
		echo("<td style='padding:10px'><b>$mintime[$x]</b><br>i=$minintense[$x]<br>p=$minprob[$x]%</td>");
		if($x==10 | $x==20 | $x==30 | $x==40 | $x==50){
			echo("</tr><tr>");
		}
	}

	echo("</tr></table>");
	echo("<br><h2>Hour Data</h2>");
	if($runhour==true){
		echo ("<table><tr>");
		for($x=0; $x<$break; $x++){
			echo("<td style='padding:10px'><b>$hourtime[$x]</b><br>i=$hourintense[$x]<br>p=$hourprob[$x]%</td>");
			if($x==7 | $x==15){
				echo("</tr><tr>");
			}
		}
		echo("</tr></table>");
	}*/

	/*
	echo "<pre>";
		var_dump($mintime);
	echo "</pre>";
	//var_dump($hourprob);
	//var_dump($hourintense);

	echo "<pre>";
	var_dump($obj[hourly]);
	echo "</pre>";
	echo "<pre>";
	var_dump($obj[minutely]);
	echo "</pre>";*/
?>