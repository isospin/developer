<?php

require $REX['INCLUDE_PATH'].'/layout/top.php';

echo rex_title($I18N->msg('developer_name'));

if (rex_post('func', 'string') == 'update') 
{
  require_once $REX['INCLUDE_PATH'] .'/addons/developer/classes/class.rex_developer_manager.inc.php';

  $settings = (array)rex_post('settings','array',array());
  $settings['dir'] = trim($settings['dir'],'/');
  $msg = '';
  
  if ($settings['templates'] || $settings['modules'] || $settings['actions'])
  {
    $msg = rex_developer_manager::checkDir($settings['dir']);
    if ($msg != '')
    {
      echo rex_warning($msg);
    }
  }
  if ($msg == '')
  {
    $old_dir = $REX['ADDON']['settings']['developer']['dir'];
    if (rex_developer_manager::saveSettings($settings))
    {
      echo rex_info($I18N->msg('developer_saved'));
      if ($old_dir != $settings['dir'] || (!$settings['templates'] && !$settings['modules'] && !$settings['actions']))
      {
        rex_developer_manager::deleteDir($old_dir);
      }
    }
    else
    {
      echo rex_warning($I18N->msg('developer_error'));
    }
  }
}

$templates = '';
if ($REX['ADDON']['settings']['developer']['templates']=="1")
  $templates = ' checked="checked"';
$modules = '';
if ($REX['ADDON']['settings']['developer']['modules']=="1")
  $modules = ' checked="checked"';
$actions = '';
if ($REX['ADDON']['settings']['developer']['actions']=="1")
  $actions = ' checked="checked"';

echo '

<div class="rex-addon-output">

<h2 class="rex-hl2">'. $I18N->msg('developer_settings') .'</h2>

<div class="rex-area">
  <div class="rex-form">
	
  <form action="index.php?page=developer" method="post">

		<fieldset class="rex-form-col-1">
      <div class="rex-form-wrapper">
			  <input type="hidden" name="func" value="update" />
        
        <div class="rex-form-row">
          <p class="rex-form-checkbox rex-form-label-right">
            <input type="hidden" name="settings[templates]" value="0" />
            <input class="rex-form-checkbox" type="checkbox" id="templates" name="settings[templates]" value="1"'.$templates.' />
            <label for="templates">'.$I18N->msg('developer_templates').'</label>
          </p>
        </div>
        
        <div class="rex-form-row">
          <p class="rex-form-checkbox rex-form-label-right">
            <input type="hidden" name="settings[modules]" value="0" />
            <input class="rex-form-checkbox" type="checkbox" id="modules" name="settings[modules]" value="1"'.$modules.' />
            <label for="modules">'.$I18N->msg('developer_modules').'</label>
          </p>
        </div>
        
        <div class="rex-form-row">
          <p class="rex-form-checkbox rex-form-label-right">
            <input type="hidden" name="settings[actions]" value="0" />
            <input class="rex-form-checkbox" type="checkbox" id="actions" name="settings[actions]" value="1"'.$actions.' />
            <label for="actions">'.$I18N->msg('developer_actions').'</label>
          </p>
        </div>
        
        <div class="rex-form-row">
          <p class="rex-form-text">
            <label for="dir">'.$I18N->msg('developer_dir').':</label>
            /redaxo/include/ <input type="text" id="dir" name="settings[dir]" value="'.$REX['ADDON']['settings']['developer']['dir'].'" />  
          </p>
        </div>
        
        <div class="rex-form-row">
				  <p>
            <input type="submit" class="rex-form-submit" name="FUNC_UPDATE" value="'.$I18N->msg('developer_save').'" />
          </p>
			  </div>
        
			</div>
    </fieldset>
  </form>
  </div>
</div>

</div>
  ';

require $REX['INCLUDE_PATH'].'/layout/bottom.php';