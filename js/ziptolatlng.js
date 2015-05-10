$(function(){
	$("#former_submit").click(function(){
		geocoder = new google.maps.Geocoder();
		var address = document.getElementById('zipcode').value;
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var lat = results[0].geometry.location.lat();
				var lng = results[0].geometry.location.lng();
				document.getElementById('latitude').value = lat;
				document.getElementById('longitude').value = lng;
				
				$("#submit").click();
				
			} else {
				alert(status);
			}
		});
	});
});