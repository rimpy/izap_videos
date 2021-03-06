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

if(isloggedin() && !$page_owner = page_owner()) {
  set_page_owner(get_loggedin_userid());
}

//resolve deprecated function
if(is_callable('elgg_list_entities'))
  $list_entities = elgg_list_entities(array(
          'types' => 'object',
          'subtypes' => 'izap_videos',
          'container_guids' => $page_owner->guid,
          'offset' => get_input('offset', 0),
          'full_view' => false
  ));
else
  $list_entities = list_entities('object','izap_videos',$page_owner->guid,10,false);

// get page contents
$area2 = elgg_view_title(elgg_echo('izap_videos:all'));

if(empty($list_entities)) {
  $area2 .= elgg_view('izap_videos/notfound');
}else {
  $area2 .= $list_entities;
}
$area2 .= elgg_view('izap_videos/izapLink');

// get tags and categories
$area3 = elgg_view('izap_videos/area3');

// finally draw page
page_draw(sprintf(elgg_echo('izap_videos:all'),$page_owner->name), elgg_view_layout("two_column_left_sidebar", '', $area2, $area3));