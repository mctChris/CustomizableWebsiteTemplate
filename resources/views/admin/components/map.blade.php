@php
$lat_name = $options['lat_name']  ?? 'latitude';
$long_name = $options['long_name']  ?? 'longitude';
@endphp


<div id="map" style="height: 300px;" class="mt-3"></div>


<div class="row mt-3">
    <div class="col-md-6">
        <label for="">Latitude</label>
        <input type="text" class="form-control {{ $errors->has($lat_name) ? 'is-invalid' : ''  }}" name="{{ $lat_name }}" value="{{ old($lat_name) ?? $record->$lat_name }}" autocomplete="off">
    </div>
    <div class="col-md-6">
        <label for="">Longitude</label>
        <input type="text" class="form-control {{ $errors->has($long_name) ? 'is-invalid' : ''  }}" name="{{ $long_name }}" value="{{ old($long_name) ?? $record->$long_name }}" autocomplete="off">
    </div>
</div>
@section('js')
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key={{ config('appcustom.google_map_key') }}&callback=initMap" defer></script>
<script src="{{ asset_admin('js/gmaps.js') }}"></script>

<script>
    var map;

    function initMap() {

        map = new GMaps({
            div: '#map',
            lat: 22.3531164,
            lng: 114.1068298,
            zoom: 11,
        });

        map.setContextMenu({
            control: 'map',
            options: [{
                title: 'Set this as my location',
                name: 'add_marker',
                action: function(e){

                    this.removeMarkers();
                    $("[name='{{ $lat_name }}']").val(e.latLng.lat().toString().substr(0, e.latLng.lat().toString().indexOf(".") + 7));
                    $("[name='{{ $long_name }}']").val(e.latLng.lng().toString().substr(0, e.latLng.lng().toString().indexOf(".") + 7));
                    this.addMarker({
                        lat: e.latLng.lat(),
                        lng: e.latLng.lng(),
                        title: 'My Location'
                    });
                    this.hideContextMenu();
                }
            }, {
                title: 'Remove marker',
                name: 'remove_marker',
                action: function(e){
                    $("[name='{{ $lat_name }}']").val('');
                    $("[name='{{ $long_name }}']").val('');
                    this.removeMarkers();
                }
            }]
        });

        if($("[name='{{ $lat_name }}']").val() != '' && $("[name='{{ $long_name }}']").val() != ''){
            zoomAndAddMarkder(parseFloat($("[name='{{ $lat_name }}']").val()), parseFloat($("[name='{{ $long_name }}']").val()));
        }
        
        var geocoder = new google.maps.Geocoder();

        $('.find_location').on('click', function(){
            geocodeAddress(geocoder, $(this));
        })
    }

    function geocodeAddress(geocoder, btn) {
        var address = btn.prev().val();
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            map.removeMarkers();
            zoomAndAddMarkder(parseFloat(results[0].geometry.location.lat()), parseFloat(results[0].geometry.location.lng()));
            $("[name='{{ $lat_name }}']").val(results[0].geometry.location.lat());
            $("[name='{{ $long_name }}']").val(results[0].geometry.location.lng());
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
    }

    function zoomAndAddMarkder(lat, lng){
        map.setCenter({lat: lat, lng: lng});
        map.addMarker({
            lat: lat,
            lng: lng
        })
        map.setZoom(18);
    }
</script>
@endsection