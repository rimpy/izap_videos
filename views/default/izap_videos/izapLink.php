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

if(izapAdminSettings_izap_videos('izapGiveUsCredit') == 'YES') {
  ?>
<div class="izap_credit" align="right" style="font-size:10px;">
  <a href="http://www.izap.in/" target="_blank">
    <img src="<?php echo $vars['url']?>mod/izap_videos/_graphics/powered-by-izap.png" alt="Powered by iZAP" />
  </a>
</div>
  <?php }?>