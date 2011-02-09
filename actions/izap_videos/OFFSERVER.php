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

$videoValues = $izap_videos->input($postedArray['videoUrl'], 'url');
if($videoValues->success != 'false') {
  if($videoValues->videoSrc == '' || $videoValues->fileContent == '') {
    register_error(elgg_echo('izap_videos:error'));
    forward($_SERVER['HTTP_REFERER']);
    exit;
  }
  if($postedArray['title'] == '') {
    $izap_videos->title = $videoValues->title;
  }

  if($postedArray['description'] == '') {
    $izap_videos->description = (is_array($videoValues->description)) ? elgg_echo('izap_videos:noDescription') : $videoValues->description;
  }

  if($postedArray['tags'] == '' && isset ($videoValues->videoTags)) {
    $izap_videos->tags = string_to_tag_array($videoValues->videoTags);
  }

  $izap_videos->videosrc = $videoValues->videoSrc;
  $izap_videos->videotype = $videoValues->type;
  $izap_videos->orignal_thumb = "izap_videos/" . $videoValues->type . "/orignal_" . $videoValues->fileName;
  $izap_videos->imagesrc = "izap_videos/" . $videoValues->type . "/" . $videoValues->fileName;
  $izap_videos->videotype_site = $videoValues->domain;
  $izap_videos->converted = 'yes';
  $izap_videos->setFilename($izap_videos->orignal_thumb);
  $izap_videos->open("write");
  if($izap_videos->write($videoValues->fileContent)) {
    $thumb = get_resized_image_from_existing_file($izap_videos->getFilenameOnFilestore(),120,90, true);
    $izap_videos->setFilename($izap_videos->imagesrc);
    $izap_videos->open("write");
    if(!$izap_videos->write($thumb)) {
      register_error(elgg_echo('izap_videos:error:saving_thumb'));
    }
  }else {
    register_error(elgg_echo('izap_videos:error:saving_thumb'));
  }
}else {
  register_error($videoValues->message);
  forward($_SERVER['HTTP_REFERER']);
  exit;
}