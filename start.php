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


define('GLOBAL_IZAP_VIDEOS_PLUGIN', 'izap_videos');
define('GLOBAL_IZAP_VIDEOS_PAGEHANDLER', 'videos');
define('GLOBAL_IZAP_VIDEOS_SUBTYPE', 'izap_videos');

/**
 * main function that register everything
 *
 * @global <type> $CONFIG
 */
function init_izap_videos() {
  global $CONFIG;

  // render this plugin from izap-elgg-bridge now
  if(is_plugin_enabled('izap-elgg-bridge')) {
    func_init_plugin_byizap(array('plugin' => array('name' => GLOBAL_IZAP_VIDEOS_PLUGIN)));
  }else{
    register_error('This plugin needs izap-elgg-bridge');
    disable_plugin(GLOBAL_IZAP_VIDEOS_PLUGIN);
  }
  
  // for the first time, admin settings are not set so send admin to the setting page, to set the default settings
  if(isadminloggedin() && (int) datalist_get('izap_videos_installtime') == 0) {
    datalist_set('izap_videos_installtime', time());
    forward($CONFIG->wwwroot . 'pg/videos/adminSettings/' . get_loggedin_user()->username.'?option=settings');
  }

  // extend the views
  if(is_callable('elgg_extend_view')) {
    $extend_view = 'elgg_extend_view';
  }else {
    $extend_view = 'extend_view';
  }

  // include the main lib file
  include dirname(__FILE__) . '/lib/izapLib.php';

  // load all the required files
  izapLoadLib_izap_videos();

  // register pagehandler
  register_page_handler('videos', 'pageHandler_izap_videos');
  register_page_handler('izap_videos_files', 'pageHandler_izap_videos_files');

  // register the notification hook
  if(is_callable('register_notification_object')) {
    register_notification_object('object', 'izap_videos', elgg_echo('izap_videos:newVideoAdded'));
  }
  $period = get_plugin_setting('izap_cron_time', GLOBAL_IZAP_VIDEOS_PLUGIN);
  if(isOnserverEnabled() && is_plugin_enabled('crontrigger') && $period != 'none') {
    register_plugin_hook('cron', $period, 'izap_queue_cron');
  }

  // asking group to include the izap_videos
  if(is_callable('add_group_tool_option')) {
    add_group_tool_option('izap_videos', elgg_echo('izap_videos:group:enablevideo'), true);
  }

  // register the notification hook
  if(is_callable('register_notification_object')) {
    register_notification_object('object', 'izap_videos', elgg_echo('izap_videos:newVideoAdded'));
  }

  // skip tags from filteration
  if(is_old_elgg()) {//allow some tags for elgg lesser than 1.6
    $CONFIG->allowedtags['object'] = array( 'width'=>array(), 'height'=>array(), 'classid'=>array(), 'codebase'=>array() , 'data' =>array(), 'type'=>array());
    $CONFIG->allowedtags['param'] = array( 'name'=>array(), 'value'=>array());
    $CONFIG->allowedtags['embed'] = array( 'src'=>array(), 'type'=>array(), 'wmode'=>array(), 'width'=>array(), 'height'=>array());
  }else {
    $allowed_tags = get_plugin_setting('izapHTMLawedTags', GLOBAL_IZAP_VIDEOS_PLUGIN);
    $CONFIG->htmlawed_config['elements'] ='object, embed, param, p, img, b, i, ul, li, ol, u, a, s, blockquote, br, strong, em' . (($allowed_tags) ? ', ' . $allowed_tags : '');
  }

  run_function_once('izapSetup_izap_videos');
  $extend_view('css', 'izap_videos/css/default');
  $extend_view('metatags','izap_videos/js/javascript');
  //$extend_view('profile/menu/links','izap_videos/menu');
  $extend_view('groups/right_column', 'izap_videos/gruopVideos', 1);

  // only if enabled by admin
  if(izapIncludeIndexWidget_izap_videos()) {
    $extend_view('index/righthandside', 'izap_videos/customindexVideos');
  }

  // only if enabled by admin
  if(izapTopBarWidget_izap_videos()) {
    $extend_view('elgg_topbar/extend','izap_videos/navBar');
  }

  // finally lets register the object
  register_entity_type('object','izap_videos');
}

/**
 * includes the required file based on the url parameters
 *
 * @param array $page url components
 * @return boolean
 */
function pageHandler_izap_videos($page) {
  if(isloggedin()) {
    set_input('username', get_loggedin_user()->name);
  }
  $action = empty($page[0])?null:$page[0];
  if(!empty($page[2]) && is_numeric($page[2])) {
    $username = $page[1];
    $guid = $page[2];
    set_input('guid',$guid);
    set_input('username', $username);
  }elseif(!empty($page[1]) && is_string($page[1])) {
    $username = $page[1];
    set_input('username',$username);
  }elseif(!empty($page[1]) && is_numeric($page[1])) {
    $guid = $page[1];
    set_input('guid',$guid);
  }

  if($action) {
    izap_load_file(dirname(__FILE__) . '/pages/actions/' . $action . '.php');
  }else {
    izap_load_file(dirname(__FILE__) . '/pages/actions/all.php');
  }
}

/**
 * sets page hadler for the thumbs and video
 *
 * @param array $page
 */
function pageHandler_izap_videos_files($page) {
  set_input('what', $page[0]);
  set_input('videoID', $page[1]);
  set_input('size', $page[2]);
  izap_load_file(dirname(__FILE__) . '/pages/actions/thumbs.php');
}

/**
 * setups the submenus
 *
 * @global <type> $CONFIG
 */
function pageSetup_izap_videos() {
  global $CONFIG;

  // get the page owner
  $pageowner = page_owner_entity();

  // if page owner is user and context is izap_videos
  if($pageowner instanceof ElggUser && get_context() == 'videos') {
    if($pageowner != get_loggedin_user()) {
      add_submenu_item(sprintf(elgg_echo('izap_videos:user'),$pageowner->name), $CONFIG->wwwroot . 'pg/videos/list/' . $pageowner->username , 'USER_IZAPVIDEOS');
      add_submenu_item(sprintf(elgg_echo('izap_videos:userfrnd'),$pageowner->name), $CONFIG->wwwroot . 'pg/videos/friends/' . $pageowner->username , 'USER_IZAPVIDEOS');
      add_submenu_item(sprintf(elgg_echo('izap_videos:user_favorites'),$pageowner->name), $CONFIG->wwwroot . 'pg/videos/favorites/' . $pageowner->username , 'USER_IZAPVIDEOS');
    }

    // for loggedin users only
    if(isloggedin()) {
      if($pageowner instanceof ElggUser) {
        add_submenu_item(elgg_echo('izap_videos:add'), $CONFIG->wwwroot . 'pg/videos/add/'.get_loggedin_user()->username, 'IZAPVIDEOS');
      }
    }
  }

  // for all
  if(get_context() == GLOBAL_IZAP_VIDEOS_PAGEHANDLER) {
    if(isloggedin()) {
      add_submenu_item(sprintf(elgg_echo('izap_videos:videos'),get_loggedin_user()->name), $CONFIG->wwwroot . 'pg/videos/list/' . get_loggedin_user()->username , 'MY_IZAPVIDEOS');
      add_submenu_item(sprintf(elgg_echo('izap_videos:frnd'),get_loggedin_user()->name), $CONFIG->wwwroot . 'pg/videos/friends/' . get_loggedin_user()->username , 'MY_IZAPVIDEOS');
      add_submenu_item(elgg_echo('izap_videos:my_favorites'), $CONFIG->wwwroot . 'pg/videos/favorites/' . get_loggedin_user()->username , 'MY_IZAPVIDEOS');
    }
    add_submenu_item(elgg_echo('izap_videos:all'), $CONFIG->wwwroot . 'pg/videos/all', 'IZAPVIDEOS');
  }

  // if the page owner is group and context is group
  if($pageowner instanceof ElggGroup && (get_context() == 'groups' || get_context() == 'videos') && ($pageowner->izap_videos_enable == 'yes' || empty($pageowner->izap_videos_enable))) {
    if(can_write_to_container(get_loggedin_userid(), $pageowner->guid, 'izap_videos')) {
      add_submenu_item(elgg_echo('izap_videos:addgroupVideo'), $CONFIG->wwwroot . 'pg/videos/add/'.$pageowner->username, 'IZAPVIDEOS');
    }
    add_submenu_item(sprintf(elgg_echo('izap_videos:user'),$pageowner->name), $CONFIG->wwwroot . 'pg/videos/list/'.$pageowner->username, 'IZAPVIDEOS');
  }

  // if the context is admin and is admin logged in
  if(get_context() == 'admin' && isadminloggedin()) {
    add_submenu_item(elgg_echo('izap_videos:adminSettings'), $CONFIG->wwwroot . 'pg/videos/adminSettings/' . get_loggedin_user()->username.'?option=settings', 'IZAPADMIN');
  }
}

function izap_queue_cron($hook, $entity_type, $returnvalue, $params) {
  izapTrigger_izap_videos();
}

// register some actions
register_action('izapAdminSettings', FALSE, dirname(__FILE__) . '/actions/izap_videos/admin/editSettings.php', TRUE);
register_action('izapResetSettings', FALSE, dirname(__FILE__) . '/actions/izap_videos/admin/resetSettings.php', TRUE);
register_action('izapRecycle', FALSE, dirname(__FILE__) . '/actions/izap_videos/admin/recycle.php', TRUE);
register_action('izap-delete-recycle', FALSE, dirname(__FILE__) . '/actions/izap_videos/admin/recycle_delete.php', TRUE);
register_action('izapAddEdit', FALSE, dirname(__FILE__) . '/actions/izap_videos/addEdit.php');
register_action('izapCopy', FALSE, dirname(__FILE__) . '/actions/izap_videos/copy.php');
register_action('izapDelete', FALSE, dirname(__FILE__) . '/actions/izap_videos/delete.php');
register_action('izapResetQueue', FALSE, dirname(__FILE__) . '/actions/izap_videos/admin/reset.php', TRUE);
register_action('izap_videos_add_favorite', FALSE, dirname(__FILE__) . '/actions/izap_videos/favorite_video.php');

// register the main fucntion with the elgg system
register_elgg_event_handler('init', 'system', 'init_izap_videos');
register_elgg_event_handler('pagesetup', 'system', 'pageSetup_izap_videos');
