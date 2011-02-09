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

$video = $vars['entity'];
$icon = $video->getAjaxedThumb(array('width' => 10, 'height' => 10));

$title = substr($video->title, 0, 10) . ((strlen($video->title) > 10) ? '...' : '' );

$description = strip_tags($video->description);
$description = substr($description, 0, 50) . ((strlen($description) > 50) ? '...' : '' );
$info = $description;
?>
<div class="contentWrapper">
  <div class="video_ajaxed_icon">
    <?php echo $icon;?>
  </div>

  <div class="video_info">
    <a href="<?php echo $video->getURL();?>"><b><?php echo $title?></b></a><br />
    <?php
      echo $description;
    ?>
  </div>

  <div class="clearfloat"></div>
</div>