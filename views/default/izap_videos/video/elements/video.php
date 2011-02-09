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
global $IZAPSETTINGS;
$height = ($vars['height']) ? $vars['height'] : 400;
$width = ($vars['width']) ? $vars['width'] : 670;

?>
<div align="center" <?php echo $playerClass;?> class="contentWrapper" style="height: <?php echo $height?>px;">
  <div id="load_video_<?php echo $vars['video']->guid ?>">
    <img src="<?php echo $vars['video']->getOrignalThumb()?>" alt="<?php echo elgg_echo('izap_videos:click_to_play')?>" height="<?php echo $height?>" width="<?php echo $width?>"/>
    <div style="position: relative; top: -<?php echo $height?>px;z-index: 1000;">
      <a href="<?php echo $vars['video']->getURL() ?>" rel="<?php echo $vars['video']->guid ?>:<?php echo $width?>x<?php echo $height?>" class="izap_ajaxed_thumb">
        <img src="<?php echo $IZAPSETTINGS->graphics;?>trans_play.png" alt="<?php echo elgg_echo('izap_videos:click_to_play')?>" height="<?php echo $height?>" width="<?php echo $width?>"/>
      </a>
    </div>
  </div>
</div>
<div class="clearfloat"></div>
