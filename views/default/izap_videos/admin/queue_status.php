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

// get action for queue
$action = get_input('action');
switch ($action) {
  case 'reset':
    izapResetQueue_izap_videos();
    forward($vars['url'] . 'pg/videos/adminSettings/' . get_loggedin_user()->username . '?option=queue_status');
    break;

  case 'delete':
    izapEmptyQueue_izap_videos();
    forward($vars['url'] . 'pg/videos/adminSettings/' . get_loggedin_user()->username . '?option=queue_status');
    break;

  default:
    break;
}

?>
<div id="videoQueue" align="center">
  <img src="<?php echo $vars['url'] . 'mod/izap_videos/_graphics/queue.gif'?>" alt="queue"/>
</div>
<p align="right">
  <?php echo elgg_view('output/confirmlink',array('href'=>$vars['url'].'action/izapResetQueue','text' => 'Reset Queue','confirm'=>'Are you sure? It will empty queue and correspoinding videos.')); ?><br /><em>Refresh after every 5 seconds.</em>
</p>
<script type="text/javascript">
  function checkQueue(){
    $('#videoQueue').load('<?php echo $vars['url'] . 'pg/videos/getQueue'?>');
  }
  $(document).ready(function(){
    checkQueue();
    setInterval(checkQueue, 5000);
  });
</script>