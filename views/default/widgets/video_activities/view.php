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

$limit = $vars['entity']->num_display;
$limit = ($limit) ? $limit : 5;
$options['type'] = 'object';
$options['subtype'] = 'izap_videos';
$options['limit'] = $limit;
if((real)get_version(true) <= 1.6) {
  $videos = get_entities($options['type'], $options['subtype'],0,'', $options['subtype']);
}else {
  $videos = elgg_get_entities($options);
}

if($videos) {
  foreach($videos as $video) {
    echo elgg_view('izap_videos/widgetListing', array('entity' => $video));
  }
}
if(is_old_elgg()) {
  ?>
<script type="text/javascript">
  $(document).ready(function() {
    $(".izap_ajaxed_thumb").click(function() {
      $("#load_video_" + this.rel).attr('style', '');
      $("#load_video_" + this.rel).html('<img src="'+video_loading_image+'" />');
      $("#load_video_" + this.rel).load(videoplay_page_url + this.rel);
      return false;
    });
  });
</script>
  <?php }?>