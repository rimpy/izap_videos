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

global $CONFIG;

$queueStatus = (izapIsQueueRunning_izap_videos()) ? elgg_echo('izap_videos:running') : elgg_echo('izap_videos:notRunning');
$queue_object = new izapQueue();
$queuedVideos = $queue_object->get();
?>
<div class="usersettings_statistics">
  <h3><?php echo elgg_echo('izap_videos:queueStatus') . ': ' . $queueStatus . ' ('.izap_count_queue().')';?></h3>
  <table>
    <tbody>

      <?php
      if(count($queuedVideos)):
        $i = 0;
        foreach($queuedVideos as $queuedVideo):
          $extension_length = strlen(izap_get_file_extension($queuedVideo['main_file']));
          $outputPath = substr($queuedVideo['main_file'], 0, '-' . ($extension_length + 1));

          $ORIGNAL_name = basename($queuedVideo['main_file']);
          $ORIGNAL_size = izapFormatBytes(filesize($queuedVideo['main_file']));

          $FLV_name = basename($outputPath . '_c.flv');
          $FLV_size = izapFormatBytes(filesize($outputPath . '_c.flv'));
          ?>
      <tr class="odd <?php echo (!$i && izapIsQueueRunning_izap_videos()) ? 'queue_selected' : ''?>">
        <td class="column_one">
              <?php echo $ORIGNAL_name . '<br />' . $FLV_name;?>
        </td>
        <td>
              <?php echo $ORIGNAL_size . '<br />' . $FLV_size;?>
        </td>
        <td>
              <?php
              if($queuedVideo['conversion'] != IN_PROCESS) {
                echo elgg_view('output/confirmlink',array('href'=>$CONFIG->wwwroot.'action/izapResetQueue?guid='.$queuedVideo['guid'],'text' => 'X','confirm'=>'Are you sure? It will delete this videos from queue and correspoindingly from db.'));
              }
              ?>
        </td>
      </tr>
          <?php
          $i++;
        endforeach;
      endif;
      ?>
    </tbody>
  </table>
</div>
