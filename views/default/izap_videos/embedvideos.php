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
?>
<h1 class="mediaModalTitle">Embed / Upload Media</h1>
<?php
echo elgg_view('embed/tabs',array('tab' => 'izap_videos', 'internalname' => $vars['internalname']));
?>
<div id='mediaEmbed'>
  <?php
  echo elgg_view('embed/pagination',array(
  'offset' => $vars['offset'],
  'baseurl' => $vars['baseUrl'],
  'limit' => $vars['limit'],
  'count' => $vars['count']
  ));
  echo elgg_view_title(elgg_echo('videos'));

  echo '<div style="margin:10px;">';
  echo '<form onSubmit="video_search(); return false">';
  echo '<input type="text" id="search" onclick="javascript: if(this.value==\'Search on tags\') this.value=\'\';" value="Search on tags"/>';
  echo '&nbsp<input type="submit" value="Search" />';
  echo '&nbsp;or&nbsp;<a href="'.$vars['url'].'pg/videos/add/'.$_SESSION['user']->username.'/">'.elgg_echo('izap_videos:add').'</a>';
  echo '</form>';
  echo '</div>';

  //include $CONFIG->pluginspath . 'izap_videos/classes/video_feed.php';
  if(is_array($vars['entities']) && count($vars['entities']) > 0) {
    foreach ($vars['entities'] as $video) {

      $player = str_replace('\'', '"', $video->getPlayer());

      $image = '<img src="'.$video->getThumb(TRUE).'" height="90" width="90">';

      $content = htmlentities($player, ENT_QUOTES);

      $friendlytime = friendly_time($video->time_created);
      $icon = '<a href="javascript: elggUpdateContent(\''.$content.'\',\''.$vars['internalname'].'\');">'.$image.'</a>';

      $info = '<a href="javascript: elggUpdateContent(\''.$content.'\',\''.$vars['internalname'].'\');">' . $video->title . '</a>';
      //echo elgg_view_listing($icon,$info);
      ?>
  <div class="embedThumbs contentWrapper" title="<?php echo $video->title?>">
    <a href="<?php echo 'javascript: elggUpdateContent(\''.$content.'\',\''.$vars['internalname'].'\');';?>">
          <?php echo $image;?>
      <br />
        <?php
        $description = strip_tags($video->description);
        echo $description = substr($description, 0, 10) . ((strlen($description) > 10) ? '...' : '' );
        ?>
    </a>
  </div>

      <?php
    }
  } else {
    echo '<div class="contentWrapper">'.elgg_echo('izap_videos:noTagVideo').'</div>';
  }
  ?>
  <div class="clearfloat"></div>
</div>

<script>
  function video_search() {
    search = $('#search').val();
    $('#videoIcons').load('<?php echo $vars['url'] . 'pg/videos/embed/?search='?>'+encodeURI(search));
  }
</script>