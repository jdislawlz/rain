$(document).ready(function(){
	var geocoder;
	var map;
	function successFunction(position) {
		var geolat = position.coords.latitude;
		var geolong = position.coords.longitude;
		//Your latitude is :'+geolat+' and longitude is '+geolong);
		var url = "php/raininfo.php?latitude=";
		url = url+geolat;
		url = url+"&longitude="+geolong;
		//alert(url);
		//alert(location+": Location confirmed. Sending supplies.");
		$("#resultsinner").fadeOut(500, function(){
			$(".loading").attr("src","img/loadingcloud4.gif");
			$(".loading").fadeIn(500, function(){
				$.ajax({url:url,success:function(result){
					$(".loading").delay(1000).fadeOut(500, function(){
						$("#resultsinner").html(result).fadeIn(500);
					});
				}});
			});
		});
	}
	function initialize() {
		geocoder = new google.maps.Geocoder();
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(successFunction);
		} else {
			alert('It seems like Geolocation, which is required for this page, is not enabled in your browser. Please use a browser which supports it.');
		}
	}
	function runit(evt){
		evt.preventDefault();
		var location = $("#address").val();
		var address = document.getElementById("address").value;
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var latlong = String(results[0].geometry.location);
				var thelat = latlong.substr(1,8);
				var thelong = latlong.substr(latlong.search(",")+2, 8);
				var url = "php/raininfo.php?latitude=";
				url = url+thelat;
				url = url+"&longitude="+thelong;
				url = url+"&address="+location;
				//alert(url);
				//alert(location+": Location confirmed. Sending supplies.");
				$("#resultsinner").fadeOut(500, function(){
					$(".loading").attr("src", "img/loadingcloud4.gif");
					$(".loading").fadeIn(500, function(){
						$.ajax({url:url,success:function(result){
							$(".loading").delay(1000).fadeOut(500, function(){
								$("#resultsinner").html(result).fadeIn(500);
							});
						}});
					});
				});
			} else {
				alert("Geocode was not successful for the following reason: " + status);
			}
		});
	}
	initialize();
	$("#submit").click(function(evt){
		runit(evt);
	});
	$('body').keypress(function (e) {
		if (e.which == 13) {
			e.preventDefault();
			runit(e);
		}
	});
});
