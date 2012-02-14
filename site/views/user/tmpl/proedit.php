<div id="continued">
<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cecfg = ContinuEdHelper::getConfig();
	?>
<div class="componentheading">User Profile Edit</div>
<?php 
echo '<form action="" method="post">';
foreach($this->userfields as $f) {
	echo '<div class="continued-user-info-row">';
	echo '<div class="continued-user-info-label">';
	$sname = $f->uf_sname;
	//field title
	if ($f->uf_type != "cbox") echo '<label id="jform_'.$sname.'-lbl" for="jform_'.$sname.'" class="hasTip" title="'.$f->uf_name.'::">'.$f->uf_name.'</label>';
	echo '</div>';
	echo '<div class="continued-user-info-value">';
	if ($f->uf_change) {
		
		//checkbox
		if ($f->uf_type=="cbox") {
			if (!empty($this->userinfo->$sname)) $checked = ($this->userinfo->$sname == '1') ? ' checked="checked"' : '';
			else $checked = '';
			echo '<input type="checkbox" name="jform['.$sname.']" id="jform_'.$sname.'"'.$checked.'/>'."\n";
			echo '<label for="jform_'.$sname.$o->value.'">';
			echo ' '.$f->uf_name.'</label><br />'."\n";
		}
	
		//multi checkbox
		if ($f->uf_type=="mcbox") {
			foreach ($f->options as $o) {
				if (!empty($this->userinfo->$sname)) $checked = in_array($o->value,$this->userinfo->$sname) ? ' checked="checked"' : '';
				else $checked = '';
				echo '<input type="checkbox" name="jform['.$sname.'][]" value="'.$o->value.'" id="jform_'.$sname.$o->value.'"'.$checked.'/>'."\n";
				echo '<label for="jform_'.$sname.$o->value.'">';
				echo ' '.$o->text.'</label><br />'."\n";
				
			}
		}
	
		//radio
		if ($f->uf_type=="multi") {
			foreach ($f->options as $o) {
				if (!empty($this->userinfo->$sname)) $checked = in_array($o->value,$this->userinfo->$sname) ? ' checked="checked"' : '';
				else $checked = '';
				echo '<input type="radio" name="jform['.$sname.']" value="'.$o->value.'" id="jform_'.$sname.$o->value.'"'.$checked.'/>'."\n";
				echo '<label for="jform_'.$sname.$o->value.'">';
				echo ' '.$o->text.'</label><br />'."\n";
				
			}
		}
	
		//dropdown
		if ($f->uf_type=="dropdown") {
			echo '<select id="jform_'.$sname.'" name="jform['.$sname.']" class="inputbox" size="1">';
			foreach ($f->options as $o) {
				if (!empty($this->userinfo->$sname)) $selected = ($o->value == $this->userinfo->$sname) ? ' selected="selected"' : '';
				else $selected = '';
				echo '<option value="'.$o->value.'"'.$selected.'>';
				echo ' '.$o->text.'</option>';
			}
			echo '</select>';
		}
		
		//text field, phone #, email, username
		if ($f->uf_type=="textbox" || $f->uf_type=="email" || $f->uf_type=="username" || $f->uf_type=="phone") {
			echo '<input name="jform['.$sname.']" id="jform_'.$sname.'" value="'.$this->userinfo->$sname.'" class="inputbox" size="70" type="text">';
		}
		
		//password
		if ($f->uf_type=="password") {
			echo '<input name="jform['.$sname.']" id="jform_'.$sname.'" class="inputbox" size="20" type="password">';
		}
		
		//text area
		if ($f->uf_type=="textar") {
			echo '<textarea name="jform['.$sname.']" id="jform_'.$sname.'" cols="70" rows="4" class="inputbox">'.$this->userinfo->$sname.'</textarea>';
		}
		
		//Yes no
		if ($f->uf_type=="yesno") {
			echo '<select id="jform_'.$sname.'" name="jform['.$sname.']" class="inputbox" size="1">';
			$selected = ' selected="selected"';
			echo '<option value="1"';
			echo ($this->userinfo->$sname == "1") ? $selected : '';
			echo '>Yes</option>';
			echo '<option value="0"';
			echo ($this->userinfo->$sname == "0") ? $selected : '';
			echo '>No</option>';
			
			echo '</select>';
			
		}
	} else {
		echo $this->userinfo->$sname;
	}

	echo '</div></div>';
} 
echo '<div class="continued-user-info-row">';
echo '<div class="continued-user-info-label">';
echo '</div>';
echo '<div class="continued-user-info-value">';
echo '<input type="submit" value="Save" name="subform">';
echo '</div></div>';
echo '<input type="hidden" name="option" value="com_continued">';
echo '<input type="hidden" name="view" value="user">';
echo '<input type="hidden" name="layout" value="saveuser">';
echo '<input type="hidden" name="jform[userGroupID]" value="'.$this->userinfo->userGroupID.'">';
echo '</form>';
?>
</div>