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

$options = $vars['options'];
$selectedTab = $vars['selected'];
?>
<div id="elgg_horizontal_tabbed_nav">
  <ul>
    <?php
    foreach ($options as $addOption) {
      ?>
    <li class="<?php echo ($addOption == $selectedTab) ? 'selected' : '';?>">
      <a href="?option=<?php echo $addOption?>">
          <?php echo elgg_echo('izap_videos:addEditForm:' . $addOption);?>
      </a>
    </li>
      <?php
    }
    ?>
  </ul>
</div>