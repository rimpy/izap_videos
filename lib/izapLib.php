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

global $CONFIG, $IZAPSETTINGS;
$IZAPSETTINGS = new stdClass();

$IZAPSETTINGS->filesPath = $CONFIG->wwwroot . 'pg/izap_videos_files/';
$IZAPSETTINGS->playerPath = $CONFIG->wwwroot . 'mod/izap_videos/player/izap_player.swf';
$IZAPSETTINGS->api_server = 'http://api.pluginlotto.com/';
$IZAPSETTINGS->apiUrl = $IZAPSETTINGS->api_server . '?api_key=' . get_plugin_setting('izapAPIKey', GLOBAL_IZAP_VIDEOS_PLUGIN) . '&domain=' . base64_encode(strtolower($_SERVER['HTTP_HOST']));
$IZAPSETTINGS->allowedExtensions = array('avi', 'flv', '3gp', 'mp4', 'wmv', 'mpg', 'mpeg');
$IZAPSETTINGS->ffmpegPath = $CONFIG->pluginspath . 'izap_videos/ffmpeg/bin/ffmpeg.exe';
$IZAPSETTINGS->ffmpegPresetPath = $CONFIG->pluginspath . 'izap_videos/ffmpeg/presets/libx264-hq.ffpreset';
$IZAPSETTINGS->graphics = $CONFIG->wwwroot . 'mod/izap_videos/_graphics/';
$IZAPSETTINGS->ajaxed_video_height = 200;
$IZAPSETTINGS->ajaxed_video_width = 250;

function izapLoadLib_izap_videos() {
  // Get config
  global $CONFIG;

  $files = array(
    'elgg_16_functions',
    'settings',
    'convert',
    'izapLib',
    'izap_api',
    'izap_sqlite',
    'izap_videos',
    'video_feed',
    '../upgrade');
  $current_location = dirname(__FILE__).'/';
  // Include them
  foreach($files as $file) {
    @include_once($current_location.$file.'.php');
  }
}
/**
 * checks if the field is null or not
 *
 * @param variable $input any variable
 * @param array $exclude in case if we want to exclude some values
 * @return boolean true if null else false
 */
function izapIsNull_izap_videos($input, $exclude = array()) {
  if(!is_array($input)) {
    $input = array($input);
  }

  if(count($input) >= 1) {
    foreach ($input as $key => $value) {
      if(!in_array($key, $exclude)) {
        //if(is_null($value) || empty($value)){
        if(empty($value)) {
          return TRUE;
        }
      }
    }
  }else {
    return TRUE;
  }

  return FALSE;
}

/**
 * this converts the array into object
 *
 * @param array $array
 * @return object
 */
function izapArrayToObject_izap_videos($array) {
  if(!is_array($array))
    return FALSE;

  $obj = new stdClass();
  foreach ($array as $key => $value) {
    if($key != '' && $value != '') {
      $obj->$key = $value;
    }
  }

  return $obj;
}

/**
 * sets or gets the private settings for the izap_videos
 *
 * @param string $settingName setting name
 * @param mix $values sting or array of value
 * @param boolean $override if we want to force override the value
 * @param boolean $makeArray if we want the return value in the array
 * @return value array or string
 */
function izapAdminSettings_izap_videos($settingName, $values = '', $override = false, $makeArray = false) {
  // get the old value
  $oldSetting = get_plugin_setting($settingName, 'izap_videos');

  if(is_array($values)) {
    $pluginValues = implode('|', $values);
  }else {
    $pluginValues = $values;
  }
  // if it is not set yet
  if(empty($oldSetting) || $override) {
    if(!set_plugin_setting($settingName, $pluginValues, 'izap_videos')) {
      return FALSE;
    }
  }

  if($oldSetting) {
    $oldArray = explode('|', $oldSetting);
    if(count($oldArray) > 1) {
      $returnVal = $oldArray;
    }else {
      $returnVal = $oldSetting;
    }
  }else {
    $returnVal = $values;
  }

  if(!is_array($returnVal) && $makeArray) {
    $newReturnVal[] = $returnVal;
    $returnVal = $newReturnVal;
  }
  return $returnVal;
}

/**
 * checks if it is windows
 *
 * @return boolean TRUE if windows else FALSE
 */
function izapIsWin_izap_videos() {
  if(strtolower(PHP_OS) == 'winnt') {
    return TRUE;
  }else {
    return FALSE;
  }
}

/**
 * gets the video add options from the admin settings
 *
 * @return array
 */
function izapGetVideoOptions_izap_videos() {
  $videoOptions = izapAdminSettings_izap_videos('izapVideoOptions', '', FALSE, TRUE);
  return $videoOptions;
}

/**
 * this function will check that is the given id is of izap_videos
 *
 * @param int $videoId video id
 * @return video entity or FALSE
 */
function izapVideoCheck_izap_videos($videoId, $canEditCheck = FALSE) {
  $videoId = (int) $videoId;
  if($videoId) {
    $video = get_entity($videoId);

    if($video && $canEditCheck && !$video->canEdit()) {
      forward();
    }

    if($video && ($video instanceof IzapVideos)) {
      return $video;
    }
  }

  // if it reaches here then certainly send back
  forward();
}

/**
 * this function saves the entry for futher processing
 * @param string $file main filepath
 * @param int $videoId video guid
 * @param int $ownerGuid owner guid
 * @param int $accessId access id to be used after completion of encoding of video
 */
function izapSaveFileInfoForConverting_izap_videos($file, $video, $defined_access_id = 2) {
// this will not let save any thing if there is no file to convert
  if(!file_exists($file) || !$video) {
    return FALSE;
  }
  $queue = new izapQueue();
  $queue->put($video,$file,$defined_access_id);
  izapTrigger_izap_videos();
}

function isOnserverEnabled() {
  $settings = izapAdminSettings_izap_videos('izapVideoOptions');
  if(!is_array($settings)) {
    $settings = array($settings);
  }

  if(in_array('ONSERVER', $settings)) {
    return TRUE;
  }

  return FALSE;
}

/**
 * this function triggers the queue
 *
 * @global <type> $CONFIG
 */
function izapTrigger_izap_videos() {
  global $CONFIG;
  $PHPpath = izapGetPhpPath_izap_videos();
  if(!izapIsQueueRunning_izap_videos()) {
    if(izapIsWin_izap_videos()) {
      pclose( popen("start \"MyProcess\" \"cmd /C ".$PHPpath . " " . $CONFIG->pluginspath . "izap_videos/izap_convert_video.php izap web", "r") );
    }else {
      time();
      exec($PHPpath . ' ' . $CONFIG->pluginspath . 'izap_videos/izap_convert_video.php izap web > /dev/null 2>&1 &', $output);
    }
  }
}

/**
 * this function gives the path of PHP
 *
 * @return string path
 */
function izapGetPhpPath_izap_videos() {
  $path = izapAdminSettings_izap_videos('izapPhpInterpreter');
  $path = html_entity_decode($path);
  if(!$path)
    $path = '';
  return $path;
}

/**
 * this function checks if the queue is running or not
 *
 * @return boolean TRUE if yes or FALSE if no
 */
function izapIsQueueRunning_izap_videos() {
  // check for *nix machine. For windows, it is under development
  $queue_object = new izapQueue();

  $numberof_process = $queue_object->check_process();
  if($numberof_process) {
    return TRUE;
  }else {
    return FALSE;
  }

}

/**
 * resets queue
 *
 * @return boolean
 */
function izapResetQueue_izap_videos() {
  return izapAdminSettings_izap_videos('isQueueRunning', 'no', TRUE);
}

/**
 * clears queue and resets it
 *
 * @return boolean
 */
function izapEmptyQueue_izap_videos() {
  $pending_videos = izapGetNotConvertedVideos_izap_videos();
  if($pending_videos) {
    foreach($pending_videos as $video) {
      $video->delete();
    }
  }

  return izapResetQueue_izap_videos();
}

/**
 * grants the access
 *
 * @param <type> $functionName
 */
function izapGetAccess_izap_videos() {
  izap_access_override(array('status' => TRUE));
}

/**
 * remove access
 *
 * @global global $CONFIG
 * @param string $functionName
 */
function izapRemoveAccess_izap_videos() {
  izap_access_override(array('status' => FALSE));
}


function izap_access_override($params=array()) {
  global $CONFIG;

  if($params['status']) {
    $func="register_plugin_hook";
  } else {
    $func="unregister_plugin_hook";
  }

  $func_name="izapGetAccessForAll_izap_videos";

  $func("premissions_check","all",$func_name, 9999);
  $func("container_permissions_check","all",$func_name, 9999);
  $func("permissions_check:metadata","all",$func_name, 9999);

}

/**
 * elgg hook to override permission check of entities (izap_videos, izapVideoQueue, izap_recycle_bin)
 *
 * @param <type> $hook
 * @param <type> $entity_type
 * @param <type> $returnvalue
 * @param <type> $params
 * @return <type>
 */
function izapGetAccessForAll_izap_videos($hook, $entity_type, $returnvalue, $params) {
  return TRUE;
}

function izapRunQueue_izap_videos() {
  $queue_object = new izapQueue();
  $queue = $queue_object->fetch_videos();
  c($queue);
  if(is_array($queue)) {
    foreach($queue as $pending) {
      $converted = izapConvertVideo_izap_videos($pending['main_file'], $pending['guid'], $pending['title'], $pending['url'], $pending['owner_id']);
      c($converted);
      if(!$converted) {
        $queue_object->move_to_trash($pending['guid']);
      }
      $queue_object->delete($pending['guid']);
      izap_update_all_defined_access_id($pending['guid'], $pending['access_id']);
    }
    // recheck if there is new video in the queue
    if($queue_object->count() > 0) {
      izapRunQueue_izap_videos();
    }
  }
  return true;
}

/**
 * this function gets the site admin
 *
 * @param boolean $guid if only guid is required
 * @return mix depends on the input and result
 */
function izapGetSiteAdmin_izap_videos($guid = FALSE) {
  $admin = get_entities_from_metadata('admin', 1, 'user', '', 0, 1, 0);
  if($admin[0]->admin || $admin[0]->siteadmin) {
    if($guid)
      return $admin[0]->guid;
    else
      return $admin[0];
  }
  return FALSE;
}

/**
 * this function copies the files from one location to another
 *
 * @param int $sourceOwnerGuid guid of the file owner
 * @param string $sourceFile source file location
 * @param int $destinationOwnerGuid guid of new file owner, if not given then takes loggedin user id
 * @param string $destinationFile destination location, if blank then same as source
 */
function izapCopyFiles_izap_videos($sourceOwnerGuid, $sourceFile, $destinationOwnerGuid = 0, $destinationFile = '') {
  $filehandler = new ElggFile();

  $filehandler->owner_guid = $sourceOwnerGuid;
  $filehandler->setFilename($sourceFile);
  $filehandler->open('read');
  $sourceFileContents = $filehandler->grabFile();

  if($destinationFile == '')
    $destinationFile = $sourceFile;

  if(!$destinationOwnerGuid)
    $destinationOwnerGuid = get_loggedin_userid();

  $filehandler->owner_guid = $destinationOwnerGuid;
  $filehandler->setFilename($destinationFile);
  $filehandler->open('write');
  $filehandler->write($sourceFileContents);

  $filehandler->close();
}

/**
 * this function get all the videos for a user or all users
 *
 * @param int $ownerGuid id of the user to get videos for
 * @param boolean $count Do u want the total or videos ? :)
 * @return videos or false
 */
function izapGetAllVideos_izap_videos($ownerGuid = 0, $count = FALSE, $izapVideoType = 'object', $izapSubtype = 'izap_videos') {
  $videos = get_entities($izapVideoType, $izapSubtype, $ownerGuid, '', 0);
  return $videos;
}

/**
 * wraps the string to given number of words
 *
 * @param string $string string to wrap
 * @param integer $length max length of sting
 * @return sting $string wrapped sting
 */
function izapWordWrap_izap_videos($string, $length = 300, $add_ending = false) {
  if (strlen($string) <= $length) {
    $string = $string; //do nothing
  } else {
    $string = strip_tags($string);
    $string = wordwrap(str_replace("\n", "", $string), $length);
    $string = substr($string, 0, strpos($string, "\n"));

    if($add_ending) {
      $string .= '...';
    }
  }

  return $string;
}

/**
 * this function will tell if the admin wants to include the index page widget
 *
 * @return boolean true for yes and false for no
 */
function izapIncludeIndexWidget_izap_videos() {
  $var = izapAdminSettings_izap_videos('izapIndexPageWidget', 'YES');

  if($var == 'NO')
    return FALSE;

  return TRUE;
}

/**
 * this function will tell if the admin wants to include the top bar upload button
 *
 * @return boolean true for yes and false for no
 */
function izapTopBarWidget_izap_videos() {
  $var = izapAdminSettings_izap_videos('izapTopBarWidget', 'YES');

  if($var == 'NO')
    return FALSE;

  return TRUE;
}

/**
 * manages the url for embeding the videos
 *
 * @param string $text all text
 * @return string
 */
function izapParseUrls_izap_videos($text) {
  return preg_replace_callback('/[^movie=](?<!=["\'])((ht|f)tps?:\/\/[^\s\r\n\t<>"\'\!\(\)]+)/i',
          create_function(
          '$matches',
          '$url = $matches[1];
        $urltext = str_replace("/", "/<wbr />", $url);
        return "<a href=\"$url\" style=\"text-decoration:underline;\">$urltext</a>";
      '
          ), $text);
}

/**
 * gets the not converted videos
 *
 * @return boolean or entites
 */
function izapGetNotConvertedVideos_izap_videos() {
  $not_converted_videos = get_entities_from_metadata('converted', 'no', 'object', 'izap_videos', 0, 999999);
  if($not_converted_videos) {
    return $not_converted_videos;
  }

  return FALSE;
}


function izapReadableSize_izap_videos($inputSize) {
  if (strpos($inputSize, 'M'))
    return $inputSize . 'B';

  $outputSize = $inputSize / 1024;
  if ($outputSize < 1024) {
    $outputSize = number_format($outputSize, 2);
    $outputSize .= ' KB';
  } else {
    $outputSize = $outputSize / 1024;
    if($outputSize < 1024) {
      $outputSize = number_format($outputSize, 2);
      $outputSize .= ' MB';
    } else {
      $outputSize = $outputSize / 1024;
      $outputSize = number_format($outputSize, 2);
      $outputSize .= ' GB';
    }
  }
  return $outputSize;
}

/**
 * this will upgrade you old izap_videos plugin to this version.
 *
 * @global <type> $CONFIG
 */
function izapSetup_izap_videos() {
  global $CONFIG;
  add_subtype('object', 'izap_videos', 'IzapVideos');
  datalist_set('izap_videos_version', '3.55b');
}

/**
 * a quick way to convert bytes to a more readable format
 * http://in3.php.net/manual/en/function.filesize.php#91477
 *
 * @param integer $bytes size in bytes
 * @param integer $precision
 * @return string
 */
function izapFormatBytes($bytes, $precision = 2) {
  $units = array('B', 'KB', 'MB', 'GB', 'TB');

  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($units) - 1);

  $bytes /= pow(1024, $pow);

  return round($bytes, $precision) . ' ' . $units[$pow];
}


/**
 * counts the queued videos
 * @return integer
 */
function izap_count_queue() {
  $queue_object = new izapQueue();
  return $queue_object->count();
}

function izap_get_video_name_prefix() {
  global $CONFIG;

  $domain = get_site_domain($CONFIG->site_guid);
  $domain = preg_replace('/[^A-Za-z0-9]+/','_',$domain);

  return $domain . '_izap_videos_';
}

//Hack to correct the access id of the uploaded video.

function izap_update_all_defined_access_id($entity_guid, $accessId = ACCESS_PUBLIC) {
  global $CONFIG;
  // update metadata
  $query = 'UPDATE ' . $CONFIG->dbprefix . 'metadata SET access_id = ' . $accessId . ' WHERE entity_guid = ' . $entity_guid;
  $query = update_data($query);
  if(!$query) {
    return FALSE;
  }
  $query = 'UPDATE ' . $CONFIG->dbprefix . 'entities SET access_id = ' . $accessId . ' WHERE guid = ' . $entity_guid;
  update_data($query);
  return $query;
}


function izap_is_my_favorited($video) {
  $users = (array) $video->favorited_by;
  $key = array_search(get_loggedin_userid(), $users);
  if($key !== FALSE) {
    return TRUE;
  }

  return FALSE;
}

function izap_remove_favorited($video, $user_guid = 0) {
  $users = (array) $video->favorited_by;

  if(!$user_guid) {
    $user_guid = get_loggedin_userid();
  }

  $key = array_search($user_guid, $users);

  if($key !== FALSE) {
    unset($users[$key]);
  }

  izapGetAccess_izap_videos();
  $video->favorited_by = array_unique($users);
  izapRemoveAccess_izap_videos();

  return TRUE;
}

function is_old_elgg() {
  if((real)get_version(true) <= 1.6) {
    return TRUE;
  }

  return FALSE;
}

function izap_get_supported_videos_list() {
  global $IZAPSETTINGS;
  $ch = new IzapCurl($IZAPSETTINGS->api_server . 'supported_sites.php');
  $data = $ch->exec();

  $array = unserialize($data);
  foreach ($array as $title => $href) {
    $string[] = '<a href="'.$href.'" title="'.$title.'" target="_blank">'.$title.'</a>';
  }

  return '(' . elgg_echo('izap_videos:total') . ': ' . count($array).') ' . implode(', ', $string);
}