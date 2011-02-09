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

$options = izapGetVideoOptions_izap_videos();
if(in_array('ONSERVER', $options)) {
  ?>
<a
  href="<?php echo $vars['url'] . 'pg/videos/add/' . get_loggedin_user()->username . '?option=ONSERVER' ?>"
  title="<?php echo elgg_echo('izap_videos:uploadVideo') ?>"
  style="margin: 0px;"
  >
  <img src="<?php echo $vars['url']?>mod/izap_videos/_graphics/upload_video.png" alt="<?php echo elgg_echo('izap_videos:uploadVideo') ?>"/>
</a>
  <?php
}
?>