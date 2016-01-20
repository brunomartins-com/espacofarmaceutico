/**
 *	Set the autocomplete details for the new entity modal
 * 	mapObj = createMapObj()
 *	return mapObj
 *
 */
var path = "/assets/";

function newEntityPlaces(mapObj)
{
    var input = new google.maps.places.Autocomplete(
        document.getElementById("newEntityAddress"),{
            types: ["geocode"]

        });

    google.maps.event.addListener(input, 'place_changed', function() {
        //clear markers
        mapObj.googleMarkers = unsetMarkers(mapObj);
        mapObj.overlays = unsetAllOverlays(mapObj);
        $("#newEntityShowMap").prop("disabled", false);

        //find the place info when user chooses a place
        var place = input.getPlace();
        //add this to the array to return
        mapObj.markers = Array(place);

        //put this place on the map if its shown
        var map = mapObj.map;
        if(map)
        {
            if(map != null)
            {
                //map.setCenter(place.geometry.location);
                //map.setZoom(19);
                //set the marker here
                mapObj.siteMarkers = setSiteMarkers(mapObj);
                //set the overlay here
                mapObj.overlays = setCircleOverlayForMarkers(map, mapObj.siteMarkers);
                //move the map to the marker
                moveMapToMarker(mapObj.map, mapObj.siteMarkers[0]);
                //create the trigger
                markerTriggerCreate(mapObj.siteMarkers[0], mapObj.overlays[0], $("#lat"), $("#lng"), $("#radius"));
            }
        }
    });

    return mapObj;
}

function editSiteAutocomplete(mapObj, input)
{

    var box = new google.maps.places.Autocomplete(
        document.getElementById(input)
    );

    //types["geocode"];


    google.maps.event.addListener(box, "place_changed", function(){
        //remove input alerts
        $(".input-alerts").remove();

        //clear markers
        mapObj.siteMarkers = unsetMarkers(mapObj);
        mapObj.overlays = unsetAllOverlays(mapObj);

        //get the place info when user chooses palce
        var place = box.getPlace();
        console.log(place);
        //take the places name and add it to the name field if there is no
        //other name
        var name = $("#siteName").val();
        if(name == "")
        {
            $("#siteName").val(place.name);
        }


        //set the other inputs
        var componentForm = {
            'street_number': 'short_name',
            'route': 'long_name',
            'locality': 'long_name',
            'administrative_area_level_1': 'short_name',
            'country': 'long_name',
            'postal_code': 'short_name'
        };
        var address = findAddressComponents(componentForm, place);
        $("#streetNumber").val(address["street_number"]);
        $("#streetName").val(address["route"]);
        $("#suburb").val(address["locality"]);
        $("#state").val(address["administrative_area_level_1"]);
        $("#country").val(address["country"]);
        $("#postCode").val(address["postal_code"]);
        $("#lat").val(place.geometry.location.lat());
        $("#lng").val(place.geometry.location.lng());


        //add this to the array to return
        mapObj.markers = Array(place);

        //put this place on the map
        var map = mapObj.map;
        if(map)
        {
            if(map != null)
            {
                //set the marker here
                mapObj.siteMarkers = setSiteMarkers(mapObj);
                //set the overlay
                mapObj.overlays = setCircleOverlayForMarkers(map, mapObj.siteMarkers);
                //move the map to the marker
                moveMapToMarker(mapObj.map, mapObj.siteMarkers[0]);
                //create the trigger
                markerTriggerCreate(mapObj.siteMarkers[0], mapObj.overlays[0], $("#lat"), $("#lng"), $("#radius"));
            }
        }

        $("#radius").val(mapObj.overlays[0].getRadius());
    });

    return mapObj;
}

/**
 * Finds and sorts all the components of an address for a google place result based on
 * a set of rules.
 * componentForm = {
 *  google address component : component type
 *  Eg:  street_number : short_name
 *   
 * }
 * returns {
 *  address component : val
 * }
 */
function findAddressComponents(componentForm, result)
{
    console.log(result);
    console.log(componentForm);
    //initialise the return obj
    var frmReturn = {};
    //set blank results for each key in the obj
    for(c in componentForm)
    {
        frmReturn[c] = "";
    }


    //for each of the google returned address components
    for (var j = 0; j < result.address_components.length; j++)
    {
        for(var i = 0; i < result.address_components[j].types.length; i++)
        {
            //define the component type to look for.
            var att = result.address_components[j].types[i];
            //if this component exists in the above array
            if(componentForm[att])
            {
                //The value is this component's value in the format defined above
                // eg: route.long_name
                var val = result.address_components[j][componentForm[att]];
                //add this to the reutrn object
                frmReturn[result.address_components[j].types[i]] = val;
                //go to the next address component if found
                break;
            }
        }
    }

    return frmReturn;
}

/**
 *	Create the map on the create entity prompt
 *	String div : div for the map
 *
 *	return google.maps.Map
 */
function showCreateEntityMap(div)
{
    var gmap = new google.maps.Map(document.getElementById(div), {
        center: new google.maps.LatLng(0, 0),
        zoom: 2
    });
    return gmap;
}

/**
 *	Create the map on the view site page
 * 	String div: div for the map
 *
 *	return google.maps.Map
 */
function showViewSiteMap(div)
{
    var gmap = new google.maps.Map(document.getElementById(div), {
        center : new google.maps.LatLng(0,0),
        zoom: 2
    });
    return gmap;
}

function showEditSiteMap(div)
{
    var gmap = new google.maps.Map(document.getElementById(div), {
        center : new google.maps.LatLng(0,0),
        zoom : 2
    });
    return gmap;
}

function showUserMap(div)
{
    var gmap = new google.maps.Map(document.getElementById(div), {
        center : new google.maps.LatLng(0,0),
        zoom: 2,
        maxZoom: 19
    });
    return gmap;
}

function showSiteControlMap(div)
{
    var gmap = new google.maps.Map(document.getElementById(div), {
        center : new google.maps.LatLng(0,0),
        zoom: 2
    });
    return gmap;
}

function showUserActivityMap(div)
{
    var gmap = new google.maps.Map(document.getElementById(div), {
        center : new google.maps.LatLng(0,0),
        zoom: 2
    });
    return gmap;
}

/**
 *Get the bounds from the sw and ne markers
 *return then bounds
 */
function getBoundsFromMarkers(markers)
{
    var lat = [];
    var lng = [];
    for(var i = 0; i < markers.length; i++)
    {
        var c = markers[i];
        if(c.lat != 0 && c.lng != 0)
        {
            lat.push(c.lat);
            lng.push(c.lng);
        }
    }

    //put them in order
    lat.sort();
    lng.sort();
    var sw = new google.maps.LatLng(lat[0], lng[0]);
    lat = lat.reverse();
    lng = lng.reverse();
    var ne = new google.maps.LatLng(lat[0], lng[0]);
    //set the bounds to include the smallest/largest
    var bounds = new google.maps.LatLngBounds(sw, ne);

    return bounds;
}

/**
 * Get the sw and ne markers, add their radius
 * return bounds
 */
function getBoundsFromRadius(overlays)
{
    var lat = [];
    var lng = [];
    //each overlay
    for(var i = 0; i < overlays.length; i++)
    {
        //get bounds
        var o = overlays[i].getBounds();
        //get ne
        var ne = o.getNorthEast();
        //get sw
        var sw = o.getSouthWest();

        lat.push(ne.lat());
        lng.push(ne.lng());
        lat.push(sw.lat());
        lng.push(sw.lng());
    }

    //sort them
    lat.sort();
    lng.sort();

    var boundsSw = new google.maps.LatLng(lat[0], lng[0]);
    lat.reverse();
    lng.reverse();
    var boundsNe = new google.maps.LatLng(lat[0], lng[0]);

    var bounds = new google.maps.LatLngBounds(boundsSw, boundsNe);

    return bounds;
}


/**
 * Sets the myteam map
 * moves the bounds to the marker boundaries
 */
function showMyTeamMap(div, markers)
{
    console.log("show my team");
    var gmap = new google.maps.Map(document.getElementById(div), {
        center: new google.maps.LatLng(0,0),
        zoom: 2
        //maxZoom: 21
    });

    if(markers.length > 0)
    {
        var bounds = getBoundsFromMarkers(markers);
        gmap.fitBounds(bounds);
    }

    return gmap;
}

/**
 * Shows the dash map
 * moves the boundaries to the markers
 */
function showDashMap(div, d)
{
    //console.log(bounds);
    //make map
    var gmap = new google.maps.Map(document.getElementById(div), {
        center: new google.maps.LatLng(0,0),
        zoom : 2
    });

    //fit to bounds
    if(d.length > 0)
    {
        var bounds = getBoundsFromMarkers(d);
        gmap.fitBounds(bounds);
    }
    return gmap;

}

/**
 * SET THE (SITE?) MARKERS TO THE MAP
 * For editing/creating only
 * Uses a google place result
 * mapObj = {
*	map: google.maps.Map,
*	markers: [google.maps.places],
*	overlays: [google.maps.Circle],
*	googleMarkers: [google.maps.Marker]
* }
 *
 * Return [google.maps.Marker]
 */
function setSiteMarkers(mapObj)
{
    //console.log("Set site marker");
    var markers = mapObj.markers;
    var map = mapObj.map;

    var googleMarkers = Array();
    for(var i = 0; i < markers.length; i++)
    {
        //console.log(markers[i]);
        var m = markers[i];
        var img = {
            url: path+"img/pin/site.png",
            scaledSize: new google.maps.Size(25,60)
        };

        var loc = m.geometry.location;
        var temp = new google.maps.Marker({
            position: loc,
            map: map,
            draggable: true,
            icon: img
        });

        googleMarkers.push(temp);

    }
    return googleMarkers;
}

/**
 *	Unset all the markers on the map
 *	mapObj = {
*		map: google.maps.Map,
*		markers: [google.maps.places],
*		overlays: [google.maps.Circle],
*		googleMarkers: [google.maps.Marker]	
*	}
 *
 *	Return [google.maps.Marker]
 */
function unsetMarkers(mapObj)
{
    var mark = mapObj.userMarkers.concat(mapObj.siteMarkers);
    //console.log(mark.length);
    for(var i = 0; i < mark.length; i++)
    {
        var m = mark[i];
        m.setMap(null);
        //mark.splice(i,1);
    }
    //console.log(mark.length);
    //return mark;
    return Array();
}

/**
 * Unset markers of a particular set Eg: user /site
 */
function unsetMarkerSet(markers)
{
    //console.log(markers);
    //var deleted = Array();
    for(var i = 0; i < markers.length; i++)
    {
        var m = markers[i];
        m.setMap(null);
        //deleted.push(m);
    }

    //console.log(deleted);
    return Array();
}

/**
 * Sets a circle overlay for a marker
 * Takes the markers position and makes a circle around it
 */
function setCircleOverlayForMarkers(map, markers, radius)
{
    //if places

    //if marker

    //if lat/lng
    if(radius == null)
    {
        radius = 40;
    }

    var overlays = Array();
    for(var i = 0; i < markers.length; i++)
    {
        var m = markers[i]
        var center = m.position;
        var o = new google.maps.Circle({
            map: map,
            center: center,
            editable: true,
            draggable: false,
            radius: radius,
            fillColor: "#20B2AA",
            fillOpacity: 0.1,
            strokeColor: "#008000"
        });
        overlays.push(o);

        google.maps.event.addListener(o, "radius_changed", function(){
            //console.log(o.getRadius());
            if(o.getRadius() < 40)
            {
                o.setRadius(40);
            }
        });
    }

    return overlays;
}

/**
 *	Unset all the markers on the map
 *	mapObj = {
*		map: google.maps.Map,
*		markers: [google.maps.places],
*		overlays: [google.maps.Circle],
*		googleMarkers: [google.maps.Marker]	
*	}
 *
 *	Return [google.maps.Marker]
 */
function unsetAllOverlays(mapObj)
{
    var overlay = mapObj.overlays;
    for(var i = 0; i < overlay.length; i++)
    {
        var o = overlay[i];
        o.setMap(null);
        //overlay.splice(i,1);
    }

    //return overlay;
    return [];
}


/**
 *	Move the map to this marker
 *	mapObj = {
*		map: google.maps.Map,
*		markers: [google.maps.places],
*		overlays: [google.maps.Circle],
*		googleMarkers: [google.maps.Marker]	
*	}
 *
 *	googleMarker = google.maps.Marker
 */
function moveMapToMarker(map, googleMarker, zoom)
{
    if(map && googleMarker)
    {
        //if zoom not set, set to 19
        if(zoom == null)
        {
            zoom = 19
        }
        map.setCenter(googleMarker.position);
        map.setZoom(zoom);
    }
}
/**
 *	Set the triggers for the markers and overlays on the create site maps
 *   Triggers changes the map elements and adds the value to the form element
 *	google.maps.Marker : marker
 *	google.maps.Circle: overlay (can be any with center key)
 *   jQuery obj : lat
 *   jQuery obj : lng
 *   jQuery obj : radius
 *
 */
function markerTriggerCreate(marker, overlay, lat, lng, radius)
{
    if(marker != null)
    {
        //make the circle overlay follow the marker
        google.maps.event.addListener(marker, "drag", function(){
            //console.log("drag marker");
            var pos = marker.getPosition();
            //move the overlay
            overlay.setCenter(pos);

            //set lat/lng
            lat.val(pos.lat());
            lng.val(pos.lng());
        });

        //make the marker follow the overlay
        //send back the
        google.maps.event.addListener(overlay, "center_changed", function(){
            //console.log("drag circle");
            var pos = overlay.getCenter();
            //move the marker
            marker.setPosition(pos);

            //set lat/lng
            lat.val(pos.lat());
            lng.val(pos.lng());
        });

        //send back the radius
        google.maps.event.addListener(overlay, "radius_changed", function(){
            radius.val(overlay.getRadius());
        });
    }
}
/**
 *  mapObj = {
*		map : google.maps.Map
*		googleMarkers : [google.maps.Marker]
*	}
 *
 *	markers : [lat: double, lng: double, firstName: str, lastName: str, entityName: str]
 *
 */
function setUserMarkers(mapObj,markers, currEntity)
{
    //console.log(mapObj);
    var entityC = Array();

    //create info window
    if(mapObj.infoWindow == null)
    {
        mapObj.infoWindow = new google.maps.InfoWindow({maxWidth: 200});
    }

    var win = mapObj.infoWindow;

    for(var i = 0; i < markers.length; i++)
    {
        var m = markers[i];

        if(m.lat != 0 && m.lng != 0)
        {
            var alt = false; //default is they're part of my entity
            //logged in user's entity - ""
            //another entity - _other
            if(m.isPulse == false)
            {
                if(m.userEntityId == m.siteEntityId) //if user and site share an entity not alternative
                {
                    alt = false;
                }
                else if(currEntity != null && m.userEntityId != currEntity) //else if there is an entity and the user doens't match it
                {
                    alt = true;
                }
            }

            var imgStr = createUserMarkerImg(m.checkinTypeId, m.status, m.isPulse, alt);
            //create the marker

            var img = {
                url: imgStr,
                scaledSize: new google.maps.Size(30,50)
            };
            var loc = new google.maps.LatLng(m.lat, m.lng);
            var temp = new google.maps.Marker({
                position: loc,
                map: mapObj.map,
                draggable: false,
                icon: img,
                anchorPoint: new google.maps.Point(-2.5,-50)
            });

            //push to the map object
            mapObj.userMarkers.push(temp);

            //set this depending on map?

            //create content for the info window
            //add some more info about thei actions and location
            var content = "<div class='userInfo row'>"
                +"<div class='row'>"
                +"<div class='col-xs-12'>"
                +"<div class='col-xs-10 col-xs-offset-1'>"
                +"<img src='"+path+"include/userImg.php?userId="+m.userId+"' class='img-circle img-responsive col-xs-12' alt='"+m.firstName+"'>"
                +"</div>"
                +"</div>"
                +"</div>"
                +"<div class='row'>"
                +"<div class='col-xs-12 text-center'>"
                +"<h4 class='col-xs-12'>"+m.firstName+" "+m.lastName+"</h4>"
                +"</div>"
                +"</div>"
                +"<div class='row'>"
                +"<div class='col-xs-12 text-center'>"
                +"<h5 class='col-xs-12'><em>"+m.userEntityName+"</em></h5>"
                +"</div>"
                +"</div>"
                +"<div class='row'>"
                +"<div class='col-md-12'>"
                +"<div class='col-md-6'>"
                +"<a type='button' class='btn btn-default btn-block' href='/user/view/"+m.userId+"'>View</a>"
                +"</div>"
                +"<div class='col-md-6'>"
                +"<a type='button' class='btn btn-default btn-block' href='/user/edit/"+m.userId+"'>Edit</a>"
                +"</div>"
                +"</div>"
                +"</div>"
                +"</div>";

            //create the map listener for the info window
            google.maps.event.addListener(temp, "click", (function(temp, content, win){
                //close last window
                win.set("marker",null);
                win.close();

                //using a closure allows for more then one info window
                return function()
                {
                    win.setContent(content);
                    win.open(mapObj.map, temp);
                    mapObj.openInfo = temp;
                };
            })(temp, content, win));
        }
    }

    //if the indfo window has been opened open it on refresh
    if(mapObj.openInfo != null)
    {
        win.open(mapObj.map, mapObj.openInfo);
    }

    //if map is clicked close the window
    google.maps.event.addListener(mapObj.map, "click", (function(){
        win.close();
    }));

    var styles = [
        {
            url: "img/user_blue_cluster.png",
            height: 56,
            width: 56,
            anchor: [0,0],
            textColor: "#ffffff",
            textSize: 14
        },
        {
            url: "img/user_orange_cluster.png",
            height: 56,
            width: 56,
            anchor: [0,0],
            textColor: "#000000",
            textSize: 14
        },
        {
            url: "img/user_red_cluster.png",
            height: 56,
            width: 56,
            anchor: [0,0],
            textColor: "#000000",
            textSize: 14
        }
    ];
    var clusterOptions = {
        styles: styles,
        maxZoom: 18
    };

    var mc = new MarkerClusterer(mapObj.map, mapObj.userMarkers, clusterOptions);
    mapObj.userClusters = mc;

    return mapObj;
}

/**
 * Show the user's route on the map
 * content windows show checkin data rather then user data
 */
function trackUserMap(mapObj, data, userId, status)
{
    //console.log("track user");
    //create infowindow
    if(mapObj.infoWindow == null)
    {
        mapObj.infoWindow = new google.maps.InfoWindow({maxWidth: 200});
    }
    var win = mapObj.infoWindow;

    //for each data entry
    for(var i = 0; i < data.length; i++)
    {
        var m = data[i];
        var imgStr = createUserMarkerImg(m.checkinTypeId, status, m.isPulse, false);


        var img = {
            url: imgStr,
            scaledSize: new google.maps.Size(30,50)
        };
        var loc = new google.maps.LatLng(m.lat, m.lng);
        //console.log(loc);
        var temp = new google.maps.Marker({
            position: loc,
            map: mapObj.map,
            draggable: false,
            icon: img,
            anchorPoint: new google.maps.Point(-2.5,-50)
        });

        //push to the map object
        mapObj.userMarkers.push(temp);
        var winContent = "";

        //get date information
        var date = m.dateObj.month.substr(0,3)+" "+m.dateObj.mday;
        if(!m.dateObj.year.match(new Date().getFullYear()))
        {
            date = m.dateObj.mday+"/"+m.dateObj.mon+"/"+m.dateObj.yday;
        }
        var time = addZero(m.dateObj.hours)+" : "+addZero(m.dateObj.minutes);
        //find timezone. If they match user's timezone don't show
        var timezone = m.timezone;
        var tz = jstz.determine().name();
        //console.log(tz);
        if(tz != timezone)
        {
            var slash = timezone.lastIndexOf("/")+1;
            timezone = timezone.substr(slash);
            timezone = timezone.replace("_", " ");
        }
        else
        {
            timezone = "";
        }
        //this needs to change depending on pulse/checkin
        if(m.isPulse == false)
        {

            winContent = "<div class='userInfo row'>"
                +"<div class='row'>"
                +"<div class='col-xs-12 text-center'>"
                +"<h5 class='col-xs-12'>"+m.checkinType+"</h5>"
                +"</div>"
                +"</div>"
                +"<div class='row'>"
                +"<div class='col-xs-12 text-center'>"
                +"<h5 class='col-xs-12'>"+m.name+"</h5>"
                +"</div>"
                +"</div>"
                +"<div class='row'>"
                +"<div class='col-xs-12 text-center'>"
                +"<p>"+date+"</p>"
                +"</div>"
                +"</div>"
                +"<div class='row'>"
                +"<div class='col-xs-12 text-center'>"
                +"<p>"+time+" "+timezone+"</p>"
                +"</div>"
                +"</div>"
                +"<div class='row'>"
                +"<div class='col-xs-12'>"
                +"<div class='col-xs-6'><a class='btn btn-default btn-block' type='button' href='/site/view/"+m.siteId+"'>View</a></div>"
                +"<div class='col-xs-6'><a class='btn btn-default btn-block' type='button' href='/question-report/"+m.checkinId+"'>OHS</a></div>"
                +"</div>"
                +"</div>"
                +"</div>";
        }
        else
        {
            winContent = "<div class='userInfo row'>"
                +"<div class='row'>"
                +"<div class='col-xs-12 text-center'>"
                +"<h4>"+m.checkinType+"</h4>"
                +"</div>"
                +"</div>"
                +"<div class='row'>"
                +"<div class='col-xs-12 text-center'>"
                +"<p>"+date+"</p>"
                +"</div>"
                +"</div>"
                +"<div class='row'>"
                +"<div class='col-xs-12 text-center'>"
                +"<p>"+time+" "+timezone+"</p>"
                +"</div>"
                +"</div>"
                +"</div>";
        }

        //create the map listener for the info window
        google.maps.event.addListener(temp, "click", (function(temp, winContent, win){
            //close last window
            win.set("marker",null);
            win.close();

            //using a closure allows for more then one info window
            return function()
            {
                win.setContent(winContent);
                win.open(mapObj.map, temp);
                mapObj.openInfo = temp;
            };
        })(temp, winContent, win));

    } //end loop

    //find bounds and fit the map to it
    var bounds = getBoundsFromMarkers(data);
    mapObj.map.fitBounds(bounds);

    //if the indfo window has been opened open it on refresh
    if(mapObj.openInfo != null)
    {
        win.open(mapObj.map, mapObj.openInfo);
    }

    //if map is clicked close the window
    google.maps.event.addListener(mapObj.map, "click", (function(){
        win.close();
        mapObj.openInfo = null;
    }));
    //on close clock remove openInfo
    google.maps.event.addListener(win, "closeclick", (function(){
        mapObj.openInfo = null;
    }));
    return mapObj;
}

/**
 * Sets the site marker and the radius overlay
 * SETS ONE MARKER
 * mapObj : {
*		map: google.maps.Map
*		overlays : [google.maps.Circle]
*		siteMarkers : [google.maps.Marker]
*		userMarkers: [google.maps.Marker]
*	}
 * site : {
* 		lat : double
*		lng : double
*		radius : double,
*		entityName : str
*	}
 */
function setSiteMarkerOverlay(mapObj, site)
{
    //console.log(site);
    //set the marker
    var img = {
        url: path+"img/pin/site_new.png",
        scaledSize: new google.maps.Size(12.5,30)
    };
    var loc = new google.maps.LatLng(site.lat, site.lng);
    var temp = new google.maps.Marker({
        position: loc,
        map: mapObj.map,
        icon: img
    });

    //console.log(temp);
    if(mapObj.infoWindow == null)
    {
        mapObj.infoWindow = new google.maps.InfoWindow();
    }
    var win = mapObj.infoWindow;
    var content = "<div class='siteInfo row'>"
        +"<div class='row'>"
        +"<div class='col-md-12'>"
        +"<p class='col-md-12'>"+site.name+"</p>"
        +"</div>"
        +"</div>"
        +"<div class='row'>"
        +"<div class='col-md-12'>"
        +"<p class='col-md-12'>"+site.entityName+"</p>"
        +"</div>"
        +"</div>"
        +"<div class='row'>"
        +"<div class='col-md-12'>"
        +"<div class='col-md-6'>"
        +"<a type='button' class='btn btn-default btn-block' href='/site/view/"+site.siteId+"'>View</a>"
        +"</div>"
        +"<div class='col-md-6'>"
        +"<a type='button' class='btn btn-default btn-block' href='/site/edit/"+site.siteId+"'>Edit</a>"
        +"</div>"
        +"</div>"
        +"</div>"
        +"</div>";
    //set info window for site
    //create the map listener for the info window
    google.maps.event.addListener(temp, "click", (function(temp, content, win){
        //close last window
        win.set("marker",null);
        win.close();

        //using an enclosure allows for more then one info window
        return function()
        {
            win.setContent(content);
            win.open(mapObj.map, temp);
        };
    })(temp, content, win));

    //mapObj.googleMarkers.push(temp);
    mapObj.siteMarkers.push(temp);
    moveMapToMarker(mapObj.map, temp, 17);
    //set the overlay
    var tempO = new google.maps.Circle({
        map: mapObj.map,
        center: loc,
        editable: false,
        draggable: false,
        radius: parseInt(site.radius),
        fillColor: "#20B2AA",
        fillOpacity: 0.1,
        strokeColor: "#008000"
    });

    google.maps.event.addListener(tempO, "radius_changed", function(){
        console.log("radius changed");
        console.log(tempO.getRadius());
        if(tempO.getRadius() < 100)
        {
            tempO.setRadius(100);
        }
    });

    mapObj.overlays.push(tempO);
    return mapObj;
}

/**
 * Sets a whole set of markers
 * Moves the map to where they are
 * mapObj = map object
 * markers = []
 *NOTE: user cluster.clearMarkers() over cluster.setMap(null)
 */
function setSiteMarkerSet(mapObj, markers)
{
    //clear the marker clusterer
    //this is in initial script
    // if(mapObj.siteClusters != null)
    // {
    // mapObj.siteClusters.clearMarkers();
    // }
    //var mc = new MarkerClusterer(mapObj.map, [], clusterOptions);
    var bounds = null;
    if(markers.length > 0) //if no markers don't do anything
    {
        //set each pin
        for(var i = 0; i < markers.length; i++)
        {
            mapObj = setSiteMarkerOverlay(mapObj, markers[i]);
        }

        //move the map to fit all the markers
        //bounds = getBoundsFromRadius(mapObj.overlays);
        bounds = getBoundsFromRadius(mapObj.overlays);
        mapObj.map.fitBounds(bounds);
        console.log(mapObj);
        var clusterOptions = {
            maxZoom: 18
        };
        var mc = new MarkerClusterer(mapObj.map, mapObj.siteMarkers, clusterOptions);
        mapObj.siteClusters = mc;
    }

    return {"mapObj" : mapObj, "bounds" : bounds};
}

/**
 * Create the map on the site control page
 * Uses sites filtered by the search
 * Only shows pins that are correct
 */
function createSiteControlMap(sites)
{

    if(mapObj.map == null) // new map
    {
        mapObj.map = showSiteControlMap("siteCenterMap");
    }
    else
    {
        //unset all markers and overlays
        mapObj.siteMarkers = unsetMarkerSet(mapObj.siteMarkers);
        mapObj.overlays = unsetAllOverlays(mapObj);
        mapObj.siteClusters.clearMarkers();
        mapObj.markers = Array();
    }

    //set all the markers on the map
    markerData = setSiteMarkerSet(mapObj, sites);

    var bounds = markerData.bounds;
    //set the new mapObj
    mapObj = markerData.mapObj;

    //fix the map being rendered funny while hidden
    $("#siteControlCarousel").on("slid.bs.carousel", function(){
        //console.log("resize");
        google.maps.event.trigger(mapObj.map, 'resize');
        //if there are set bounds move the map again
        if(bounds != null)
        {
            mapObj.map.fitBounds(bounds);
        }
    });

}


/**
 *Unset all the clusters in the array
 */
function unsetClusters(c)
{
    console.log("clusters");
    console.log(c);
    if(c.getTotalClusters() > 0)
    {
        c.setMap(null);
        console.log("clusters exist");
        //for(var i = 0; i < clusters.getTotalClusters().length; i++)
        //{
        //	clusters[i].setMap(null);
        //}

        c.clearMarkers();
    }

    return Array();
}

/**
 * Sets the breadcrumb map with each action performed by the user
 */
function setDailyActivityTrail(userId, startTime, endTime, breadcrumb)
{
    //get tz from userId timezone
    var data = new FormData();
    data.append("function", "findUserActivityBetweenTimes");
    data.append("userId", userId);
    data.append("startTime", startTime);
    data.append("endTime", endTime);
    data.append("timestamp", new Date().getTime()/1000);

    if(ajaxObj != null)
    {
        ajaxObj.abort();
    }

    ajaxObj = $.ajax({
        url: "/include/function.php",
        data: data,
        type: "POST",
        contentType: false,
        processData: false
    }).done(function(d){
        d = JSON.parse(d);
        if(mapObj.map == null)
        {
            mapObj.map = showUserActivityMap("userFullMap");
            mapObj.polyLine = new google.maps.Polyline({
                path: []
            });
        }
        else
        {
            //clear everything
            unsetMarkers(mapObj);
            mapObj.overlays = unsetAllOverlays(mapObj);
            mapObj.polyLine.setPath([]);
            mapObj.userMarkers = [];
            mapObj.siteMarkers = [];
        }

        var table = "<table class='table table-bordered col-xs-12'>";
        if(d.success == true)
        {
            //add a marker to the map for each activity
            if(d.activity.length > 0)
            {
                var siteNum = -1;
                table += "<tr>"
                    +"<th></th>"
                    +"<th>Location</th>"
                    +"<th>Action</th>"
                    +"<th>Time</th>"
                    +"<th>Timezone</th>"
                    +"</tr>";
                //create info window
                if(mapObj.infoWindow == null)
                {
                    mapObj.infoWindow = new google.maps.InfoWindow({maxWidth: 200});
                }
                var win = mapObj.infoWindow;
                var pathArr = [];
                for(var i = 0; i < d.activity.length; i++)
                {
                    var c = d.activity[i];
                    var pos;
                    //add to list of sites
                    //needs to go before user markers because of bounds
                    if(c.siteId != null)
                    {
                        var exists = false;
                        for(site in mapObj.siteMarkers)
                        {
                            if(site.siteId == c.siteId)
                            {
                                exists = true;
                                break;
                            }
                        }

                        if(exists == false)
                        {
                            var siteObj = {
                                siteId: c.siteId,
                                name: c.siteName,
                                entityName: c.siteEntityName,
                                lat: c.siteLat,
                                lng: c.siteLng,
                                radius: c.radius
                            };
                            //this site doesn't exist add it
                            mapObj = setSiteMarkerOverlay(mapObj, siteObj);
                            if(breadcrumb == false)
                            {
                                pos = new google.maps.LatLng(c.siteLat, c.siteLng);
                            }

                            siteNum++;
                        }
                    }

                    if(breadcrumb == true)
                    {
                        pos = new google.maps.LatLng(c.lat, c.lng);
                        var temp = new google.maps.Marker({
                            map : mapObj.map,
                            position: pos
                        });
                        //add marker to arr
                        mapObj.userMarkers.push(temp);
                    }

                    pathArr.push(pos);

                    var time = addZero(c.dateObj.hours)+" : "+addZero(c.dateObj.minutes);
                    var timezone = c.timezone;
                    var slash = timezone.lastIndexOf("/")+1;
                    timezone = timezone.substr(slash);

                    var siteName = (c.siteName != null || c.isPulse == false) ? c.siteName : "Pulse";

                    table += "<tr>"
                        +"<td>"+(i+1)+"</td>";
                    //if its a breadcrumb trail set the button to open infowindows on user markers
                    if(breadcrumb == true)
                    {
                        table  += "<td><button type='button' class='btn btn-link btn-block' onclick=\"triggerClickMarker("+i+", 'user')\">"+siteName+"</button></td>";
                    }
                    else if(c.isPulse == false) //if its not a breadcrumb and not a pulse set the button to open the site infowindow
                    {
                        table += "<td><button type='button' class='btn btn-link btn-block' onclick=\"triggerClickMarker("+siteNum+", 'site')\">"+siteName+"</button></td>";
                    }
                    else //set the pulse as a label with no action
                    {
                        table  += "<td class='text-center'><p>"+siteName+"</p></td>";
                    }
                    table += "<td>"+c.desc+"</td>"
                        +"<td>"+time+"</td>"
                        +"<td>"+timezone+"</td>"
                        +"</tr>";

                    if(breadcrumb == true)
                    {
                        var content = "<div class='userInfo row'>"
                            +"<div class='row'>"
                            +"<div class='col-xs-12'>"
                            +"<div class='col-xs-12 text-center'>"
                            +"<h5>"+(i+1)+"</h5>"
                            +"</div>"
                            +"</div>"
                            +"</div>"
                            +"<div class='row'>"
                            +"<div class='col-xs-12'>"
                            +"<div class='col-xs-12 text-center'>"
                            +"<h5>";
                        content += (c.isPulse == true) ? c.desc : siteName; //change the name in the info window
                        content	+=  				"</h5>"
                            +"</div>"
                            +"</div>"
                            +"</div>"
                            +"<div class='row'>"
                            +"<div class='col-xs-12'>"
                            +"<div class='col-xs-12 text-center'>"
                            +"<h5>"+time+" "+timezone+"</h5>"
                            +"</div>"
                            +"</div>"
                            +"</div>"
                            +"</div>";

                        //create the map listener for the info window
                        google.maps.event.addListener(temp, "click", (function(temp, content, win){
                            //close last window
                            win.set("marker",null);
                            win.close();

                            //using a closure allows for more then one info window
                            return function()
                            {
                                win.setContent(content);
                                win.open(mapObj.map, temp);
                                mapObj.openInfo = temp;
                            };
                        })(temp, content, win));
                    }
                }

                //make a path of all the places
                var path = new google.maps.MVCArray(pathArr);
                var symbol = {
                    path: google.maps.SymbolPath.FORWARD_OPEN_ARROW
                };
                //console.log((pathArr.length/100)*100 + "%");
                var polyLine = new google.maps.Polyline({
                    map: mapObj.map,
                    path: path,
                    icons: [{
                        icon: symbol,
                        offset: "0",
                        repeat: "25%"
                    }],
                });

                if(breadcrumb == true)
                {
                    var bounds = getBoundsFromMarkers(d.activity);
                }
                else
                {
                    //max callstack exceeded -.-?
                    var bounds = getBoundsFromRadius(mapObj.overlays);
                }
                mapObj.map.fitBounds(bounds);

                mapObj.polyLine = polyLine;
            }
            else
            {
                table += "<tr><th><div class='alert alert-block alert-warning'>No Results</div></th></tr>";
            }

        }
        else
        {
            console.log(error);
        }

        table += "</table>";
        $("#activityList").html(table);
        return mapObj;
    });
}

function triggerClickMarker(i, type)
{
    if(type == "user")
    {
        google.maps.event.trigger(mapObj.userMarkers[i], "click");
    }
    else if(type == "site")
    {
        google.maps.event.trigger(mapObj.siteMarkers[i], "click");
    }

}