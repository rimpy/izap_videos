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

$page_owner = page_owner_entity();
if(is_callable('elgg_get_entities_from_metadata')) {
  $options = array(
          'type' => 'object',
          'subtype' => 'izap_videos',
          'metadata_names' => 'favorited_by',
          'metadata_values' => $page_owner->guid,
          'count' => TRUE,
  );
  $count = elgg_get_entities_from_metadata($options);
  if($count) {
    unset($options['count']);
    $options['offset'] = get_input('offset', 0);
    $entities = elgg_get_entities_from_metadata($options);
    $entity_list = elgg_view_entity_list($entities, $count, get_input('offset', 0), 10, FALSE, FALSE, TRUE);
  }
}else {
  $entity_list = list_entities_from_metadata('favorited_by', $page_owner->guid, 'object', 'izap_videos', 0, 10, FALSE, FALSE);
}

if($page_owner->guid == get_loggedin_userid()) {
$title = elgg_echo('izap_videos:my_favorites');
}else{
  $title = sprintf(elgg_echo('izap_videos:user_favorites'), $page_owner->name);
}

$body = elgg_view_title($title);
if(!empty($entity_list)) {
$body .= $entity_list;
}else{
  $body .= '<div class="contentWrapper">'.elgg_echo('izap_videos:no_favorites').'</div>';
}
$body .= elgg_view('izap_videos/izapLink');
$area3 = elgg_view('izap_videos/area3');
$body = elgg_view_layout('two_column_left_sidebar', '', $body, $area3);
page_draw($title, $body);