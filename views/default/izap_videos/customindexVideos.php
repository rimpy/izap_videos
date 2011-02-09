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

$video = $vars['entities'];
$total = 16;
if(isset($vars['videosTOdisplay'])) {
  $total = $vars['videosTOdisplay'];
}
?>
<div class="index_box">
    <?php echo elgg_view_title(elgg_echo('videos')); ?>
  <div class="contentWrapper">
    <div id="elgg_horizontal_tabbed_nav">
      <ul>
        <li id="ltab">
          <a href="#" rel="<?php echo $vars['url']; ?>pg/videos/customindexVideos/?type=latest&videosTOdisplay=<?php echo $total;?>" class="izapLoadVideo" title="ltab">
            <?php echo elgg_echo('izap_videos:latestvideos'); ?>
          </a>
        </li>
        <li id="vtab">
          <a href="#" rel="<?php echo $vars['url']; ?>pg/videos/customindexVideos/?type=views&videosTOdisplay=<?php echo $total;?>" class="izapLoadVideo" title="vtab">
            <?php echo elgg_echo('izap_videos:topViewed'); ?>
          </a>
        </li>
        <li id="ctab">
          <a href="#" rel="<?php echo $vars['url']; ?>pg/videos/customindexVideos/?type=com&videosTOdisplay=<?php echo $total;?>" class="izapLoadVideo" title="ctab">
            <?php echo elgg_echo('izap_videos:topCommented'); ?>
          </a>
        </li>
      </ul>
    </div>
    <div id="videoContainer"></div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $('#ltab').addClass('selected');
    showVideos('<?php echo $vars['url']; ?>pg/videos/customindexVideos/?type=latest&videosTOdisplay=<?php echo $total;?>');
    $('.izapLoadVideo').click(function(){
      $('#ltab').removeClass('selected');
      $('#vtab').removeClass('selected');
      $('#ctab').removeClass('selected');
      $('#' + this.title + '').addClass('selected');
      showVideos(this.rel);
      return false;
    });
  });
  function showVideos(url){
    $('#videoContainer').html('<p align="center"><img src="<?php echo $vars['url']?>mod/izap_videos/_graphics/video_converting.gif"></p>');
    $('#videoContainer').load(url);
  }
</script>