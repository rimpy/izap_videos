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

$page_owner = page_owner_entity();
if($vars['entity']->izap_videos_enable != 'no') {
  echo '<div id="izap_widget_layout">';
  echo '<h2>' . elgg_echo('izap_videos:groupvideos') . '</h2>';

  $options['type'] = 'object';
  $options['subtype'] = 'izap_videos';
  $options['container_guid'] = $page_owner->guid;
  if(is_old_elgg()) {
    $videos = get_entities('object', 'izap_videos', $page_owner->guid);
  }else{
    $videos = elgg_get_entities($options);
  }

  if($videos) {
    echo '<div class="group_video_wrap">';
    echo elgg_view('izap_videos/videos_bunch',array('videos' => $videos, 'title_length' => 30, 'wrap' => FALSE));
    echo '<div class="view_all"><a href="' .  $CONFIG->wwwroot . 'pg/videos/list/' . $page_owner->username . '">'.elgg_echo('izap_videos:view_all').'</a></div>';
    echo '</div>';
  }else {
    echo '<div class="forum_latest">' . elgg_echo('izap_videos:notfound') . '</div>';
  }
  echo '<div class="clearfloat"></div></div>';
}