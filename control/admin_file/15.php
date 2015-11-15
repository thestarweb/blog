<?php
	switch(isset($_GET['doing'])?$_GET['doing']:''){
		case 'out':
			//echo '<script>alert("',$_POST['min']==""?1:0,'");';
			$xt=new xml_tool('./out.download.temp.xml','essays');
			$start=isset($_POST['min'])&&$_POST['min']!=''?$_POST['min']-1:0;
			$end=isset($_POST['max'])&&$_POST['max']!=''?$_POST['max']+0:9999999;
			//导出文章
			if($start>$end) return;
			$es=new essay_server($system);
			$ts=new type_server($system);
			$oid=0;
			while($start<$end){
				if($start+10>$end){
					$essays=$es->get_for_out($start,$end-$start);
				}else{
					$essays=$es->get_for_out($start,10);
				}
				$start+=10;
				if(!$essays) break;
				foreach($essays as $essay){
					$xt->add('/essays',0,'essay');
					$xt->add('/essays/essay',$oid,'title',html_entity_decode($essay['title'],ENT_NOQUOTES));
					$xt->add('/essays/essay',$oid,'content',html_entity_decode($essay['content'],ENT_NOQUOTES));
					$xt->add('/essays/essay',$oid,'time',$essay['time']);
					$xt->add('/essays/essay',$oid,'sender',$essay['sender']);
					$xt->add('/essays/essay',$oid,'display',$essay['display']);
					$etypes=$ts->get_types_by_eid($essay['id']);
					$et='';
					foreach($etypes as $etype){
						$et.=$etype['tid'].',';
					}
					$xt->add('/essays/essay',$oid++,'etypes',$et);
				}
			}
			$xt->save();
			header('Content-type: application/octet-stream');
			header('Content-Disposition: attachment; filename=out.xml');
			ob_clean();
			echo file_get_contents('./out.download.temp.xml');
			unlink('./out.download.temp.xml');
			break;
		case 'in':
			switch ($isset($_POST['type'])?$_POST['type']:'') {
				case 'value':
					$system->db()->do_SQL('TRUNCATE TABLE `'.essay::table.'`');
					$system->db()->do_SQL('TRUNCATE TABLE `'.essay::link_table.'`');
				default:
					# code...
					break;
			}
		default:
			include $system->get_view('admin/essay_io',false);
	}