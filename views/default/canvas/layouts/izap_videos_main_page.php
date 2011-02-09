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
<!-- main content -->
<div>
  <div id="two_column_left_sidebar_maincontent">

    <?php
    if (isset($vars['area2'])) echo $vars['area2']; ?>

  </div><!-- /two_column_right_sidebar_maincontent -->

  <!-- right sidebar -->
  <div id="two_column_right_sidebar">

    <?php
    echo elgg_view('page_elements/owner_block',array('content' => $vars['area1']));
//if (isset($vars['area1'])) echo $vars['area1'];
    if (isset($vars['area3'])) echo $vars['area3']; ?>
  </div>
</div><!-- /two_column_right_sidebar -->