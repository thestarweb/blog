<?php
$remark=new remark_server($system);
$remarks=$remark->get_all();
include $system->get_view('admin/see_new_remark');