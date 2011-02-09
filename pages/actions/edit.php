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

gatekeeper();

// get video
$id = (int)get_input('guid');
$video = izapVideoCheck_izap_videos($id, TRUE);

// make the video owner page owner
//set_page_owner($izap_videos_video->container_guid);

$title = $izap_videos_video->title;

$area2 = elgg_view_title(elgg_echo('izap_videos:editVideo') . ': ' . $video->title);
$area2 .= elgg_view('izap_videos/forms/_partial',array('entity' => $video));
$area2 .= elgg_view('izap_videos/izapLink');

// get tags and categories
$area3 = elgg_view('izap_videos/area3');
$body = elgg_view_layout("two_column_left_sidebar", '', $area2, $area3);

page_draw($title,$body);