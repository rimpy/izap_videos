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

if ($vars['video']->canEdit()) {
  $DeleteEdit .= elgg_view("output/confirmlink", array(
          'href' => $vars['url'] . "action/izapDelete?video_id=" . $vars['video']->getGUID(),
          'text' => elgg_echo('delete'),
          'confirm' => elgg_echo('izap_videos:remove'),
  ));
  $DeleteEdit .= '&nbsp;&nbsp;';
  if($vars['video']->converted == 'yes') {
    $DeleteEdit .= '<a href="' . $vars['url']  . 'pg/videos/edit/' . get_entity($vars['video']->container_guid)->username . '/' . $vars['video']->getGUID() . '">' . elgg_echo('izap_videos:edit') . '</a>';
  }else {
    $queue_object = new izapQueue();
    $trash_guid_array = $queue_object->get_from_trash($vars['video']->guid);
    if($trash_guid_array) {
      $DeleteEdit .= elgg_echo("izap_videos:form:izapTrashedVideoMsg");
    }else {
      $DeleteEdit .= elgg_echo("izap_videos:form:izapEditMsg");
    }
  }
}else {
  $DeleteEdit = '<br />';
}

$container_entity = get_entity($vars['video']->container_guid);
?>
<div class="contentWrapper">
  <h3>
    <a href="<?php echo $vars['video']->getUrl()?>">
      <?php echo $vars['video']->title;?>
    </a>
  </h3>
  
    <div class="listing_icon">
      <a href="<?php echo $vars['video']->getUrl()?>">
        <?php  echo $vars['video']->getThumb(FALSE, array('width' => 80, 'height' => 80, 'alt' => $vars['video']->title), TRUE);?>
      </a>
    </div>

    <div class="main_page_total_views">
      <h3>
        <?php echo $vars['video']->getViews();?>
      </h3>
      <?php echo elgg_echo('izap_videos:views');?>
    </div>

    <div class="generic_comment_details">
      <?php echo friendly_time($vars['video']->time_created); ?>
      <?php echo elgg_echo('by'); ?> <a href="<?php echo $vars['url']; ?>pg/videos/list/<?php echo $container_entity->username; ?>"><?php echo $container_entity->name; ?></a> &nbsp;
      <!-- display the comments link -->
      <a href="<?php echo $vars['video']->getURL(); ?>"><?php echo sprintf(elgg_echo("comments")) . " (" . elgg_count_comments($vars['video']) . ")"; ?></a>
      <?php
      echo $DeleteEdit;
      ?>
      <p class="generic_comment_owner">
        <?php
        $description = strip_tags($vars['video']->description);
        echo $description = substr($description, 0, 250) . ((strlen($description) > 250) ? '...' : '' );
        ?>
      </p>
    </div>

  <div class="clearfloat"></div>
</div>