<?PHP

class HotelsInformation{




 public function HotelInformation($hotelId){
    $url  ='http://api.ean.com/ean-services/rs/hotel/v3/info?';
    $url .= '&apiKey=yfxtvdbc26q33ppdfhy89keq';

    $url .= '&hotelId='.$hotelId;


    $header[] = "Accept: application/json";
    $header[] = "Accept-Encoding: gzip";
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
    curl_setopt($ch,CURLOPT_ENCODING , "gzip");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $response = json_decode(curl_exec($ch));

    $response1=objectToArray($response);



    return $response1;
}

function objectToArray($d) {
    if (is_object($d)) {
        $d = get_object_vars($d);
    }

    if (is_array($d)) {

        return array_map(__FUNCTION__, $d);
    }
    else {
        return $d;
    }
}

}
