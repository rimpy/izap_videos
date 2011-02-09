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


if($vars['video']) {
  $owner = $vars['video']->getOwnerEntity();
  $friendlytime = friendly_time($vars['video']->time_created);
  $icon = '<a href="' . $vars['video']->getURL() . '"  class="screenshot" rel="' . $vars['video']->getThumb(TRUE) . '"><img src="'.$vars['video']->getThumb(TRUE).'"></a>';

  $info = elgg_echo('videos') . " : ";
  $info .= '<a href="' . $vars['video']->getURL() . '"  class="screenshot" rel="' . $vars['video']->getThumb(TRUE) . '">' . $vars['video']->title . '</a>';
  $info .= "<br />";
  $info .= "<a href=\"{$owner->getURL()}\">{$owner->name}</a> {$friendlytime}";
  echo elgg_view_listing($icon,$info);
}