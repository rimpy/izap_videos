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

$data = get_input('username');
$data = explode(':', $data);

$video = get_entity($data[0]);
if($video instanceof IzapVideos) {
  $width = $IZAPSETTINGS->ajaxed_video_width;
  $hegiht = $IZAPSETTINGS->ajaxed_video_height;
  if(isset ($data[1])) {
    $dimensions = $data[1];
    $dimensions = explode('x', $dimensions);
    $width = $dimensions[0];
    $hegiht = $dimensions[1];
  }
  global $IZAPSETTINGS;
  $player = $video->getPlayer($width, $hegiht, 1);
  echo $player;
}else {
  echo elgg_echo('izap_videos:ajaxed_videos:error_loading_video');
}
?>