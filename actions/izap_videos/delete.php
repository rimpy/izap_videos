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

gatekeeper();
$guid = (int) get_input('video_id');
$izap_videos = izapVideoCheck_izap_videos($guid, TRUE);
if($izap_videos->videotype == 'uploaded' && $izap_videos->converted == 'no') {
  $must_trigger = TRUE;
}
$owner = get_entity($izap_videos->container_guid);

if($izap_videos->delete()) {
  system_message(elgg_echo('izap_videos:deleted'));
  if($must_trigger === TRUE) {
    izapTrigger_izap_videos();
  }
}else {
  register_error(elgg_echo('izap_videos:notdeleted'));
}
forward('pg/videos/list/' . $owner->username);
exit;