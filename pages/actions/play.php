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

// get the video id as input 
$video = (int) get_input('guid');
$izap_videos = izapVideoCheck_izap_videos($video);

// make the video owner page owner
set_page_owner($izap_videos->container_guid);

$title = $izap_videos->title;

// get page contents
$video = elgg_view_entity($izap_videos, TRUE);
$area2 .= elgg_view('izap_videos/izapLink');
// get tags and categories
if($izap_videos->converted == 'yes') {
  $share .= elgg_view('izap_videos/video/elements/share', array('video' => $izap_videos));
}
$area3 = elgg_view('izap_videos/video/elements/related', array('video' => $izap_videos));
$layout = izapAdminSettings_izap_videos('izap_display_page');
switch ($layout) {
  case 'default':
    $body = elgg_view_layout("izap_videos_main_page", $share, $video . $area2, $area3);
    break;

  case 'left':
    $body = elgg_view_layout("izap_videos_main_page_leftbar", $share, $video . $area2, $area3);
    break;

  case 'full':
    $video = elgg_view_title($izap_videos->title);
    $video .= $Add;
    $video .= elgg_view('izap_videos/video/elements/video', array('video' => $izap_videos, 'height' => 500, 'width' => 800));

    $content .= '<br />' . elgg_view('izap_videos/video/elements/description', array('video' => $izap_videos));
    if($izap_videos->converted == 'yes') {
      $content .= elgg_view('izap_videos/video/elements/comments', array('video' => $izap_videos));
    }

    $body = elgg_view_layout("izap_videos_main_page_full", $share, $content . $area2, $area3, $video);
    break;

  default:
    $body = elgg_view_layout("izap_videos_main_page", $share, $area2, $area3);
    break;
}
page_draw($title, $body);