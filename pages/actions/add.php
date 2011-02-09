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
$page_owner = page_owner_entity();

$area2 = elgg_view_title(elgg_echo('izap_videos:add'));
$area2 .= elgg_view('izap_videos/forms/_partial');
$area2 .= elgg_view('izap_videos/izapLink');

// get tags and categories
$area3 = elgg_view('izap_videos/area3');
$body = elgg_view_layout("two_column_left_sidebar", '', $area2, $area3);

page_draw(sprintf(elgg_echo('izap_videos:user'),$page_owner->name),$body);