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
?>

<p>
  <label for="video_file">
    <?php echo elgg_echo('izap_videos:addEditForm:videoEmbed');?>
  </label>
  <?php
  echo elgg_view('input/text', array(
    'internalname' => 'izap[videoEmbed]',
    'value' => $vars['loaded_data']->videoEmbed,
    'internalid' => 'video_file',
  ));
  ?>
</p>
<?php
echo elgg_view('input/hidden', array(
  'internalname' => 'izap[videoType]',
  'value' => 'EMBED',
));
?>