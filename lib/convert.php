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

class izapConvert {
  private $invideo;
  private $outvideo;
  private $outimage;
  private $imagepreview;
  private $values = array();
  private $is_flv = FALSE;

  public $format = 'flv';

  public function izapConvert($in = '') {
    $this->invideo = $in;
    $extension_length = strlen(izap_get_file_extension($this->invideo));
    $outputPath = substr($this->invideo, 0, '-' . ($extension_length + 1));
    $this->outvideo =  $outputPath . '_c.' . $this->format;
    $this->outimage = $outputPath . '_i.png';
    $this->imagepreview = $outputPath.'_p.png';
  }

  public function izap_video_convert() {

    // check if the file is already flv
    $current_file_type = izap_get_file_extension($this->invideo);
    if($current_file_type == 'flv') {
      $this->make_array_for_flv();
    } else {
      $videoCommand = izapGetFfmpegVideoConvertCommand_izap_videos();
      $videoCommand = str_replace('[inputVideoPath]', $this->invideo, $videoCommand);
      $videoCommand = str_replace('[outputVideoPath]', $this->outvideo, $videoCommand);
      //$videoCommand .= ' 2>&1';

      //echo $videoCommand;exit;
      exec($videoCommand, $arr, $ret);

      if(!$ret == 0) {
        $return = array();
        $return['error'] = 1;
        $return['message'] = end($arr);
        $return['completeMessage'] = implode(' ', $arr);

        return $return;
      }
    }
    return end(explode('/', $this->outvideo));
  }

  public function photo() {
    $videoThumb = izapGetFfmpegVideoImageCommand_izap_videos();
    $videoThumb = str_replace('[inputVideoPath]', $this->invideo, $videoThumb);
    $videoThumb = str_replace('[outputImage]', $this->outimage, $videoThumb);
    // run command to take snapshot
    exec($videoThumb, $out2, $ret2);

    if(!$ret2 == 0)
      return FALSE;
    return $this->outimage;
  }

  public function getValues($image_only = false) {

    if($this->is_flv) { // if it is flv then return the created array
      return $this->values;
    }

    if(!$image_only) { // if we want the full video values
      $this->values['origname'] = time() . '_' . end(explode('/', $this->invideo));
      $this->values['origcontent'] = file_get_contents($this->invideo);
      $this->values['filename'] = time() . '_' . end(explode('/', $this->outvideo));
      $this->values['filecontent'] = file_get_contents($this->outvideo);
      if($this->values['filecontent'] != '') {
          @unlink($this->invideo);
          @unlink($this->outvideo);
      }
    }else{
      // if only image is needed
      $this->values['imagename'] = time() . '_' . end(explode('/', $this->outimage));
      $this->values['preview'] = time() . '_' . end(explode('/', $this->imagepreview));
      $this->values['imagecontent'] = file_get_contents($this->outimage);
      @unlink($this->outimage);
    }
    return $this->values;
  }

  public function getValuesForAPI() {
    $this->values['orignal_video'] = $this->invideo;
    $this->values['converted_video'] = $this->outvideo;
    $this->values['video_thumb'] = $this->outimage;

    return $this->values;
  }

  public function make_array_for_flv() {
    $this->is_flv = TRUE;
    $this->values['origname'] = time() . '_' . end(explode('/', $this->invideo));
    $this->values['origcontent'] = file_get_contents($this->invideo);
    $this->values['filename'] = time() . '_' . end(explode('/', $this->outvideo));
    $this->values['filecontent'] = file_get_contents($this->invideo);

    if($this->values['filecontent'] != '') {
        @unlink($this->invideo);
        @unlink($this->outvideo);
    }
  }
}


/**
 * this function gives the FFmpeg video converting command
 *
 * @return string path
 */
function izapGetFfmpegVideoConvertCommand_izap_videos() {
  $path = get_plugin_setting('izapVideoCommand', GLOBAL_IZAP_VIDEOS_PLUGIN);
  $path = html_entity_decode($path);
  if(!$path)
    $path = '';
  return $path;
}

/**
 * this function gives the FFmpeg video image command
 *
 * @return string path
 */
function izapGetFfmpegVideoImageCommand_izap_videos() {
  $path = get_plugin_setting('izapVideoThumb', GLOBAL_IZAP_VIDEOS_PLUGIN);
  $path = html_entity_decode($path);
  if(!$path)
    $path = '';
  return $path;
}

/**
 * this fucntion actually converts the video
 * @param string $file file loacation
 * @param int $videoId video guid
 * @param int $ownerGuid video owner guid
 * @param int $accessId access id
 * @return boolean
 */
function izapConvertVideo_izap_videos($file, $videoId, $videoTitle, $videoUrl, $ownerGuid, $accessId = 2) {
  global $CONFIG;
  $return = FALSE;

  // works only if we have the input file
  if(file_exists($file)) {
    // now convert video
    //
    // Need to set flag for the file going in the conversion.
    $queue_object = new izapQueue();
    $queue_object->change_conversion_flag($videoId);

    $video = new izapConvert($file);
    $videofile = $video->izap_video_convert();

    // check if every this is ok
    if(!is_array($videofile)) {
      // if every thing is ok then get back values to save
      $file_values = $video->getValues();
      $izap_videofile = 'izap_videos/uploaded/' . $file_values['filename'];
      $izap_origfile = 'izap_videos/uploaded/' . $file_values['origname'];
      $izap_videos = new IzapVideos($videoId);
      $izap_videos->setFilename($izap_videofile);
      $izap_videos->open("write");
      $izap_videos->write($file_values['filecontent']);

      //check if you do not want to keep original file
      if(get_plugin_setting('izapKeepOriginal', GLOBAL_IZAP_VIDEOS_PLUGIN) == 'YES'){
        $izap_videos->setFilename($izap_origfile);
        $izap_videos->open("write");
        $izap_videos->write($file_values['origcontent']);
      }

      $izap_videos->converted = 'yes';
      $izap_videos->videofile = $izap_videofile;
      $izap_videos->orignalfile = $izap_origfile;
      notify_user($ownerGuid,
            $CONFIG->site_guid,
            elgg_echo('izap_videos:notifySub:videoConverted'),
            sprintf(elgg_echo('izap_videos:notifyMsg:videoConverted'), $izap_videos->getUrl())
      );
      return true;
    }else {
      $errorReason = $videofile['message'];
    }
  }else {
    $errorReason = elgg_echo('izap_videos:fileNotFound');
  }
  $adminGuid = izapGetSiteAdmin_izap_videos(TRUE);

    // notify admin
    notify_user($adminGuid,
            $CONFIG->site_guid,
            elgg_echo('izap_videos:notifySub:videoNotConverted'),
            sprintf(elgg_echo('izap_videos:notifyAdminMsg:videoNotConverted'), $errorReason)
    );
  if(!empty($errorReason)) {
    $return = array('error' => TRUE, 'reason' => $errorReason);
  }
  return $return;
}


/**
 * this returns the array of supported videos for uploading
 *
 * @global <type> $CONFIG
 * @return array array of supported videos
 */
function izapGetSupportingVideoFormats_izap_videos() {
  global $IZAPSETTINGS;

  foreach ($IZAPSETTINGS->allowedExtensions as $formats) {
    $supportedFormats[] = $formats;
  }

  asort($supportedFormats);
  return $supportedFormats;
}
