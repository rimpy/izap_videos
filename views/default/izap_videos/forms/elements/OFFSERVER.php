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
  <label for="video_url">
    <?php echo elgg_echo('izap_videos:addEditForm:videoUrl');?>
  </label>
  <?php
  echo elgg_view('input/text', array(
          'internalname' => 'izap[videoUrl]',
          'value' => $vars['loaded_data']->videoUrl,
          'internalid' => 'video_url',
          )) . '<br />';
  echo '<a href="#" id="view_supported_sites">' . elgg_echo('izap_videos:supported_videos') . '</a>';
  ?>
  <br />
  <span id="supported_sites_list" style="display: none;">
    <?php echo izap_get_supported_videos_list(); ?>
    <br />
  </span>
<br /><a href="#" id="view_extra_from"><b><?php echo elgg_echo('izap_videos:view_full_form')?></b></a>
  <script type="text/javascript">
    $('#view_supported_sites').click(function() {
      $('#supported_sites_list').toggle();
      return false;
    });
  </script>
</p>
<?php
echo elgg_view('input/hidden', array(
'internalname' => 'izap[videoType]',
'value' => 'OFFSERVER',
));
?>