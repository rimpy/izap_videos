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


// get video
$video = $vars['video'];
$iconPath = $vars['url'] . 'mod/izap_videos/_graphics/sharing/';
$embedCode =  trim(str_replace('"', '\'', $vars['video']->getPlayer()));
$url = current_page_url();
$title = $video->title;
$embed = elgg_view('input/text', array('value' => $embedCode, 'js' => ' onClick = this.select() READONLY'));

?>
<div id="videoSrc">
  <br />
  <div>
    <img src="<?php echo $iconPath?>facebook.png" alt="Facebook">
    <a href="http://www.facebook.com/share.php?u=<?php echo $url;?>" target="_blank">
      Facebook
    </a>
    <br />
    <img src="<?php echo $iconPath?>twitter.png" alt="Twitter">
    <a href="http://twitter.com/home/?status=<?php echo $url;?>" target="_blank">
      Twitter
    </a>
    <br />
    <img src="<?php echo $iconPath?>myspace.png" alt="MySpace">
    <a href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?php echo $url;?>&t=<?php echo friendly_title($title)?>&c=<?php echo urlencode($embedCode);?>" target="_blank">
      Myspace
    </a>
    <br />
    <img src="<?php echo $iconPath?>linkedin.png" alt="LinkedIn">
    <a href="http://www.linkedin.com/shareArticle?url=<?php echo $url;?>" target="_blank">
      Linkedin
    </a>
    <br />
    <?php
    if($vars['video']->converted == 'yes') {
      if(izap_is_my_favorited($vars['video'])) {
        ?>
    <img src="<?php echo $iconPath?>remove_favorite.png" alt="<?php echo elgg_echo('izap_videos:remove_favorite');?>" />
    <a href="<?php echo elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_videos_add_favorite?guid=' . $vars['video']->guid . '&izap_action=remove');?>" title="<?php echo elgg_echo('izap_videos:remove_favorite');?>">
          <?php echo elgg_echo('izap_videos:remove_favorite');?>
    </a>
        <?php
      }else {
        ?>
    <img src="<?php echo $iconPath?>add_favorite.png" alt="<?php echo elgg_echo('izap_videos:save_favorite');?>">
    <a href="<?php echo elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_videos_add_favorite?guid=' . $vars['video']->guid);?>" title="<?php echo elgg_echo('izap_videos:save_favorite');?>">
          <?php echo elgg_echo('izap_videos:save_favorite');?>
    </a>
        <?php
      }
    }
    ?>

    <div>
      <div class="embed_text">
        <?php echo elgg_echo('izap_videos:embed_text'); ?>
      </div>
      <?php echo $embed?>
    </div>

    <div class="izap_video_rate">
      <?php
      echo elgg_echo('izap_videos:rate');
      echo elgg_view('input/rate', array('entity' => $video));
      ?>
    </div>
    <div class="clearflaot"></div>
  </div>
</div>