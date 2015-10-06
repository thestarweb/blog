<?php
$type=new type_server($system);
$list=$type->get_list_p(20);
include $system->get_view('admin/type');