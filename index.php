<?php

// load the geoip api
include("maxmind/geoipcity.inc");
include("maxmind/geoipregionvars.php");
$gi = geoip_open("GeoLiteCity.dat",GEOIP_STANDARD);

// get server results from servers.minetest.ru
$dom = new DOMDocument();  
$dom->preserveWhiteSpace = false;   
$html = $dom->loadHTMLFile('http://servers.minetest.ru/');  
$tables = $dom->getElementsByTagName('table');   
$rows = $tables->item(0)->getElementsByTagName('tr');   
$results = array();
foreach ($rows as $row) {
	$cols = $row->getElementsByTagName('td');  
	$result = array();
	foreach ($cols as $col) {
		$result[] = $col->nodeValue;
	}
	$results[] = $result;
}   

// extract the server info
$servers = array();
foreach($results as $result) {
	if (!$result) continue; 
	list($host, $port) = explode(':',$result[1]);
	$geoip = geoip_record_by_addr($gi,gethostbyname($host));
	if (!$geoip) continue; 
	$servers[] = array(
		'name'=>$result[0],
		'host'=>$host,
		'port'=>$port,
		'lat'=>$geoip->latitude,
		'lon'=>$geoip->longitude,
		'site'=>$result[2],
		'status'=>$result[3],
		'uptime'=>$result[4],
	);
}

?><!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<style type="text/css">
			html { height: 100% }
			body { height: 100%; margin: 0; padding: 0 }
			#map_canvas { height: 100% }
		</style>
		<script type="text/javascript"
			src="http://maps.googleapis.com/maps/api/js?key=AIzaSyA6aXUYnpxJTCdvHwVRK24Sc-fx28Z4gXc&sensor=false">
		</script>
		<script type="text/javascript">
		function initialize() {
			var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
			var mapOptions = {
				zoom: 3,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
			var infowindow = new google.maps.InfoWindow();
			<?php 
			foreach ($servers as $k=>$server) {
				?>
				var marker_<?php echo $k; ?> = new google.maps.Marker({
					position: new google.maps.LatLng(<?php echo $server['lat']; ?>,<?php echo $server['lon']; ?>),
					title: '<?php echo addslashes($server['name']); ?>',
					map: map
				});
				google.maps.event.addListener(marker_<?php echo $k; ?>, 'click', function() {
					infowindow.setContent('<strong><a href="<?php echo $server['site']; ?>"><?php echo addslashes($server['name']); ?></a></strong><br/><?php echo $server['host']; ?>:<?php echo $server['port']; ?><br/><br/>Status: <?php echo $server['status']; ?> | Uptime: <?php echo $server['uptime']; ?>');
					infowindow.open(map, marker_<?php echo $k; ?>);
				});
				<?php
			}
			?>
		}
		</script>
	</head>
	<body onload="initialize()">
		<div id="map_canvas" style="width:100%; height:100%"></div>
	</body>
</html>