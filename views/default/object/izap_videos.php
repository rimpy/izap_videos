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


if($vars['entity'] instanceof IzapVideos) { // works only if it has got video
  if(get_context() == 'search') {
    echo elgg_view('izap_videos/searchListElement', array('video' => $vars['entity']));
  }elseif(get_context() == 'izapminilist') {
    echo elgg_view('izap_videos/miniListElement', array('video' => $vars['entity']));
  }else {
    if($vars['full']) {
      echo elgg_view('izap_videos/video/index', array('video' => $vars['entity']));
    }else {
      echo elgg_view('izap_videos/listElement', array('video' => $vars['entity']));
    }
  }
}