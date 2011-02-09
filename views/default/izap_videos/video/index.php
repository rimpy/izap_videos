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

// copy video link only shows up if the user is loggedin,
// video doesn't belongs to loggedin user and
// video is converted(in case of uploaded videos)
$vars['video']->updateViews();
if(($vars['video']->canCopy()))
  if(is_callable('elgg_add_action_tokens_to_url'))
    $Add = '<div class="contentWrapper"><h3 align="center"><a href="' . elgg_add_action_tokens_to_url($CONFIG->wwwroot . 'action/izapCopy?videoId=' . $vars['video']->getGUID()) . '">' . elgg_echo('izap_videos:addtoyour') . '</a></h3></div>';
  else
    $Add = '<div class="contentWrapper"><h3 align="center"><a href="' . $CONFIG->wwwroot . 'action/izapCopy?videoId=' . $vars['video']->getGUID() . '">' . elgg_echo('izap_videos:addtoyour') . '</a></h3></div>';
?>
<div>
  <?php
  echo elgg_view_title($vars['video']->title);
  echo $Add;
  echo elgg_view('izap_videos/video/elements/video', array('video' => $vars['video']));
  echo elgg_view('izap_videos/video/elements/description', array('video' => $vars['video']));
  // view for other plugins to extend
  echo elgg_view('izap_videos/extendedPlay');
  if($vars['video']->converted == 'yes') {
    echo elgg_view('izap_videos/video/elements/comments', array('video' => $vars['video']));
  }
  ?>
</div>