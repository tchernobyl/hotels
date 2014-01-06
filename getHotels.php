<?php

define('DIRECTORY', '/home/pw2/Pictures/hotels');
class getHotels{

    public function connect(){
        $host = "localhost";
        $user = "root";
        $bdd = "tunisiaway";
        $passwd  = "pw2300063";
        mysql_connect($host, $user,$passwd) OR die("error connection");

        mysql_select_db($bdd) OR die("error data base name");

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


    public function listHotels(){
        $url  ='http://api.ean.com/ean-services/rs/hotel/v3/list?';
        $url .= '&apiKey=yfxtvdbc26q33ppdfhy89keq';
        $url .= '&locale=en_TN&city=Sousse&countryCode=TN';


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


        $array=$response1['HotelListResponse']['HotelList']['HotelSummary'];

        $info=new HotelsInformation();
        echo '<table>';
        $counter=0;
        $host = "localhost";
        $user = "root";
        $bdd = "tunisiaway";
        $passwd  = "pw2300063";
        mysql_connect($host, $user,$passwd) OR die("error connection");

        mysql_select_db($bdd) OR die("error data base name");

        function insertImage($url,$id,$delta,$width,$height){

            $content = file_get_contents($url);
            $path = parse_url($url, PHP_URL_PATH);
            $filename = basename($path);
//            file_put_contents(DIRECTORY.'/'.$filename, $content);

            $filesize= filesize(DIRECTORY.'/'.$filename);

            $name = strtok($filename, '.');
            $uri="public://hotels/".$filename;

            $query="INSERT INTO `file_managed`
    ( `uid`, `filename`, `uri`, `filemime`, `filesize`, `status`, `timestamp`)
     VALUES (1,'$filename','$uri','image/jpeg',$filesize,1,1389019711)";
            mysql_query($query);
            $fid=mysql_insert_id();
            $query1="INSERT INTO `file_usage`
    (`fid`, `module`, `type`, `id`, `count`)
    VALUES ($fid,'file','node',$id,1)";
            mysql_query($query1);
            $query2="INSERT INTO `field_data_field_images`
(`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_images_fid`, `field_images_alt`, `field_images_title`, `field_images_width`, `field_images_height`)
    VALUES ('node','hotel',0,$id,$id,'und',$delta,$fid,$width,$height)";
            mysql_query($query2);
            $query3="INSERT INTO `field_revision_field_images`
(`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_images_fid`, `field_images_alt`, `field_images_title`, `field_images_width`, `field_images_height`)
    VALUES ('node','hotel',0,$id,$id,'und',$delta,$fid,$width,$height)";
            mysql_query($query3);
        }
        foreach ($array as $hotel){

            $counter++;
            echo $counter.'<br>';
            $name=$hotel['name'];

            $hotelId=$hotel['hotelId'];

            $address1=$hotel['address1'];

            $city=$hotel['city'];

            $postalCode=$hotel['postalCode'];

            $countryCode=$hotel['countryCode'];

            $airportCode=$hotel['airportCode'];

            $locationDescription=$hotel['locationDescription'];

            $shortDescription=$hotel['shortDescription'];

            $latitude=$hotel['latitude'];

            $longitude=$hotel['longitude'];

            $hotelInDestination=$hotel['hotelInDestination'];


            $array2=$info->HotelInformation($hotelId);

            $HotelSummary=$array2['HotelInformationResponse']['HotelSummary'];

            $HotelDetails=$array2['HotelInformationResponse']['HotelDetails'];

            $HotelImages=$array2['HotelInformationResponse']['HotelImages'];
            $hotelRating=$HotelSummary['hotelRating'];
            $numberOfFloors=$HotelDetails['numberOfFloors'];
            $numberOfRooms=$HotelDetails['numberOfRooms'];
//            $propertyInformation=$HotelDetails['propertyInformation'];
            $propertyDescription=$HotelDetails['propertyDescription'];
            $hotelPolicy=$HotelDetails['hotelPolicy'];
            $roomInformation=$HotelDetails['roomInformation'];
//
//            echo '<tr><td>hotelRating         :</td><td>'.$hotelRating.'</td></tr>';
//            echo '<tr><td>numberOfFloors      :</td><td>'.$numberOfFloors.'</td></tr>';
//            echo '<tr><td>numberOfRooms       :</td><td>'.$numberOfRooms.'</td></tr>';
//            echo '<tr><td>propertyInformation :</td><td>'.$propertyInformation.'</td></tr>';
//            echo '<tr><td>propertyDescription :</td><td>'.$propertyDescription.'</td></tr>';
//            echo '<tr><td>hotelPolicy         :</td><td>'.$hotelPolicy.'</td></tr>';
//            echo '<tr><td>roomInformation     :</td><td>'.$roomInformation.'</td></tr>';

            $querynode  = "INSERT INTO node(type,language,title,uid,status,created,changed,comment,promote,sticky,tnid,translate)
                        VALUES('hotel','und','$name',1,1,1385120290,1385120290,2,1,0,0,0)";
            mysql_query($querynode);
            $id=mysql_insert_id();
            $update="UPDATE node
            SET vid=$id WHERE nid=$id";
            mysql_query($update);
            $queryrevisionnode="INSERT INTO `node_revision`(`nid`, `vid`, `uid`, `title`, `timestamp`, `status`, `comment`, `promote`, `sticky`)
            VALUES ($id,$id,1,'$name',1385120290,1,2,1,0)";
            mysql_query($queryrevisionnode);
//---------------------->field_data_field_adresse

           $query1="INSERT INTO `field_data_field_adresse`
           (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_adresse_value`)
             VALUES ('node','hotel',0,$id,$id,'und',0,'$address1')";
            $query2="INSERT INTO `field_data_field_adresse`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_adresse_value`)
             VALUES ('node','hotel',0,$id,$id,'und',0,'$address1')";
            mysql_query($query1);
            mysql_query($query2);
//---------------------->field_data_field_$hotelId

            $query3="INSERT INTO `field_data_field_hotelid`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_hotelid_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$hotelId)";
            $query4="INSERT INTO `field_revision_field_hotelid`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_hotelid_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$hotelId)";
            mysql_query($query3);
            mysql_query($query4);
//---------------------->field_data_field_city


            $query5="INSERT INTO `field_data_field_city`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_city_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$city')";
            $query6="INSERT INTO `field_revision_field_city`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_city_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$city')";
            mysql_query($query5);
            mysql_query($query6);

//---------------------->field_data_field_postalcode
            $query7="INSERT INTO `field_data_field_postalcode`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_postalcode_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$postalCode)";
            $query8="INSERT INTO `field_revision_field_postalcode`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_postalcode_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$postalCode)";
            mysql_query($query7);
            mysql_query($query8);
//---------------------->field_data_field_countrycode
            $query9="INSERT INTO `field_data_field_countrycode`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_countrycode_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$countryCode')";
            $query10="INSERT INTO `field_revision_field_countrycode`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_countrycode_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$countryCode')";
            mysql_query($query9);
            mysql_query($query10);
//---------------------->field_data_field_hotelrating

            $query11="INSERT INTO `field_data_field_hotelrating`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_hotelrating_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$hotelRating)";
            $query12="INSERT INTO `field_revision_field_hotelrating`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_hotelrating_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$hotelRating)";
            mysql_query($query11);
            mysql_query($query12);


//---------------------->field_data_field_locationDescription

            $query13="INSERT INTO `field_data_field_locationdescription`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_locationdescription_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$locationDescription')";
            $query14="INSERT INTO `field_revision_field_locationdescription`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_locationdescription_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$locationDescription')";
            mysql_query($query13);
            mysql_query($query14);


//---------------------->field_data_field_locationhotel latitude and longitude

            $query15="INSERT INTO `field_data_field_locationhotel`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_locationhotel_geom`, `field_locationhotel_geo_type`, `field_locationhotel_lat`, `field_locationhotel_lon`, `field_locationhotel_left`, `field_locationhotel_top`, `field_locationhotel_right`, `field_locationhotel_bottom`, `field_locationhotel_geohash`)
            VALUES ('node', 'hotel', '0', $id, $id, 'und', '0', 0x01010000005eba490c020b274064cc5d4bc8872440, 'point',
            $latitude,$longitude, $longitude, $latitude, $longitude, $latitude, 's3b4qhtxn')
            ";
            $query16="INSERT INTO `field_revision_field_locationhotel`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_locationhotel_geom`, `field_locationhotel_geo_type`, `field_locationhotel_lat`, `field_locationhotel_lon`, `field_locationhotel_left`, `field_locationhotel_top`, `field_locationhotel_right`, `field_locationhotel_bottom`, `field_locationhotel_geohash`)
            VALUES ('node', 'hotel', '0', $id, $id, 'und', '0', 0x01010000005eba490c020b274064cc5d4bc8872440, 'point',
            $latitude,$longitude, $longitude, $latitude, $longitude, $latitude, 's3b4qhtxn')
            ";
            mysql_query($query15);
            mysql_query($query16);

//---------------------->field_data_field_numberOfRooms

            $query17="INSERT INTO `field_data_field_numberofrooms`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_numberofrooms_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$numberOfRooms)";
            $query18="INSERT INTO `field_revision_field_numberofrooms`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_numberofrooms_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$numberOfRooms)";
            mysql_query($query17);
            mysql_query($query18);

//---------------------->field_data_field_numberOfFloors

            $query19="INSERT INTO `field_data_field_numberoffloors`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_numberoffloors_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$numberOfFloors)";
            $query20="INSERT INTO `field_revision_field_numberoffloors`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_numberoffloors_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,$numberOfFloors)";
            mysql_query($query19);
            mysql_query($query20);
////---------------------->field_data_field_propertyInformation
//
//            $query21="INSERT INTO `field_data_field_propertyinformation`
//            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_propertyinformation_value`)
//            VALUES ('node','hotel',0,$id,$id,'und',0,'$propertyInformation')";
//            $query22="INSERT INTO `field_revision_field_propertyinformation`
//            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_propertyinformation_value`)
//            VALUES ('node','hotel',0,$id,$id,'und',0,'$propertyInformation')";
//            mysql_query($query21);
//            mysql_query($query22);

//---------------------->field_data_field_property_description

            $query23="INSERT INTO `field_data_field_property_description`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_property_description_value`, `field_property_description_format`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$propertyDescription','full_html')";
            $query24="INSERT INTO `field_revision_field_property_description`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_property_description_value`, `field_property_description_format`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$propertyDescription','full_html')";
            mysql_query($query23);
            mysql_query($query24);
//---------------------->field_data_field_hotelpolicy

            $query25="INSERT INTO `field_data_field_hotelpolicy`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_hotelpolicy_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$hotelPolicy')";
            $query26="INSERT INTO `field_revision_field_hotelpolicy`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_hotelpolicy_value`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$hotelPolicy')";
            mysql_query($query25);
            mysql_query($query26);

//---------------------->field_data_field_room_information

            $query27="INSERT INTO `field_data_field_room_information`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_room_information_value`, `field_room_information_format`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$roomInformation','full_html')";
            $query28="INSERT INTO `field_revision_field_room_information`
            (`entity_type`, `bundle`, `deleted`, `entity_id`, `revision_id`, `language`, `delta`, `field_room_information_value`, `field_room_information_format`)
            VALUES ('node','hotel',0,$id,$id,'und',0,'$roomInformation','full_html')";
            mysql_query($query27);
            mysql_query($query28);

            $numberOfImages=$array2['HotelInformationResponse']['HotelImages']['@size'];
            $images=$array2['HotelInformationResponse']['HotelImages']['HotelImage'];

for($c=0;$c<$numberOfImages;$c++){

    $ur=$images[$c]['url'];

    $width=$images[$c]['width'];

    $height=$images[$c]['height'];
    insertImage($ur,$id,$c,$width,$height);
}

        }
//        foreach ($array as $hotel){
//            $hotelId=$hotel['hotelId'];
//            $array2=$info->HotelInformation($hotelId);
//            $numberOfImages=$array2['HotelInformationResponse']['HotelImages']['@size'];
//            $images=$array2['HotelInformationResponse']['HotelImages']['HotelImage'];
//            echo '<br>'.$hotelId."-->".$numberOfImages.'<br>';
//            var_dump($images);
//        }
        mysql_close();
        echo '</table>';
        return $array;

    }
}