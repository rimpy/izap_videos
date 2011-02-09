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
  $icon = '<a href="' . $vars['video']->getURL() . '" title="'.$vars['video']->title.'"><img src="'.$vars['video']->getThumb(TRUE).'"></a>';
  $info .= '<a href="' . $vars['video']->getURL() . '" title="'.$vars['video']->title.'">' . substr($vars['video']->title, 0, 15) . '..</a>';
  $info .= "<br />";
  $info .= substr($vars['video']->description, 0, 30);
}
?>
<div class="search_listing izapMiniList"  style="background-color:#FFFFFF; margin-top:5px" >
  <div class="search_listing_icon">
    <?php echo $icon; ?>
  </div>
  <div class="search_listing_info">
    <?php echo $info; ?>
  </div>

  <div class="clearfloat"></div>
</div>