// This example adds a user-editable rectangle to the map.
// When the user changes the bounds of the rectangle,
// an info window pops up displaying the new bounds.
var rectangle;
var map;
var infoWindow, infoWindows;

function initialize() {
	var latt = $('#shp_lat').val();  
	var lngg = $('#shp_lng').val();
	var addr = $('#shp_loc').val();
	
	var ShopLoc = $("#ShopLocMsp").val();
	
	const locString = '<h5>'+ShopLoc+'</h5> <p>'+addr+'</p>' ;
	
    var map_options = {
		zoom: 11,
		center: new google.maps.LatLng(latt, lngg)
	} 
	map = new google.maps.Map(document.getElementById("homeservice_map"), map_options);
	
	marker = new google.maps.Marker({
		map: map,
		position: new google.maps.LatLng(latt, lngg)
		
	});	
	infowindow = new google.maps.InfoWindow({
		content: locString
	});
	google.maps.event.addListener(marker, "click", function () {
		infowindow.open(map, marker);
	});
	initMap() ;
}


function initMap() {
	var lat1 = $('#shp_lat').val();  
	var lng1 = $('#shp_lng').val();	 

	var setB = 0;
    if($("#staff_id_value").val() > 0) {	  
	    if($("#home_service_home").is(":checked")) {  
			if($("#selected_area").val() != '') {
				var sptarr = $("#selected_area").val().split(",");
				var nt = parseFloat(sptarr[0]);	  
				var et = parseFloat(sptarr[1]);
				var st = parseFloat(sptarr[2]);
				var wt = parseFloat(sptarr[3]);
				setB = 1;
			}
	    }
    }
	if(setB == 0) { 
		var nt = parseFloat(lat1) + parseFloat(0.023);
		var st = parseFloat(lat1) - parseFloat(0.045);
		var et = parseFloat(lng1) + parseFloat(0.234);
		var wt = parseFloat(lng1) + parseFloat(0.123);
	} 
  
  
 const bounds = {
    north: nt,
    south: st,
    east: et,
    west: wt,
  };  
 
  
  // Define the rectangle and set its editable property to true.
  rectangle = new google.maps.Rectangle({
    bounds: bounds,
    editable: true,
    draggable: true,
  });
  rectangle.setMap(map);
  // Add an event listener on the rectangle.
 
  rectangle.addListener("bounds_changed", showNewRect);
  rectangle.addListener("idle", showNewRect);

  // Define an info window on the map.
  infoWindows = new google.maps.InfoWindow();
}
if($("#staff_id_value").val() > 0) {	  
	if($("#home_service_home").is(":checked")) {  
		if($("#selected_area").val() != '') {
			setTimeout(showNewRect, 1000); // Page-load - In Edit, if Staff do Home-Service
			setTimeout(function(){ $('html, body').animate({scrollTop: jQuery("body").offset().top}, 0); }, 1010);
		}
	}
}

/** Show the new coordinates for the rectangle in an info window. */
function showNewRect() { 
  const ne = rectangle.getBounds().getNorthEast();
  const sw = rectangle.getBounds().getSouthWest();
  
  var boundry = ne.lat() +","+ne.lng()+","+sw.lat()+","+sw.lng();
  $("#selected_area").val(boundry); 
  
  var SelLoc = $("#SelLocMsp").val();
  const contentString = "<b>"+SelLoc+"</b><br>" ;
  
  // Set the info window's content and position.
  infoWindows.setContent(contentString);
  infoWindows.setPosition(ne);
  infoWindows.open(map);
  
  
}