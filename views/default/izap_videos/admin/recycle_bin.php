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

$queueStatus = (izapIsQueueRunning_izap_videos()) ? elgg_echo('izap_videos:running') : elgg_echo('izap_videos:notRunning');
$buggy_videos_object = new izapQueue();
$buggy_videos = $buggy_videos_object->get_from_trash();
$main_url = $CONFIG->wwwroot . 'pg/videos/adminSettings/'.get_loggedin_user()->username.'?option=recycle_bin';
?>
<form method="post" action="<?php echo $vars['url']?>action/izap-delete-recycle">
  <?php
  echo elgg_view('input/securitytoken');
  ?>
  <div class="usersettings_statistics">
    <h3 align="center"><?php echo elgg_echo('izap_videos:error_videos');?></h3>
    <table>
      <tbody>

        <?php
        if($buggy_videos):
          foreach($buggy_videos as $video_to_be_recycled):
            $ORIGNAL_name = $video_to_be_recycled['main_file'];
            $ORIGNAL_size = izapFormatBytes(filesize($video_to_be_recycled['main_file']));

            ?>
        <tr class="odd">
          <td class="column_one">
                <?php
                echo $ORIGNAL_name;
                ?>
            <br />
            Size: <?php echo $ORIGNAL_size; ?>
            <br />
            <?php if(is_callable('elgg_add_action_tokens_to_url')) {?>
            <a href="<?php echo elgg_add_action_tokens_to_url($vars['url'].'action/izapRecycle?guid='.$video_to_be_recycled['guid']); ?>"><?php echo elgg_echo('izap_videos:restore')?></a>
            <?php }else {?>
            <a href="<?php echo $vars['url'].'action/izapRecycle?guid='.$video_to_be_recycled['guid']; ?>"><?php echo elgg_echo('izap_videos:restore')?></a>
            <?php }?>
            <br />
            <?php if(is_plugin_enabled('messages')): ?>
            <input type="text" name="izap[user_message_<?php echo $video_to_be_recycled['guid']?>]" value="" /><br />
            <label><input type="checkbox" name="izap[send_message_<?php echo $video_to_be_recycled['guid']?>]" value="yes" /><?php echo elgg_echo('izap_videos:send_user_message');?></label>
            <?php else: ?>
              <?php echo elgg_echo('izap_videos:adminSettings:messages_plugin_missing') ?>
            <?php endif; ?>
            <br />
            <input type="submit" name="izap[<?php echo $video_to_be_recycled['guid']?>]" value="Delete" />
          </td>
        </tr>
          <?php
          endforeach;
        endif;
        ?>
      </tbody>
    </table>
  </div>
</form>

