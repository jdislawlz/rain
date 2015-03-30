$(document).ready(function(){
	var geocoder;
	var map;
	function successFunction(position) {
		var geolat = position.coords.latitude;
		var geolong = position.coords.longitude;
		console.log('Your latitude is :'+geolat+' and longitude is '+geolong);
		var url = "php/raininfo.php?latitude=";
		url = url+geolat;
		url = url+"&longitude="+geolong;
		console.log(url);
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
		var user_location = $("#address").val();
		var address = document.getElementById("address").value;
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var latlong = String(results[0].geometry.location);
				var thelat = latlong.substr(1,8);
				var thelong = latlong.substr(latlong.search(",")+2, 8);
				var url = "php/raininfo.php?latitude=";
				url = url+thelat;
				url = url+"&longitude="+thelong;
				url = url+"&address="+user_location;
				$('.user_input').fadeOut(350, function(){
					$('main.valign').css("top", "50%");
					$('.results').fadeIn(350, function(){
						$("#resultsinner").fadeOut(1, function(){
							$(".loading").fadeIn(1, function(){
								$.ajax({url:url,success:function(result){
									$(".loading").delay(1000).fadeOut(350, function(){
										$('main.valign').css("top", "47.5%");
										$("#resultsinner").html(result).fadeIn(350, function(){
											$(".again").fadeIn(500);
										});
									});
								}});
							});
						});
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
	$('.again').on('click', function(e){
		e.preventDefault();
		$('.results').fadeOut(350, function(){
			$('.user_input').fadeIn(350, function(){
				$('#resultsinner').empty();
				$(".again").fadeOut(350);
			});
		});
	});
});
