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

// get the choosen video (video choosed by user to display)
$id = $vars['entity']->selected_video;

// gets the video on click to change
$ary = explode("/",$_GET['page']);
if(count($ary) > 1)
  $id = $ary[2];

$displayVideo = get_entity($id);

// if still there is no video, then chooses the latest video added by user
if(!$displayVideo) {
  if(is_callable('elgg_get_entities')) {
    $options = array(
            'type' => 'object',
            'subtype' => 'izap_videos',
            'owner_guid' => $vars['entity']->owner_guid,
            'limit' => 1,
    );
    $video = elgg_get_entities($options);
  }else {
    $video = get_entities('object', 'izap_videos',$vars['entity']->owner_guid, "", 1);
  }
  $displayVideo = $video[0];
}

if($displayVideo) {

  $owner = $displayVideo->getOwnerEntity();
  $friendlytime = friendly_time($displayVideo->time_created);

  // gets back the player code
  $playerClass = 'class="izap_videos_selected"';
  ?>
<div <?php echo $playerClass?>>
    <?php
    echo '<div align="center">' . $displayVideo->getPlayer(270, 180) . '</div>';
    ?>
  <a href="<?php echo $displayVideo->getURL();?>" style="color: white;"><b><?php echo $displayVideo->title;?></b></a>
</div>
  <?php
}

// video listing starts here on
$num = ($vars['entity']->num_display) ? $vars['entity']->num_display : 4;

// lets get the user videos
if(is_callable('elgg_get_entities')) {
  $options = array(
          'type' => 'object',
          'subtype' => 'izap_videos',
          'owner_guid' => $vars['entity']->owner_guid,
          'limit' => $num,
  );
  $videos = elgg_get_entities($options);
}else {
  $videos = get_entities('object', 'izap_videos',$vars['entity']->owner_guid, "", $num);
}

if($videos) {
  foreach($videos as $video) {
    if($video->getGUID() != $id) {
      ?>
<div class="izap_shares_widget_wrapper">
  <div class="izap_shares_widget_icon">
    <a href="javascript: izap_vid('<?php echo $vars['url']; ?>',<?php echo $vars['entity']->getGUID(); ?>,<?php echo $video->getGUID(); ?>)">
      <img src="<?php echo $video->getThumb(TRUE) ?>" height="40" width="40" />
    </a>
  </div>

  <div>
    <p class="izap_shares_title">
      <a href="javascript: izap_vid('<?php echo $vars['url']; ?>',<?php echo $vars['entity']->getGUID(); ?>,<?php echo $video->getGUID(); ?>)">
              <?php echo izapWordWrap_izap_videos($video->title, 30, TRUE); ?>
      </a>
    </p>
    <p class="izap_shares_timestamp" align="right">
            <?php echo friendly_time($video->time_created); ?>
    </p>
  </div>

  <div class="clearfloat"></div>
</div>
      <?php
    }
  }

  $userVideos = $vars['url'] . 'pg/videos/list/' . $vars['entity']->getOwnerEntity()->username;
  echo '<div class="contentWrapper" align="right"><a href="'.$userVideos.'">'.elgg_echo('izap_videos:everyone').'</a></div>';
}
else {
  echo '<div class="contentWrapper">' . elgg_echo('izap_videos:notfound') . '</div>';
}