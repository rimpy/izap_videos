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

$ts = time();
$token = generate_action_token($ts);

echo elgg_view('input/hidden', array('internalname' => '__elgg_token', 'value' => $token));
echo elgg_view('input/hidden', array('internalname' => '__elgg_ts', 'value' => $ts));
