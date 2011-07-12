<?php

class rex_xform_google_geocode extends rex_xform_abstract
{

	function enterObject()
	{	

		$labels = explode(",",$this->getElement(2)); // Fields of Position
		$label_lng = $labels[0];
		$label_lat = $labels[1];
		
		$value_lng = "0";
		$value_lat = "0";

		$address = explode(",",$this->getElement(3)); // Fields of getPosition
		
		$label = "";
		if(isset($this->getElement(4))) $label = $this->getElement(4);

		$map_width = 400;
		if ($this->getElement(5) != "") $map_width = (int) $this->getElement(5);
		$map_height = 200;
		if ($this->getElement(6) != "") $map_height = (int) $this->getElement(6);

		foreach($this->obj as $o)
		{
			if($o->getName() == $label_lng) 		$value_lng = floatval($o->getValue());
			if($o->getName() == $label_lat) 		$value_lat = floatval($o->getValue());
		}

		// rex_com::debug($this->obj);
	
		if ($this->getValue() == "" && !$this->params["send"])
			if (isset($this->getElement(4))) 
				$this->setValue($this->getElement(4));

		$wc = "";
		if (isset($this->params["warning"][$this->getId()])) $wc = $this->params["warning"][$this->getId()];
		
		$output = "";
		// Script nur beim ersten mal ausgeben
		if (!defined('REX_XFORM_GOOGLE_GEOCODE_JSCRIPT')) {
			define('REX_XFORM_GOOGLE_GEOCODE_JSCRIPT', true);
			$output .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>';
		}

		// für jede Map ein neues rexGmap_GeoCoder-Objekt anlegen
		$map_id = 'map_canvas'.$this->getId();


	echo '<script type="text/javascript">
	//<![CDATA[

	var rex_geo_coder = function()
	{
	
		var myLatlng = new google.maps.LatLng('.$value_lat.', '.$value_lng.');
	    var myOptions = {
	      zoom: 8,
	      center: myLatlng,
	      mapTypeId: google.maps.MapTypeId.ROADMAP
	    }

	    var map = new google.maps.Map(document.getElementById("'.$map_id.'"), myOptions);		
		
		var marker = new google.maps.Marker({
	        position: myLatlng, 
	        map: map,
	        draggable: true
	    }); 
	
		google.maps.event.addListener(marker, "dragend", function() {
			rex_geo_updatePosition(marker.getPosition()); 
		}); 
		
		geocoder = new google.maps.Geocoder();
	
		rex_geo_updatePosition = function(latLng) { 
		
			jQuery(".formlabel-'.$label_lat.' input").val(latLng.lat()); 
			jQuery(".formlabel-'.$label_lng.' input").val(latLng.lng()); 

		} 
	
		rex_geo_getPosition = function(address) {

			fields = address.split(",");
			for(i=0;i<fields.length;i++)
			{
				jQuery(function($){ 
					fields[i] = $(".formlabel-"+fields[i].trim()+" input").val();
				});
			}
			
			address = fields.join(",");
			
			geocoder.geocode( { "address": address}, function(results, status) {
		        if (status == google.maps.GeocoderStatus.OK) {
		          if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {

		            map.setCenter(results[0].geometry.location);
		    
		            marker = new google.maps.Marker({
		                position: results[0].geometry.location,
		                map: map, 
		                title:address,
	       				draggable: true
		            }); 
		
					rex_geo_updatePosition(marker.getPosition());
		
		          } else {
		            // alert("No results found");
		          }
		        } else {
		          // alert("Geocode was not successful for the following reason: " + status);
		        }
		      });
		
		}
		
		rex_geo_resetPosition = function() {
		
			jQuery(function($){ 
				$(".formlabel-'.$label_lat.' input").val("0"); 
				$(".formlabel-'.$label_lng.' input").val("0"); 
			});
			
			marker.setMap(null);
		
		}
		
	
	}

	jQuery(function($){
		rex_geo_coder'.$this->getId().' = new rex_geo_coder();
	});

	//]]>
	</script>
	';
		
		$output .= '
			<div class="xform-element form_google_geocode formlabel-'.$this->getName().'" id="'.$this->getHTMLId().'">
				<label class="text '.$wc.'" for="el_'.$this->getId().'_lat" >'.$label.'</label>
				<p class="form_google_geocode">';

		$output .= '<a href="#" onclick="rex_geo_getPosition(\''.implode(",",str_replace('"','',$address)).'\')">Geodaten holen</a> | ';	

		$output .= '<a href="#" onclick="rex_geo_resetPosition()">Geodaten nullen</a></p>
				<div class="form_google_geocode_map" id="'.$map_id.'" style="width:'.$map_width.'px; height:'.$map_height.'px">Google Map</div>
			</div>';
			
		$this->params["form_output"][] = $output;


	}
	
	function getDescription()
	{
		return "google_geocode -> Beispiel: google_geocode|gcode|pos_lng,pos_lat|strasse,plz,ort|Google Map|width|height|
		";
	}
	
  function getDefinitions()
  {
    return array(
            'type' => 'value',
            'name' => 'google_geocode',
            'values' => array(
              array( 'type' => 'name',   'label' => 'Name' ),
              array( 'type' => 'getNames',	'label' => '"lng"-name,"lat"-name'),
              array( 'type' => 'getNames','label' => 'Names Positionsfindung'),
              array( 'type' => 'text',     'label' => 'Bezeichnung'),
              array( 'type' => 'text',     'label' => 'Map-Breite'),
              array( 'type' => 'text',     'label' => 'Map-H&ouml;he'),
            ),
            'description' => 'GoogeMap Positionierung',
            'dbtype' => 'text'
      );
  
  }
	
}

/*





	<script type="text/javascript">
	//<![CDATA[
	var rex_geoOptions'.$this->getId().' = { id: '.$this->getId().', lat: "el_'.$label_lat_id.'", lng: "el_'.$label_lng_id.'", '.$vv.' htdocspath : "'.$REX['HTDOCS_PATH'].'", resizable_map: '.$mapresize.' };
	rex_geocoder'.$this->getId().' = new rexGmap_GeoCoder(rex_geoOptions'.$this->getId().');
	//]]>
	</script>	













	<script type="text/javascript">
	//<![CDATA[

	var rexGmap_GeoCoder = function(loadoptions)
	{	

		var rexGmap_GeoCoder_defaults = {
			id: 1, // Id für Map-Canvas
			lat: "", // Feld für lat
			lng: "", // Feld für lng
			geofield1: "", // Feld für Geo-Ermittlung
			geofield2: "", // Feld für Geo-Ermittlung
			geofield3: "", // Feld für Geo-Ermittlung
			htdocspath: "./", // Pfad zu HTDOCS
			rex_zoom: 10, // Default Zoom bei vorhandenen Geo-Daten
			rex_zoom_get: 15, // Default Zoom nach Ermittlung der Geo-Daten
			rex_zoom_err: 5, // Default Zoom bei nicht vorhandenen Geo-Daten
			rex_default_lat: 51, // Default-Position bei nicht vorhandenen Geo-Daten
			rex_default_lng: 10, // Default-Position bei nicht vorhandenen Geo-Daten		
			min_width: 300, // minimale Map-Breite
			min_height: 150, // minimale Map-Höhe
			resizable_map: true // Map resizeable
		};	
		var options = jQuery.extend(rexGmap_GeoCoder_defaults, loadoptions);

		var map = null;
		var geocoder = null;
		var marker = null;

		var mapcontainer = null;
		var resizeButton = null;
		var resizable = false;
		var mouseX, mouseY, diffX, diffY;

		function ResizeControl(){};
		ResizeControl.prototype = new GControl();
		ResizeControl.prototype.initialize = function() 
		{
			resizeButton = document.createElement("div");
			resizeButton.style.width = "20px";
			resizeButton.style.height = "20px";
			resizeButton.style.backgroundImage = "url(\'"+options.htdocspath+"files/addons/xform/resize.gif\')";
			resizeButton.style.cursor = "se-resize";

			mapcontainer = map.getContainer();
			mapcontainer.appendChild(resizeButton);

			var terms = mapcontainer.childNodes[2];
			terms.style.marginRight = "20px";

			jQuery(resizeButton).mousedown(function(){ resizable = true; map.hideControls(); resizeButton.style.visibility = "visible"; });
			jQuery("body").mouseup(function(){ resizable = false; map.checkResize(); map.showControls(); });
			jQuery("body").mousemove(function(e){ watchMouse(e); });

			return resizeButton;
		}
		ResizeControl.prototype.getDefaultPosition = function()
		{
			return new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(0,0));
		}

		function watchMouse(e) 
		{
			// Include possible scroll values
			var sx = window.scrollX || document.documentElement.scrollLeft || 0;
			var sy = window.scrollY || document.documentElement.scrollTop || 0;

			if(!e) e = window.event; // IEs event definition
			mouseX = e.clientX + sx;
			mouseY = e.clientY + sy;

			// Direction of mouse movement * deltaX: -1 for left, 1 for right * deltaY: -1 for up, 1 for down
			var deltaX = mouseX - diffX;
			var deltaY = mouseY - diffY;
			// Store difference in global variables
			diffX = mouseX;
			diffY = mouseY;

			if (resizable) 
			{ 
				changeMapSize(deltaX, deltaY);
			}

			return false;
		}

		function changeMapSize(dx, dy) 
		{
			var width = parseInt(mapcontainer.style.width);
			var height =  parseInt(mapcontainer.style.height);
			if ((width + dx) < options.min_width) { width = options.min_width; dx = 0; }
			if ((height + dy) < options.min_height) { height = options.min_height; dy = 0; }
			mapcontainer.style.width = (width + dx) + "px";
			mapcontainer.style.height= (height + dy) + "px";
		}

		function createMarker(point) 
		{
			var marker = new GMarker(point, {draggable:true, bouncy:true});

			GEvent.addListener(marker, "drag", function(){
				point = marker.getPoint();
				jQuery("#"+options.lat)[0].value = point.lat();
				jQuery("#"+options.lng)[0].value = point.lng();
			});
			GEvent.addListener(marker, "dragend", function(){
				point = marker.getPoint();
				jQuery("#"+options.lat)[0].value = point.lat();
				jQuery("#"+options.lng)[0].value = point.lng();
				map.panTo(point, true);
			});

			return marker;
		}

		function getGGeo(noalert)
		{
			var address = "";

			if (options.geofield1)
				address += jQuery("#"+options.geofield1)[0].value+", ";
			if (options.geofield2)
				address += jQuery("#"+options.geofield2)[0].value+", ";
			if (options.geofield3)
				address += jQuery("#"+options.geofield3)[0].value+", ";

			if (geocoder) 
			{
				geocoder.getLatLng(address, function(point){
					if (!point) 
					{
						if (!noalert) { alert(address + " nicht gefunden!"); }
						return false
					} 
					else 
					{
						map.savePosition();
						jQuery("#"+options.lat)[0].value = point.lat();
						jQuery("#"+options.lng)[0].value = point.lng();
						if (map.getZoom() < options.rex_zoom_get) { map.setZoom(options.rex_zoom_get); }
						if (!marker) 
						{
							marker = createMarker(point);
							map.addOverlay(marker);
						}
						marker.setPoint(point);
						map.panTo(point, true);
						return true;
					}
				});
			}
			else
			{
				return false;
			}
		}

		jQuery(function($){

			if (GBrowserIsCompatible()) 
			{
				geocoder = new GClientGeocoder();
				var create_marker = true;

				var rex_lat = jQuery("#"+options.lat)[0].value;
				var rex_lng = jQuery("#"+options.lng)[0].value;

				if ((rex_lat == "" || rex_lat == 0) && (rex_lng == "" || rex_lng == 0)) 
				{
					if (!getGGeo(true))
					{
						options.rex_zoom = options.rex_zoom_err;
						rex_lat = options.rex_default_lat;
						rex_lng = options.rex_default_lng;		
						create_marker = false;
					}				
				}

				var point = new GLatLng(rex_lat, rex_lng);
				map = new GMap2(jQuery("#map_canvas"+options.id)[0], {draggableCursor:"default"});
				map.setCenter(point, options.rex_zoom);
				map.setUIToDefault();

				if (options.resizable_map)
				{
					map.addControl(new ResizeControl());
				}
				
				if (create_marker) 
				{
					marker = createMarker(point);
					map.addOverlay(marker);
				}

				GEvent.addListener(map, "click", function(overlay, point){
					if (point) 
					{
						map.savePosition();
						jQuery("#"+options.lat)[0].value = point.lat();
						jQuery("#"+options.lng)[0].value = point.lng();
						if (!marker) 
						{
							marker = createMarker(point);
							map.addOverlay(marker);
						}
						marker.setPoint(point);
						map.panTo(point, true);
					}
				});

				jQuery("#rex_getGGeo"+options.id).click(function(){
					getGGeo();
					return false;
				});
				jQuery("#rex_deleteGGeo"+options.id).click(function(){
					jQuery("#"+options.lat)[0].value = "0";
					jQuery("#"+options.lng)[0].value = "0";
					if (marker) 
					{
						map.removeOverlay(marker);
						marker = null;
					}
					return false;
				});
			}
			
		});	

	};

	//]]>
	</script>

















*/





?>