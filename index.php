<?php
$NAMOBJ = [
	"Aluh_Aluh" => "#ff0000",
	"Aranio" => "#ffe119",
	"Astambul" => "#f7e40c",
	"Beruntung_Baru" => "#1ffa02",
	"Cintapuri_Darussalam" => "#f01105",
	"Gambut" => "#000075",
	"Karang_Intan" => "#800000",
	"Kertak_Hanyar" => "#2afae3",
	"Martapura_Barat" => "#03fccf",
	"Martapura_Timur" => "#ff03d9",
	"Martapura" => "#000000",
	"Mataraman" => "#0bfc03",
	"Paramasan" => "#fc03db",
	"Pengaron" => "#00e7fc",
	"Sambung_Makmur" => "#0303ff",
	"Simpang_Empat" => "#000000",
	"Sungai_Pinang" => "#a8ace6",
	"Sungai_Tabuk" => "#faf602",
	"Tatah_Makmur" => "#ff7403",
	"Telaga_Bauntung" => "#911eb4"
];
?>

<!DOCTYPE html>
<html>

<head>
	<title>WebGIS GeoJSON</title>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
</head>
<link rel="stylesheet" href="assets/js/leaflet-panel-layers-master/src/leaflet-panel-layers.css" />
<style type="text/css">
	#mapid {
		height: 100vh;
	}
	.icon {
	display: inline-block;
	margin: 2px;
	height: 16px;
	width: 16px;
	background-color: #ccc;
}
.icon-bar {
	background: url('assets/js/leaflet-panel-layers-master/examples/images/icons/bar.png') center center no-repeat;
}
</style>

<body>
	<div id="mapid"></div>
</body>
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin="">
</script>

<script src="assets/js/leaflet-panel-layers-master/src/leaflet-panel-layers.js"></script>
<script src="assets/js/leaflet.ajax.js"></script>

<script type="text/javascript">
	var mymap = L.map('mapid').setView([-3.2776676, 114.8174057], 9);

	var LayerKita = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	});
	mymap.addLayer(LayerKita);



	function popUp(f, l) {
		var out = [];
		if (f.properties) {
			//for (key in f.properties) {
			//	out.push(key + ": " + f.properties[key]);
			//}

			//out.push(key + ": " + f.properties[key]);
			out.push("Kecamatan: " + f.properties['NAMOBJ']);
			//out.push("Luas Wilayah: " + f.properties['LUASWILAYAH'] + " Km2");
			//out.push("Jumlah Penduduk: " + f.properties['JUMLAHPENDUDUK'] + " Jiwa");
			//out.push("Jumlah Penduduk Miskin: " + f.properties['PENDUDUKMISKIN'] + " Jiwa");

			l.bindPopup(out.join("<br />"));
		}
	}



	//legend


	function iconByName(name) {
		return '<i class="icon icon-' + name + '"></i>';
	}

	function featureToMarker(feature, latlng) {
		return L.marker(latlng, {
			icon: L.divIcon({
				className: 'marker-' + feature.properties.amenity,
				html: iconByName(feature.properties.amenity),
				iconUrl: '../images/markers/' + feature.properties.amenity + '.png',
				iconSize: [25, 41],
				iconAnchor: [12, 41],
				popupAnchor: [1, -34],
				shadowSize: [41, 41]
			})
		});
	}

	var baseLayers = [{
			name: "OpenStreetMap",
			layer: LayerKita
		},
		//{
		//	name: "OpenCycleMap",
		//	layer: L.tileLayer('https://{s}.tile.opencyclemap.org/cycle/{z}/{x}/{y}.png')
		//},
		//{
		//	name: "Outdoors",
		//	layer: L.tileLayer('https://{s}.tile.thunderforest.com/outdoors/{z}/{x}/{y}.png')
		//}
	];
	<?php
	foreach ($NAMOBJ as $key => $value) {
	?>
		var myStyle<?=$key?> = {
			"color": "<?=$value?>",
			"weight": 1,
			"opacity": 0.65
		};
	<?php
		$arrayKec[] = '{
			name: "' .str_replace('_',' ', $key) . '",
			icon: iconByName("bar"),
			layer: new L.GeoJSON.AJAX(["assets/geojson/' . $key . '.geojson"], {
			onEachFeature: popUp,
			style: myStyle'.$key.',
			pointToLayer: featureToMarker })
			.addTo(mymap)
		}';
	}

	?>
	var overLayers = [
		<?= implode(', ', $arrayKec) ?>
	];

	var panelLayers = new L.Control.PanelLayers(baseLayers, overLayers);

	mymap.addControl(panelLayers);
</script>

</html>