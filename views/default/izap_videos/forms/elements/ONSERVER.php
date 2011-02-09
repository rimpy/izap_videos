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
  <label for="video_file">
    <?php echo elgg_echo('izap_videos:addEditForm:videoFile');?>
  </label><br />
  <?php
  if(is_plugin_enabled('izap-uploadify')) {
    echo elgg_view('input/izap-uploadify', array(
    'internalname' => 'izap[videoFile]',
    'value' => $vars['loaded_data']->videoFile,
    'internalid' => 'video_file',
    'form_id' => 'video_form',
    'redirect_url' => $vars['url'] . 'pg/videos/list/' . get_loggedin_user()->username,
    ));
  }else {
    echo elgg_view('input/file', array(
    'internalname' => 'izap[videoFile]',
    'value' => $vars['loaded_data']->videoFile,
    'internalid' => 'video_file',
    ));
  }
  ?>
</p>
<?php
echo elgg_view('input/hidden', array(
'internalname' => 'izap[videoType]',
'value' => 'ONSERVER',
));
?>