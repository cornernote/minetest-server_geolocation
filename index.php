<?php
$servers = array();
$servers[] = array(
	'name'=>'D-L-K Networks GameServer',
	'host'=>'game.d-l-k.net',
	'port'=>'30000',
	'site'=>'',
);
$servers[] = array(
	'name'=>'GameBoom\'s MineTest server MT1',
	'host'=>'mt1.gameboom.net',
	'port'=>'30000',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=2592',
);
$servers[] = array(
	'name'=>'glomie\'s server',
	'host'=>'94.23.33.108',
	'port'=>'30000',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=2111',
);
$servers[] = array(
	'name'=>'Minetest.Ru main server',
	'host'=>'minetest.ru',
	'port'=>'30000',
	'site'=>'http://minetest.ru/',
);
$servers[] = array(
	'name'=>'[Wazu] Clan Server 0.4.1',
	'host'=>'wazuclan.com',
	'port'=>'30000',
	'site'=>'http://www.wazuclan.com',
);
$servers[] = array(
	'name'=>'Jordach\'s Pixel Art',
	'host'=>'redcrab.suret.net',
	'port'=>'30402',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=1867',
);
$servers[] = array(
	'name'=>'Redcrab\'s Minetest Server 0.3.1',
	'host'=>'redcrab.suret.net',
	'port'=>'30031',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=1380',
);
$servers[] = array(
	'name'=>'Redcrab\'s server : for serious builder',
	'host'=>'redcrab.suret.net',
	'port'=>'30401',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=1705',
);
$servers[] = array(
	'name'=>'Mrtux\'s Minetest server',
	'host'=>'50.112.56.189',
	'port'=>'30001',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=2653',
);
$servers[] = array(
	'name'=>'M13\'s 0.4.3 Server',
	'host'=>'m13.sytes.net',
	'port'=>'30000',
	'site'=>'',
);
$servers[] = array(
	'name'=>'Calinou\'s Server',
	'host'=>'calin.sytes.net',
	'port'=>'30000',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=3102',
);
$servers[] = array(
	'name'=>'Redcrab\'s server 0.4 dev20120106-1',
	'host'=>'redcrab.suret.net',
	'port'=>'30401',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=606',
);
$servers[] = array(
	'name'=>'CoRNeTNoTe\'S SeRVeR [SkyBlock][Australia]',
	'host'=>'cornernote.servegame.com',
	'port'=>'30000',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=3154',
);
$servers[] = array(
	'name'=>'Free Build',
	'host'=>'199.119.227.56',
	'port'=>'30000',
	'site'=>'',
);
$servers[] = array(
	'name'=>'Menche\'s Server',
	'host'=>'menche.servegame.com',
	'port'=>'30001',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=2124',
);
$servers[] = array(
	'name'=>'Globis server',
	'host'=>'176.31.175.144',
	'port'=>'30000',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=2111',
);
$servers[] = array(
	'name'=>'Zenoheld the Backstab server',
	'host'=>'minetest.freedns.in',
	'port'=>'30000',
	'site'=>'http://minetest.net/forum/viewtopic.php?id=796',
);
$servers[] = array(
	'name'=>'freebuild',
	'host'=>'thelunarrepublic.cu.cc',
	'port'=>'30000',
	'site'=>'http://thelunarrepublic.site90.net/',
);
include("maxmind/geoipcity.inc");
include("maxmind/geoipregionvars.php");
$gi = geoip_open("GeoLiteCity.dat",GEOIP_STANDARD);
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
			$record = geoip_record_by_addr($gi,gethostbyname($server['host']));
			if ($record) {
				?>
				var marker_<?php echo $k; ?> = new google.maps.Marker({
					position: new google.maps.LatLng(<?php echo $record->latitude; ?>,<?php echo $record->longitude; ?>),
					title: '<?php echo addslashes($server['name']); ?>',
					map: map
				});
				google.maps.event.addListener(marker_<?php echo $k; ?>, 'click', function() {
					infowindow.setContent('<strong><a href="<?php echo $server['site']; ?>"><?php echo addslashes($server['name']); ?></a></strong><br/><?php echo $server['host']; ?>:<?php echo $server['port']; ?>');
					infowindow.open(map, marker_<?php echo $k; ?>);
				});
				<?php
			}
		}
		?>
      }
    </script>
  </head>
  <body onload="initialize()">
    <div id="map_canvas" style="width:100%; height:100%"></div>
  </body>
</html>
<?php
geoip_close($gi);
?>