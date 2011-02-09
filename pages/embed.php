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

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
global $SESSION;
global $CONFIG;
$search = get_input('search', FALSE);

$internalname = get_input('internalname');

$options['type'] = 'object';
$options['subtype'] = 'izap_videos';
$options['limit'] = 30;
$options['offset'] = (int) get_input('offset',0);
$options['container_guid'] = 0;
$options['count'] = TRUE;

if(!$search) {
  if(is_callable('elgg_get_entities')) {
    $count = elgg_get_entities($options);
    if($count) {
      unset ($options['count']);
      $entities = elgg_get_entities($options);
    }
  }else {
    $count = get_entities($options['type'],$options['subtype'],$options['container_guid'],'',$options['limit'],$options['offset'],$options['count']);
    if($count) {
      $entities = get_entities($options['type'],$options['subtype'],$options['container_guid'],'',$options['limit'],$options['offset']);
    }
  }
  $baseUrl = $CONFIG->wwwroot . 'pg/videos/'.$_SESSION['username'].'/embed?internalname='.$internalname;
}else {
  $options['metadata_name'] = 'tags';
  $options['metadata_values'] = $search;
  if(is_callable('elgg_get_entities_from_metadata')) {
    $count = elgg_get_entities_from_metadata($options);
    if($count) {
      unset ($options['count']);
      $entities = elgg_get_entities_from_metadata($options);
    }
  }else {
    $count = get_entities_from_metadata($options['metadata_name'], $options['metadata_values'], $options['type'],$options['subtype'],$options['container_guid'],$options['limit'],$options['offset'], '',0,$options['count']);
    if($count) {
      $entities = get_entities_from_metadata($options['metadata_name'], $options['metadata_values'], $options['type'],$options['subtype'],$options['container_guid'],$options['limit'],$options['offset'], '',0);
    }
  }
  
  $baseUrl = $CONFIG->wwwroot . 'pg/videos/'.$_SESSION['username'].'/embed?internalname='.$internalname.'&search='.$search;
}
$videos .= elgg_view('izap_videos/embedvideos', array(
        'entities' => $entities,
        'internalname' => $internalname,
        'offset' => $offset,
        'count' => $count,
        'limit' => $limit,
        'baseUrl' => $baseUrl,
));
?>
<div id="videoIcons">
  <?php echo $videos?>
</div>