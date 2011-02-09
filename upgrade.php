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

$new_version = '3.9b';

function upgrade_izap_videos_to($version){
  global $CONFIG;
  $update_entity_subtype = "UPDATE {$CONFIG->dbprefix}entity_subtypes SET class = 'IzapVideos' WHERE subtype = 'izap_videos'";
  $del_entity_query = "DELETE FROM {$CONFIG->dbprefix}entities
                WHERE subtype IN (SELECT id FROM {$CONFIG->dbprefix}entity_subtypes
                                  WHERE subtype='izapVideoQueue')";
  $del_queue_object_query = "DELETE FROM {$CONFIG->dbprefix}entity_subtypes where subtype='izapVideoQueue'";

  if(update_data($update_entity_subtype) || (delete_data($del_entity_query) || delete_data($del_queue_object_query))){
    datalist_set('izap_videos_version', $version);
  }  
}


// lesser than 3.55 version of izap_videos,
// needs some data changes in the database

if((real)datalist_get('izap_videos_version') < 3.55){
  // clears the old plugin settings
  if((real)get_version(true)< 1.7){
    $cleared = clear_plugin_setting('', 'izap_videos');
  }else{
    $cleared = clear_all_plugin_settings('izap_videos');
  }
  upgrade_izap_videos_to($new_version);
}elseif( (real)datalist_get('izap_videos_version') < (real)$new_version){
  datalist_set('izap_videos_version', $new_version);
}
