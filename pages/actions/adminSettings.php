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

admin_gatekeeper();
set_context('admin');
$page_owner = page_owner_entity();

$title = elgg_echo('izap_videos:adminSettings:settings');

$area2 = elgg_view_title($title);
$area2 .= elgg_view('izap_videos/admin/settings/form');
$area2 .= elgg_view('izap_videos/izapLink');
$body = elgg_view_layout("two_column_left_sidebar", '', $area2);

page_draw($title, $body);
