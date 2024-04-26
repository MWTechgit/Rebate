function geocode_property_address(address, map_placeholder, info_content) {

    var map;
    var kml;
    var resident_marker;
    var resident_info_window;
    var map_options;
    var geocoder = new google.maps.Geocoder();

    address.replace(/" "/, "+");

    geocoder.geocode({'address': address}, function(results, status) {

        if (status != google.maps.GeocoderStatus.OK) {
            console.log('Google Map geocode failed');
            return;
        }

        map_options = {
            zoom: 12,
            center: results[0].geometry.location,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById(map_placeholder), map_options);

        kml = new google.maps.KmlLayer(Nova.config.gmap_kml_file_url, {
            map: map,
            preserveViewport: true,
            suppressInfoWindows: true
        });

        resident_marker = new google.maps.Marker({
            animation: google.maps.Animation.DROP,
            map: map,
            position: results[0].geometry.location
        });

        resident_info_window = new google.maps.InfoWindow({content: info_content});
        resident_info_window.open(map, resident_marker);
    });
}

export default function initGmaps(address) {
    // var address = "901 NW 11 Ave Fort Lauderdale, FL 33311";
    var infoContent = address;
    geocode_property_address(address, "gmap-embed", infoContent);
}