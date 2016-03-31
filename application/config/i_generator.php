<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['i_table']['page'] = 0;
$config['i_table']['offset'] = 0;
$config['i_table']['limit'] = 20;

$config['i_grid']['id'] = 1;
$config['i_grid']['properties']['icon']           ='<i class="fa fa-table"></i>';
$config['i_grid']['properties']['title']         ='';  
$config['i_grid']['properties']['loading']          = 'ball_pulse';
$config['i_grid']['properties']['id_prefix_grid'] = 'i_grid_';
$config['i_grid']['properties']['id_prefix_form'] = 'i_form_';
$config['i_grid']['properties']['id_prefix_table'] = 'i_table_';

$config['i_grid']['properties']['link_table_head']['left'] = array();
$config['i_grid']['properties']['link_table_foot']['left'] = array();
$config['i_grid']['properties']['link_table_head']['right'] = array('create', 'refresh','search','export');
$config['i_grid']['properties']['link_table_foot']['right'] = array();
$config['i_grid']['properties']['link_form_head']['left'] = array();
$config['i_grid']['properties']['link_form_foot']['left'] = array('back');
$config['i_grid']['properties']['link_form_head']['right'] = array();
$config['i_grid']['properties']['link_form_foot']['right'] = array();


$config['i_grid']['properties']['thumb']['img']['tag_open'] = '<img ';
$config['i_grid']['properties']['thumb']['img']['tag_close'] = '>';
$config['i_grid']['properties']['thumb']['img']['attr']['class'] = 'img-thumbnail';
$config['i_grid']['properties']['thumb']['img']['attr']['style'] ='width :50px; height:50px';
$config['i_grid']['properties']['thumb']['doc']['tag_open']        = '<a>';
$config['i_grid']['properties']['thumb']['doc']['tag_close']          = '</a>';
$config['i_grid']['properties']['thumb']['doc']['text']          = '<i class="fa fa-undo"> View Document</i>';
$config['i_grid']['properties']['thumb']['doc']['attr']['class']     ='i_link_search btn btn-default panel-collapsed';
$config['i_grid']['properties']['thumb']['doc']['attr']['target']       = '_blank';

$config['i_grid']['properties']['extension']['doc'] =  array('doc','docx','txt','zip','xls','xlsx','ppt','pptx');
$config['i_grid']['properties']['extension']['img'] =  array('jpg','bmp','png','gif','jpeg');



/*
 * $config['i_grid']['layout']['panel']
 * @Class       I_grid
 * @Object      I_layout
 * @Variable    panel
 * 
 * @Key         default,main,table,form,head,body,foot
 * 
 */
$config['i_grid']['layout']['panel']['default']['tag_open']                         = '<div>';
$config['i_grid']['layout']['panel']['default']['tag_close']                        = '</div>';
$config['i_grid']['layout']['panel']['main']['tag_open']                         = '<div>';
$config['i_grid']['layout']['panel']['main']['tag_close']                        = '</div>';
$config['i_grid']['layout']['panel']['main']['attr']['class']                    = 'i_grid';
$config['i_grid']['layout']['panel']['table']['tag_open']                         = '<div>';
$config['i_grid']['layout']['panel']['table']['tag_close']                        = '</div>';
$config['i_grid']['layout']['panel']['table']['attr']['id']       = "panel-table";
$config['i_grid']['layout']['panel']['table']['attr']['class']    = 'panel panel-primary';
$config['i_grid']['layout']['panel']['form']['tag_open']                         = '<div>';
$config['i_grid']['layout']['panel']['form']['tag_close']                        = '</div>';
$config['i_grid']['layout']['panel']['form']['attr']['id']                  = "panel-form";
$config['i_grid']['layout']['panel']['form']['attr']['class']  = 'panel panel-default';
$config['i_grid']['layout']['panel']['form']['attr']['style']  = 'display:none';
$config['i_grid']['layout']['panel']['head']['tag_open']                         = '<div>';
$config['i_grid']['layout']['panel']['head']['tag_close']                        = '</div>';
$config['i_grid']['layout']['panel']['head']['attr']['class']  = 'panel-heading clearfix';
$config['i_grid']['layout']['panel']['body']['tag_open']                         = '<div>';
$config['i_grid']['layout']['panel']['body']['tag_close']                        = '</div>';
$config['i_grid']['layout']['panel']['body']['attr']['class']  = 'panel-body';
$config['i_grid']['layout']['panel']['body']['attr']['style'] = 'display:none';
$config['i_grid']['layout']['panel']['form_show']['tag_open']                         = '<div>';
$config['i_grid']['layout']['panel']['form_show']['tag_close']                        = '</div>';
$config['i_grid']['layout']['panel']['form_show']['attr']['id']                  = "panel-form";
$config['i_grid']['layout']['panel']['form_show']['attr']['class']  = 'panel panel-default';
$config['i_grid']['layout']['panel']['body_show']['tag_open']                         = '<div>';
$config['i_grid']['layout']['panel']['body_show']['tag_close']                        = '</div>';
$config['i_grid']['layout']['panel']['body_show']['attr']['class']  = 'panel-body';
$config['i_grid']['layout']['panel']['foot']['tag_open']                         = '<div>';
$config['i_grid']['layout']['panel']['foot']['tag_close']                        = '</div>';
$config['i_grid']['layout']['panel']['foot']['attr']['class']  = 'panel-footer clearfix';

$config['i_grid']['layout']['button']['default']['tag_open']        = '<div class="btn">';
$config['i_grid']['layout']['button']['default']['tag_close']          = '</div>';
$config['i_grid']['layout']['button']['left']['tag_open']        = '<div class="btn pull-left">';
$config['i_grid']['layout']['button']['left']['tag_close']          = '</div>';
$config['i_grid']['layout']['button']['right']['tag_open']        = '<div class="btn pull-right">';
$config['i_grid']['layout']['button']['right']['tag_close']          = '</div>';
$config['i_grid']['layout']['button_group']['default']['tag_open']        = '<div class="btn-group">';
$config['i_grid']['layout']['button_group']['default']['tag_close']          = '</div>';
$config['i_grid']['layout']['button_group']['left']['tag_open']        = '<div class="btn-group pull-left">';
$config['i_grid']['layout']['button_group']['left']['tag_close']          = '</div>';
$config['i_grid']['layout']['button_group']['right']['tag_open']        = '<div class="btn-group pull-right">';
$config['i_grid']['layout']['button_group']['right']['tag_close']          = '</div>';
$config['i_grid']['layout']['form_group']['default']['tag_open']        = '<div class="form-group">';
$config['i_grid']['layout']['form_group']['default']['tag_close']          = '</div>';
$config['i_grid']['layout']['separator'] = '<div class="clearfix col-lg-12"><hr/></div>';


/*
 * $config['i_grid']['html']['table']
 * @Class       I_grid
 * @Object      I_html
 * @Variable    table
 * 
 * @Key         root,main,head,body,foot,th,td
 * 
 */
$config['i_grid']['html']['table']['root']['tag_open']               = '<div>';
$config['i_grid']['html']['table']['root']['tag_close']  = '</div>';
$config['i_grid']['html']['table']['root']['attr']['class']  = 'table-responsive';
$config['i_grid']['html']['table']['main']['tag_open']           = '<table>';
$config['i_grid']['html']['table']['main']['tag_close']          = '</table>';
$config['i_grid']['html']['table']['main']['attr']['class']      = 'i_table table table-condensed table-striped table-hover ';
$config['i_grid']['html']['table']['group']['tag_open']               = '<colgroup>';
$config['i_grid']['html']['table']['group']['tag_close']  = '</colgroup>';
$config['i_grid']['html']['table']['col']['tag_open']               = '<col ';
$config['i_grid']['html']['table']['col']['tag_close']  = ' >';
$config['i_grid']['html']['table']['head']['tag_open']               = '<thead>';
$config['i_grid']['html']['table']['head']['tag_close']  = '</thead>';
$config['i_grid']['html']['table']['body']['tag_open']               = '<tbody>';
$config['i_grid']['html']['table']['body']['tag_close']  = '</tbody>';
$config['i_grid']['html']['table']['foot']['tag_open']               = '<tfoot>';
$config['i_grid']['html']['table']['foot']['tag_close']  = '</tfoot>';
$config['i_grid']['html']['table']['foot']['tag_open']               = '<tfoot>';
$config['i_grid']['html']['table']['foot']['tag_close']  = '</tfoot>';
$config['i_grid']['html']['table']['tr']['tag_open']               = '<tr>';
$config['i_grid']['html']['table']['tr']['tag_close']  = '</tr>';
$config['i_grid']['html']['table']['th']['tag_open']               = '<th>';
$config['i_grid']['html']['table']['th']['tag_close']  = '</th>';
$config['i_grid']['html']['table']['th']['attr']['class']            = 'text-center';
$config['i_grid']['html']['table']['td']['tag_open']               = '<th>';
$config['i_grid']['html']['table']['td']['tag_close']  = '</th>';
$config['i_grid']['html']['table']['td']['attr']['class']            = 'text-left';
$config['i_grid']['html']['table']['filter']['default']['type']    = 'type';
$config['i_grid']['html']['table']['filter']['type']['attr']['type']    = 'text';
$config['i_grid']['html']['table']['filter']['type']['attr']['class']   = 'i_filter form-control input-sm';
$config['i_grid']['html']['table']['filter']['type']['data']            = array();
$config['i_grid']['html']['table']['filter']['select']['attr']['class']   = 'i_filter form-control input-sm';
$config['i_grid']['html']['table']['filter']['select']['data']            = array();



$config['i_grid']['loading']['line_scale'] = '<div class="i_loading"><div class="loader"><div class="loader-inner line-scale"><div></div><div></div><div></div><div></div><div></div><div></div></div></div></div>';
$config['i_grid']['loading']['ball_pulse'] = '<div class="i_loading"><div class="loader"><div class="loader-inner ball-pulse"><div></div><div></div><div></div></div></div></div>';
$config['i_grid']['loading']['ball_grid_pulse'] = '<div class="i_loading"><div class="loader"><div class="loader-inner ball-grid-pulse"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div></div>';
$config['i_grid']['loading']['ball_clip_rotate'] = '<div class="i_loading"><div class="loader"><div class="loader-inner ball-clip-rotate"><div></div></div></div></div>';
$config['i_grid']['loading']['ball_clip_rotate_pulse'] = '<div class="i_loading"><div class="loader"><div class="loader-inner ball-clip-rotate-pulse"><div></div><div></div></div></div></div>';
#$config['i_grid']['loading'][''] = '';
#$config['i_grid']['loading'][''] = '';
#$config['i_grid']['loading'][''] = '';
#$config['i_grid']['loading'][''] = '';
#$config['i_grid']['loading'][''] = '';


$config['i_grid']['search']['main']['form']['action'] = ''; 
$config['i_grid']['search']['main']['form']['attr']['method'] = 'post'; 
$config['i_grid']['search']['main']['form']['attr']['class'] = 'i_form_search form-horizontal '; 
$config['i_grid']['search']['main']['form']['attr']['id'] = 'i_form_search'; 
$config['i_grid']['search']['button'] = array('search','reset');
$config['i_grid']['search']['type']['text']['attr']['class']          = 'form-control input-sm';
$config['i_grid']['search']['type']['text']['operator'] = 'LIKE';
$config['i_grid']['search']['type']['text']['logic']    = 'AND';  
$config['i_grid']['search']['type']['text']['data'] = array();
$config['i_grid']['search']['type']['select']['attr']['class']          = 'form-control input-sm';
$config['i_grid']['search']['type']['select']['operator'] = 'LIKE';
$config['i_grid']['search']['type']['select']['logic']    = 'AND';  
$config['i_grid']['search']['type']['select']['data'] = array();
$config['i_grid']['search']['type']['radio']['attr']['class']          = '';
$config['i_grid']['search']['type']['radio']['operator'] = '=';
$config['i_grid']['search']['type']['radio']['logic']    = 'AND';  
$config['i_grid']['search']['type']['radio']['data'] = array();
$config['i_grid']['search']['type']['checkbox']['attr']['class']          = '';
$config['i_grid']['search']['type']['checkbox']['operator'] = 'LIKE';
$config['i_grid']['search']['type']['checkbox']['logic']    = 'AND';  
$config['i_grid']['search']['type']['checkbox']['logic_array']    = '||';  
$config['i_grid']['search']['type']['checkbox']['data'] = array();
$config['i_grid']['search']['type']['multiselect']['attr']['class']          = 'form-control input-sm';
$config['i_grid']['search']['type']['multiselect']['operator'] = 'LIKE';
$config['i_grid']['search']['type']['multiselect']['logic']    = 'AND';  
$config['i_grid']['search']['type']['multiselect']['logic_array']    = '||';  
$config['i_grid']['search']['type']['multiselect']['data'] = array();
$config['i_grid']['search']['type']['default']['group_open'] = '<div class = "col-sm-3">';
$config['i_grid']['search']['type']['default']['group_close'] = '</div>';
$config['i_grid']['search']['type']['default']['label_open'] = '<label class="control-label">';
$config['i_grid']['search']['type']['default']['label_close'] = '</label>';
$config['i_grid']['search']['type']['default']['attr']['class']          = 'form-control input-sm';
$config['i_grid']['search']['type']['default']['operator'] = 'LIKE';
$config['i_grid']['search']['type']['default']['logic']    = 'AND';  
$config['i_grid']['search']['type']['default']['data'] = array();


$config['i_grid']['form']['main']['form']['uri'] = '';
$config['i_grid']['form']['main']['form']['attr']['id']             = '';
$config['i_grid']['form']['main']['form']['attr']['class']          = 'i_form_input form-vertical';
$config['i_grid']['form']['main']['form']['multipart'] = true;
$config['i_grid']['form']['main']['form']['hidden'] = array();
$config['i_grid']['form']['main']['form']['multiple']         = false;
$config['i_grid']['form']['main']['form']['multiple_index']         = 0;
$config['i_grid']['form']['main']['form']['multiple_separator']=  ',';
$config['i_grid']['form']['button'] = array('save','reset');


$config['i_grid']['form']['type']['hidden']['name'] = '';
$config['i_grid']['form']['type']['hidden']['value'] = '';

$config['i_grid']['form']['type']['text']['label']['content'] = '';
$config['i_grid']['form']['type']['text']['label']['attr']['class'] = 'control-label';
$config['i_grid']['form']['type']['text']['attr']['class'] ='form-control';


$config['i_grid']['form']['type']['textarea']['label']['content'] = '';
$config['i_grid']['form']['type']['textarea']['label']['attr']['class'] = 'control-label';
$config['i_grid']['form']['type']['textarea']['attr']['class'] ='form-control';
$config['i_grid']['form']['type']['textarea']['attr']['rows'] = "2";

$config['i_grid']['form']['type']['select']['label']['content'] = '';
$config['i_grid']['form']['type']['select']['label']['attr']['class'] = 'control-label';
$config['i_grid']['form']['type']['select']['attr']['class'] ='form-control';

$config['i_grid']['form']['type']['multiselect']['label']['content'] = '';
$config['i_grid']['form']['type']['multiselect']['label']['attr']['class'] = 'control-label';
$config['i_grid']['form']['type']['multiselect']['attr']['class'] ='form-control';
$config['i_grid']['form']['type']['multiselect']['data']   = array();
$config['i_grid']['form']['type']['radio']['label']['content'] = '';
$config['i_grid']['form']['type']['radio']['label']['attr']['class'] = 'control-label';
$config['i_grid']['form']['type']['radio']['data']   = array();
$config['i_grid']['form']['type']['radio']['attr']['class'] ='';
$config['i_grid']['form']['type']['radio']['tag_open_input'] = '<div class="checkbox"><label>';
$config['i_grid']['form']['type']['radio']['tag_close_input'] = '</label></div>';

$config['i_grid']['form']['type']['multiradio']['label']['content'] = '';
$config['i_grid']['form']['type']['multiradio']['label']['attr']['class'] = 'control-label';
$config['i_grid']['form']['type']['multiradio']['data']   = array();
$config['i_grid']['form']['type']['multiradio']['attr']['class'] ='';
$config['i_grid']['form']['type']['multiradio']['tag_open_input'] = '<div class="checkbox"><label>';
$config['i_grid']['form']['type']['multiradio']['tag_close_input'] = '</label></div>';
        
$config['i_grid']['form']['type']['checkbox']['label']['content'] = '';
$config['i_grid']['form']['type']['checkbox']['label']['attr']['class'] = 'control-label';
$config['i_grid']['form']['type']['checkbox']['data']   = array();
$config['i_grid']['form']['type']['checkbox']['attr']['class'] ='';
$config['i_grid']['form']['type']['checkbox']['tag_open_input'] = '<div class="checkbox"><label>';
$config['i_grid']['form']['type']['checkbox']['tag_close_input'] = '</label></div>';

$config['i_grid']['form']['type']['multicheckbox']['label']['content'] = '';
$config['i_grid']['form']['type']['multicheckbox']['label']['attr']['class'] = 'control-label';
$config['i_grid']['form']['type']['multicheckbox']['data']   = array();
$config['i_grid']['form']['type']['multicheckbox']['attr']['class'] ='';
$config['i_grid']['form']['type']['multicheckbox']['tag_open_input'] = '<div class="checkbox"><label>';
$config['i_grid']['form']['type']['multicheckbox']['tag_close_input'] = '</label></div>';

$config['i_grid']['form']['type']['image']['label']['content'] = '';
$config['i_grid']['form']['type']['image']['label']['attr']['class'] = 'control-label';
$config['i_grid']['form']['type']['image']['attr']['class']      = 'file file-preview-image';
$config['i_grid']['form']['type']['image']['attr']['data-show-upload']      = false;
$config['i_grid']['form']['type']['image']['attr']['data-preview-file-type'] = "any";
$config['i_grid']['form']['type']['image']['attr']['multiple'] = false;
//$config['i_grid']['form']['type']['image']['attr']['data-preview-file-type'] = "image"; 
$config['i_grid']['form']['type']['image']['attr']['data-initial-caption']  = "";
//$config['i_grid']['form']['type']['image']['attr']['data-overwrite-initial'] = true;
$config['i_grid']['form']['type']['image']['upload']['initial_preview']  ='';
$config['i_grid']['form']['type']['image']['upload']['overwrite_initial']        = true;
$config['i_grid']['form']['type']['image']['upload']['max_filesize']             = 300000;
$config['i_grid']['form']['type']['image']['upload']['max_filesnum']             = 5;
$config['i_grid']['form']['type']['image']['upload']['allowed_filetypes']         = "'image', 'html', 'text', 'video', 'audio', 'flash', 'object'";
$config['i_grid']['form']['type']['image']['upload']['allowed_fileextensions']         = "'jpg', 'png','gif','pdf','pdf','txt'";
$config['i_grid']['form']['type']['image']['upload']['show_upload']         = 0;
$config['i_grid']['form']['type']['image']['upload']['preview_class']         = 'file-preview-center-slide';
$config['i_grid']['form']['type']['image']['upload']['show_remove']         = true;
$config['i_grid']['form']['type']['image']['upload']['show_preview']         = 1;
$config['i_grid']['form']['type']['image']['extension']   = array('jpg','bmp','png','gif','jpeg');


$config['i_grid']['form']['type']['default']['group_open'] = '<div class = "col-sm-12"><div class="form-group">';
$config['i_grid']['form']['type']['default']['group_close'] = '</div></div>';
$config['i_grid']['form']['type']['default']['label_open'] = '<label class="control-label">';
$config['i_grid']['form']['type']['default']['label_close'] = '</label>';

$config['i_grid']['form']['type']['default']['data'] = array();


$config['i_btn']['default']['tag_open']        = '<button>';
$config['i_btn']['default']['tag_close']          = '</button>';

$config['i_btn']['search']['text']           ='<i class="fa fa-search"></i> Search';
$config['i_btn']['search']['attr']['type']            = 'submit';
$config['i_btn']['search']['attr']['class']     ='i_btn_search btn btn-success';
$config['i_btn']['save']['text']           ='<i class="fa fa-search"></i> Save';
$config['i_btn']['save']['attr']['type']            = 'submit';
$config['i_btn']['save']['attr']['class']     ='i_btn_search btn btn-success';
$config['i_btn']['save']['attr']['data-toggle']   = 'tooltip';
$config['i_btn']['reset']['text']           ='<i class="fa fa-refresh"></i> Reset';
$config['i_btn']['reset']['attr']['type']            = 'reset';
$config['i_btn']['reset']['attr']['class']     ='i_btn_create btn btn-warning';


$config['i_link']['default']['tag_open']        = '<a>';
$config['i_link']['default']['tag_close']          = '</a>';
$config['i_link']['default']['query'] = '';

$config['i_link']['create']['text']         = '<i class="fa fa-edit"></i> Create';
$config['i_link']['create']['attr']['class']         = 'i_link_create btn  btn-default';
$config['i_link']['create']['attr']['data-toggle']   = 'tooltip';
$config['i_link']['create']['query'] = 'action=create&request=form';

$config['i_link']['update']['text']         = '<i class="fa fa-edit"> </i> ';
$config['i_link']['update']['attr']['title']    = 'Update';
$config['i_link']['update']['attr']['class']         = 'i_link_update btn btn-xs btn-primary';
$config['i_link']['update']['attr']['data-toggle']   = 'tooltip';
$config['i_link']['update']['query'] = 'action=update&request=form';

$config['i_link']['view']['text']         = '<i class="fa fa-external-link"> </i> ';
$config['i_link']['view']['attr']['title']    = 'View';
$config['i_link']['view']['attr']['class']         = 'i_link_update btn btn-xs btn-default';
$config['i_link']['view']['attr']['data-toggle']   = 'tooltip';
$config['i_link']['view']['query'] = 'action=update&request=view';

$config['i_link']['delete']['text']         = '<i class="fa fa-trash"></i> ';
$config['i_link']['delete']['attr']['title']    = 'Delete';
$config['i_link']['delete']['attr']['class']         = 'i_link_delete btn btn-xs btn-danger';
$config['i_link']['delete']['attr']['data-toggle']   = 'tooltip';
$config['i_link']['delete']['attr']['data-confirm']  ='Apakah Anda Akan Mengapus Data';
$config['i_link']['delete']['attr']['action'] = 'delete';
$config['i_link']['delete']['query'] = 'action=delete';

$config['i_link']['back']['text']           ='<i class="fa fa-undo"></i> Back';
$config['i_link']['back']['attr']['class']     ='i_link_back btn btn-success';

$config['i_link']['search']['text']           ='<i class="fa fa-search"></i> Search';
$config['i_link']['search']['attr']['class']     ='i_link_search btn btn-default panel-collapsed';

$config['i_link']['refresh']['text']           ='<i class="fa fa-refresh"></i> Refresh';
$config['i_link']['refresh']['attr']['class']     ='i_link_refresh btn btn-default';
$config['i_link']['refresh']['query'] = 'action=refresh';

$config['i_link']['export']['tag_open'] = '<div class="btn-group">';
$config['i_link']['export']['tag_close'] = '</div>'; 
$config['i_link']['export']['text']           ="<button class=\"btn btn-warning btn-md dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"fa fa-bars\"></i> Export </button>"
        . "<ul class=\"dropdown-menu \" role=\"menu\">"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'json',escape:'false'});\">JSON</a></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'json',escape:'false',ignoreColumn:'[2,3]'});\"> JSON (ignoreColumn)</a></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'json',escape:'true'});\">JSON (with Escape)</a></li>"
        . "<li class=\"divider\"></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'xml',escape:'false'});\"> XML</a></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'sql'});\">SQL</a></li>"
        . "<li class=\"divider\"></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'csv',escape:'false'});\">CSV</a></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'txt',escape:'false'});\">TXT</a></li>"
        . "<li class=\"divider\"></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'excel',escape:'false'});\">XLS</a></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'doc',escape:'false'});\"> Word</a></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'powerpoint',escape:'false'});\">PowerPoint</a></li>"
        . "<li class=\"divider\"></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'png',escape:'false'});\">PNG</a></li>"
        . "<li><a href=\"#\" onclick=\"$('.i_grid').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});\">PDF</a></li>"
        . "</ul>";


$config['i_paging']                         = array();
$config['i_paging']['attr'] = array('class' => 'i_link_paging');
$config['i_paging']['per_page']             = 5;
$config['i_paging']['num_links']             = 3;
$config['i_paging']['use_page_numbers']     = TRUE;
$config['i_paging']['cur_tag_open']         =  '&nbsp;<a class="current">';
$config['i_paging']['cur_tag_close']         = '</a>';
$config['i_paging']['first_link']         = '<i class="fa fa-fast-backward"></i> First';
$config['i_paging']['next_link']         = '<i class="fa fa-forward"></i>';
$config['i_paging']['prev_link']         = '<i class="fa fa-backward"></i>';
$config['i_paging']['next_pager_link']         = '<i class="fa fa-forward"></i> Next';
$config['i_paging']['prev_pager_link']         = 'Prev <i class="fa fa-backward"></i>';
$config['i_paging']['last_link']         = 'Last <i class="fa fa-fast-forward"></i>';
$config['i_paging']['col_left_tag_open']         = '<div class="col-md-3 text-left">';
$config['i_paging']['col_left_tag_close']         = '</div>';
$config['i_paging']['col_center_tag_open']         = '<div class="col-md-6 text-center">';
$config['i_paging']['col_center_tag_close']         = '</div>';
$config['i_paging']['col_right_tag_open']         = '<div class="col-md-3 text-right">';
$config['i_paging']['col_right_tag_close']         = '</div>';
$config['i_paging']['full_paging_tag_open']         = '<ul class="pagination pagination-sm">';
$config['i_paging']['full_paging_tag_close']         = '</ul>';
$config['i_paging']['full_pager_tag_open']         = '<ul class="pager">';
$config['i_paging']['full_pager_tag_close']         = '</ul>';
$config['i_paging']['num_tag_open']         = '<li>';
$config['i_paging']['num_tag_close']         = '</li>';
$config['i_paging']['cur_tag_open']         = '<li class="active"><a href="#">';
$config['i_paging']['cur_tag_close']         = '</a></li>';
$config['i_paging']['first_tag_open']         = '<li>';
$config['i_paging']['first_tag_close']         = '</li>';
$config['i_paging']['last_tag_open']         = '<li>';
$config['i_paging']['last_tag_close']         = '</li>';
$config['i_paging']['next_tag_open']         = '<li>';
$config['i_paging']['next_tag_close']         = '</li>';
$config['i_paging']['prev_tag_open']         = '<li>';
$config['i_paging']['prev_tag_close']         = '</li>';
$config['i_paging']['total_tag_open']         = '<div class="pagination_info  pull-left"><label>';
$config['i_paging']['total_tag_close']         = '</label></div>';
$config['i_paging']['cur_page_text']         = 'Page';
$config['i_paging']['total_page_text']         = 'of';
$config['i_paging']['total_data_text']         = 'Items';
$config['i_paging']['badge_tag_open']        = '<span class="badge">';
$config['i_paging']['badge_tag_close']      = '</span>';
$config['i_paging']['page_size_text']         = 'Page Size';
$config['i_paging']['page_size_tag_open']         = '<div class="pagination_info form-inline pull-right"><label>Page Size : </label> ';
$config['i_paging']['page_size_tag_close']         = '</div>';
$config['i_paging']['page_size_option']         = array(0 => 'All',5=> 5, 25=>25, 50=>50,100=>100);
$config['i_paging']['page_size_attr'] = array('class' => 'i_select_pagesize form-control input-sm');
$config['i_paging']['suffix'] = '';







$config['status']['error'] = 'error';
$config['status']['warning'] = 'warning';
$config['status']['success'] = 'success';

$config['msg_save_success']     = 'Data berhasil di simpan';
$config['msg_save_error']      = 'Data gagal di simpan';
$config['msg_delete_success']   = 'Data berhasil di hapus';
$config['msg_delete_error']    = 'Data gagal di hapus';
$config['msg_delete_confirm']   = 'Anda yakin akan menghapus data : ';
$config['msg_update_pass']   = 'Anda yakin akan mengubah password : ';
