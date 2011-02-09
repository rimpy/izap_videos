<?php
/**
 * iZAP izap_videos
 *
 * @package Elgg videotizer, by iZAP Web Solutions.
 * @license GNU Public License version 3
 * @Contact iZAP Team "<support@izap.in>"
 * @Founder Tarun Jangra "<tarun@izap.in>"
 * @link http://www.izap.in/
 * 
 */

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");
global $CONFIG, $IZAPSETTINGS;
$guid = get_input("id");


if(!$guid)
  $guid = current(explode('.', get_input("file")));

// if nothing is found yet..
if(!$guid) {
  $guid = get_input('videoID');
}

$what = get_input("what");
$izap_videos = izapVideoCheck_izap_videos($guid);


if($izap_videos) {
  // check what is needed
  if($what == 'image') {
    if(get_input('size') == 'orignal') {
      $filename = $izap_videos->orignal_thumb;
      if($filename == '') {
        $filename = $izap_videos->imagesrc;
      }
    }else {
      $filename = $izap_videos->imagesrc;
    }
  }elseif(!isset($what) || empty($what) || $what == 'file') {
    $filename = $izap_videos->videofile;
  }

  // only works if there is some file name
  if($filename != '') {
    $fileHandler = new ElggFile();
    $fileHandler->owner_guid = $izap_videos->owner_guid;
    $fileHandler->setFilename($filename);
    if(file_exists($fileHandler->getFilenameOnFilestore()))
      $contents = $fileHandler->grabFile();
  }

  if($contents == '') {
      $contents = file_get_contents($CONFIG->pluginspath . 'izap_videos/_graphics/izapdesign_logo.gif');
  }

  $fileName = end(explode('/', $filename));
  $header_array = array();
  if($what == 'image') {
    $header_array['content_type'] = 'image/jpeg';
  }elseif(!isset($what) || empty($what) || $what == 'file') {
    $header_array['content_type'] = 'application/x-flv';
  }
  $header_array['file_name'] = $fileName;
  $header_array['expire_time'] = 60*60*60;
  izap_cache_headers($header_array);
  echo $contents;
}
exit;