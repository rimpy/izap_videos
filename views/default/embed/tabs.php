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

$embedselected = '';
$uploadselected = '';
if ($vars['tab'] == 'media') {
  $embedselected = 'class="embed_tab_selected"';
} elseif ($vars['tab'] == 'izap_videos') {
  $videosSelected = 'class="embed_tab_selected"';
} else {
  $uploadselected = 'class="embed_tab_selected"';
}
?>

<div id="embed_media_tabs">
  <ul>
    <li>
      <a href="#" <?php echo $embedselected; ?> onclick="javascript:$('.popup .content').load('<?php echo $vars['url'] . 'pg/embed/media'; ?>?internalname=<?php echo $vars['internalname']; ?>'); return false"><?php echo elgg_echo('embed:media'); ?></a>
    </li>
    <li>
      <a href="#" <?php echo $uploadselected; ?> onclick="javascript:$('.popup .content').load('<?php echo $vars['url'] . 'pg/embed/upload'; ?>?internalname=<?php echo $vars['internalname']; ?>'); return false"><?php echo elgg_echo('upload:media'); ?></a>
    </li>
    <li>
      <a href="#" <?php echo $videosSelected; ?> onclick="javascript:$('.popup .content').load('<?php echo $vars['url'] . 'pg/videos/embed/'.$_SESSION['username'].'/'; ?>?internalname=<?php echo $vars['internalname']; ?>'); return false"><?php echo elgg_echo('izap_videos:videoGal'); ?></a>
    </li>
  </ul>
</div>
<div class="clearfloat"></div>