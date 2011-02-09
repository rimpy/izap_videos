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

$sites = izapGetSupportingVideoSites_izap_videos();
?>
<h3><?php echo elgg_echo('izap_videos:form:izapSupportedSites') . ' ('.count($sites).')' . ' and yet to be discovered....';?></h3>
<ul>
  <?php foreach($sites as $site) {?>
  <li>
    <b><?php echo $site;?></b>
  </li>
    <?php } ?>
</ul>
