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
}
$DeleteEdit .= '&nbsp;&nbsp;';
$description = elgg_view('output/longtext', array('value' => $vars['video']->description));
$container_entity = get_entity($vars['video']->container_guid);
?>

<div class="contentWrapper">
  <div class="generic_comment">
    <!-- Owner icon -->
    <div class="generic_comment_icon">
      <?php
      echo elgg_view("profile/icon",array('entity' => $container_entity, 'size' => 'small'));
      ?>
    </div>

    <div class="generic_comment_details">
      <p>
        <?php
        echo sprintf(elgg_echo("izap_videos:time"), date("F j, Y",$vars['video']->time_created)) . ' ';
        echo elgg_echo('by');
        ?>
        <a href="<?php echo $vars['url']; ?>pg/videos/list/<?php echo $container_entity->username; ?>">
          <?php echo $container_entity->name; ?>
        </a> &nbsp;
        <!-- display the comments link -->
        <?php
        echo $DeleteEdit;
        // if the video is copied
        if((int)$vars['video']->copiedFrom > 0) {
          $owner = get_user($vars['video']->copiedFrom);
          echo ' <span class="copied_text">[' . elgg_echo('izap_videos:copiedFrom') . ': <a href="' . $vars['video']->copiedVideoUrl . '">' . $owner->name . '</a>]</span>';
        }
        // get tags
        ?>
      </p>

      <div class="main_page_total_views">
        <h3>
          <?php echo $vars['video']->getViews();?>
        </h3>
        <?php echo elgg_echo('izap_videos:views');?>
      </div>
    </div>


    <div id="gen" class="generic_comment_details">
      <div id="small_desc">
        <?php
        /// description text
        if(strlen($description) > 255) {
          $mini_description = strip_tags($description);
          echo substr($mini_description,0,255);
          echo '... &nbsp;&nbsp;&nbsp;<a href="#fulldesc" onClick="show_full_desc();">['.elgg_echo("izap_videos:more").']</a>';
        }else {
          echo $description;
        }
        ?>
      </div>

      <div id="full_desc" style="display:none;">
        <?php
        /// description text
        echo $description.' &nbsp;&nbsp;&nbsp;<a href="#small_desc" onClick="hide_full_desc();">['.elgg_echo("izap_videos:less").']</a>';
        ?>
      </div>
      <?php

      //// tags view

      $tags = elgg_view('output/tags', array('tags' => $vars['video']->tags));
      echo '<p id="tag_view" class="generic_comment_owner"><img src="'.$vars['url'].'_graphics/icon_tag.gif" alt="'.elgg_echo('izap_videos:tags').'"/> ' . $tags . '</p>';
      ?>
    </div>
  </div>
  <div class="clearfloat"></div>
</div>

<script language="javascript" type="text/javascript">

  function show_full_desc() {
    document.getElementById('small_desc').style.display="none";
    document.getElementById('full_desc').style.display="block";
    return false;
  }

  function hide_full_desc() {
    document.getElementById('small_desc').style.display="block";
    document.getElementById('full_desc').style.display="none";
    return false;
  }
</script>