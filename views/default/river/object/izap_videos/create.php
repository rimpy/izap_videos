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

$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
$object = get_entity($vars['item']->object_guid);
$ajaxed_icon = $object->getAjaxedThumb(array('width' => 80, 'height' => 80));
$contents = strip_tags($object->description); //strip tags from the contents to stop large images etc blowing out the river view

$url = $object->getURL();
$videoTitle .= '<a href="' . $url . '">' . $object->title . '</a>';
$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$string .= sprintf(elgg_echo('izap_videos:river:titled'),$url,$videoTitle);
$string .= "<div class=\"river_content_display\">";
$string .= '<div style="float:left;margin-right:5px;">' . $ajaxed_icon . '</div>';
if(strlen($contents) > 400) {
  $string .= substr($contents, 0, 400) . "...";
}else {
  $string .= $contents;
}
$string .= '</div><div class="clearfloat"></div>';
echo $string;