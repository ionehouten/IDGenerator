<?php

/*
 * Copyright (c) 2016, Mitos
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * * Neither the name of the copyright holder nor the names of its contributors may be 
 *   used to endorse or promote products derived from this software without specific 
 *   prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * 
 * @package     CodeIgniter
 * @author      Iwan Setiawan
 * @license     https://opensource.org/licenses/BSD-3-Clause BSD License
 * @since       1.0.0
 */

/**
 * IDGenerator Form Class
 *
 * This class enables the creation of form
 *
 * @package     CodeIgniter
 * @subpackage	Libraries
 * @category	IGenerator
 * @author	Iwan Setiawan
 * 
 */
class I_Form extends I_Grid {
    
    public $form;
    public function __construct() {
        parent::__construct();
    }
    public function init_form() {  
        //$this->id = create_key('alpha');
        $this->grid = $this;
        
        $this->config = $this->CI->config->item('i_grid');
        $this->link = $this->CI->config->item('i_link');
        $this->btn = $this->CI->config->item('i_btn');
        
        $this->properties = new ID_properties();
        $this->initialize($this->config['properties'], 'properties');

        $this->layout = new ID_layout();
        $this->initialize($this->config['layout'], 'layout');

        $this->html = new ID_html();
        $this->initialize($this->config['html'], 'html');

        $this->view = new ID_view();
        
        $this->form = new ID_form();
        $this->initialize($this->config['form'], 'form');
        
        $this->uri = I_Generator::get_uri();
        
        
        
        return $this;
    }
    
    protected function set_column(){
        $this->table->get_fields();
        if (count($this->view->columns) == 0) {
            foreach ($this->table->get_field_list() as $key => $val) {
                $total_length = $this->table->get_field_length();
                $max_length = $this->table->get_field_data()[$key]['max_length'];
                $type = $this->table->get_field_data()[$key]['type'];
                $primary_key = $this->table->get_field_data()[$key]['primary_key'];
                if($max_length === 0){
                    $width = 0;
                }else{
                    $width = ceil(($max_length / $total_length) * 100)."%";
                }
                
                $this->view->add_column($val,$val,$width);
                if($this->form !== null){
                    $this->form->add_column($val,$val,($primary_key == true) ? 'hidden' : $type, $max_length);
                }
            }
        }
        
    }
    
    public function form_init(){
        $this->properties->id_prefix_grid = $this->properties->id_prefix_grid . $this->id;
        $this->properties->id_prefix_form = $this->properties->id_prefix_form . $this->id;
        $this->properties->id_prefix_table = $this->properties->id_prefix_table . $this->id;
        $this->properties->loading = $this->CI->config->item('i_grid')['loading'][$this->properties->loading];
        
        $this->layout->panel['main']['attr']['id'] =$this->properties->id_prefix_grid;
      
      
        $this->form->main['form']['action'] =  current_url();
        $this->form->main['form']['attr']['id'] = $this->properties->id_prefix_form;
        
        $this->parse_attributes($this->vars['layout'], 'layout');
        $this->parse_attributes($this->vars['properties'], 'properties');
        $this->parse_attributes($this->vars['html'], 'html');
        $this->parse_attributes($this->vars['form'], 'form');
        
        $this->link['default']['attr']['id_generator'] = $this->id;
        $this->link['default']['attr']['id_grid'] = $this->properties->id_prefix_grid;
        foreach( $this->link as $key => $val){
            if($key != 'default'){
                if(!isset($this->link[$key]['attr']['href'])){
                    $this->link[$key]['attr']['href'] = current_url();
                }
            }
            
        }
        
        
        $this->link = $this->parse_attribute($this->link);
        $this->btn = $this->parse_attribute($this->btn);
        
        
        if (count($this->view->columns) == 0) {
            foreach ($this->table->get_field_list() as $key => $val) {
                $total_length = $this->table->get_field_length();
                $max_length = $this->table->get_field_data()[$key]['max_length'];
                $width = ceil(($max_length / $total_length) * 100)."%";
                $this->view->add_column($val,$val,$width);
            }
        }
        
        foreach ($this->view->columns as $key => $val) {
            $this->view->add_row($key, $val);
        }
        
        $this->get_request();
    }
    
    public function form_body($data){
        $output = $this->layout->panel['main']['tag_open'];
        $output .= $this->properties->loading;


        $output .= $this->layout->panel['form_show']['tag_open'];
        
        $output .= $this->layout->panel['head']['tag_open'];

        $output .= $this->layout->button['left']['tag_open'];
        $output .= $this->properties->icon . ' ' . $this->properties->title;
        $output .= $this->layout->button['left']['tag_close'];

        $output .= $this->layout->panel['head']['tag_close'];


        $output .= $this->layout->panel['body_show']['tag_open'];
        $output .= $data;
        $output .= $this->layout->panel['body_show']['tag_close'];


        $output .= $this->layout->panel['foot']['tag_open'];
        $output .= $this->layout->panel['foot']['tag_close'];

        $output .= $this->layout->panel['form_show']['tag_close'];
        #end panel_form
       
        $output .= $this->layout->panel['main']['tag_close'];
        #end main_root
        return $output;
    }
    
    public function form_create($data = null){
        
        if($this->get_input('request') == NULL){
            $this->set_input('request','form'); 
            $this->set_input('action','create'); 
        }
        if($this->get_input('request') != 'form'){
            $input_data = $this->get_input();
            switch($this->get_input('action')){
                case "create":
                    $save = $this->table->save($input_data);
                    $this->show_result('save',$save['status'],$save['message']);
                    break;
                case "update":
                   
                    $save = $this->table->save($input_data,$this->table->where);
                    $this->show_result('save',$save['status'],$save['message']);
                    break;
                case "delete" :
                    $save = $this->table->delete($this->table->where);
                    $this->show_result('save',$save['status'],$save['message']);
                    break;
            }
            
            exit(0);
        }
        
        $this->form_init();
        $output = "";
       
        $this->form->main['form']['hidden'] = array_merge($this->form->main['form']['hidden'], array('action' => $this->get_input('action')));
        
        if($this->form->main['form']['multipart'] == true){
            $output .= form_open_multipart($this->form->main['form']['action'], $this->form->main['form']['attr'],$this->form->main['form']['hidden']);
        }else{
            $output .= form_open($this->form->main['form']['action'], $this->form->main['form']['attr'],$this->form->main['form']['hidden']);
        }
        
        foreach($this->form->columns as $key => $val){
            switch(strtolower($val['type'])){
                case "char":
                case "varchar":
                    $val['type'] =  'text';
                    break;
                case "tinytext":
                case "text":
                case "mediumtext":
                case "longtext":
                    $val['type'] =  'textarea';
                    break;
                case "tinyint" :
                case "smallint" :
                case "mediumint" :
                case "int" :
                case "integer" :
                case "bigint" :
                case "real":
                case "numeric":
                case "decimal":
                case "float":
                case "double":
                    $val['type'] =  'number';
                    break;
                case "date":
                    $val['type'] =  'date';
                    break;
                case "time":
                    $val['type'] =  'time';
                    break;
                case "datetime":
                    $val['type'] =  'datetime';
                    break;
                case "tinyblob":
                case "blob":
                case "mediumblob":
                case "longblob":
                    $val['type'] =  'file';
                    break;
                case "hidden":
                case "text":
                case "email":
                case "number":
                case "password":
                case "textarea":
                case "select":
                case "multiselect":
                case "radio":
                case "multiradio":
                case "checkbox":
                case "multicheckbox":
                case "image":
                    $val['type'] = $val['type'] ;
                    break;
                default :
                    $val['type'] =  'text';
                    break;
            }
           
            $type = array_key_exists($val['type'], $this->form->type) ? $val['type'] : 'text';
            
            foreach($this->form->type[$type] as $key_dfl => $val_dfl){
                if(isset($val[$key_dfl]) && empty($val[$key_dfl]) ){
                    $val[$key_dfl] = $val_dfl;
                }
            }
            
            
            $val['multiple_separator'] =  $this->form->main['form']['multiple_separator'];
            $val['multiple_index'] =  $this->form->main['form']['multiple_index'];
            $val['multiple'] = ($this->form->main['form']['multiple'] == true)? '['.$val['multiple_index'].']' : '';
            $val['value'] = (isset($data[$key])) ? $data[$key] : '';
            $val['name'] = $key.$val['multiple'];
            
            $f_data = array();
            $f_data['id'] = $key;
            $f_data['name'] = $val['name'];
            $f_data['type'] = $val['type'];
            $f_data['maxlength'] = $val['maxlength'];
            $f_data['value'] = $val['value'];
            
            if($this->form->main['form']['multiple'] == true){
                $output .= form_hidden('multiple_index',$val['multiple_index']);
            }
            
            if ($f_data['type'] == 'hidden') {
                $output .= form_hidden($f_data['name'], $f_data['value']);
            } else {
                $output .= $val['group_open']; 
                $output .= $val['label_open'].$this->table->field_to_title($val['label']).$val['label_close'];
                switch($val['type']){
                    case "text":
                    case "email":
                    case "number":
                    case "password":
                            //$output .= $val['tag_open'];
                            $output .= form_input($f_data,$f_data['value'],$val['attr']);
                            //$output .= $val['tag_close'];
                            break;
                    case "textarea":
                            //$output .= $val['tag_open'];
                            $output .= form_textarea($f_data,$f_data['value'],$val['attr']);
                            //$output .= $val['tag_close'];
                            break;
                    case "select":
                            unset($f_data['value']);
                            unset($f_data['type']);
                            unset($f_data['maxlength']);
                            //$output .= $val['tag_open'];
                            $output .= form_dropdown($f_data['name'],$val['data'],$val['value'],$val['attr']);
                            //$output .= $val['tag_close'];
                            break;
                    case "multiselect":
                            if(!is_array($val['value'])){
                                $val['value'] = explode($val['multiple_separator'],$val['value']);
                            }
                            //$output .= $val['tag_open'];
                            $output .= form_multiselect($f_data['name'].'[]',$val['data'],$val['value'],$val['attr']);
                            //$output .= $val['tag_close'];
                            break;
                    case "radio":
                            $output .= '<div class="clear"></div>';
                            foreach($val['data'] as $key_rdo => $val_rdo){
                                    $f_data['value'] = $key_rdo;
                                    $f_data['checked'] = ($key_rdo == $val['value']) ? true : false;
                                    $output .= $val['tag_open_input'];
                                    $output .= form_radio($f_data).' '.$val_rdo;
                                    $output .= $val['tag_close_input'];
                            }

                            break;
                    case "multiradio":
                            unset($f_data['type']);
                            unset($f_data['maxlength']);
                            if(!is_array($val['value'])){
                                    $val['value'] = explode($val['multiple_separator'],$val['value']);
                            }

                            $output .= '<div class="clear"></div>';
                            $f_data['name'] = $f_data['name'].'[]';
                            foreach($val['data'] as $key_rdo => $val_rdo){

                                    $f_data['value'] = $key_rdo;
                                    $f_data['checked'] = (in_array($key_rdo,$val['value'])) ? true : false;

                                    $output .= $val['tag_open_input'];
                                    $output .= form_radio($f_data).' '.$val_rdo;
                                    $output .= $val['tag_close_input'];
                            }
                            break;
                    case "checkbox":
                            $output .= '<div class="clear"></div>';
                            foreach($val['data'] as $key_chk => $val_chk){
                                    $f_data['value'] = $key_chk;
                                    $f_data['checked'] = ($key_chk == $val['value']) ? true : false;
                                    $output .= $val['tag_open_input'];
                                    $output .= form_checkbox($f_data).' '.$val_chk;
                                    $output .= $val['tag_close_input'];
                            }
                            break;
                    case "multicheckbox":
                            unset($f_data['type']);
                            unset($f_data['maxlength']);
                            if(!is_array($val['value'])){
                                    $val['value'] = explode($val['multiple_separator'],$val['value']);
                            }

                            $output .= '<div class="clear"></div>';
                            $f_data['name'] = $f_data['name'].'[]';
                            foreach($val['data'] as $key_chk => $val_chk){

                                    $f_data['value'] = $key_chk;
                                    $f_data['checked'] = (in_array($key_chk,$val['value'])) ? true : false;

                                    $output .= $val['tag_open_input'];
                                    $output .= form_checkbox($f_data).' '.$val_chk;
                                    $output .= $val['tag_close_input'];
                            }
                            break;
                    case "image" :
                           
                            if($val['attr']['multiple'] == true){
                                    $f_data['name'] = $key.$val['multiple'].'[]';
                                    $f_data['multiple'] = 'multiple'; 
                            }else{
                                    unset($f_data['multiple']);
                            }
                            $output .= form_hidden($f_data['name'].'_old',$val['value']);
                            $output .= form_upload($f_data); 

                            $file_ext = pathinfo($f_data['value'], PATHINFO_EXTENSION);
                            $val['upload']['id'] = $key;
                            if(in_array($file_ext,$val['extension'])){
                                    if(!filter_var($f_data['value'], FILTER_VALIDATE_URL)){
                                            $f_data['value'] = base_url($f_data['value']);
                                    }
                               $val['upload']['initial_preview'] ="'<img src=\"".$f_data['value']."\" class=\"file-preview-image\">'";
                            }

                            $output .= $this->script_file($val['upload']);

                            break;
                }
                
                $output .= $val['group_close'];
            }
        }
        
        $output .= $this->layout->separator;
        $output .= $this->layout->button_group['right']['tag_open'];
        foreach($this->form->button as $key => $val){
            if (isset( $this->btn[$val])) {
                $output .= $this->btn[$val]['tag_full'];
            }
        }
        $output .= $this->layout->button_group['right']['tag_close'];
        
        
        $output .= form_close();
        
        
        if($this->CI->input->is_ajax_request()){
            if( ($this->CI->input->post('request') == 'form')){
                echo $output;
                exit(0);
            }else{
                return $output;
            }
        }else{
            return $this->form_body($output);
        }
        
    }
    
    public function script_file($option = array()){
        $output = "<script>";
            $output .= '$("#'.$option['id'].'").fileinput({';
                $output .= 'initialPreview: ['.$option['initial_preview'].'],';
                $output .= 'overwriteInitial: '.$option['overwrite_initial'].',';
                $output .= 'maxFileSize :'. $option['max_filesize'].',';
                $output .= 'maxFilesNum: '.$option['max_filesnum'].',';
                $output .= 'allowedFileTypes: ['.$option['allowed_filetypes'].'],';
                $output .= 'allowedFileExtensions: ['.$option['allowed_fileextensions'].'],';
                $output .= 'previewClass : \''.$option['preview_class'].'\',';
                $output .= 'showUpload: '.$option['show_upload'].',';
                $output .= 'showRemove: '.$option['show_remove'].',';
                $output .= 'showPreview: '.$option['show_preview'].',';
                $output .= "slugCallback: function(filename) {return filename.replace('(', '_').replace(']', '_');}";
            $output .= '});';
        $output .= "</script>";
        
        return $output;
    }
    
    
    
}

class ID_form{
    public $columns = array();
    public $main = array();
    public $button = array();
    public $type = array();
    
    public function add_column($fieldname,$label='', $type = 'text', $maxlength='', array $data = [] ){
        $label = (empty($label)) ? $fieldname : $label;
        $this->columns[$fieldname]['label'] = $label;
        $this->columns[$fieldname]['type'] = $type;
        $this->columns[$fieldname]['maxlength'] = $maxlength;
        $this->columns[$fieldname]['data'] = $data;
        $this->columns[$fieldname]['label_open'] = '';
        $this->columns[$fieldname]['label_close'] = '';
        $this->columns[$fieldname]['attr'] = array();
        $this->columns[$fieldname]['group_open'] = '';
        $this->columns[$fieldname]['group_close'] = '';
        $this->columns[$fieldname]['tag_open'] = '';
        $this->columns[$fieldname]['tag_close'] = '';
        $this->columns[$fieldname]['tag_open_input'] = '';
        $this->columns[$fieldname]['tag_close_input'] = '';
        $this->columns[$fieldname]['upload'] = array();
        $this->columns[$fieldname]['extension'] = array();
        
        
    }
    
    public function update_key($array, $key1, $key2){
        $keys = array_keys($array);
        $index = array_search($key1, $keys);

        if ($index !== false) {
            $keys[$index] = $key2;
            $array = array_combine($keys, $array);
        }

        return $array;
    }
    
    public function reset_column(){
        $this->columns = array();
    }
    
    public function remove_column($fieldname){
        if(isset($this->columns[$fieldname])){
            unset($this->columns[$fieldname]);
        }
        
    }
    
    public function move_column($fieldname, $index){
        if(isset($this->columns[$fieldname])){
            $array = $this->columns[$fieldname];
            
            if (is_int($fieldname)) {
                $tmp = array_splice($array, $fieldname, 1);
                array_splice($array, $index, 0, $tmp);
                $output = $array;
            }
            elseif (is_string($fieldname)) {
                $indexToMove = array_search($fieldname, array_keys($array));
                $itemToMove = $array[$fieldname];
                array_splice($array, $indexToMove, 1);
                $i = 0;
                $output = Array();
                foreach($array as $key => $item) {
                    if ($i == $index) {
                        $output[$fieldname] = $itemToMove;
                    }
                    $output[$key] = $item;
                    $i++;
                }
            }
            $this->columns[$fieldname] = $output;
        }
    }
   
    public function set_column_label($fieldname,$label){
        $this->columns[$fieldname]['label'] = $label;
    }
    
    public function set_column_type($fieldname,$type){
        $this->columns[$fieldname]['type'] = $type;
    }
    public function set_column_data($fieldname,$data = array(),$type = ""){
        $this->columns[$fieldname]['data'] = $data;
        if(!empty($type)){
            $this->columns[$fieldname]['type'] = $type;
        }
    }
    public function set_column_tag($fieldname,$tag_open, $tag_close){
        $this->columns[$fieldname]['tag_open'] = $tag_open;
        $this->columns[$fieldname]['tag_close'] = $tag_close;
    }
    public function set_column_group_tag($fieldname,$group_open, $group_close){
        $this->columns[$fieldname]['group_open'] = $group_open;
        $this->columns[$fieldname]['group_close'] = $group_close;
    }
    public function set_column_label_tag($fieldname,$label_open, $label_close){
        $this->columns[$fieldname]['label_open'] = $label_open;
        $this->columns[$fieldname]['label_close'] = $label_close;
    }
    
    public function set_column_attr($fieldname,array $attr = []){
        $this->columns[$fieldname]['attr'] = $attr;
    }
    public function set_column_attr_string($fieldname,$attr){
        $this->columns[$fieldname]['attr_str'] = $attr;
    }
}
