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

$videoValues = $izap_videos->input(
        array(
        'file' => $_FILE,
        'mainArray' => 'izap',
        'fileName' => 'videoFile',
        ),
        'file');

if(!is_object($videoValues)) {
  register_error(elgg_echo('izap_videos:error:code:' . $videoValues));
  forward($_SERVER['HTTP_REFERER']);
  exit;
}

if(empty($videoValues->type) || ($videoValues->is_flv !='yes' && !file_exists($videoValues->tmpFile))) {
  register_error(elgg_echo('izap_videos:error:notUploaded'));
  forward($_SERVER['HTTP_REFERER']);
  exit;
}

$izap_videos->videotype = $videoValues->type;
if($videoValues->thumb) {
  $izap_videos->orignal_thumb = $videoValues->orignal_thumb;
  $izap_videos->imagesrc = $videoValues->thumb;
}else {
  $izap_videos->imagesrc = $CONFIG->wwwroot . 'mod/izap_videos/_graphics/video_converting.gif';
}

// Defining new preview arrtibute to be saved with the video entity
if($videoValues->preview){
  $izap_videos->preview = $videoValues->preview;
}

if($videoValues->is_flv != 'yes') {
  $izap_videos->converted = 'no';
  $izap_videos->videofile = 'nop';
  $izap_videos->orignalfile = 'nop';
}
$tmpUploadedFile = $videoValues->tmpFile;

