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


// check the user name
$username = get_input('guid');
$user = get_user_by_username($username);
if($user && $user instanceof ElggUser) {
  set_input('username', $user->name);
}

// get pageowner
$page_owner = page_owner_entity();
$area2 = elgg_view_title(sprintf(elgg_echo('izap_videos:userfrnd'),$page_owner->name));
$list = list_user_friends_objects($page_owner->guid,'izap_videos',10,false);


if(empty($list)){
  $area2 .= elgg_view('izap_videos/notfound');
}else{
  $area2 .= $list;
}

$area2 .= elgg_view('izap_videos/izapLink');

// get tags and categories
$area3 = elgg_view('izap_videos/area3');

$body = elgg_view_layout("two_column_left_sidebar", '', $area2, $area3);		
page_draw(sprintf(elgg_echo('izap_videos:frnd'),$page_owner->name),$body);