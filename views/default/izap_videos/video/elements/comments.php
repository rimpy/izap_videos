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

if($vars['video']->countAnnotations()) {
  echo elgg_view_title(elgg_echo('izap_videos:comments'));
}
echo elgg_view_comments($vars['video']);