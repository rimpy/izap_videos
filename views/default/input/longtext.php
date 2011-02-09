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

$mce_lang = $vars['user']->language;
if(!is_file($CONFIG->pluginspath."tinymce/tinymce/jscripts/tiny_mce/langs/$mce_lang.js")) {
  $mce_lang = 'en';
}
?>

<?php
//Tinymce is load when loads this
if(is_plugin_enabled('tinymcebrowser')) {
  $file = $CONFIG->pluginspath.'tinymcebrowser/views/default/input/longtext.php';
}
elseif(is_plugin_enabled('tinymce_adv')) {
  $file = $CONFIG->pluginspath.'tinymce_adv/views/default/input/longtext.php';

}
elseif(is_plugin_enabled('tinymce')) {

  $file = $CONFIG->pluginspath.'tinymce/views/default/input/longtext.php';

  if(is_file($file)) {
    ob_start();
    include($file);
    $ret = ob_get_clean();
    $pos = strpos($ret,"tinyMCE.init");
    $ret = substr($ret,0,$pos) .substr($ret,$pos);

    $pos = strpos($ret,"tinyMCE.init");
    if($vars['noEditor'] != 'yes') {
      //add the plugins to init
      $substr = substr($ret,$pos);
      $limited_substr = substr($substr,0,strpos($substr,"}"));
      if(strpos($limited_substr,'plugins')!==false) {
        $pos1 = $pos + strpos($substr,"plugins");
        $substr = substr($ret,$pos1);
        $pos1 += strpos($substr,'"') +1;
        $substr = substr($ret,$pos1);
        $pos1 += strpos($substr,'"');
        $ret = substr($ret,0,$pos1) .",media". substr($ret,$pos1);
      }
      else {
        $pos1 = $pos + strpos($substr,"{") +1;
        $substr = substr($ret,$pos1);
        $ret = substr($ret,0,$pos1) ."\nplugins : \"media\",\n". substr($ret,$pos1);
      }
      echo $ret;
    }else {
      echo $vars['class'];
      //add the plugins to init
      $substr = substr($ret,$pos);
      $limited_substr = substr($substr,0,strpos($substr,"}"));
      if(strpos($limited_substr,'editor_deselector')!==false) {
        $pos1 = $pos + strpos($substr,"editor_deselector");
        $substr = substr($ret,$pos1);
        $pos1 += strpos($substr,'"') +1;
        $substr = substr($ret,$pos1);
        $pos1 += strpos($substr,'"');
        $ret = substr($ret,0,$pos1) .",mceNoEditor". substr($ret,$pos1);
      }
      else {
        $pos1 = $pos + strpos($substr,"{") +1;
        $substr = substr($ret,$pos1);
        $ret = substr($ret,0,$pos1) ."\neditor_deselector : \"mceNoEditor\",\n". substr($ret,$pos1);
      }
      echo $ret;
    }
  }else {
    echo "<p>Fatal Error while readding longtext.php from tinymce plugin!</p>";
    require($CONFIG->path.'views/default/input/longtext.php');
  }



}else {
  $class = $vars['class'];
  if (!$class) $class = "input-textarea";
  ?>
<textarea class="<?php echo $class; ?>" name="<?php echo $vars['internalname']; ?>" <?php if (isset($vars['internalid'])) echo "id=\"{$vars['internalid']}\""; ?> <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> <?php echo $vars['js']; ?>><?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?></textarea>
<script type="text/javascript">
  //  function removeEditor(){
  //    toggleEditor('izapVideoEmbed');
  //   }
  //
  //$(document).ready(function(){
  //setTimeout(toggleEditor('izapVideoEmbed'), 1);
  //});
</script>
  <?php
}