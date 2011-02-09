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

global $SESSION;
global $CONFIG;
$search = get_input('search', FALSE);

$internalname = get_input('internalname');
$offset = (int) get_input('offset',0);
$limit = (int) 30;

if(!$search) {
  $count = get_entities('object','izap_videos',0,'',null,null,true);
  $entities = get_entities('object','izap_videos',0,'',$limit,$offset);
  $baseUrl = $CONFIG->wwwroot . 'pg/videos/embed/'.$_SESSION['username'].'/?internalname='.$internalname;
}else {
  $count = get_entities_from_metadata('tags', $search, 'object', 'izap_videos', 0, '', '', '', '', TRUE);
  $entities = get_entities_from_metadata('tags', $search, 'object', 'izap_videos', 0, $limit, $offset);
  $baseUrl = $CONFIG->wwwroot . 'pg/videos/embed/'.$_SESSION['username'].'/?internalname='.$internalname.'&search='.$search;
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