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

$videoValues = $izap_videos->input($postedArray['videoEmbed'], 'embed');

if(!is_object($videoValues)) {
  register_error(elgg_echo('izap_videos:error:code:' . $videoValues));
  forward($_SERVER['HTTP_REFERER']);
}

$izap_videos->videotype = $videoValues->type;
$izap_videos->videosrc = $videoValues->videoSrc;
$izap_videos->orignal_thumb = 'izap_videos/embed/orignal_' . time() . '.jpg';
$izap_videos->imagesrc = 'izap_videos/embed/' . time() . '.jpg';
$izap_videos->converted = 'yes';