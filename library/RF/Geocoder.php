<?php
class RF_Geocoder {

    private $_host;
    private $_api_key;
    private $_delay = 0;
    private $_pending = 0;

    public function __construct($host='maps.google.com',$api_key=null){
        $this->_host = $host;
        $this->_api_key = $api_key;
    }

    public function getGeoLocationForAddress($address){

        $base_url = "http://maps.google.com/maps/api/geocode/xml?sensor=false";

        $this->_pending = true;
        $request_url = $base_url . "&address=" . urlencode ( $address );
        $xml = simplexml_load_file ( $request_url ) or die ( "url not loading" );

        $result['raw'] = $xml->result;
        $result['status'] = $xml->status;

        if ($result['status']=='OK' && !is_array($result['raw'])) {

            $this->_pending = false;
            $result['lat'] = $xml->result->geometry->location->lat;
            $result['lng'] = $xml->result->geometry->location->lng;

            return $result;

        } else if(is_array($xml->result)){

            $result['lat'] = $xml->result[count($xml->result)-1]->geometry->location->lat;
            $result['lng'] = $xml->result[count($xml->result)-1]->geometry->location->lng;
            return $result;

        }
        else if (strcmp ( $result['status'], "620" ) == 0) {
            $this->_delay += 100000;
            echo "<p>Hit the limit.</p>";
        } else {
            $this->_pending = false;
            echo "<p>Address " . $address . " failed to geocoded. ";
            echo "Received status " . $result['status'] . " ($request_url)</p>";
        }
        usleep ( $this->_delay );
    }
}