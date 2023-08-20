(function($) {
	"use strict";
	var placeSearch, autocomplete;
	initialize();
	current_location(0);
	
	var base_url=$('#base_url').val();
	var BASE_URL=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	var csrfName=$('#csrfName').val();
	var csrfHash=$('#csrfHash').val();
	$( document ).ready(function() {
		$('.current_location').on('click',function(){
			var id=$(this).attr('data-id');
			current_location(id);
		}); 
	});
	function initialize() {
    // Create the autocomplete object, restricting the search
    // to geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
    	/** @type {HTMLInputElement} */
    	(document.getElementById('user_address')), {
    		types: ['geocode']
    	});

    google.maps.event.addDomListener(document.getElementById('user_address'), 'focus', geolocate);
    autocomplete.addListener('place_changed', get_latitude_longitude);
}

function get_latitude_longitude() {
	// Get the place details from the autocomplete object.
	var place = autocomplete.getPlace(); console.log(place);
	 var key = $("#google_map_api").val();
	
	
	$.get('https://maps.googleapis.com/maps/api/geocode/json',{address:place.formatted_address,key:key},function(data, status){

		$(data.results).each(function(key,value){
			
			 $('#user_address').val(place.formatted_address);
             $('#user_latitude').val(value.geometry.location.lat);
             $('#user_longitude').val(value.geometry.location.lng);
			$.post(base_url+'home/set_location',{address:place.formatted_address,latitude:value.geometry.location.lat,longitude:value.geometry.location.lng,csrf_token_name:csrf_token});
		});
	});
}

function geolocate() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function (position) {
			var geolocation = new google.maps.LatLng(
				position.coords.latitude, position.coords.longitude);
			var circle = new google.maps.Circle({
				center: geolocation,
				radius: position.coords.accuracy
			});
			autocomplete.setBounds(circle.getBounds());
		});
	}
}

function current_location(session) {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	}
	else {
		alert("Geolocation is not supported by this browser.");
	}
	
	function showPosition(position) {
		var user_address=$('#user_address_values').val();
		var user_latitude=$('#user_latitude_values').val();
		var user_longitude=$('#user_longitude_values').val();
		var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		var geocoder = geocoder = new google.maps.Geocoder();
		geocoder.geocode({ 'latLng': latlng }, function (results, status) {

			if (status == google.maps.GeocoderStatus.OK) {
				if (results[3]) { 
					if(session==1) {
						$('#user_address').val(results[3].formatted_address);
						$('#user_latitude').val(position.coords.latitude);
						$('#user_longitude').val(position.coords.longitude);

						$.post(base_url+'home/set_location',{address:results[3].formatted_address,latitude:position.coords.latitude,longitude:position.coords.longitude,csrf_token_name:csrf_token})
					}
					else {
						if(user_address=='' && user_latitude=='' && user_longitude=='') {
							$('#user_address').val(results[3].formatted_address);
							$('#user_latitude').val(position.coords.latitude);
							$('#user_longitude').val(position.coords.longitude);
							$.post(base_url+'home/set_location',{address:results[3].formatted_address,latitude:position.coords.latitude,longitude:position.coords.longitude,csrf_token_name:csrf_token})
						}
					}
				}
			}
		});
	}
}

})(jQuery);
var modPage=$('#modules_page').val(); 
if(modPage=="home"){
	function clearlocation() {
		var base_url=$('#base_url').val();
		var csrf_token=$('#csrf_token').val();
		$.ajax({
			url:base_url+'home/clearlocation/',
			data:{csrf_token_name:csrf_token},
			type:"GET",
			success: function(res){
				$('#my_map').modal('hide');
				location.reload();
			}
		});		   
    }
    function setlocation() {
		var base_url=$('#base_url').val();
		var csrf_token=$('#csrf_token').val();
		var address  = $('#autocomplete').val();
		var latitude  = $('#user_latitude').val();
		var longitude  = $('#user_longitude').val();
		var country = $('#user_country').val();
		var distance = $('#distance').val();
		country = '';
		$.ajax({
			url:base_url+'home/setlocation/',
			data:{address:address,latitude:latitude,longitude:longitude,country:country,distance:distance,csrf_token_name:csrf_token},
			type:"POST",
			success: function(res){
				$('#my_map').modal('hide');
				location.reload();
			}
		});
	}
	function change_location() {
	  $('#my_map').modal('show');
	  initMapHome();
	  initializeHome();
	}
	function initMapHome() {
      var key = $('#google_map_api').val();
      var address = $('#autocomplete').val();
	  var language_option = $("#language_option").val();
      $.get('https://maps.googleapis.com/maps/api/geocode/json',{address:address,key:key,language:language_option},function(data, status){ 
        $(data.results).each(function(key,value){

            address   = value.formatted_address;
            latitude  = value.geometry.location.lat;
            longitude = value.geometry.location.lng;
            $(value.address_components).each(function(key1,value1){
              if(value1.types.includes('country')){
                $('#user_country').val(value1.long_name);
              }
            })
            load_msp_details(latitude, longitude)
             $('#autocomplete').val(address);
             $('#user_latitude').val(latitude);
             $('#user_longitude').val(longitude);
             
        });
      });      
    }
	var placeSearch, autocomplete, user_addr;
    function initializeHome() {
      autocomplete = new google.maps.places.Autocomplete(
      (document.getElementById('autocomplete')), {
          types: ['geocode']
      });
      google.maps.event.addDomListener(document.getElementById('autocomplete'), 'focus', geolocateHome);
      google.maps.event.addDomListener(autocomplete, 'place_changed', initMapHome);
    }
	function load_msp_details(latitude, longitude) {
		var uluru = {
		  lat: parseFloat(latitude),
		  lng: parseFloat(longitude)
		};
		var geocoder = new google.maps.Geocoder();
		var map = new google.maps.Map(document.getElementById('map'), {
		  zoom: 11,
		  center: uluru,
		  style:{background:"url('http://ressio.github.io/lazy-load-xt/dist/loading.gif') center center no-repeat" }
		});
		var marker = new google.maps.Marker({
		  position: uluru,
		  map: map,
		  draggable: true
		});
		var language_option = $("#language_option").val();
		google.maps.event.addListener(marker, 'dragend', function() {
		  geocoder.geocode({
			  'latLng': marker.getPosition(),
			  'language':language_option
		  }, function(results, status) {
			  if (status == google.maps.GeocoderStatus.OK) {
				  if (results[0]) {
					  address = results[0].formatted_address;
					  latitude = marker.getPosition().lat();
					  longitude = marker.getPosition().lng();
					  $('#autocomplete').val(address);
					  $('#user_latitude').val(latitude);
					  $('#user_longitude').val(longitude);
				  }
			  }
		  });
		});
    }
	function geolocateHome() { 
        var key = $('#google_map_api').val();       
        var lat = $('#user_latitude').val();
        var lng = $('#user_longitude').val();
		var language_option = $("#language_option").val();
        var latLng = lat+', '+lng;
        var adrs = '';
        $.get('https://maps.googleapis.com/maps/api/geocode/json',{latlng:latLng,key:key,language:language_option},function(data, status){
            $('#autocomplete').val(data.results[0].formatted_address);
            $('#user_latitude').val(lat);
            $('#user_longitude').val(lng);
        });     
    }
	$(document).on('input change', '#distance', function() {
        $('#slider_value').html( $(this).val() );
    });
}