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
  <?php echo elgg_echo('izap_videos:numbertodisplay'); ?>:
  <select name="params[num_display]">
    <option value="1" <?php if($vars['entity']->num_display == 1) echo "SELECTED"; ?>>1</option>
    <option value="2" <?php if($vars['entity']->num_display == 2) echo "SELECTED"; ?>>2</option>
    <option value="3" <?php if($vars['entity']->num_display == 3) echo "SELECTED"; ?>>3</option>
    <option value="4" <?php if($vars['entity']->num_display == 4) echo "SELECTED"; ?>>4</option>
    <option value="5" <?php if($vars['entity']->num_display == 5) echo "SELECTED"; ?>>5</option>
    <option value="6" <?php if($vars['entity']->num_display == 6) echo "SELECTED"; ?>>6</option>
    <option value="7" <?php if($vars['entity']->num_display == 7) echo "SELECTED"; ?>>7</option>
    <option value="8" <?php if($vars['entity']->num_display == 8) echo "SELECTED"; ?>>8</option>
    <option value="9" <?php if($vars['entity']->num_display == 9) echo "SELECTED"; ?>>9</option>
    <option value="10" <?php if($vars['entity']->num_display == 10) echo "SELECTED"; ?>>10</option>
  </select>
</p>

<?php
// get all the videos for the owner
$videos = izapGetAllVideos_izap_videos($vars['entity']->owner_guid);
?>
<p>
  <?php echo elgg_echo('izap_videos:chosevideo'); ?>:
  <select name="params[selected_video]">
    <?php
    if($videos) {
      foreach($videos as $video) {
        echo '<option value = "' . $video->guid . '"';
        if($vars['entity']->selected_video == $video->guid) echo 'SELECTED';
        echo '>' . substr($video->title,0,30) . ' ...</option>';
      }
    }
    ?>
  </select>
</p>