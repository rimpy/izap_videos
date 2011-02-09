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

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
// get page owner entity
$page_owner = page_owner_entity();
// if no page owner then set the loggedin user as page owner
if(!$page_owner)
  set_page_owner($_SESSION['user']->guid);

if($page_owner == $_SESSION['user'])
  $area2 = elgg_view_title(elgg_echo('izap_videos:videos'));
elseif($page_owner)
  $area2 = elgg_view_title(sprintf(elgg_echo('izap_videos:user'),$page_owner->name));
else
  $area2 = elgg_view_title(elgg_echo('izap_videos:all'));

// get user videos
//$list .= list_user_objects($page_owner->guid,'izap_videos',10,false);
$list .= elgg_list_entities(array(
  'type' => 'object',
  'subtype' => 'izap_videos',
  'limit' => 10,
  'offset' => get_input('offset', 0),
));

if(empty($list) || $list == '')
  $area2 .= '<div class="contentWrapper">' . elgg_echo('izap_videos:notfound') . '</div>';
else
  $area2 .= $list;

// get tags and categories
//$area3 = elgg_view('izap_videos/area3');

$body = elgg_view_layout("two_column_left_sidebar", '', $area2, $area3);
page_draw(sprintf(elgg_echo('izap_videos:user'),$page_owner->name),$body);