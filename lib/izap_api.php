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


/**
 * class for providing the api to fetch and convert video, that other plugins can
 * use to enable the feature of adding video from them.
 * (CURRENTLY SUPPORTING URLs ONLY)
 * just need to include the small code and you will get the video player
 * eg.
 *
 * if(is_plugin_enabled('izap_videos')){
 *    $video = new IZAPVideoApi($input); // input is URL or FILEPATH
 *    $return = $video->getFeed($width, $height);
 *  }
 *
 *
 */

class IZAPVideoApi {
  private $input_object;
  public function __construct($input = '') {
    if(!empty($input)) {
      $this->input_object = $input;
    }
  }

  /**
   * converts the video
   *
   * @return <type>
   */
  public function convertVideo() { // experimental
    if(!izapSupportedVideos_izap_videos($this->input_object)) {
      return elgg_echo('izap_videos:error:code:106');
    }

    $convert_video = new izapConvert($this->input_object);
    if($convert_video->photo()) {
      if($convert_video->izap_video_convert()) {
        return $convert_video->getValuesForAPI();
      }
    }

    // if nothing is processes so far
    return FALSE;
  }

  /**
   * returns the video player code, if the input is URL
   *
   * @param int $width width of video player
   * @param int $height height of video playe
   * @param int $autoPlay autocomplete option
   * @return HTML player code
   */
  public function getFeed($width = 640, $height = 385, $autoPlay = 0) {
    $get_url_feed = new IzapVideos();
    $feed = $get_url_feed->input($this->input_object, 'url');
    $get_url_feed->videosrc = $feed->videoSrc;
    $get_url_feed->converted = 'yes';
    $player = $get_url_feed->getPlayer($width, $height, $autoPlay);
    return $player;
  }
}
