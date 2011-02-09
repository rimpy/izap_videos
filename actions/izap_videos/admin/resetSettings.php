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

admin_gatekeeper();

if((real)get_version(true)< 1.7){
  $cleared = clear_plugin_setting('', 'izap_videos');
}else{
  $cleared = clear_all_plugin_settings('izap_videos');
}
if($cleared) {
  system_message(elgg_echo('izap_videos:success:adminSettingsReset'));
}else {
  register_error(elgg_echo('izap_videos:error:adminSettingsReset'));
}
forward($_SERVER['HTTP_REFERER']);
exit;