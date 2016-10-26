<?php

?>
<html>
<head>
<link rel="stylesheet" href="/assets/leaflet/leaflet.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="/assets/leaflet/leaflet.js"></script>

<style>
#mapid{width:100%;height:300px;}
</style>
</head>
<body>

<h1>Особенности реализации</h1>
Проверка данных только на уровне заполненности всех полей (на стороне клиента и сервера).<br>
Использование leaflet для отображения карты (как на скриншоте к заданию).<br>
Вместо фреймворка использовался класс для REST-API url-разметки и собственный db-коннектор из другого проекта.<br>
Для удобства стили и скрипты встроены в тело страницы.


<h1>Добавление точки</h1>
<form id="add_points_form" method="POST">
	<p><label for="field_lat">lat <input type="text" name="point_lat" id="field_lat" placeholder="55.753706"></label></p>
	<p><label for="field_lon">lon <input type="text" name="point_lon" id="field_lon" placeholder="37.612338"></label></p>
	<p><label for="field_text">text <input type="text" name="point_text" id="field_text" placeholder="Манеж"></label></p>
	<p><input type="submit"></p>
</form>

<h1>Карта точек</h1>
<div id="mapid"></div>

<script>


(function($){

	function mapRefresh(){
		mymap = L.map('mapid').setView([55.753316, 37.618346], 15);

		L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
    		maxZoom: 18,
    		id: 'mapbox.streets',
    		accessToken: 'pk.eyJ1IjoibGV4YW5kZXIiLCJhIjoiY2l1cXA3ZTQ3MDAwODJ5bXNidzloMWt4eSJ9.9CvsOp2RwTMEqN6MAmOG_Q'
		}).addTo(mymap);
		
		 $.ajax("/v1/points/",{
			method:"GET",
			dataType:"json",
			success:function(data){
				if(data["errors"].length>0) throw new Error(data["errors"][0]);
				else for(var i in data["response"]){
					var p_obj=data["response"][i];
					console.log(p_obj);
					L.marker([p_obj.point_lat, p_obj.point_lon]).addTo(mymap).bindPopup("<b>Сообщение:</b><br>"+p_obj.point_text).openPopup();
				}

			}
		});
}


$(function(){
	mapRefresh();

		$('#add_points_form').submit(
		function(e){
			var has_empty = false;
			$(this).find('input[type!="hidden"][type!="submit"]').each(function () {
			if ($(this).val()==="") {
				has_empty=true;
				return false;
				}
	  	 	});

			if (has_empty) {
   				alert('Пожалуйста, заполните все поля формы.');
   				return false;
   				}
	
			 $.ajax("/v1/points/",{
				method:"POST",
				dataType:"json",
				data: $('#add_points_form').serialize(),
				success:function(data){
					if(data["errors"].length>0) throw new Error(data["errors"][0]);
					else {
						alert("Точка добавлена");
						p_obj=data["response"];
						L.marker([p_obj.point_lat, p_obj.point_lon]).addTo(mymap).bindPopup("<b>Сообщение:</b><br>"+p_obj.point_text).openPopup();
					}
				}
			});
			e.preventDefault();
		}
);

});})(jQuery)
</script>

</body>
</html>