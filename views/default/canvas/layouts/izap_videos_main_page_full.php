<?php
/**
 * Elgg 2 column left sidebar canvas layout
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
?>
<div class="full_video_wrapper">
  <?php echo $vars['area4']?>
</div>
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