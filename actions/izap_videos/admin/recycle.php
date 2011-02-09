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
$guid = get_input('guid');
$queue_object = new izapQueue();
if($queue_object->restore($guid)){
  system_message(elgg_echo('izap_videos:adminSettings:restore_video'));
  izapTrigger_izap_videos();
}
forward($_SERVER['HTTP_REFERER']);
exit;