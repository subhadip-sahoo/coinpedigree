function initialize() {
	var options = {};
	var input = document.getElementById('address1');
	autocomplete = new google.maps.places.Autocomplete(input, options);

	google.maps.event.addListener(autocomplete, 'place_changed', function() {
	    var place = autocomplete.getPlace();

	    for (var i = 0; i < place.address_components.length; i++) {
	    
	        if (place.address_components[i].types[0] == "administrative_area_level_1") {
	            var statename = place.address_components[i].long_name;
	            document.getElementById('state').value = statename;
	        }
	        
	        if (place.address_components[i].types[0] == "administrative_area_level_2") {
	            var cityname = place.address_components[i].long_name;
	            document.getElementById('city').value = cityname;
	        }
	        
	    }
	});
	
}
google.maps.event.addDomListener(window, 'load', initialize);