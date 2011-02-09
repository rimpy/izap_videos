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

global $IZAPSETTINGS;

// tabs array
$activated_options = izapGetVideoOptions_izap_videos();
if(!in_array('ONSERVER', $activated_options)) {
  $options = array('settings', 'server_analysis');
}else {
  $options = array(
          'settings',
          'queue_status',
          'recycle_bin',
          'server_analysis',
  );
}

$selectedTab = get_input('option');
if(empty($selectedTab)) {
  $selectedTab = 'settings';
}
?>
<div class="contentWrapper">

  <div id="elgg_horizontal_tabbed_nav">
    <ul>
      <?php
      foreach ($options as $option) :
        ?>
      <li class="<?php echo ($option == $selectedTab) ? 'selected' : ''; ?>">
        <a href="?option=<?php echo $option?>">
            <?php
            echo elgg_echo('izap_videos:adminSettings:tabs_' . $option);
            if(preg_match("/queue_status|recycle_bin/", $option)) {
              $queue_object = new izapQueue();
              echo ($option == 'queue_status') ? ' (' . $queue_object->count() . ')' : '';
              echo ($option == 'recycle_bin') ? ' (' . $queue_object->count_trash() . ')' : '';
            }
            ?>
        </a>
      </li>
      <?php
      endforeach;
      ?>
    </ul>
  </div>

  <?php
  if($selectedTab == 'settings') {
    ?>

  <form action="<?php echo $vars['url']?>action/izapAdminSettings" method="POST">

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapVideoOptions');?>
        <br />
          <?php
          echo elgg_view('input/checkboxes', array(
          'internalname' => 'izap[izapVideoOptions]',
          'options' => array(
                  elgg_echo('izap_videos:adminSettings:offServerVideos') => 'OFFSERVER',
                  elgg_echo('izap_videos:adminSettings:onServerVideos') => 'ONSERVER',
                  elgg_echo('izap_videos:adminSettings:embedCode') => 'EMBED',
          ),
          'class' => 'izap_videos_checkboxes',
          'value' => izapAdminSettings_izap_videos('izapVideoOptions', array('OFFSERVER', 'EMBED')),
          ));
          ?>
      </label>
    </p>

    <p>
      <label>
          <?php printf(elgg_echo('izap_videos:adminSettings:APIKEY'),strtolower($_SERVER['HTTP_HOST']));?>
        <br />
          <?php
          echo elgg_view('input/text', array(
          'internalname' => 'izap[izapAPIKey]',
          'value' => izapAdminSettings_izap_videos('izapAPIKey'),
          ));
          ?>
      </label>
      <span class="izap_info_text">
          <?php echo elgg_echo('izap_videos:adminSettings:info:register_api');?>
      </span>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapPhpInterpreter');?>
        <br />
          <?php
          echo elgg_view('input/text', array(
          'internalname' => 'izap[izapPhpInterpreter]',
          'value' => izapAdminSettings_izap_videos('izapPhpInterpreter', (izapIsWin_izap_videos()) ? '' : '/usr/bin/php'),
          ));
          ?>
      </label>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapVideoCommand');?>
        <br />

          <?php
          echo elgg_view('input/text', array(
          'internalname' => 'izap[izapVideoCommand]',
          'value' => izapAdminSettings_izap_videos(
          'izapVideoCommand',
          (izapIsWin_izap_videos()) ?
          $IZAPSETTINGS->ffmpegPath . ' -y -i [inputVideoPath] -vcodec libx264 -vpre '.$IZAPSETTINGS->ffmpegPresetPath.' -b 300k -bt 300k -ar 22050 -ab 48k -s 400x400 [outputVideoPath]'
          :
          '/usr/bin/ffmpeg -y -i [inputVideoPath] [outputVideoPath]'
          ),
          ));
          ?>
      </label>
      <span class="izap_info_text">
          <?php echo elgg_echo('izap_videos:adminSettings:info:convert-command');?>
      </span>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapVideoThumb');?>
        <br />

          <?php
          echo elgg_view('input/text', array(
          'internalname' => 'izap[izapVideoThumb]',
          'value' => izapAdminSettings_izap_videos(
          'izapVideoThumb',
          (izapIsWin_izap_videos()) ?
          $IZAPSETTINGS->ffmpegPath . ' -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]'
          :
          '/usr/bin/ffmpeg -y -i [inputVideoPath] -vframes 1 -ss 00:00:10 -an -vcodec png -f rawvideo -s 320x240 [outputImage]'
          ),
          ));
          ?>
      </label>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapHTMLawedTags');?>
        <br />

          <?php
          echo elgg_view('input/text', array(
          'internalname' => 'izap[izapHTMLawedTags]',
          'value' => izapAdminSettings_izap_videos('izapHTMLawedTags'),
          ));
          ?>
      </label>
      <span class="izap_info_text">
          <?php echo elgg_echo('izap_videos:adminSettings:info:izapHTMLawedTags');?>
      </span>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapBarColor');?>
        <br />

          <?php
          echo elgg_view('input/text', array(
          'internalname' => 'izap[izapBorderColor1]',
          'value' => izapAdminSettings_izap_videos('izapBorderColor1'),
          ));
          ?>
      </label>
      <span class="izap_info_text">
          <?php echo elgg_echo('izap_videos:adminSettings:info:bg-color');?>
      </span>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapTextColor');?>
        <br />

          <?php
          echo elgg_view('input/text', array(
          'internalname' => 'izap[izapBorderColor2]',
          'value' => izapAdminSettings_izap_videos('izapBorderColor2'),
          ));
          ?>
      </label>
      <span class="izap_info_text">
          <?php echo elgg_echo('izap_videos:adminSettings:info:bg-color');?>
      </span>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapButtoncolor');?>
        <br />

          <?php
          echo elgg_view('input/text', array(
          'internalname' => 'izap[izapBorderColor3]',
          'value' => izapAdminSettings_izap_videos('izapBorderColor3'),
          ));
          ?>
      </label>
      <span class="izap_info_text">
          <?php echo elgg_echo('izap_videos:adminSettings:info:bg-color');?>
      </span>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izap_cron_time');?>
        <br />

          <?php
          if(is_plugin_enabled('crontrigger')) {
            echo elgg_view('input/pulldown', array(
            'internalname' => 'izap[izap_cron_time]',
            'options_values' => array(
                    'minute' => elgg_echo('izap_videos:adminSettings:minute'),
                    'fiveminute' => elgg_echo('izap_videos:adminSettings:fiveminute'),
                    'fifteenmin' => elgg_echo('izap_videos:adminSettings:fifteenmin'),
                    'halfhour' => elgg_echo('izap_videos:adminSettings:halfhour'),
                    'hourly' => elgg_echo('izap_videos:adminSettings:hourly'),
                    'none' => elgg_echo('izap_videos:adminSettings:cron_off'),
            ),
            'value' => izapAdminSettings_izap_videos('izap_cron_time', 'hourly', FALSE),
            ));
          }else {
            echo elgg_echo('izap_videos:adminSettings:cron_not_installed');
          }
          ?>
      </label>
    </p>

    <p>
      <label>
        <?php echo elgg_echo('izap_videos:adminSettings:izap_display_page');?>
        <br />

        <?php
          echo elgg_view('input/radio', array(
          'internalname' => 'izap[izap_display_page]',
          'options' => array(
                  elgg_echo('izap_videos:adminSettings:izap_default_page') => 'default',
                  elgg_echo('izap_videos:adminSettings:izap_left_column_page') => 'left',
                  elgg_echo('izap_videos:adminSettings:izap_full_page') => 'full',
          ),
          'value' => izapAdminSettings_izap_videos('izap_display_page', 'default'),
          ));
          ?>
      </label>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapMaxFileSize');?>
        <br />

          <?php
          echo elgg_view('input/text', array(
          'internalname' => 'izap[izapMaxFileSize]',
          'value' => izapAdminSettings_izap_videos('izapMaxFileSize', '5'),
          ));
          ?>
      </label>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapKeepOriginal');?>
        <br />

          <?php
          echo elgg_view('input/checkboxes', array(
          'internalname' => 'izap[izapKeepOriginal]',
          'options' => array(
                  elgg_echo('izap_videos:adminSettings:keep-original') => 'YES',
          ),
          'value' => izapAdminSettings_izap_videos('izapKeepOriginal', 'YES', FALSE, TRUE),
          ));
          ?>
      </label>
      <span class="izap_info_text">
          <?php echo elgg_echo('izap_videos:adminSettings:info:izapKeepOriginal');?>
      </span>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapTopBarWidget');?>
        <br />

          <?php
          echo elgg_view('input/checkboxes', array(
          'internalname' => 'izap[izapTopBarWidget]',
          'options' => array(
                  elgg_echo('izap_videos:adminSettings:addOnTopBar') => 'YES',
          ),
          'value' => izapAdminSettings_izap_videos('izapTopBarWidget', 'YES', FALSE, TRUE),
          ));
          ?>
      </label>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:tag_cloud');?>
        <br />

          <?php
          echo elgg_view('input/checkboxes', array(
          'internalname' => 'izap[izapTagCloud]',
          'options' => array(
                  elgg_echo('izap_videos:adminSettings:yes') => 'YES',
          ),
          'value' => izapAdminSettings_izap_videos('izapTagCloud', 'YES', FALSE, TRUE),
          ));
          ?>
      </label>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapIndexPageWidget');?>
        <br />

          <?php
          echo elgg_view('input/checkboxes', array(
          'internalname' => 'izap[izapIndexPageWidget]',
          'options' => array(
                  elgg_echo('izap_videos:adminSettings:addOnHomePage') => 'YES',
          ),
          'value' => izapAdminSettings_izap_videos('izapIndexPageWidget', 'YES', FALSE, TRUE),
          ));
          ?>
      </label>
    </p>

    <p>
      <label>
          <?php echo elgg_echo('izap_videos:adminSettings:izapGiveUsCredit');?>
        <br />

          <?php
          echo elgg_view('input/checkboxes', array(
          'internalname' => 'izap[izapGiveUsCredit]',
          'options' => array(
                  elgg_echo('izap_videos:adminSettings:giveUsCredit') => 'YES',
          ),
          'value' => izapAdminSettings_izap_videos('izapGiveUsCredit', 'YES', FALSE, TRUE),
          ));
          ?>
      </label>
      <span class="izap_info_text">
          <?php echo elgg_echo('izap_videos:adminSettings:info:give-credit');?>
      </span>
    </p>

      <?php
      echo elgg_view('input/securitytoken');
      echo elgg_view('input/submit', array(
      'value' => elgg_echo('izap_videos:adminSettings:save'),
      ));
      ?>
  </form>

  <p align="right">
    <a href="<?php echo is_callable('elgg_add_action_tokens_to_url')?elgg_add_action_tokens_to_url($vars['url'] . 'action/izapResetSettings'):$vars['url'] . 'action/izapResetSettings'; ?>" class="link_to_button">
        <?php echo elgg_echo('izap_videos:adminSettings:resetSettings');?>
    </a>
  </p>
    <?php }else {
    echo elgg_view('izap_videos/admin/' . $selectedTab);
  }
  ?>
</div>