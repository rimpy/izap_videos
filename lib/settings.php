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
///
// get global




return array(
        'path'=>array(
                'www'=>array(
                        'page' => $CONFIG->wwwroot . 'pg/' . GLOBAL_IZAP_VIDEOS_PAGEHANDLER . '/',
                        'images' => $CONFIG->wwwroot . 'mod/'.GLOBAL_IZAP_VIDEOS_PLUGIN.'/_graphics/',
                ),
                'dir'=>array(
                        'plugin' => dirname(dirname(__FILE__)) . '/',
                        'images' => dirname(dirname(__FILE__)) . '/_graphics/',
                        'actions' => $CONFIG->pluginspath. GLOBAL_IZAP_VIDEOS_PLUGIN . '/actions/',
                        'lib' => dirname(__FILE__) . '/',
                        'views'=>array(
                                'home'=> GLOBAL_IZAP_VIDEOS_PLUGIN . '/',
                                'forms'=> GLOBAL_IZAP_VIDEOS_PLUGIN . '/forms/',
                                'file' => GLOBAL_IZAP_VIDEOS_PLUGIN . '/files/',
                                'icon' => GLOBAL_IZAP_VIDEOS_PLUGIN . '/files/icon/',
                                'view'=> GLOBAL_IZAP_VIDEOS_PLUGIN . '/files/view/',
                                'river'=> 'river/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/',
                                'widget' => 'widgets/' . GLOBAL_IZAP_VIDEOS_PLUGIN . '/',
                        ),
                        'pages' => dirname(dirname(__FILE__)).'/pages/',
                ),
        ),

        'plugin'=>array(
                'name'=>GLOBAL_IZAP_VIDEOS_PLUGIN,
                'title'=>"iZAP-Videos",
                'menu'=>array(
                        'pg/'.GLOBAL_IZAP_VIDEOS_PAGEHANDLER.'/' => array(
                                'title' => 'videos',
                                'public' => TRUE
                        ),
                ),

                'widget' =>  array(
                        'izap_videos' => array(
                                'name' => elgg_echo('izap_videos:videos'),
                                'description' => elgg_echo('izap_videos:widget'),
                        ),
                        'video_activities' => array(
                                'name' => elgg_echo('izap_videos:widget:video_activities'),
                                'description' => elgg_echo('izap_videos:widget:video_activities:info'),
                        ),
                ),
        ),
);