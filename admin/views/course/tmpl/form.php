<?php defined('_JEXEC') or die('Restricted access');

$etype[0]=JHTML::_('select.option','assess','Assessment');
$etype[1]=JHTML::_('select.option','unassess','unAssessment');


$ltype[0]=JHTML::_('select.option','online enduring material','online enduring material');
$ltype[1]=JHTML::_('select.option','enduring material','enduring material');
$ltype[2]=JHTML::_('select.option','live activity','live activity');
$ltype[3]=JHTML::_('select.option','journal based CME activity','journal based CME activity');

$qatype[0]=JHTML::_('select.option','none','None');
$qatype[1]=JHTML::_('select.option','submit','Submit Questions');
$qatype[2]=JHTML::_('select.option','qanda','Q & A Results');
$qatype[3]=JHTML::_('select.option','all','Submit with Q & A Results');

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">

<table width="100%">
	<tr>
		<td valign="top">
		<fieldset class="adminform"><legend><?php echo JText::_( 'Settings' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Published' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','published','',$this->continued->published,'Yes','No','published'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Access' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('list.accesslevel',$this->continued); ?></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Title' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="cname" id="cname"
					size="70" maxlength="255"
					value="<?php echo $this->continued->cname;?>" /></td>
			</tr>

			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Subtitle' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="course_subtitle"
					id="course_subtitle" size="70" maxlength="255"
					value="<?php echo $this->continued->course_subtitle;?>" /></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Title on Certificate' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="certifname"
					id="certifname" size="70" maxlength="255"
					value="<?php echo $this->continued->certifname;?>" /></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Course Description' ); ?>:
				</label></td>
				<td><textarea class="text_area" name="cdesc" id="cdesc" cols="60"
					rows="2"><?php echo $this->continued->cdesc;?></textarea></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Course Keywords' ); ?>:
				</label></td>
				<td><textarea class="text_area" name="keywords" id="keywords"
					cols="60" rows="2"><?php echo $this->continued->keywords;?></textarea>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Searchable' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_searchable','',$this->continued->course_searchable,'Yes','No','course_searchable'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Purchase Required' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_purchase','',$this->continued->course_purchase,'Yes','No','course_purchase'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Purchase Sku' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="course_purchasesku"
					id="course_purchasesku" size="70" maxlength="255"
					value="<?php echo $this->continued->course_purchasesku;?>" /></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Purchase Link' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="course_purchaselink"
					id="course_purchaselink" size="70" maxlength="255"
					value="<?php echo $this->continued->course_purchaselink;?>" /></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Preview Image' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="previmg" id="previmg"
					size="60" value="<?php echo $this->continued->previmg; ?>" />
				Images are loaded form images/continued/preview/</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Category' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.genericlist',$this->catlist,'ccat',NULL,'cid','catname',$this->continued->ccat); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Catalog Link' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="cataloglink"
					id="cataloglink" size="70" maxlength="255"
					value="<?php echo $this->continued->cataloglink;?>" /></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Prerequsite Course' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.genericlist',$this->courselist,'prereq',NULL,'value','text',$this->continued->prereq); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Provider' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.genericlist',$this->prolist,'provider',NULL,'pid','pname',$this->continued->provider); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Validity' ); ?>:
				</label></td>
				<td>From: <?php echo JHTML::_('calendar',$this->continued->startdate,'startdate','startdate','%Y-%m-%d %H:%M:%S',null); ?>
				To: <?php echo JHTML::_('calendar',$this->continued->enddate,'enddate','enddate','%Y-%m-%d %H:%M:%S',null); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Activity Date' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('calendar',$this->continued->actdate,'actdate','actdate','%Y-%m-%d',null); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Faculty' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="faculty" id="faculty"
					size="60" maxlength="255"
					value="<?php echo $this->continued->faculty;?>" /></td>
			</tr>
		</table>
		</fieldset>

		<fieldset class="adminform"><legend><?php echo JText::_( 'Front Matter' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Enable Front Matter' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','hasfm','',$this->continued->hasfm,'Yes','No','hasfm'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Front Matter' ); ?>:
				</label></td>
				<td><?php echo $this->editor->display('frontmatter',$this->continued->frontmatter,'100%','300','30','30',false); ?>
				</td>
			</tr>
		</table>
		</fieldset>
		<fieldset class="adminform"><legend><?php echo JText::_( 'Material' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Enable Material' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','hasmat','',$this->continued->hasmat,'Yes','No','hasmat'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Intermediate Questions' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_hasinter','',$this->continued->course_hasinter,'Yes','No','course_hasinter'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Q and A' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.genericlist',$qatype,'course_qanda',NULL,'value','text',$this->continued->course_qanda); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Material' ); ?>:
				</label></td>
				<td><?php echo $this->editor->display('material',$this->continued->material,'100%','300','30','30',false); ?>
				</td>
			</tr>
		</table>
		</fieldset>
		</td>
		<td valign="top">
		<fieldset class="adminform"><legend><?php echo JText::_( 'Certificate' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Enable Certificate' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','hascertif','',$this->continued->hascertif,'Yes','No','published'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Credits' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="credits" id="credits"
					size="5" maxlength="6"
					value="<?php echo $this->continued->credits;?>" /></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'CNE Program #' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="cneprognum"
					id="cneprognum" size="20" maxlength="30"
					value="<?php echo $this->continued->cneprognum;?>" /></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'CPE Program #' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="cpeprognum"
					id="cpeprognum" size="60" maxlength="255"
					value="<?php echo $this->continued->cpeprognum;?>" /></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Learning Fromat' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.genericlist',$ltype,'course_learntype',NULL,'value','text',$this->continued->course_learntype); ?></td>
			</tr>

			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Default Certificate' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.genericlist',$this->certlist,'defaultcertif',NULL,'crt_id','crt_name',$this->continued->defaultcertif); ?>
				</td>
			</tr>
		</table>
		</fieldset>
		<fieldset class="adminform"><legend><?php echo JText::_( 'PreTest' ); ?></legend>
		<table class="admintable">

			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Enable PreTest' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_haspre','',$this->continued->course_haspre,'Yes','No','course_haspre'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Change PreTest Answers' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_changepre','',$this->continued->course_changepre,'Yes','No','course_changepre'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( '# Eval Parts' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.integerlist',1,10,1,'course_preparts','',$this->continued->course_preparts); ?>
				</td>
			</tr>

		</table>
		</fieldset>
		<fieldset class="adminform"><legend><?php echo JText::_( 'Evaluation' ); ?></legend>
		<table class="admintable">

			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Enable Evaluation' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','haseval','',$this->continued->haseval,'Yes','No','haseval'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Eval Type' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.genericlist',$etype,'course_evaltype',NULL,'value','text',$this->continued->course_evaltype); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( '# Eval Parts' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.integerlist',1,10,1,'evalparts','',$this->continued->evalparts); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Compare Answers Course ID' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="course_compcourse"
					id="course_compcourse" size="5" maxlength="6"
					value="<?php echo $this->continued->course_compcourse;?>" /></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'View Answers on Catalog' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_viewans','',$this->continued->course_viewans,'Yes','No','course_viewans'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Pass Message' ); ?>:
				</label></td>
				<td><textarea class="text_area" name="course_passmsg"
					id="course_passmsg" cols="60" rows="2"><?php echo $this->continued->course_passmsg;?></textarea><br />
				Will replace Thank You message</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Fail Message' ); ?>:
				</label></td>
				<td><textarea class="text_area" name="course_failmsg"
					id="course_failmsg" cols="60" rows="2"><?php echo $this->continued->course_failmsg;?></textarea><br />
				Will replace Thank You message</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Enable Rating' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_allowrate','',$this->continued->course_allowrate,'Yes','No','course_allowrate'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Current Rating' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="course_rating"
					id="course_rating" size="5" maxlength="30"
					value="<?php echo $this->continued->course_rating;?>" /><br />
				0 to reset</td>
			</tr>
		</table>
		</fieldset>
		<fieldset class="adminform"><legend><?php echo JText::_( 'Category Nest' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Catagory Link?' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_catlink','',$this->continued->course_catlink,'Yes','No','course_catlink'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Category Menu' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.genericlist',$this->catlist,'course_catmenu',NULL,'cid','catname',$this->continued->course_catmenu); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Show Course Rating' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.genericlist',$this->courselist,'course_catrate',NULL,'value','text',$this->continued->course_catrate); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Category Expires?' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_catexp','',$this->continued->course_catexp,'Yes','No','course_catexp'); ?>
				</td>
			</tr>
		</table>
		</fieldset>
		<fieldset class="adminform"><legend><?php echo JText::_( 'External Link' ); ?></legend>
		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'External Link?' ); ?>:
				</label></td>
				<td><?php echo JHTML::_('select.booleanlist','course_extlink','',$this->continued->course_extlink,'Yes','No','course_extlink'); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Ext. Link URL' ); ?>:
				</label></td>
				<td><input class="text_area" type="text" name="course_exturl"
					id="course_exturl" size="70" maxlength="255"
					value="<?php echo $this->continued->course_exturl;?>" /></td>
			</tr>

		</table>
		</fieldset>

		</td>
	</tr>
</table>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="disporder"
	value="<?php echo $this->continued->disporder; ?>" /> <input
	type="hidden" name="id" value="<?php echo $this->continued->id; ?>" />
<input type="hidden" name="task" value="" /> <input type="hidden"
	name="controller" value="course" /></form>
