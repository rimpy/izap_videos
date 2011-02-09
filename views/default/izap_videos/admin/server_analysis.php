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

$Fail_functions = ini_get('disable_functions');

$php_version = phpversion();

$exec = (!strstr($Fail_functions, 'exec') && is_callable('exec')) ? TRUE : FALSE;
$curl = (extension_loaded('curl')) ? TRUE : FALSE;
$ffmpeg_path = current(explode(' ', izapAdminSettings_izap_videos('izapVideoCommand')));
$pdo_sqlite = (extension_loaded('pdo_sqlite')) ? TRUE : FALSE;
if($exec) {
  $php_command = exec(izapAdminSettings_izap_videos('izapPhpInterpreter') . ' --version', $output_PHP, $return_value);
  if($return_value === 0) {
    $php = nl2br(implode('', $output_PHP));
  }

  $ffmpeg_command = exec($ffmpeg_path . ' -version', $output_FFmpeg, $return_var);
  if($return_var === 0) {
    $ffmpeg = nl2br(implode($output_FFmpeg));

    if(!izapIsWin_izap_videos()) { // set the testing videos commands if it is not windows
      $in_video =  $CONFIG->pluginspath . 'izap_videos/server_test/test_video.avi';
      $izap_videos = new IzapVideos();
      $izap_videos->owner_guid = get_loggedin_userid();
      $izap_videos->setFilename('izap_videos/server_test/test_video.avi');
      $izap_videos->open('write');
      $izap_videos->write(file_get_contents($in_video));

      $in_video = $izap_videos->getFilenameOnFilestore();
      if(!file_exists($in_video)) {
        $in_video =  $CONFIG->pluginspath . 'izap_videos/server_test/test_video.avi';
        exit;
        $izap_videos->open('write');
        $izap_videos->write(file_get_contents($in_video));
        $in_video = $izap_videos->getFilenameOnFilestore();
      }
      $izap_videos->close();

      if(file_exists($in_video)) {
        $in_video;
        $outputPath = substr($in_video, 0, -4);
        $out_video =  $outputPath . '_c.flv';

        $commands = array(
                'Simple command' => $ffmpeg_path . ' -y -i [inputVideoPath] [outputVideoPath]',
        );
      }
    }
  }
}

$max_file_upload = izapReadableSize_izap_videos(ini_get('upload_max_filesize'));
$max_post_size = izapReadableSize_izap_videos(ini_get('post_max_size'));
$max_input_time = ini_get('max_input_time');
$max_execution_time = ini_get('max_execution_time');
$memory_limit = ini_get('memory_limit');
?>
<div class="usersettings_statistics izap_server_report">
  <h3>izap_videos version: <?php echo datalist_get('izap_videos_version');?></h3>
  <table>
    <tbody>
      <tr class="odd <?php echo ($exec) ? 'ok' : 'not_ok';?>">
        <td class="column_one">exec()</td>
        <td><?php echo ($exec) ? 'Success' : 'Fail';?></td>
        <td>Required to execute the commands.</td>
      </tr>

      <tr class="odd <?php echo ($curl) ? 'ok' : 'not_ok';?>">
        <td class="column_one">cURL support</td>
        <td><?php echo ($curl) ? 'Success' : 'Fail';?></td>
        <td>Required for fetching the remote feed.</td>
      </tr>

      <tr class="odd <?php echo ($pdo_sqlite) ? 'ok' : 'not_ok';?>">
        <td class="column_one">PDO_SQLITE Support</td>
        <td><?php echo ($pdo_sqlite) ? 'Success' : 'Fail';?></td>
        <td>Required to manage que by sqlite database.</td>
      </tr>

      <tr class="odd <?php echo ($php) ? 'ok' : 'not_ok'?>">
        <td class="column_one">PHP interpreter test</td>
        <td><?php echo ($php) ? 'Success' : 'Fail';?></td>
        <td>
<?php if(!$php) {?>
          PHP interpreter not found. <br />
          <em><b>Action</b>: Be sure the provided path is correct in admin settings.</em>
            <?php }else {
            echo $php;
}?>
        </td>
      </tr>

      <tr class="odd <?php echo ($ffmpeg) ? 'ok' : 'not_ok';?>">
        <td class="column_one">FFmpeg</td>
        <td class="column_one"><?php echo ($ffmpeg) ? 'Success' : 'Fail';?></td>
        <td><?php echo $ffmpeg;?><br />
          <?php
          if(izapIsWin_izap_videos()) {
            echo '<b>Download "<a href="http://www.izap.in/ie-commerce/pg/file/carl/read/2361/ffmpeg-svnr20192-binaries-for-windows" target="_blank">FFmpeg SVN-r20192</a>", if you have lower version.</b>';
            echo 'Unzip the package and place it in the izap_videos folder.';
          }
?>
        </td>
      </tr>

      <?php
      if(!empty($commands)) {
        foreach($commands  as $key => $command) {
          $exec_command = str_replace(array('[inputVideoPath]', '[outputVideoPath]'), array($in_video, $out_video), $command);
          exec($exec_command, $out_array, $return_array);
          if($return_array > 0) {
      ?>
      <tr class="odd not_ok">
        <td class="column_one"><?php echo $key; ?></td>
        <td>Fail</td>
        <td><!-- <input type="text" value="<?php echo $command?>" onclick="this.select();"/> -->
          Recommended action: Copy the success command and paste it in the "Video converting command" in <a href="<?php echo $vars['url']?>pg/videos/adminSettings/admin?option=settings">admin settings</a>.<br />
      <?php if($key == 'Simple command') {?>
          <em><b>Tested with avi format only.</b></em>
        <?php  }?>
        </td>
      </tr>
            <?php
          }else {
      ?>
      <tr class="odd ok">
        <td class="column_one"><?php echo $key; ?></td>
        <td>Success</td>
        <td>
          <input type="text" value="<?php echo $command?>" onclick="this.select();"/><br />
      <?php if($key == 'Simple command') {?>
          <em><b>Tested with avi format only.</b></em>
        <?php  }?>
        </td>
      </tr>
            <?php
          }
        }
      }
?>

      <tr class="odd ok">
        <td class="column_one">upload_max_filesize</td>
        <td class="column_one"><?php echo $max_file_upload;?></td>
        <td>The maximum size of files that PHP will accept uploads. Keep it bigger for big files.</td>
      </tr>

      <tr class="odd ok">
        <td class="column_one">post_max_size</td>
        <td class="column_one"><?php echo $max_post_size;?></td>
        <td>Needs to be a small amount bigger or same, than upload_max_filesize for a file upload to work. Keep it bigger for big files.</td>
      </tr>

      <tr class="odd ok">
        <td class="column_one">max_input_time</td>
        <td class="column_one"><?php echo $max_input_time;?></td>
        <td>Determines how much time PHP will wait to receive file data. Keep it "0" for bigger file.</td>
      </tr>

      <tr class="odd ok">
        <td class="column_one">max_execution_time</td>
        <td class="column_one"><?php echo $max_execution_time;?></td>
        <td>This sets the maximum time in seconds a script is allowed to run before it is terminated. Keep it "0" for bigger files.</td>
      </tr>
      <tr class="odd ok">
        <td class="column_one">memory_limit</td>
        <td class="column_one"><?php echo $memory_limit;?></td>
        <td>This is php main memory limit and it needs to be bigger enough for your bigger file need to process via ffmpeg.</td>
      </tr>
    </tbody>
  </table>
</div>