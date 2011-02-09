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

global $CONFIG;
$video = $vars['video'];
$videos = $video->getRelatedVideos();
if($videos) {
  echo elgg_view_title(elgg_echo('izap_videos:related_videos'));
  echo elgg_view('izap_videos/videos_bunch', array('videos' => $videos));
}

$options['type'] = 'object';
$options['subtype'] = 'izap_videos';
$options['limit'] = 10;
if(is_old_elgg()) {
  $videos = get_entities($options['type'], $options['subtype'],0,'', $options['subtype']);
}else {
  $videos = elgg_get_entities($options);
}

if($videos) {
  echo elgg_view_title(elgg_echo('izap_videos:latest'));
  echo elgg_view('izap_videos/videos_bunch', array('videos' => $videos));
}
