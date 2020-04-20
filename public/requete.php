<?php
$mysqli = new mysqli("localhost", "root", "", "sorties");
if($mysqli->connect_error) {
    exit('Could not connect');
}

$sql = "SELECT l.nom,rue,latitude,longitude,v.nom,code_postal
FROM lieu l JOIN ville v ON v.id=l.ville_id WHERE l.id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $_GET['q']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($nom, $rue, $latitude, $longitude,$ville,$cpo);
$stmt->fetch();
$stmt->close();


echo '<p>Ville:'.$ville.'</p>';
echo '<p>Rue:'.$rue.'</p>';
echo '<p>Code postal:'.$cpo.'</p>';
echo '<p>Latitude:'.$latitude.'°</p>';
echo '<p>Longitude:'.$longitude.'°</p>';