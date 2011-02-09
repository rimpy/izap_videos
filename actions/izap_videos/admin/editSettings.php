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

action_gatekeeper();
admin_gatekeeper();

$postedArray = get_input('izap');

$plugin = find_plugin_settings('izap_videos');

// get the video options checkboxes
$videoOptions = filter_tags($_POST['izap']['izapVideoOptions']);
if(empty($videoOptions)) {
  register_error(elgg_echo('izap_videos:error:videoOptionBlank'));
  forward($_SERVER['HTTP_REFERER']);
}
$postedArray['izapVideoOptions'] = $videoOptions;

// get the index page widget
if(!empty($postedArray['izapExtendVideoSupport'])) {
  $postedArray['izapExtendVideoSupport'] = 'YES';
}else {
  $postedArray['izapExtendVideoSupport'] = 'NO';
}

// get the index page widget
if(!empty($postedArray['izapIndexPageWidget'])) {
  $postedArray['izapIndexPageWidget'] = 'YES';
}else {
  $postedArray['izapIndexPageWidget'] = 'NO';
}

// get the top bar icon
if(!empty($postedArray['izapTopBarWidget'])) {
  $postedArray['izapTopBarWidget'] = 'YES';
}else {
  $postedArray['izapTopBarWidget'] = 'NO';
}

// get tag area3 settings
if(!empty($postedArray['izapTagCloud'])) {
  $postedArray['izapTagCloud'] = 'YES';
}else {
  $postedArray['izapTagCloud'] = 'NO';
}

// get the credit
if(!empty($postedArray['izapGiveUsCredit'])) {
  $postedArray['izapGiveUsCredit'] = 'YES';
}else {
  $postedArray['izapGiveUsCredit'] = 'NO';
}

// get to keep values
if(!empty($postedArray['izapKeepOriginal'])) {
  $postedArray['izapKeepOriginal'] = 'YES';
}else {
  $postedArray['izapKeepOriginal'] = 'NO';
}

foreach($postedArray AS $key => $values) {
  izapAdminSettings_izap_videos($key, $values, TRUE);
  if($key == 'izapVideoOptions') {
    if(in_array('ONSERVER', $values)) {
     $queue_object = new izapQueue();
    }
  }
}

system_message(elgg_echo('izap_videos:success:adminSettingsSaved'));
forward($_SERVER['HTTP_REFERER']);
exit;