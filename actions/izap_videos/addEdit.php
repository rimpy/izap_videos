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

// get the posted data
$postedArray = get_input('izap');
$_SESSION['izapVideos'] = $postedArray;

$izap_videos = new IzapVideos($postedArray['guid']);
$izap_videos->container_guid = $postedArray['container_guid'];
$izap_videos->title = $postedArray['title'];
$izap_videos->description = $postedArray['description'];
$izap_videos->access_id = $postedArray['access_id'];
$izap_videos->tags = string_to_tag_array($postedArray['tags']);

$izap_videos->video_views = 1;

switch ($postedArray['videoType']) {
  case 'OFFSERVER':
  // if url is not valid then send it back
    if(!filter_var($postedArray['videoUrl'], FILTER_VALIDATE_URL)) {
      register_error(elgg_echo('izap_videos:error:notValidUrl'));
      forward($_SERVER['HTTP_REFERER']);
    }
    include_once (dirname(__FILE__) . '/OFFSERVER.php');
    break;

  case 'ONSERVER':
    $izap_videos->access_id = ACCESS_PUBLIC;
    if(empty($izap_videos->title)) {
      register_error(elgg_echo('izap_videos:error:emptyTitle'));
      forward($_SERVER['HTTP_REFERER']);
    }
    include_once (dirname(__FILE__) . '/ONSERVER.php');
    break;

  case 'EMBED':
    if(empty($izap_videos->title)) {
      register_error(elgg_echo('izap_videos:error:emptyTitle'));
      forward($_SERVER['HTTP_REFERER']);
    }

    if(empty($postedArray['videoEmbed'])) {
      register_error(elgg_echo('izap_videos:error:emptyEmbedCode'));
      forward($_SERVER['HTTP_REFERER']);
    }
    include_once (dirname(__FILE__) . '/EMBED.php');
    break;

  default:
//    register_error(elgg_echo('izap_videos:error:unknownFileType'));
//    forward($_SERVER['HTTP_REFERER']);
    break;
}

// if we have the optional image then replace all the previous values
if($_FILES['izap']['error']['videoImage'] == 0 && in_array(strtolower(end(explode('.', $_FILES['izap']['name']['videoImage']))), array('jpg', 'gif', 'jpeg', 'png'))) {
  $izap_videos->setFilename($izap_videos->orignal_thumb);
  $izap_videos->open("write");
  $izap_videos->write(file_get_contents($_FILES['izap']['tmp_name']['videoImage']));

  $thumb = get_resized_image_from_existing_file($izap_videos->getFilenameOnFilestore(),120,90, true);

  $izap_videos->setFilename($izap_videos->imagesrc);
  $izap_videos->open("write");
  $izap_videos->write($thumb);
}

// filter tags
if(is_array($izap_videos->tags)) {
  $filtered_tags = FALSE;

  foreach($izap_videos->tags as $tag) {
    if($tag != '') {
      $filtered_tags[] = $tag;
    }
  }
  if($filtered_tags) {
    $izap_videos->tags = array_unique($filtered_tags);
  }
}else if($izap_videos->tags == '' || !$izap_videos->tags){
  unset ($izap_videos->tags);
}

if(!$izap_videos->save()) {
  register_error(elgg_echo('izap_videos:error:save'));
  forward($_SERVER['HTTP_REFERER']);
  exit;
}

// save the file info for converting it later  in queue
if($postedArray['videoType'] == 'ONSERVER' && $postedArray['guid'] == 0) {
  $izap_videos->videosrc = $CONFIG->wwwroot . 'pg/izap_videos_files/file/' . $izap_videos->guid . '/' . friendly_title($izap_videos->title) . '.flv';
  if(izap_get_file_extension($tmpUploadedFile) != 'flv') { // will only send to queue if it is not flv
    izapSaveFileInfoForConverting_izap_videos($tmpUploadedFile, $izap_videos, $postedArray['access_id']);
  }
}

if($postedArray['guid'] == 0) {
  add_to_river('river/object/izap_videos/create', 'create', $izap_videos->owner_guid, $izap_videos->guid);
}
system_message(elgg_echo('izap_videos:success:save'));
unset($_SESSION['izapVideos']);
forward($izap_videos->getUrl());
exit;