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
<script type="text/javascript">
  var videoplay_page_url = '<?php echo $vars['url'] . 'pg/videos/playvideo/'?>';
  var video_loading_image = '<?php echo $vars['url'] . 'mod/izap_videos/_graphics/ajax-loader.gif'?>';
  var play_image = '<?php echo $vars['url'] . 'mod/izap_videos/_graphics/play_button.png'?>';

  $(document).ready(function() {
<?php if(is_old_elgg()) {?>
      $(".izap_ajaxed_thumb").click(function() {
  <?php }else {?>
            $(".izap_ajaxed_thumb").live('click', function() {
  <?php }?>
        $("#load_video_" + this.rel).attr('style', '');
        $("#load_video_" + this.rel).html('<img src="'+video_loading_image+'" />');
        $("#load_video_" + this.rel).load(videoplay_page_url + this.rel);
        return false;
      });
    });
</script>
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/izap_videos/views/default/izap_videos/js/default.js"></script>