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

class IzapVideos extends ElggFile {
  private $IZAPSETTINGS;

  public function __construct($guid = null) {
    parent::__construct($guid);

    // set some initial values so that old videos can work
    if(empty($this->videosrc)) {
      $this->videosrc = $this->IZAPSETTINGS->filesPath . 'file/' . $this->guid . '/' . friendly_title($this->title) . '.flv';
    }

    // sets the defalut value for the old videos, if not set yet
    if(empty($this->converted)) {
      $this->converted = 'yes';
    }
  }

  protected function initialise_attributes() {
    global $IZAPSETTINGS;
    parent::initialise_attributes();
    $this->attributes['subtype'] = 'izap_videos';
    $this->IZAPSETTINGS = $IZAPSETTINGS;
  }

  /**
   * takes input and type of input and sends back the required parameters to save
   * a videos
   *
   * @param string $input video url, video file or video embed code
   * @param string $type url, file, embed
   * @return object
   */
  public function input($input, $type) {
    switch ($type) {
      case 'url':
        return $this->readUrl($input);
        break;
      case 'file':
        return $this->processFile($input);
        break;
      case 'embed':
        return $this->embedCode($input);
        break;
      default:
        return false;
        break;
    }
  }

  /**
   * used to read the url and process feed
   *
   * @param url $url url of the video site
   * @return object
   */
  protected function readUrl($url) {
    $urlFeed = new UrlFeed();
    $feed = $urlFeed->setUrl($url);
    return $feed;
  }

  /**
   * used to process the video file
   * @param string $file upload file name
   * @return object
   */
  protected function processFile($file) {

    $returnValue =  new stdClass();
    $returnValue->type = 'uploaded';
    $fileName = $_FILES[$file['mainArray']]['name'][$file['fileName']];
    $error = $_FILES[$file['mainArray']]['error'][$file['fileName']];
    $tmpName = $_FILES[$file['mainArray']]['tmp_name'][$file['fileName']];
    $type = $_FILES[$file['mainArray']]['type'][$file['fileName']];
    $size = $_FILES[$file['mainArray']]['size'][$file['fileName']];

    // if error
    if($error > 0) {
      return 104;
    }

    // if file is of zero size
    if($size == 0) {
      return 105;
    }


    // check supported video type
    if(!izapSupportedVideos_izap_videos($fileName)) {
      return 106;
    }

    // check supported video size
    if(!izapCheckFileSize_izap_videos($size)) {
      return 107;
    }

    // upload the tmp file
    $newFileName = izapGetFriendlyFileName_izap_videos($fileName);
    $this->setFilename('tmp/' . $newFileName);
    $this->open("write");
    $this->write(file_get_contents($tmpName));
    $returnValue->tmpFile = $this->getFilenameOnFilestore();

    // take snapshot of the video
    $image = new izapConvert($returnValue->tmpFile);
    if($image->photo()) {
      $retValues = $image->getValues(TRUE);
      if($retValues['imagename'] != '' && $retValues['imagecontent'] != '') {
        $this->setFilename('izap_videos/uploaded/orignal_' . $retValues['imagename']);
        $this->open("write");
        if($this->write($retValues['imagecontent'])) {
          $orignal_file_path = $this->getFilenameOnFilestore();

          $thumb = get_resized_image_from_existing_file($orignal_file_path, 120, 90);
          $this->setFilename('izap_videos/uploaded/' . $retValues['imagename']);
          $this->open("write");
          $this->write($thumb);

          $this->close();
          $returnValue->orignal_thumb = "izap_videos/uploaded/orignal_" . $retValues['imagename'];
          $returnValue->thumb = 'izap_videos/uploaded/' . $retValues['imagename'];
          // Defining new preview attribute of standard object
          $returnValue->preview_400 = 'izap_videos/uploaded/preview_400';
          $returnValue->preview_200 = 'izap_videos/uploaded/preview_200';
        }
      }
    }

    // check if it is flv, then dont send it to queue
    if(izap_get_file_extension($returnValue->tmpFile) == 'flv') {
      $file_name = 'izap_videos/uploaded/' . $newFileName;

      $this->setFilename($file_name);
      $this->open("write");
      $this->write(file_get_contents($returnValue->tmpFile));

      $this->converted = 'yes';
      $this->videofile = $file_name;
      $this->orignalfile = $file_name;
      $returnValue->is_flv = 'yes';
      // remove the tmp file
      @unlink($returnValue->tmpFile);
    }
    return $returnValue;
  }

  /**
   * process the embed code
   *
   * @param HTML $code embed code
   * @return object
   */
  protected function embedCode($code) {
    $returnValue =  new stdClass();
    $returnValue->type = 'embed';
    $returnValue->videoSrc = $code;

    return $returnValue;
  }

  /**
   * gets the video player according to the video type
   *
   * @param int $width width of video player
   * @param int $height height of video player
   * @param int $autoPlay autoplay option (1 | 0)
   * @param string $extraOptions extra options if available
   * @return HTML complete player code
   */
  public function getPlayer($width = 670, $height = 400, $autoPlay = 0, $extraOptions = '') {
    global $CONFIG;
    $html = '';

    if (filter_var($this->videosrc, FILTER_VALIDATE_URL)) {
      switch ($this->videotype) {
        case 'youtube':
          $html = "<object width=\"$width\" height=\"$height\"><param name=\"movie\" value=\"{$this->videosrc}&hl=en&fs=1&autoplay={$autoPlay}\"></param><param name=\"wmode\" value=\"transparent\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"{$this->videosrc}&hl=en&fs=1&autoplay={$autoPlay} \" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"$width\" height=\"$height\" wmode=\"transparent\"></embed></object>";
          break;
        case 'vimeo':
          $html = "<object width=\"$width\" height=\"$height\"><param name=\"wmode\" value=\"transparent\"></param><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"{$this->videosrc}&amp;autoplay={$autoPlay}\" /><embed src=\"{$this->videosrc}&amp;autoplay={$autoPlay}\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"$width\" height=\"$height\" wmode=\"transparent\"></embed></object>";
          break;
        case 'veoh':
          $html = "<embed src=\"{$this->videosrc}&videoAutoPlay={$autoPlay}\" allowFullScreen=\"true\" width=\"$width\" height=\"$height\" bgcolor=\"#FFFFFF\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" wmode=\"transparent\"></embed>";
          break;
        case 'uploaded':
          if ($this->converted == 'yes') {
            $border_color1 = izapAdminSettings_izap_videos('izapBorderColor1');
            $border_color2 = izapAdminSettings_izap_videos('izapBorderColor2');
            $border_color3 = izapAdminSettings_izap_videos('izapBorderColor3');

            if (!empty($border_color3))
              $extraOptions .= '&btncolor=0x' . $border_color3;
            if (!empty($border_color1))
              $extraOptions .= '&accentcolor=0x' . $border_color1;
            if (!empty($border_color2))
              $extraOptions .= '&txtcolor=0x' . $border_color2;
            $html = "
           <object width='" . $width . "' height='" . $height . "' id='flvPlayer'>
            <param name='allowFullScreen' value='true'>
             <param name='allowScriptAccess' value='always'>
            <param name='movie' value='" . $this->IZAPSETTINGS->playerPath . "?movie=" . $this->videosrc . $extraOptions . "&volume=30&autoload=on&autoplay=on&vTitle=" . $this->title . "&showTitle=yes' >
            <embed src='" . $this->IZAPSETTINGS->playerPath . "?movie=" . $this->videosrc . $extraOptions . "&volume=30&autoload=on&autoplay=on&vTitle=" . $this->title . "&showTitle=yes' width='" . $width . "' height='" . $height . "' allowFullScreen='true' type='application/x-shockwave-flash' allowScriptAccess='always' wmode='transparent'>
           </object>";
          }
          else {
            $html = elgg_echo('izap_videos:processed');
          }
          break;
        case 'embed':
        case 'others':
          $html = izapGetReplacedHeightWidth_izap_videos($height, $width, $this->videosrc);
          break;
      }
    } else {
      $html = izapGetReplacedHeightWidth_izap_videos($height, $width, $this->videosrc);
    }
    return $html;
  }

  /**
   * returns the thumbnail for the video
   *
   * @param boolean $pathOnly if we want the img src only or full <img ... /> tag
   * @param array $attArray attributes for the <img /> tag
   * @return HTML <img /> tag or image src
   */
  public function getThumb($pathOnly = false, $attArray = array(), $play_icon = false) {
    GLOBAL $IZAPSETTINGS;
    $html = '';
    $attString = '';
    $imagePath = $this->IZAPSETTINGS->filesPath . 'image/' . $this->guid . '/' . friendly_title($this->title) . '.jpg';
    if(count($attArray) > 0) {
      foreach ($attArray as $att => $value) {
        $attString .= ' '.$att.'="'.$value.'" ';
      }
    }
    if($pathOnly) {
      $html = $imagePath;
    }else {
      $html = '<div style="position: relative;">';
      $html .= '<img src="'.$imagePath.'"  '.$attString.' />';
      $html .= '<span class="izap_play_icon"><img src="'.$IZAPSETTINGS->graphics.'c-play.png" /></span>';
      $html .= '</div>';
    }

    return $html;
  }

  public function getOrignalThumb() {
    return $this->IZAPSETTINGS->filesPath . 'image/' . $this->guid . '/orignal/' . friendly_title($this->title) . '.jpg';
  }
  public function getAjaxedThumb($attArray = array()) {
    GLOBAL $IZAPSETTINGS;
    $id = $this->guid;
    $html = '<div class="izap_ajaxed_thumb_div" id="load_video_'.$id.'" style="position: relative;height: 90px ; width: 90px;" >
      <a href="'.$this->getURL().'" rel="'.$id.':250x200" class="izap_ajaxed_thumb">		
        <img src="'.$this->getThumb(TRUE, $attArray).'" style="max-height:90px; max-width: 90px;" />
		<span class="izap_play_icon"><img src="'.$IZAPSETTINGS->graphics.'c-play.png" /></span>
      </a>
      </div>';

    return $html;
  }

  /**
   * function to return the icon path
   * @uses getThumb()
   * @return url
   */
  public function getIcon($ajaxed = FALSE) {
    return $this->getThumb(TRUE);
  }

  public function getURL() {
    global $CONFIG;
    return $CONFIG->wwwroot . 'pg/videos/play/'.get_entity($this->container_guid)->username.'/' . $this->guid . '/' . friendly_title($this->title);
  }

  /**
   * updates the video views
   */
  public function updateViews() {
    if($this->converted == 'yes') {
      izapGetAccess_izap_videos();
      $this->views = ((int)$this->views + 1);
      izapRemoveAccess_izap_videos();
    }
  }

  /**
   * returns the video views
   *
   * @return int video views
   */
  public function getViews() {
    return (int)$this->views;
  }

  /**
   * checks if the video can be copied
   *
   * @return boolean
   */
  public function canCopy() {
    if($this->owner_guid != get_loggedin_userid()
            && $this->converted == 'yes'
            && isloggedin()
    ) {
      return TRUE;
    }

    // default
    return FALSE;
  }

  /**
   * returns the full video attributes for copying the video
   *
   * @return object
   */
  public function getAttributes() {
    $attrib = new stdClass();
    $attrib->guid = $this->guid;
    $attrib->title = $this->title;
    $attrib->owner_guid = $this->owner_guid;
    $attrib->container_guid = $this->container_guid;
    $attrib->description = $this->description;
    $attrib->access_id = $this->access_id;
    $attrib->tags = $this->tags;
    $attrib->views = $this->views;
    $attrib->videosrc = $this->videosrc;
    $attrib->videotype = $this->videotype;
    $attrib->imagesrc = $this->imagesrc;
    $attrib->videotype_site = $this->videotype_site;
    $attrib->videotype_id = $this->videotype_id;
    $attrib->converted = $this->converted;
    $attrib->videofile = $this->videofile;
    $attrib->orignalfile = $this->orignalfile;
    return $attrib;
  }

  public function getRelatedVideos($max_limit = 5) {
    $tags = $this->tags;
    if(!is_array($tags) && !empty ($tags)) {
      $tags = array($tags);
    }

    $options['type'] = $this->getType();
    $options['subtype'] = $this->getSubtype();

    if(sizeof($tags)) {
      $total_tags = count($tags);
      $per_tag_limit = (int)((int)$max_limit / (int)$total_tags);
      $per_tag_limit = ($per_tag_limit) ? $per_tag_limit+1 : 1;
      foreach($tags as $tag) {
        if((real)get_version(true) <= 1.6) {
          $entities[] = get_entities_from_metadata('tags', $tag, $options['type'], $options['subtype'], 0, $per_tag_limit);
        }else {
          $options['metadata_name'] = 'tags';
          $options['metadata_value'] = $tag;
          $entities[] = elgg_get_entities_from_metadata($options);
        }
      }
    }

    if($entities) {
      foreach($entities as $videos) {
        foreach ($videos as $video) {
          if($video->guid != $this->guid) {
            $return[$video->guid] = $video;
          }
        }
      }
    }

    $return = array_chunk($return, $max_limit);
    return $return[0];
  }

  /**
   * deletes a video, override for the parent delete
   *
   * @return boolean
   */
  public function delete() {
    global $CONFIG;

    if($this->videotype == 'uploaded' && $this->converted == 'no') {
      // delete entity from queue and trash with related media
      $queue_object = new izapQueue();
      $queue_object->delete_from_trash($this->guid, true);
      $queue_object->delete($this->guid, true);
    }

    $imagesrc = $this->imagesrc;
    $filesrc = $this->videofile;
    $ofilesrc = $this->orignalfile;
    $orignal_thumb = $this->orignal_thumb;

    //delete entity from elgg db and corresponding files if exist
    $this->setFilename($imagesrc);
    $image_file = $this->getFilenameOnFilestore();
    file_exists($image_file) && @unlink($image_file);

    $this->setFilename($filesrc);
    $video_file = $this->getFilenameOnFilestore();
    file_exists($video_file) && @unlink($video_file);

    $this->setFilename($ofilesrc);
    $orignal_file = $this->getFilenameOnFilestore();
    file_exists($orignal_file) && @unlink($orignal_file);

    $this->setFilename($orignal_thumb);
    $orignal_thumb_file = $this->getFilenameOnFilestore();
    file_exists($orignal_thumb_file) && @unlink($orignal_thumb_file);

    return delete_entity($this->guid, TRUE);
  }

  /**
   * returns the url for the owner video list
   *
   * @global object $CONFIG
   * @return URL
   */
  public function getOwnerUrl() {
    global $CONFIG;
    return $CONFIG->wwwroot . 'pg/videos/list/' . $this->getOwnerEntity()->username . '/';
  }
}


/**
 * returns the file name, that ffmpeg can operate
 *
 * @param string $fileName file name
 * @return string all formated file name
 */
function izapGetFriendlyFileName_izap_videos($fileName) {
  global $CONFIG;

  $new_name .= izap_get_video_name_prefix();
  $new_name .= time() . '_';
  $new_name .= preg_replace('/[^A-Za-z0-9\.]+/','_',$fileName);
  return $new_name;
}

/**
 * this function checks the supported videos
 * @global <type> $CONFIG
 * @param string $videoFileName video name with extension
 * @return boolean TRUE if supported else FALSE
 */
function izapSupportedVideos_izap_videos($videoFileName) {
  global $IZAPSETTINGS;
  $supportedFormats = $IZAPSETTINGS->allowedExtensions;
  $extension = izap_get_file_extension($videoFileName);
  if(in_array($extension, $supportedFormats))
    return TRUE;

  return FALSE;
}

/**
 * this function will check the max upload limit for file
 *
 * @param integer $fileSize in Mb
 * @return boolean true if everything is ok else false
 */
function izapCheckFileSize_izap_videos($fileSize) {
  $maxFileSize = (int) izapAdminSettings_izap_videos('izapMaxFileSize');
  $maxSizeInBytes = $maxFileSize*1024*1024;

  if($fileSize > $maxSizeInBytes)
    return FALSE;

  return TRUE;
}

/**
 * changes the height and width of the video player
 *
 * @param integer $newHeight height
 * @param integer $newWidth width
 * @param string $object video player
 * @return HTML video player
 */
function izapGetReplacedHeightWidth_izap_videos($newHeight, $newWidth, $object) {
  $videodiv = preg_replace('/width=["\']\d+["\']/', 'width="' . $newWidth . '"', $object);
  $videodiv = preg_replace('/width:\d+/', 'width:'.$newWidth, $videodiv);
  $videodiv = preg_replace('/height=["\']\d+["\']/', 'height="' . $newHeight . '"', $videodiv);
  $videodiv = preg_replace('/height:\d+/', 'height:'.$newHeight, $videodiv);

  return $videodiv;
}

function izap_get_file_extension($filename) {
  if(empty($filename)) {
    return false;
  }

  return strtolower(end(explode('.', $filename)));
}