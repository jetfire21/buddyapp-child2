<!-- for volunteers.php -->


  <div id="a21_job_map"></div>
    <script>

      function initMap() {

        var map = new google.maps.Map(document.getElementById('a21_job_map'), {
          zoom: 3,
          center: {lat: -28.024, lng: 140.887},
          maxZoom:9
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: ''});
            // {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }
      var locations = [
          <?php
            global $wpdb;
            $post_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type='job_listing' AND post_status='publish'");
            // print_r($post_ids);

            foreach ($post_ids as $k=>$v) {
              if(!empty($v)):
                 $geolocation = $wpdb->get_col($wpdb->prepare("(SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key='geolocation_lat' AND post_id=%d LIMIT 1) UNION (SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key='geolocation_long' AND post_id=%d LIMIT 1)",(int)$v,(int)$v));
                $location .= "{lat: ".$geolocation[0].", lng: ".$geolocation[1]."},";
              endif;
            }
            echo $location = substr($location, 0,-1);
          ?>
      //   {lat: -31.563910, lng: 147.154312},
      //   {lat: -33.718234, lng: 150.363181}
      //   // {lat: -37.759859, lng: 145.128708},
      //   // {lat: -37.765015, lng: 145.133858},
      ]
    </script>
    <script src="http://my-wp.dev/wp-content/themes/jobify/inc/integrations/wp-job-manager/js/map/vendor/markerclusterer/markerclusterer.js"></script>
    <!-- <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script> -->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJOQtVZMrDEIVLt8uNBIJbMNou5LkzT-c&callback=initMap">
    </script>