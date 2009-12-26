<?php
/*
Plugin Name: Whydowork Adsense 
Plugin URI: http://www.whydowork.com/blog/whydowork-adsense-plugin/
Description: Insert Adsense code in your pages without modifying the template. Show different ads for articles older than 7 days (or as old as you wish).
Author: Whydowork
Version: 1.2
Author URI: http://whydowork.com/
*/
		function whydowork_session(){
      $_SESSION['whydowork_posx'] = '';
      $_SESSION['whydowork_nri'] = 0;
    }
		function whydowork_adsense_install(){
      add_option("whydowork_adsense_admin",'on');
			add_option('whydowork_adsense_oldday', '7');
			add_option('whydowork_exclude', '');
			for ($i=1;$i<11;$i++){add_option('whydowork_code_'.$i, 'ADSENSE CODE #'.$i);}
			for ($i=1;$i<=3;$i++){
				add_option('whydowork_front_code_'.$i, 'FALSE');
				add_option('whydowork_front_pos_'.$i, 'top');
				add_option('whydowork_front_post_'.$i, '1');
				add_option('whydowork_page_code'.$i, 'FALSE');
				add_option('whydowork_page_pos'.$i, 'top');
				add_option('whydowork_single_code'.$i, 'FALSE');
				add_option('whydowork_single_pos'.$i, 'top');
				add_option('whydowork_singleold_code'.$i, 'top');
				add_option('whydowork_singleold_pos'.$i, 'top');
			}
		}
		function whydowork_adsense_menu(){
			if (function_exists('add_options_page')){
				add_options_page('Whydowork Adsense', 'Whydowork Adsense', 9, 'whydowork_adsense', 'whydowork_adsense_display');
			}
		}
		
		
		function whydowork_align($align_bd,$align){
			if ($align_bd == $align) return ' selected="selected"';
			else return '';
		}
		function whydowork_generate_align_menu($pos){
      $pos = 'whydowork_'.$pos;
			$align_bd = get_option($pos);

			$output = '			<td align="right" width="15%">Alignment of Ads: </td>'."\n";
			$output .= '			<td align="left" width="15%" colspan="2">'."\n";
			$output .= '				<select name="'.$pos.'">'."\n";
			$output .= '					<option value="top"'.whydowork_align($align_bd,'top').'>Top</option>'."\n";
			$output .= '					<option value="top-middle"'.whydowork_align($align_bd,'top-middle').'>Top middle</option>'."\n";
			$output .= '					<option value="top-left"'.whydowork_align($align_bd,'top-left').'>Top Left</option>'."\n";
			$output .= '					<option value="top-right"'.whydowork_align($align_bd,'top-right').'>Top Right</option>'."\n";
			$output .= '					<option value="middle"'.whydowork_align($align_bd,'middle').'>Middle</option>'."\n";
			$output .= '					<option value="middle-left"'.whydowork_align($align_bd,'middle-left').'>Middle Left</option>'."\n";
			$output .= '					<option value="middle-right"'.whydowork_align($align_bd,'middle-right').'>Middle Right</option>'."\n";
			$output .= '					<option value="bottom"'.whydowork_align($align_bd,'bottom').'>Bottom</option>'."\n";
			$output .= '					<option value="random"'.whydowork_align($align_bd,'random').'>Random</option>'."\n";
			$output .= '				</select>'."\n";
			$output .= '			</td>';
			return $output;
		}
		
		function whydowork_generate_code_menu($code){
      $code = 'whydowork_'.$code;
			$code_nr = get_option($code);
			
			$output = '			<td align="right" width="15%">Code #: </td>'."\n";
			$output .= '			<td align="left" width="15%">'."\n";
			$output .= '				<select name="'.$code.'">'."\n";
			$output .= '					<option value="FALSE"';
			if ($code_nr == FALSE){$output .= ' selected="selected"';}
			$output .= '>No code</option>'."\n";
			for ($i=1;$i<11;$i++){
				$output .= '					<option value="'.$i.'"';
				if ($code_nr == $i){$output .= ' selected="selected"';}
				$output .= '>'.$i.'</option>'."\n";
			}
			$output .= '				</select>'."\n";
			$output .= '			</td>';
			return $output;
		}
    
    function whydowork_adsense_display(){
        if($_POST['Submit']){
          update_option("whydowork_adsense_admin", $_POST['whydowork_adsense_admin']);
          
          // OLD DAY
          $oldday = $_POST['whydowork_adsense_oldday'];
          if ($oldday<=0){$oldday=1;}
          update_option("whydowork_adsense_oldday", $oldday);
          
          // EXCLUDE
          update_option("whydowork_exclude", $_POST['whydowork_exclude']);
          
          // CODE
          update_option("whydowork_code_".$_POST['idx'], $_POST['whydowork_code']);

          for ($i=1;$i<=3;$i++){
            // FRONT PAGE
            update_option('whydowork_front_code_'.$i, $_POST['whydowork_front_code_'.$i]);
            update_option('whydowork_front_pos_'.$i, $_POST['whydowork_front_pos_'.$i]);
            update_option('whydowork_front_post_'.$i, $_POST['whydowork_front_post_'.$i]);
            
            // PAGE
            update_option('whydowork_page_code_'.$i, $_POST['whydowork_page_code_'.$i]);
            update_option('whydowork_page_pos_'.$i, $_POST['whydowork_page_pos_'.$i]);
            
            // SINGLE PAGE
            update_option('whydowork_single_code_'.$i, $_POST['whydowork_single_code_'.$i]);
            update_option('whydowork_single_pos_'.$i, $_POST['whydowork_single_pos_'.$i]);
            
            // SINGLE PAGE - OLD
            update_option('whydowork_singleold_code_'.$i, $_POST['whydowork_singleold_code_'.$i]);
            update_option('whydowork_singleold_pos_'.$i, $_POST['whydowork_singleold_pos_'.$i]);
          }
          echo '<div id="message" class="updated fade"><p>Update successful!</p></div>';
      }
      $idx = 1;
      if (isset($_GET['idcode'])) $idx = $_GET['idcode'];
      if ($idx > 10 || $idx < 1) $idx = 1;
      
      $code = stripslashes(get_option('whydowork_code_'.$idx));

      // SET CODE
      $output = '<div class="wrap" align="center">'."\n";
      $output .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
      $output .= '<input type="hidden" name="idx" value="'.$idx.'" />';
      for ($i=1;$i<11;$i++){
        $output .= '	[<a href="?page=whydowork_adsense&idcode='.$i.'"><font color="';
        
        if ($i == $idx) $output .= '#ff0000';
        else $output .= '#000000';
        
        $output .= '">Code #'.$i.'</font></a>]'."\n";
        
        if ($i != 10){$output .= ' - ';}
      }
      $output .= '</div>'."\n";
      
      $output .= '<div class="wrap">'."\n";
      $output .= '	<table width="100%" border="0" cellspacing="0" cellpadding="6">'."\n";
      $output .= '		<tr>'."\n";
      $output .= '			<td align="left" colspan="2"><h2>Set code #'.$idx.'</h2></td>'."\n";
      $output .= '		</tr>'."\n";
      $output .= '		<tr>'."\n";
      $output .= '			<td align="right" valign="top" width="15%">Ads Code #'.$idx.': </td>'."\n";
      $output .= '			<td align="left"><textarea name="whydowork_code" cols="60" rows="7">'.$code.'</textarea></td>'."\n";
      $output .= '		</tr>'."\n";
      $output .= '		<tr>'."\n";
      $output .= '			<td align="right" width="15%" valign="top">Preview: </td>'."\n";
      $output .= '			<td align="left" colspan="3"><div style="border: 1px solid black;">'.$code.'</div></td>'."\n";
      $output .= '		</tr>'."\n";
      $output .= '	</table>'."\n";
      $output .= '</div>'."\n";
      
      // GENERAL OPTIONS
      $output .= '<div class="wrap">'."\n";
      $output .= '	<h2>General options</h2>'."\n";
      $output .= '	<p><strong>Notes:</strong>If the post doesn\'t have at least 2 paragraph, the ads from the middle position will not show. If you use Firefox, we also recommend installing our <a href=https://addons.mozilla.org/en-US/firefox/addon/12735 target=_blank>Firefox AdSense Monitor</a> to track your stats.</p>'."\n";
      $output .= '	<p>Show adsense to administrator? &nbsp;&nbsp;<input name="whydowork_adsense_admin" type="checkbox"';
      if (get_option('whydowork_adsense_admin') == 'on') $output .= ' checked="checked" ';
      $output .= '/>';
      $output .= '	<table width="100%" border="0" cellspacing="0" cellpadding="6">'."\n";

      // EXCLUDE POSTS OR/AND PAGES
      $output .= '		<tr>'."\n";
      $output .= '			<td align="left" colspan="6"><h2>Exclude posts or/and pages</h2></td>'."\n";
      $output .= '		</tr>'."\n";
      $output .= '		<tr>'."\n";
      $output .= '			<td align="right" width="15%" valign="top">Exclude: </td>'."\n";
      $output .= '			<td align="left" colspan="5"><input type="text" name="whydowork_exclude" value="'.get_option('whydowork_exclude').'" /> (Write here the post ID. Example: 1,5,4)<br />Enter multiple "ID" comma separated.<br />"ID" can be found at "Manage" page.</td>'."\n";
      $output .= '		</tr>'."\n";

      // Front Page
      $output .= '		<tr>'."\n";
      $output .= '			<td align="left" colspan="6"><h2>Front Page(categories, archive)</h2></td>'."\n";
      $output .= '		</tr>'."\n";
      for ($j=1;$j<=3;$j++){			
        $output .= '		<tr>'."\n";
        $output .= whydowork_generate_code_menu('front_code_'.$j);
        $output .= whydowork_generate_align_menu('front_pos_'.$j);
        $output .= '			<td align="right" width="15%">Show to post number: </td>'."\n";
        $output .= '			<td align="left">'."\n";
        $output .= '				<select name="whydowork_front_post_'.$j.'">'."\n";
        $post_nr = get_option('whydowork_front_post_'.$j);
        for ($i=1;$i<11;$i++){
          $selected = '';
          if ($i == $post_nr){$selected = ' selected="selected"';}
          $output .= '					<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n";
        }
        $output .= '				</select>'."\n";
        $output .= '			</td>';
        $output .= '		</tr>'."\n";
      }
      
      // PAGE
      $output .= '		<tr>'."\n";
      $output .= '			<td align="left" colspan="6"><h2>Page</h2></td>'."\n";
      $output .= '		</tr>'."\n";
      for ($j=1;$j<=3;$j++){		
        $output .= '		<tr>'."\n";
        $output .= whydowork_generate_code_menu('page_code_'.$j);
        $output .= whydowork_generate_align_menu('page_pos_'.$j);
        $output .= '		</tr>'."\n";
      }
      
      // Single page
      $output .= '		<tr>'."\n";
      $output .= '			<td align="left" colspan="6"><h2>Single page</h2></td>'."\n";
      $output .= '		</tr>'."\n";
      for ($j=1;$j<=3;$j++){			
        $output .= '		<tr>'."\n";
        $output .= whydowork_generate_code_menu('single_code_'.$j);
        $output .= whydowork_generate_align_menu('single_pos_'.$j);
        $output .= '		</tr>'."\n";
      }
      
      // Single page - OLD
      $output .= '		<tr>'."\n";
      $output .= '			<td align="left" colspan="6"><h2>Single Page(older than x days)</h2></td>'."\n";
      $output .= '		</tr>'."\n";
      for ($j=1;$j<=3;$j++){			
        $output .= '		<tr>'."\n";
        $output .= whydowork_generate_code_menu('singleold_code_'.$j);
        $output .= whydowork_generate_align_menu('singleold_pos_'.$j);
        $output .= '		</tr>'."\n";
      }
      $output .= '		<tr>'."\n";
      $output .= '			<td align="right" width="15%">Use these settings for articles older than: </td>'."\n";
      $output .= '			<td align="left" colspan="5"><input type="text" name="whydowork_adsense_oldday" value="'.get_option('whydowork_adsense_oldday').'" /> day</td>';
      $output .= '		</tr>'."\n";

      $output .= '		<tr>'."\n";
      $output .= '			<td align="center" colspan="6">'."\n";
      $output .= '				<input type="submit" name="Submit" class="button" value="Update" />&nbsp;&nbsp;'."\n";
      $output .= '			</td>'."\n";
      $output .= '		</tr>'."\n";
      $output .= '	</table>'."\n";
      $output .= '</form>';
      $output .= '</div>'."\n";
      echo $output;
    }
    

    function whydowork_show_code($idx,$content){
      $output = $content;
			for ($j=1;$j<=3;$j++){
				$codeid = get_option('whydowork_'.$idx.'_code_'.$j);

				$verify = TRUE;
				if ($idx == 'front'){
					$post_nr = get_option('whydowork_front_post_'.$j);
					if ($_SESSION['whydowork_nri'] != $post_nr) $verify = FALSE;
				}

				if ($codeid != 'FALSE' && $verify){
					$align = get_option('whydowork_'.$idx.'_pos_'.$j);
					$_SESSION['whydowork_posx'] .= $align;
				}
			}

      for ($i=1;$i<=3;$i++){
        $verify = TRUE;
        if ($idx == 'front'){
          $post_nr = get_option('whydowork_front_post_'.$i);
          if ($_SESSION['whydowork_nri'] != $post_nr) $verify = FALSE;
        }

        $code_id = get_option('whydowork_'.$idx.'_code_'.$i);
        
        if ($code_id != 'FALSE' && $verify){
          $align = get_option('whydowork_'.$idx.'_pos_'.$i);
          
          if ($align == 'random'){
            $whydowork_posx = $_SESSION['whydowork_posx'];
            $align_array = array();
            if (!ereg('top',$whydowork_posx)){
              $align_array[] = 'top';
              $align_array[] = 'top-middle';
              $align_array[] = 'top-left';
              $align_array[] = 'top-right';
            }
            if (!ereg('middle',$whydowork_posx)){
              $align_array[] = 'middle';
              $align_array[] = 'middle-left';
              $align_array[] = 'middle-right';
            }
            if (!ereg('bottom',$whydowork_posx)){
              $align_array[] = 'bottom';
            }
            
            $rand = rand(0, count($align_array)-1);
            $align = $align_array[$rand];
            $_SESSION['whydowork_posx'] .= $align;
          }
          $output1 = explode('</p>', $output);
          $c_o1 = count($output1);
          
          $code = stripslashes(get_option('whydowork_code_'.$code_id));
          
          if ($align == 'top'){				$output = '<p>'.$code.'</p>'.$output;}
          elseif ($align == 'top-middle'){		$output = '<p style="text-align: center;">'.$code.'</p>'.$output;}
          elseif ($align == 'top-left'){		$output = '<p style="float: left;margin: 4px;">'.$code.'</p>'.$output.'<p></p>';}
          elseif ($align == 'top-right'){		$output = '<p style="float: right;margin: 4px;">'.$code.'</p>'.$output.'<p></p>';}
          elseif ($align == 'bottom'){		$output = $output.'<p style="text-align: center;">'.$code.'</p>';}
          elseif (ereg('middle',$align) && $c_o1>1){
            $output ='';
            for ($j=0;$j<$c_o1;$j++){
              if ($j == intval(($c_o1/2)-0.5)){
                if ($align == 'middle'){			$output.= $output1[$j].'</p><p style="float: left;">'.$code.'</p>';}
                elseif ($align == 'middle-left'){	$output .= $output1[$j].'</p><p style="float: left;margin: 4px;">'.$code.'</p>';}
                elseif ($align == 'middle-right'){	$output .= $output1[$j].'</p><p style="float: right;margin: 4px;">'.$code.'</p>';}
              }
              else {
                if ($j != 0){$output .= '</p>';}
                $output .= $output1[$j];
              }
            }
          }
        }
      }
      return $output;
	}

	function whydowork_adsense_filter($content){
		global $id,$user_level;
		$output = $content;

    if ($user_level != 10 || get_option('whydowork_adsense_admin') == 'on'){
      $exclude = FALSE;
      $whydowork_exclude = chop(get_option('whydowork_exclude'));
      if (ereg(',',$whydowork_exclude)){
        $whydowork_exclude = explode(',',$whydowork_exclude);
        for ($i=0;$i<count($whydowork_exclude);$i++){
          if ($id == $whydowork_exclude[$i] || $exclude == TRUE) $exclude = TRUE;
        }
      }
      elseif ($whydowork_exclude == $id && $whydowork_exclude != '') $exclude = TRUE;
      
      if (ereg('<!-no-adsense-->',$output)) $exclude = TRUE;
      
      if (!$exclude){	
        if (is_single()){
          $whydowork_soc = 0;
          for ($i=1;$i<=3;$i++){
            $code_id = get_option('whydowork_singleold_code_'.$i);
            if ($code_id == 'FALSE') $whydowork_soc += 1;
          }
          
          $oldday = get_option('whydowork_adsense_oldday');
          $expire = time() - $oldday*24*60*60;
          if (get_the_time('U') < $expire && $whydowork_soc != 3) $output = whydowork_show_code('singleold',$output);  // Single Post - Old day
          else $output = whydowork_show_code('single',$output); // Single Post
        }
        else {
          if (is_page()) $output = whydowork_show_code('page',$output); // Page
          else {  // Categories, Archive, Front page
            $_SESSION['whydowork_nri'] += 1;
            $output = whydowork_show_code('front',$output);
          }
        }
      }
    }
		return $output;
	}



/* START WIDGET CODE */
function widget_whydowork_adsense_init(){
	if (!function_exists('register_sidebar_widget')) return;

	function widget_whydowork_adsense($args){
    global $user_level;
    extract($args);
    echo $before_widget;
    if ($user_level != 10 || get_option('whydowork_adsense_admin') == 'on'){
      $title = htmlspecialchars(get_option('whydowork_adsense_widget_title'));
      echo $before_title.$title.$after_title;
      echo '<ul>'."\n";
      echo '  <li>'.stripslashes(get_option('whydowork_adsense_widget_code')).'</li>'."\n";
      echo '</ul>'."\n";
    }
    echo $after_widget;
	}
	function widget_whydowork_adsense_options() {
		if ($_POST['whydowork_adsense_title']){
			$widget_title = strip_tags(stripslashes($_POST['whydowork_adsense_title']));
			update_option('whydowork_adsense_widget_title', $widget_title);
			update_option('whydowork_adsense_widget_code', $_POST['whydowork_adsense_code']);
		}
		$widget_title = htmlspecialchars(get_option('whydowork_adsense_widget_title'));
		$code = stripslashes(get_option('whydowork_adsense_widget_code'));
    $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="6">'."\n";
    $output .= '	<tr>'."\n";
    $output .= '		<td align="right" valign="top" width="25%">Widget Title: </td>'."\n";
    $output .= '		<td align="left"><input type="text" name="whydowork_adsense_title" value="'.$widget_title.'" /></td>'."\n";
    $output .= '	</tr>'."\n";
    $output .= '	<tr>'."\n";
    $output .= '		<td align="right" width="25%" valign="top">Code: </td>'."\n";
    $output .= '		<td align="left" colspan="3"><textarea name="whydowork_adsense_code" cols="25" rows="5">'.$code.'</textarea></td>'."\n";
    $output .= '	</tr>'."\n";
    $output .= '</table>'."\n";
    echo $output;
	}
	register_sidebar_widget('Whydowork Adsense', 'widget_whydowork_adsense');
	register_widget_control('Whydowork Adsense', 'widget_whydowork_adsense_options', 350, 200);
}

  // Add action
  add_action('plugins_loaded', 'widget_whydowork_adsense_init');
	add_action('wp_head', 'whydowork_session');
  add_action('activate_whydowork_adsense.php', 'whydowork_adsense_install');
  add_action('admin_menu', 'whydowork_adsense_menu');
  
  // Add filter
	add_filter('the_content', 'whydowork_adsense_filter', 25);
	add_filter('the_excerpt', 'whydowork_adsense_filter', 25);
?>