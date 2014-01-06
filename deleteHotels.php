<?php

$host = "localhost";
$user = "root";
$bdd = "tunisiaway";
$passwd  = "pw2300063";
mysql_connect($host, $user,$passwd) OR die("error connection");

mysql_select_db($bdd) OR die("error data base name");

$tables=array('node_revision','node',
    'field_data_field_adresse','field_revision_field_adresse',
    'field_data_field_hotelid','field_revision_field_hotelid',
    'field_data_field_city','field_revision_field_city',
    'field_data_field_postalcode','field_revision_field_postalcode',
    'field_data_field_countrycode','field_revision_field_countrycode',
    'field_data_field_hotelrating','field_revision_field_hotelrating',
    'field_data_field_locationdescription','field_revision_field_locationdescription',
    'field_data_field_locationhotel','field_revision_field_locationhotel',
    'field_data_field_numberofrooms','field_revision_field_numberofrooms',
    'field_data_field_numberoffloors','field_revision_field_numberoffloors',
    'field_data_field_propertyInformation','field_revision_field_propertyInformation',
    'field_data_field_property_description','field_revision_field_property_description',
    'field_data_field_hotelpolicy','field_revision_field_hotelpolicy',
    'field_data_field_room_information','field_revision_field_room_information'

);

for($i=2;$i<30;$i++){
    $query="DELETE FROM $tables[$i] WHERE bundle='hotel'";
    mysql_query($query);
    echo $tables[$i].'<br>';
}
//for($i=0;$i<2;$i++){
//    $query1="DELETE FROM $tables[$i] WHERE nid>217";
//    mysql_query($query1);
//}

mysql_close();