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

$videoId = get_input('videoId');
$video = izapVideoCheck_izap_videos($videoId);

$attribs = $video->getAttributes();

$newVideo = new IzapVideos();
foreach($attribs as $attribute => $value) {
  $newVideo->$attribute = $value;
}
$newVideo->views = 1;
$newVideo->owner_guid = get_loggedin_userid();
$newVideo->container_guid = get_loggedin_userid();
$newVideo->access_id = $video->access_id;
$newVideo->copiedFrom = $video->owner_guid;
$newVideo->copiedVideoId = $videoId;
$newVideo->copiedVideoUrl = $video->getUrl();

izapCopyFiles_izap_videos($video->owner_guid, $video->imagesrc);

if($video->videotype == 'uploaded') {
  izapCopyFiles_izap_videos($video->owner_guid, $video->videofile);
  izapCopyFiles_izap_videos($video->owner_guid, $video->orignalfile);
}

if($newVideo->save()) {
  system_message(elgg_echo('izap_videos:success:videoCopied'));
  forward($newVideo->getURL());
}else {
  system_message(elgg_echo('izap_videos:success:videoNotCopied'));
  forward($_SERVER['HTTP_REFERER']);
}
