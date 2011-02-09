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

global $CONFIG;

$type = get_input('type');
$tot = get_input('videosTOdisplay', 16);

if(is_callable('elgg_get_entities')) {
  $get_entities = elgg_get_entities(array('type' => 'object', 'subtype' => 'izap_videos', 'owner_guid' => 0, 'limit' => $tot));
}else {
  $get_entities = get_entities('object', 'izap_videos', 0, '', $tot);
}

switch ($type) {
  case 'latest':
    $videos = $get_entities;
    break;

  case 'views':
    $qry_topViews = "SELECT * FROM ".$CONFIG->dbprefix."metadata as m join ".$CONFIG->dbprefix."entities e on e.guid = m.entity_guid join ".$CONFIG->dbprefix."metastrings n on n.id = m.name_id join ".$CONFIG->dbprefix."metastrings v on v.id = m.value_id where e.subtype=".get_subtype_id('object', 'izap_videos')." AND n.string = 'views' AND ".get_access_sql_suffix('e')." ORDER BY cast(v.string AS SIGNED) DESC LIMIT 0, " . $tot;
    $videos = get_data($qry_topViews, 'entity_row_to_elggstar');
    break;

  case 'com':
    $qry_topCommented = "SELECT * FROM ".$CONFIG->dbprefix."annotations AS an JOIN ".$CONFIG->dbprefix."entities e ON e.guid = an.entity_guid WHERE e.subtype=".get_subtype_id('object', 'izap_videos')." AND ".get_access_sql_suffix('e')." GROUP BY an.entity_guid  ORDER BY count(an.entity_guid) DESC LIMIT 0, " . $tot;
    $videos = get_data($qry_topCommented, 'entity_row_to_elggstar');
    break;

  default:
    $videos = $get_entities;
    break;
}

if($videos) {
  foreach ($videos as $entity) {
    echo '<div class="customIndexIcon">
      <a href="'.$entity->getUrl().'" title="'.$entity->title.'">'.$entity->getThumb(FALSE, array('width' => 40, 'height' => 40, 'alt' => $entity->title), TRUE).'</a>
        </div>';
  }
  echo '<div class="clearfloat"></div>';
}
?>