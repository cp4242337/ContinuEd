<?php
$path = JPATH_SITE.'/cache/';
$filename = 'ContinuEd_Users_Report' . '-' . date("Y-m-d").'.csv';
$contents = "";

$contents .= '"User Id","Username","Name","email","User Group","LastVisit","Last Update","Registered"';
foreach ($this->fdata as $f) {
	$contents .= ',"'.$f->uf_name.'"';
}
$contents .= "\n";

foreach ($this->items as $i) {
	$contents .= '"'.$i->id.'",'; 
	$contents .= '"'.$i->username.'",'; 
	$contents .= '"'.$i->name.'",';
	$contents .= '"'.$i->email.'",'; 
	$contents .= '"'.$i->ug_name.'",'; 
	$contents .= '"'.$i->lastvisitDate.'",'; 
	$contents .= '"'.$i->lastUpdate.'",'; 
	$contents .= '"'.$i->registerDate.'"'; 
	foreach ($this->fdata as $f) {
		$contents .= ',"';
		if (!$f->uf_cms) {
			$fn=$f->uf_sname;
			if ($f->uf_type == 'multi' || $f->uf_type == 'dropdown' || $f->uf_type == 'mcbox' || $f->uf_type == 'mlist') {
				$ansarr=explode(" ",$i->$fn);
				foreach ($ansarr as $a) {
					$contents .= $this->adata[$a]." "; 
				}
			} else if ($f->uf_type == 'cbox' || $f->uf_type == 'yesno') {
				$contents .= ($i->$fn == "1") ? "Yes" : "No";
			} else if ($f->uf_type == 'birthday') {
				$contents .= date("F j",strtotime('2000-'.substr($i->$fn,0,2)."-".substr($i->$fn,2,2).''));
			} else{
				$contents .= $i->$fn;
			}
		}
		$contents .= '"';
	}
	$contents .= "\n";
}
JFile::write($path.$filename,$contents);

 $app = JFactory::getApplication();
 $app->redirect('../cache/'.$filename);

