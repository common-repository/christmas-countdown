<?php
/*
Plugin Name: Christmas Countdown
Plugin URI: http://ChristmasCountdownClock.com/
Description: A nice countdown to christmas.
Author: ChristmasCountdownClock.com
Version: 1.1.5
Author URI: http://ChristmasCountdownClock.com/
*/

/*  Copyright 2009  wildblogger  (email : wb@christmas0.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function christmas_countdown_widget($args) {
        extract($args);

        $swf = 'cc2.swf';
        $version = get_option('christmas_countdown_version');
        if ($version == 'dark')
            $swf = 'cc2_dark.swf';
        
        $width=get_option('christmas_countdown_width');
        $wrap=get_option('christmas_countdown_wrap');
        if (!is_numeric($width)){
            $width = 200;
        }
        $height = round($width/3);

        $timerTitle=get_option('christmas_countdown_title');
        $footerText=get_option('christmas_countdown_footer_text');
        $daysCaption=get_option('christmas_countdown_days_text');
        $hoursCaption=get_option('christmas_countdown_hours_text');
        $minsCaption=get_option('christmas_countdown_mins_text');
        $secsCaption=get_option('christmas_countdown_secs_text');
        $padding=get_option('christmas_countdown_padding');

        $flashVars = '?';
        if (!empty($timerTitle))
                $flashVars .= "&timerCaption=".urlencode($timerTitle);
        if (!empty($daysCaption))
                $flashVars .= "&daysCaption=".urlencode($daysCaption);
        if (!empty($hoursCaption))
                $flashVars .= "&hoursCaption=".urlencode($hoursCaption);
        if (!empty($minsCaption))
                $flashVars .= "&minsCaption=".urlencode($minsCaption);
        if (!empty($secsCaption))
                $flashVars .= "&secsCaption=".urlencode($secsCaption);

        if ($flashVars == '?')
            $flashVars = '';

        if (empty($footerText))
                $footerText = 'Christmas Countdown';

        if ($wrap) echo $before_widget;
        if ($wrap) echo $before_title;
        if ($wrap) echo "&nbsp;";
        if ($wrap) echo $after_title;

        echo '<div style="text-align:center;width:'.$width.'px;margin:0;padding:'.$padding.';">';
        echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$width.'" height="'.$height.'">';
        echo '<param name="movie" value="http://static.christmas0.com/plugins/wp/swf/'.$swf.$flashVars.'" />';
        echo '<!--[if !IE]>-->';
        echo '<object type="application/x-shockwave-flash" data="http://static.christmas0.com/plugins/wp/swf/'.$swf.$flashVars.'" width="'.$width.'" height="'.$height.'">';
        echo '<!--<![endif]-->';
        echo '<!--[if !IE]>-->';
        echo '</object>';
        echo '<!--<![endif]-->';
        echo '</object>';
        echo "<div><a href = \"http://christmascountdownclock.com\" title='countdown to cristmas' style='font-size:80%;'>{$footerText}</a></div>";
        echo '</div>';

        if ($wrap) echo $after_widget;
}

function christmas_countdown_control(){
    if ($_POST['christmas_countdown_width']) {
        christmas_countdown_save_option('christmas_countdown_width');
        christmas_countdown_save_option('christmas_countdown_padding');
        christmas_countdown_save_option('christmas_countdown_version');
        christmas_countdown_save_option('christmas_countdown_title');
        christmas_countdown_save_option('christmas_countdown_footer_text');
        christmas_countdown_save_option('christmas_countdown_days_text');
        christmas_countdown_save_option('christmas_countdown_hours_text');
        christmas_countdown_save_option('christmas_countdown_mins_text');
        christmas_countdown_save_option('christmas_countdown_secs_text');
        update_option('christmas_countdown_wrap', isset($_POST['christmas_countdown_wrap']));
    }
    christmas_countdown_ib('christmas_countdown_width', 'Width', ' pixels', 5);
    christmas_countdown_ib('christmas_countdown_padding', 'Padding', ' <i>i.e. 5<b>px</b>, 10<b>pt</b></i>', 10, '0');
    christmas_countdown_select('christmas_countdown_version', 'Version', array('light'=>'Light', 'dark'=>'Dark'));
    christmas_countdown_ib('christmas_countdown_title', 'Caption');
    christmas_countdown_ib('christmas_countdown_footer_text', 'Footer Text');
    christmas_countdown_ib('christmas_countdown_days_text', 'Days Caption');
    christmas_countdown_ib('christmas_countdown_hours_text', 'Hours Caption');
    christmas_countdown_ib('christmas_countdown_mins_text', 'Minutes Caption');
    christmas_countdown_ib('christmas_countdown_secs_text', 'Seconds Caption');
    christmas_countdown_cb('christmas_countdown_wrap', 'Use theme\'s wrapping');
}
function christmas_countdown_cb($var, $caption){
    $val=get_option($var);
    echo '<label for="'.$var.'">';
    echo '<input id="'.$var.'" name="'.$var.'" type="checkbox"';
    if ($val)
        echo ' checked';
    echo ' /> '.$caption.'</label><br/>';
}
function christmas_countdown_select($var, $caption, $options){
    $selected = get_option($var);
    echo '<label for="'.$var.'">'.$caption.':</label><br/>';
    echo "<select name='{$var}' id='{$var}'>";
    foreach ($options as $ovar=>$ocap){
        echo "<option value='{$ovar}'";
        if ($ovar == $selected)
            echo ' selected';
        echo ">{$ocap}</option>";
    }
    echo "</select><br/>";
}
function christmas_countdown_save_option($var){
    $val=$_POST[$var];
    update_option($var, $val);
}
function christmas_countdown_ib($var, $caption, $afterInput='', $size=0, $default=''){
    $value=get_option($var);
    echo '<label for="'.$var.'">'.$caption.':<br/><input id="'.$var.'" name="'.$var.'" type="text"';
    if (!empty($size))
            echo ' size="'.$size.'"';
    echo ' value="';
    if (empty($value) && !empty($default))
        echo $default;
    else
        echo $value;
    echo '" />'.$afterInput.'</label><br/>';
}

function init_christmas_countdown(){
    register_sidebar_widget("Christmas Countdown", "christmas_countdown_widget");
    register_widget_control("Christmas Countdown", "christmas_countdown_control");
}

add_action("plugins_loaded", "init_christmas_countdown");

?>