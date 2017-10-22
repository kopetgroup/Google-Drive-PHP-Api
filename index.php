<?php
require_once __DIR__.'/vendor/autoload.php';

session_start();
//session_destroy();

$client = new Google_Client();
$client->setAuthConfig('kopet.json');
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

  $client->setAccessToken($_SESSION['access_token']);
  $drive = new Google_Service_Drive($client);

  echo '<pre>';
	$files = $drive->files->listFiles(array())->getFiles(); //http://stackoverflow.com/questions/37975479/call-to-undefined-method-google-service-drive-filelistgetitems
  foreach($files as $f){

    $response = $drive->files->get($f->id, array( 'alt' => 'media'));
    $content = $response->getBody()->getContents();

    echo $f->id.' - '.$f->mimeType.' - '.$f->name.'<br/>';
    echo $content;

  }

  //$files = $drive->files->listFiles(array())->getItems();
  //echo json_encode($files);

} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
