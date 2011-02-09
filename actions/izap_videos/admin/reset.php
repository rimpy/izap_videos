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
$queue_object = new izapQueue();
foreach ($queue_object->get(get_input('guid')) as $key => $prods){
  get_entity($prods['guid'])->delete();
}
system_message(elgg_echo('izap_videos:adminSettings:reset_queue'));
forward($_SERVER['HTTP_REFERER']);
exit;