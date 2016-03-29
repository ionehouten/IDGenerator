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
 * IDGenerator Grid Class
 *
 * This class enables the creation of table
 *
 * @package     CodeIgniter
 * @subpackage	Libraries
 * @category	IGenerator
 * @author	Iwan Setiawan
 * 
 */
class I_Grid extends I_GeneratorBridge {

    public $properties;
    public $layout;
    public $html;
    public $view;
    public $search;
    public $tmp;
    public $uri;
    public $config;
    public $link;
    public $btn;
    public $id;
    protected $search_data = array();
 
    
    public function __construct() {
        parent::__construct();
    }

    public function init_grid() {  
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
        
        $this->search = new ID_search();
        $this->initialize($this->config['search'], 'search');
        
        $this->uri = I_Generator::get_uri();
        
        return $this;
    }

    protected function initialize(array $config = array(), $object = null) {
        if (isset($config)) {
            foreach ($config as $key => $val) {
                if ($object === null) {
                    if (property_exists($this, $key)) {
                        $this->$key = $val;
                    }
                } else {
                    if (property_exists($this, $object)) {
                        if (property_exists($this->$object, $key)) {
                            $this->$object->$key = $val;
                            $this->set_vars($object, $key);
                        }
                    }
                }
            }
        }
    }

    protected function parse_attributes(array $config = array(), $object = null) {

        foreach ($config as $key => $val) {
            if (property_exists($this->$object, $key)) {
                $data = $this->$object->$key;

                if (is_array($data)) {

                    /* @var $key_data type */
                    foreach ($data as $key_data => $val_data) {
                        if (is_array($val_data)) {

                            foreach ($val_data as $key_dtl => $val_dtl) {

                                if (array_key_exists('default', $data)) {
                                    if (is_array($data['default'])) {
                                        foreach ($data['default']as $key_dfl => $val_dfl) {
                                            if (!array_key_exists($key_dfl, $data[$key_data])) {
                                                $data[$key_data][$key_dfl] = $val_dfl;
                                            }
                                        }
                                    }
                                }
                                if (is_array($val_dtl)) {

                                    if ($key_dtl == 'attr' && is_array($val_dtl)) {
                                        $data[$key_data]['attr_str'] = '';
                                        foreach ($val_dtl as $key_attr => $val_attr) {
                                            $data[$key_data]['attr_str'] .= sprintf(' %s="%s"', $key_attr, $val_attr);
                                        }
                                        $data[$key_data]['attr_str']= $data[$key_data]['attr_str'];
                                    }

                                    if (isset($data[$key_data]['tag_open']) && isset($data[$key_data]['tag_close']) && isset($data[$key_data]['attr'])) {
                                        $tag_open = substr($data[$key_data]['tag_open'], 0, strlen($data[$key_data]['tag_open']) - 1);
                                        $tag_open .= $data[$key_data]['attr_str'];
                                        $tag_open .= substr($data[$key_data]['tag_open'], strlen($data[$key_data]['tag_open']) - 1, 1);
                                        $data[$key_data]['tag_open'] = $tag_open;

                                        if (isset($data[$key_data]['text'])) {
                                            $data[$key_data]['tag_full'] = $data[$key_data]['tag_open'] . $data[$key_data]['text'] . $data[$key_data]['tag_close'];
                                        }


                                        //unset($data[$key_data]['attr']);
                                    }
                                }
                            }
                        }
                    }
                }

                $this->$object->$key = $data;
            }
        }
    }

    protected function parse_attribute($data){
         foreach ($data as $key_data => $val_data) {
            if (is_array($val_data)) {
                if (isset($val_data['uri'])) {
                    $val_data['attr']['href'] = site_url($val_data['uri']);
                }
                
                if (isset($val_data['attr']['href'])) {
                    if (!empty($val_data['query'])) {
                        $val_data['attr']['href'].= "?" . $val_data['query'];
                    }
                }
                
                foreach ($val_data as $key_dtl => $val_dtl) {

                    if (array_key_exists('default', $data)) {
                        if (is_array($data['default'])) {
                            foreach ($data['default']as $key_dfl => $val_dfl) {
                                if (!isset($data[$key_data][$key_dfl])) {
                                    $data[$key_data][$key_dfl] = $val_dfl;
                                }
                            }
                        }
                    }
                    
                    if (is_array($val_dtl)) {
                        
                        if ($key_dtl == 'attr' && is_array($val_dtl)) {
                            
                            
                            $data[$key_data][$key_dtl] = '';
                            foreach ($val_dtl as $key_attr => $val_attr) {
                                $data[$key_data][$key_dtl] .= sprintf(' %s="%s"', $key_attr, $val_attr);
                            }
                            $data[$key_data]['attr_str']= $data[$key_data][$key_dtl];
                          
                        }

                        if (isset($data[$key_data]['tag_open']) && isset($data[$key_data]['tag_close']) && isset($data[$key_data]['attr'])) {
                            $tag_open = substr($data[$key_data]['tag_open'], 0, strlen($data[$key_data]['tag_open']) - 1);
                            $tag_open .= $data[$key_data]['attr'];
                            $tag_open .= substr($data[$key_data]['tag_open'], strlen($data[$key_data]['tag_open']) - 1, 1);
                            $data[$key_data]['tag_open'] = $tag_open;

                            if (isset($data[$key_data]['text'])) {
                                $data[$key_data]['tag_full'] = $data[$key_data]['tag_open'] . $data[$key_data]['text'] . $data[$key_data]['tag_close'];
                            }


                            unset($data[$key_data]['attr']);
                        }
                    }
                }
            }
        }
        return $data;
    }
    
    protected function check_request(){
        
        if ($this->CI->input->is_ajax_request()) {
            if($this->CI->input->post('id_generator',true) == $this->id){
                return TRUE;
            }else{
                //return FALSE;
                return TRUE;
            }
        }else{
            return TRUE;
        }
    }
    
    protected function get_request(){
        
        if ($this->CI->input->is_ajax_request()) {
            if($this->CI->input->post('id_generator',true) == $this->id){
                
                if($this->CI->input->post('page_size') != null ){
                    $this->table->limit = $this->CI->input->post('page_size');
                }
                if($this->CI->input->post('i_pagination_page') != null){
                    $this->table->page = $this->CI->input->post('i_pagination_page');
                }
                if($this->CI->input->get('refresh',true)){
                    $this->CI->session->unset_userdata($this->CI->input->get('refresh',true));
                }
                $request = $this->CI->input->post('request',true) ;
                switch($request){
                    case "refresh" :
                        $this->CI->session->unset_userdata($this->CI->input->post('id_generator',true));
                        $this->CI->session->unset_userdata($this->properties->id_prefix_form);
                        break;
                    case "paging" :
                        break;
                }
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            $this->table->page = isset($this->CI->uri->segments[$this->uri['count_segments'] + ($this->id - 1)]) ? $this->CI->uri->segments[$this->uri['count_segments'] + ($this->id - 1)] : 1;
            return TRUE;
        }
    }
    
    protected function get_search() {
        $output = array();
        $session = array();
        
        $id = $this->properties->id_prefix_form;
        $post = $this->CI->input->post(null, true);
       
        if (count($post) > 0) {
            foreach ($post as $key => $val) {
                $key = explode('#', $key);
                if (count($key) > 1) {
                    if ($key[0] == $id) {
                        $output[$key[1]] = $val;
                    }
                }
            }
        }
       
        if ($output == null) {
            $output = $this->CI->session->userdata($id);
        }
        
        if (count($output) > 0) {
            $session[$id] = $output;
            $this->CI->session->set_userdata($session);
            $this->search_data = $output;
            return $output;
        } else {
            $this->search_data = null;
            return  null;
        }
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
            }
        }
        
    }
    
    protected function find_row(){
        $field_list = $this->table->get_field_list();
        if(count($this->view->rows) > 0){
            foreach($this->view->rows as $key => $val){
                foreach($val as $key_dtl => $val_dtl){
                    if(array_key_exists($key_dtl,$field_list)){
                        $this->view->index_main_row = $key;
                    }
                }
            }
        }
    }
    
    public function create_key($type = 'md5', $len = 8) {
        switch ($type) {
            case 'date' :
                return date('ymdhis') . round(microtime(true) * 1000);
            case 'basic':
                return mt_rand();
            case 'alnum':
            case 'numeric':
            case 'nozero':
            case 'alpha':
                switch ($type) {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $pool = '123456789';
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);

            case 'md5':
                //return md5(uniqid(mt_rand()));
                return md5(date('ymdhis') . round(microtime(true) * 1000));
            case 'sha1':
                //return sha1(uniqid(mt_rand(), TRUE));
                return sha1(date('ymdhis') . round(microtime(true) * 1000));
        }
    }

    public function set_title($title){
        $this->properties->title = $title;
    }
    
    public function set_table($table){
        $this->table->name = $table;
        $this->set_column();
    }
    
    public function set_view($view){
        $this->table->view = $view;
        $this->set_column();
    }
    
    public function set_join($table,$condition, $type =''){
        $i = count($this->join);
        $this->table->join[$i]['table'] = $table;
        $this->table->join[$i]['cond'] = $condition;
        $this->table->join[$i]['type'] = $type;
    }
    
    public function set_where($where){
        $this->table->where = $where;
    }
    
    public function set_like($like){
        $this->table->like = $like;
    }
    
    public function set_or_like($or_like){
        $this->table->or_like = $or_like;
    }
    
    public function set_group($group){
        $this->table->group = $group;
    }
    
    public function set_order($order){
        $this->table->order = $order;
    }
    
    public function set_page($page){
        $this->table->page = $page;
    }
    
    public function set_limit($limit){
        $this->table->limit = $limit;
    }
    
    public function set_offset($offset){
        $this->table->offset = $offset;
    }
    
    public function set_fields($fields = array()){
        $this->table->fields = $fields;
    }
    
    public function set_query($query, $params= array()){
        $this->table->query = $query;
        if(count($params) > 0){
            $this->table->params = $params;
        }
        $this->set_column();
    }
    
    public function set_query_count($query_count,$params_count= array()){
        $this->table->query_count = $query_count;
        if(count($params_count) > 0){
            $this->table->params_count = $params_count;
        }
    }
    
    public function set_params($params= array()){
        $this->table->params = $params;
    }
    
    public function set_link_attr($name, $attr, $value){
        $this->link[$name][$attr] = $value;
    }
    
    public function set_btn_attr($name, $attr, $value){
        $this->btn[$name][$attr] = $value;
    }
    
    public function show_result($type = 'save', $status = 'warning', $message = '', $redirect = '', $html = '') {
        $data = array();
        $data['status'] = $status;
        $data['message'] = $message;
        $data['redirect'] = '';
        if (!empty($html)) {
            $data['html'] = $html;
        }
        if ($type == 'save') {
            if (!empty($redirect)) {
                $data['redirect'] = $redirect;
            }
        } 

        
         if ($this->CI->input->is_ajax_request()) {
            echo json_encode($data);
        } else {
            redirect($redirect);
        }
    }
    
    public function table_init() {
        
        $this->properties->id_prefix_grid = $this->properties->id_prefix_grid . $this->id;
        $this->properties->id_prefix_form = $this->properties->id_prefix_form . $this->id;
        $this->properties->id_prefix_table = $this->properties->id_prefix_table . $this->id;
        $this->properties->loading = $this->CI->config->item('i_grid')['loading'][$this->properties->loading];

        
        $this->layout->panel['main']['attr']['id'] =$this->properties->id_prefix_grid;
      
        $this->search->main['form']['action'] =site_url($this->uri['segments']);
         
        $this->parse_attributes($this->vars['layout'], 'layout');
        $this->parse_attributes($this->vars['properties'], 'properties');
        $this->parse_attributes($this->vars['html'], 'html');
        $this->parse_attributes($this->vars['search'], 'search');
        
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
        $this->get_search();
        $this->find_row();
    }

    public function table_head() {

        $output = $this->html->table['group']['tag_open'];
        foreach ($this->view->rows[$this->view->index_main_row] as $key => $val) {

            $output .= $this->html->table['col']['tag_open'];
            $output .= 'width="' . $val['width'] . '"';
            $output .= $this->html->table['col']['tag_close'];
        }
        $output .= $this->html->table['group']['tag_close'];

        $output .= $this->html->table['head']['tag_open'];
        $output .= $this->table_row_title($this->view->rows);
        $output .= $this->table_row_filter($this->view->rows[$this->view->index_main_row]);
        $output .= $this->html->table['head']['tag_close'];

        return $output;
    }

    public function table_foot() {
        krsort($this->view->rows);
        $output = $this->html->table['foot']['tag_open'];
        $output .= $this->table_row_title($this->view->rows);
        $output .= $this->html->table['foot']['tag_close'];

        return $output;
    }

    public function table_body($data) {
        $config = $this->CI->config->item('i_grid');
        $config = $config['html'];

        $output = $this->html->table['body']['tag_open'];

        $i = $this->table->offset + 1;
        foreach ($data as $key_data => $val_data) {

            if (array_key_exists($this->view->index_main_row, $this->view->rows)) {

                $output .= $this->html->table['tr']['tag_open'];
                foreach ($this->view->rows[$this->view->index_main_row] as $key_col => $val_col) {
                    $td_attr_str = "";
                    $td_attr = $config['table']['td']['attr'];

                    if (array_key_exists("td", $val_col)) {
                        if (is_array($val_col['td'])) {
                            foreach ($val_col['td'] as $key_td => $val_td) {
                                if (!is_array($val_td)) {
                                    $td_attr[$key_td] = $val_td;
                                }
                            }
                        } else {
                            $td_attr = $val_col['td'];
                        }
                    }
                    if (is_array($td_attr)) {
                        foreach ($td_attr as $key_td_attr => $val_td_attr) {
                            $td_attr_str .= $key_td_attr . '="' . $val_td_attr . '" ';
                        }
                    } else {
                        $td_attr_str = $td_attr;
                    }


                    $output .= '<td ' . $td_attr_str . '>';
                    if (in_array($key_col, $this->table->get_field_list())) {
                        if (isset($val_data[$key_col])) {
                            $type = "string";
                            $ext = pathinfo($val_data[$key_col], PATHINFO_EXTENSION);

                            if (in_array($ext, $this->properties->extension['img'])) {
                                $type = 'img';
                            }
                            if (in_array($ext, $this->properties->extension['doc'])) {
                                $type = 'doc';
                            }
                            switch ($type) {
                                case "img":
                                    if (!filter_var($val_data[$key_col], FILTER_VALIDATE_URL)) {
                                        $val_data[$key_col] = base_url($val_data[$key_col]);
                                    }
                                    $output .= $this->properties->thumb['img']['tag_open'] . ' src="' . $val_data[$key_col] . '" ' . $this->properties->thumb['img']['tag_close'];
                                    break;
                                case "doc":
                                    break;
                                default :
                                    if (!empty($val_col['format'])) {

                                        $format = str_replace('$value', '$val_data[$key_col]', $val_col['format']);

                                        $format = eval($format);

                                        $output .= $format;
                                    } else {
                                        $output .= $val_data[$key_col];
                                    }

                                    break;
                            }
                        }
                    } else {
                        switch ($key_col) {
                            case "no":
                                $output .= $i++;
                                break;
                        }
                    }
                    if (array_key_exists('link', $val_col)) {
                        $links = $this->CI->config->item('i_link');
                        
                        foreach ($val_col['link'] as $key_link => $val_link) {
                            if (isset($val_link['uri'])) {
                                $attr_uri_ex = explode('/', $val_link['uri']);

                                foreach ($attr_uri_ex as $key_attr_uri => $val_attr_uri) {
                                    $attr_var = substr($val_attr_uri, 0, 1);
                                    if ($attr_var == '$') {
                                        $attr_uri_ex[$key_attr_uri] = $val_data[substr($val_attr_uri, 1, strlen($val_attr_uri))];
                                    }
                                }
                                $attr_uri_im = implode('/', $attr_uri_ex);
                               
                                $val_link['uri'] = $attr_uri_im;
                            }
                            if(isset($links[$val_link['name']])){
                                $link['default'] = $links['default'];
                                $link['default']['attr']['id_generator'] = $this->id;
                                $link['default']['attr']['id_grid'] = $this->properties->id_prefix_grid;
                                $link[$val_link['name']]  = $links[$val_link['name']];
                                $link[$val_link['name']]['uri'] = $val_link['uri'];
                                $link = $this->parse_attribute($link);
                                $output .= $link[$val_link['name']]['tag_full'];
                            }
                            
                        }
                        
                    }

                    $output .= '</td>';
                }
                $output .= $this->html->table['tr']['tag_close'];
            }
        }
        $output .= $this->html->table['body']['tag_close'];


        return $output;
    }

    public function table_row_title($row = array()) {
        $output = "";
        foreach ($row as $key_row => $val_row) {
            $output .= $this->html->table['tr']['tag_open'];
            foreach ($val_row as $key_col => $val_col) {


                $config = $this->CI->config->item('i_grid');
                $th_title = "";
                $th_attr_str = "";
                $th_attr = $config['html']['table']['th']['attr'];

                $td_attr_str = "";
                $td_attr = $config['html']['table']['td']['attr'];

                if (array_key_exists("th", $val_col)) {
                    if (is_array($val_col['th'])) {
                        foreach ($val_col['th'] as $key_th => $val_th) {
                            if (!is_array($val_th)) {
                                $th_attr[$key_th] = $val_th;
                            }
                        }
                    } else {
                        $th_attr = $val_col['th'];
                    }
                }

                if (is_array($th_attr)) {
                    foreach ($th_attr as $key_th_attr => $val_th_attr) {
                        $th_attr_str .= $key_th_attr . '="' . $val_th_attr . '" ';
                    }
                } else {
                    $th_attr_str = $th_attr;
                }


                if (array_key_exists("td", $val_col)) {
                    if (is_array($val_col['td'])) {
                        foreach ($val_col['td'] as $key_td => $val_td) {
                            if (!is_array($val_td)) {
                                $td_attr[$key_td] = $val_td;
                            }
                        }
                    } else {
                        $td_attr = $val_col['td'];
                    }
                }
                if (is_array($td_attr)) {
                    foreach ($td_attr as $key_td_attr => $val_td_attr) {
                        $td_attr_str .= $key_td_attr . '="' . $val_td_attr . '" ';
                    }
                } else {
                    $td_attr_str = $td_attr;
                }

                $th_title = $this->table->field_to_title($val_col['title']);


                $output .= '<th ' . $th_attr_str . '>' . $th_title . '</th>';
            }
            $output .= $this->html->table['tr']['tag_close'];
        }

        return $output;
    }

    public function table_row_filter($col = array()) {
        $output = '';
        $output .= $this->html->table['tr']['tag_open'];
        foreach ($col as $key => $val) {

            if (count($val['filter']) == 0) {
                $val['filter'] = $this->html->table['filter']['default'];
            }

            $output .= '<td>';
            if (isset($this->html->table['filter'][$val['filter']['type']])) {
                foreach ($this->html->table['filter'][$val['filter']['type']] as $key_flt => $val_flt) {
                    if (!isset($val['filter'][$key_flt])) {
                        $val['filter'][$key_flt] = $val_flt;
                    }
                }


                if (in_array($key, $this->table->get_field_list()) || $key == 'no') {
                    switch ($val['filter']['type']) {
                        case "select":
                            $output .= form_dropdown($val['filter']['attr'], $val['filter']['data']);
                            break;

                        default :
                            $output .= form_input($val['filter']['attr']);
                            break;
                    }
                }
            }
            $output .= '</td>';
        }
        $output .= $this->html->table['tr']['tag_close'];
        return $output;
    }

    public function table_create() {
        
        $check_request = $this->check_request();
        
        if($check_request === FALSE){
            return "";
        }
        
        $this->table_init();
        

        $output = $this->layout->panel['main']['tag_open'];
        $output .= $this->properties->loading;


        $output .= $this->layout->panel['form']['tag_open'];
        $output .= $this->layout->panel['head']['tag_open'];

        $output .= $this->layout->button['left']['tag_open'];
        $output .= $this->properties->icon . ' ' . $this->properties->title;
        $output .= $this->layout->button['left']['tag_close'];

        $output .= $this->layout->button_group['left']['tag_open'];
        if (is_array($this->properties->link_form_head['left'])) {
            foreach ($this->properties->link_form_head['left'] as $key => $val) {
                if (isset( $this->link[$val])) {
                    $output .= $this->link[$val]['tag_full'];
                }
            }
        }
        $output .= $this->layout->button_group['left']['tag_close'];

        $output .= $this->layout->button_group['right']['tag_open'];
        if (is_array($this->properties->link_form_head['right'])) {
            foreach ($this->properties->link_form_head['right'] as $key => $val) {
                if (isset($this->link[$val])) {
                    $output .= $this->link[$val]['tag_full'];
                }
            }
        }
        $output .= $this->layout->button_group['right']['tag_close'];

        $output .= $this->layout->panel['head']['tag_close'];


        $output .= $this->layout->panel['body']['tag_open'];
        $output .= $this->layout->panel['body']['tag_close'];


        $output .= $this->layout->panel['foot']['tag_open'];
        
        $output .= $this->layout->button_group['left']['tag_open'];
        if (is_array($this->properties->link_form_foot['left'])) {
            foreach ($this->properties->link_form_foot['left'] as $key => $val) {
                if (isset( $this->link[$val])) {
                    $output .=  $this->link[$val]['tag_full'];
                }
            }
        }
        $output .= $this->layout->button_group['left']['tag_close'];

        $output .= $this->layout->button_group['right']['tag_open'];
        if (is_array($this->properties->link_form_foot['right'])) {
            foreach ($this->properties->link_form_foot['right'] as $key => $val) {
                if (isset($this->link[$val])) {
                    $output .=  $this->link[$val]['tag_full'];
                }
            }
        }
        $output .= $this->layout->button_group['right']['tag_close'];
        
        $output .= $this->layout->panel['foot']['tag_close'];


        $output .= $this->layout->panel['form']['tag_close'];
        #end panel_form
        #str panel_table
        $output .= $this->layout->panel['table']['tag_open'];
        $output .= $this->layout->panel['head']['tag_open'];

        $output .= $this->layout->button['left']['tag_open'];
        $output .= $this->properties->icon . ' ' . $this->properties->title;
        $output .= $this->layout->button['left']['tag_close'];

        $output .= $this->layout->button_group['left']['tag_open'];
        if (is_array($this->properties->link_table_head['left'])) {
            foreach ($this->properties->link_table_head['left'] as $key => $val) {
                if (isset($this->link[$val])) {
                    $output .=  $this->link[$val]['tag_full'];
                }
            }
        }
        $output .= $this->layout->button_group['left']['tag_close'];

        $output .= $this->layout->button_group['right']['tag_open'];
        if (is_array($this->properties->link_table_head['right'])) {
            foreach ($this->properties->link_table_head['right'] as $key => $val) {
                if (isset( $this->link[$val])) {
                    $output .=  $this->link[$val]['tag_full'];
                }
            }
        }
        $output .= $this->layout->button_group['right']['tag_close'];

        $output .= $this->layout->panel['head']['tag_close'];


        $output .= $this->layout->panel['body']['tag_open'];
        $output .= $this->table_search();
        $output .= $this->layout->panel['body']['tag_close'];
        $output .= $this->html->table['root']['tag_open'];
        $output .= $this->html->table['main']['tag_open'];
        $output .= $this->table_head();
        
        $output .= $this->table_body($this->table->data());
        $output .= $this->table_foot();
        $output .= $this->html->table['main']['tag_close'];
        $output .= $this->html->table['root']['tag_close'];

        $output .= $this->layout->panel['foot']['tag_open'];
        $output .= $this->table_paging();
        $output .= $this->layout->panel['foot']['tag_close'];
        $output .= $this->layout->panel['table']['tag_close'];
        #end panel_table

        $output .= $this->layout->panel['main']['tag_close'];
        #end main_root
        
        //$output .= 'Page rendered in <strong>'.$this->CI->benchmark->elapsed_time().'</strong> seconds'; 
        if ($this->CI->input->is_ajax_request()) {
            if (
                    ($this->CI->input->post('request') == 'refresh' && $this->CI->input->post('id_generator') == $this->id) ||
                    ($this->CI->input->post('request') == 'paging' && $this->CI->input->post('id_generator') ==  $this->id) ||
                    ($this->CI->input->post('request') == 'search' && $this->CI->input->post('id_generator') ==  $this->id)
            ) {
                echo $output;
                exit(1);
            } else {
                return $output;
            }
        } else {
            return $output;
        }

        
    }

    public function table_paging() {
        if ($this->table->limit == 0 && $this->table->offset == 0) {
            return;
        }
       

        $schema_id = $this->id;
        $schema_count = $this->id - 1;

        $uri = $this->uri['segments'];
        $uri_segment = $this->uri['count_segments'];
        $uri_segment_current = count($this->CI->uri->segment_array());
        
        $config = $this->CI->config->item('i_paging');
        
        
        if($schema_count > 0){      
            $k = 3;
            for($i = 0; $i < $schema_count; $i++){
                $uri[$k] = $this->CI->uri->segment($uri_segment,1);
                $uri_segment++;
                $k++;
            }
                
            $uri_suffix = array();
            if($uri_segment == $uri_segment_current){
                $config['suffix'] = "";
            }
            else if($uri_segment_current > $uri_segment){
                for($i = 1 ; $i <= $uri_segment_current; $i++){
                    if($i > $uri_segment){
                        $uri_suffix[$i] = $this->CI->uri->segment($i,1);
                    }
                }
                $uri_suffix = '/'.implode('/',$uri_suffix);
                $config['suffix'] =$uri_suffix;
            }
        }else{
     
            $uri_suffix = array();
            if($uri_segment_current > $uri_segment){
                for($i = 1 ; $i <= $uri_segment_current; $i++){
                    if($i > $uri_segment){
                        $uri_suffix[$i] = $this->CI->uri->segment($i,1);
                    }
                }
                $uri_suffix = '/'.implode('/',$uri_suffix);
                $config['suffix'] =$uri_suffix;
            }
        }
       
        $base_url = site_url($uri);
        $config['suffix'] =  $config['suffix'];
        $config['attr']['id_generator'] = $this->id;
        $config['attr']['id_grid'] = $this->properties->id_prefix_grid;
        $config['per_page'] = $this->table->limit;
        $config['base_url'] =  $base_url;
        $config['uri_segment'] =  $uri_segment;
        $config['total_rows'] =  $this->table->data('total');
        $config['page_size_attr']['id_generator'] = $this->id;
        $config['page_size_attr']['id_grid'] = $this->properties->id_prefix_grid;
        $this->CI->i_pagination->initialize($config);
        $output =  $this->CI->i_pagination->create_links();
      
        
        return $output;
        
        
    }
    
    public function table_search(){
        $field_list = $this->table->get_field_list();
        if(count($this->search->columns) == 0){
            foreach ($this->view->rows[$this->view->index_main_row] as $key => $val) {
                if(in_array($key,$field_list)){
                     $this->search->add_column(array_search($key,$field_list),$val['title']);
                }
                
            }
        }
        
        $where_default =  $this->table->where;
        $this->search->main['form']['attr_str'] = $this->search->main['form']['attr_str'] ." id_generator='".$this->id."' id_grid='".$this->properties->id_prefix_grid."'";
        $output = form_open($this->search->main['form']['action'],$this->search->main['form']['attr_str'],array('request'=>'search','id_generator'=>  $this->id));
        
        foreach($this->search->columns as $key => $val){
            $val['type'] = array_key_exists($val['type'], $this->search->type) ? $val['type'] : 'text';
            foreach($this->search->type[$val['type']] as $key_dfl => $val_dfl){
                if(isset($val[$key_dfl]) && empty($val[$key_dfl]) ){
                    $val[$key_dfl] = $val_dfl;
                }
            }
            $data = array();
            $val['name'] = $this->properties->id_prefix_form.'#'.$key;
            $val['value'] = (isset($this->search_data[$key])) ? $this->search_data[$key] : '';
            $val['data_type'] = '';
            
            $output .= $val['group_open'] . $val['label_open'] .$this->table->field_to_title($val['title']) . $val['label_close'];
            switch ($val['type']) {
                case "select":
                    $output .= form_dropdown($val['name'], $val['data'], $val['value'], $val['attr']);
                    break;
                case "radio" :
                    foreach ($val['data'] as $key_rdo => $val_rdo) {
                        $checked = ($val['value'] == $key_rdo) ? TRUE : FALSE;
                        $output .= '<div class="radio"><label>';
                        $output .= form_radio($val['name'], $key_rdo, $checked, $val['attr']) . ' ' . $val_rdo . '';
                        $output .= '</label></div>';
                    }
                    break;
                case "checkbox" :
                    foreach ($val['data'] as $key_rdo => $val_rdo) {
                        $checked = in_array($key_rdo, (is_array($val['value']) ? $val['value'] : array())) ? TRUE : FALSE;
                        $output .= '<div class="checkbox"><label>';
                        $output .= form_checkbox($val['name'] . '[]', $key_rdo, $checked, $val['attr']) . ' ' . $val_rdo . '';
                        $output .= '</label></div>';
                    }
                    break;
                case "multiselect":
                    $output .= form_multiselect($val['name'] . '[]', $val['data'], (is_array($val['value']) ? $val['value'] : array()), $val['attr']);
                    break;
                default :
                    $data['type'] = $val['type'];
                    $data['name'] = $val['name'];
                    $output .= form_input($data, $val['value'], $val['attr']);
                    break;
            }
            $output .= $val['group_close'];
            
            $logic = "";
            $query = "";
            if (!empty($val['value'])) {

                switch (strtoupper($val['operator'])) {
                    case "=" :
                        $query = " %s = '%s' ";
                        break;
                    case "%LIKE":
                        $query = " %s LIKE '%%%s' ";
                        break;
                    case "LIKE%":
                        $query = " %s LIKE '%s%%' ";
                        break;
                    case "LIKE" :
                        $query = " %s LIKE '%%%s%%' ";
                        break;
                    default :
                        $query = " %s  $val[operator] '%s' ";
                        break;
                }

                if (!is_array($val['value'])) {
                    if (!empty($where_default)) {
                        $logic = $val['logic'];
                    }
                    $where_default .= $logic . sprintf($query, $key, $val['value']);
                } else {
                    $i = 0;
                    foreach ($val['value'] as $key_arr => $val_arr) {
                        $i++;
                        if (!empty($where_default)) {
                            if ($i == 1) {
                                $logic = $val['logic'];
                            } else {
                                $logic = $val['logic_array'];
                            }
                        }
                        $open = $i == 1 ? ' (' : '';
                        $close = $i == count($val['value']) ? ') ' : '';

                        $where_default .= $logic . $open . sprintf($query, $key, $val_arr) . $close;
                    }
                }
            }
           
            $this->search->columns[$key] = $val;
        }
      
        
        
        $output .= $this->layout->separator;
        $output .= $this->layout->button_group['right']['tag_open'];
        foreach($this->search->button as $key => $val){
            if (isset( $this->btn[$val])) {
                $output .= $this->btn[$val]['tag_full'];
            }
        }
        $output .= $this->layout->button_group['right']['tag_close'];
        
        $output .= form_close();
        
        $this->table->where = $where_default;
        
        return $output;
        
    }
    
    public function get_files($name = '', $redirect = '') {
   
        if (isset($_FILES[$name])) {
            $file = $_FILES[$name];
            $files = array();
            if (is_array($_FILES[$name]['name'])) {
                foreach ($file as $key => $val) {
                    if(is_array($val)){
                        foreach ($val as $key_ => $val_) {
                            if(is_array($val_)){
                                foreach ($val_ as $key__ => $val__) {
                                    $files[$key_][$key__][$key] = $val__;
                                }
                            }else{
                                $files[$key_][$key] = $val_;
                            }
                            
                        }
                    }else{
                        $files[$key] = $val;
                    }
                    
                }
                return $files;
            } else {
                return $file;
            }

        } else {
        
            if (!empty($redirect)) {
                redirect($redirect);
            }
        }
        
        /*
        if (isset($_FILES[$name])) {
            $file = $_FILES[$name];
            $files = array();
            if (is_array($_FILES[$name]['name'])) {
                foreach ($file as $key => $val) {
                
                    foreach ($val as $key_ => $val_) {
                        $files[$key_][$key] = $val_;
                    }
                }
                return $files;
            } else {
                return $file;
            }

        } else {
            if (!empty($redirect)) {
                redirect($redirect);
            }
        }
        */

    }
    
    public function get_input($name = '', $redirect = '') {
        $input = array();
        
        $post = $this->CI->input->post(null, true);
        $get = $this->CI->input->get(null, true);

        if (is_array($post)) {
            $input = array_merge($input, $post);
        }
        if (is_array($get)) {
            $input = array_merge($input, $get);
        }
        
        if(isset($this->form->main['form']['multiple'])){
            $multiple = $this->form->main['form']['multiple'] ;
        }else{
            $multiple = false;
        }
        if(isset($this->form->main['form']['multiple_separator'])){
            $multiple_sep = $this->form->main['form']['multiple_separator'];
        }else{
            $multiple_sep = $this->form->main['form']['multiple_separator'];
        }
        foreach($input as $key => $val){
            if($multiple == true){
                if(is_array($val)){
                    foreach($val as $key_arr => $val_arr){
                        if(is_array($val_arr)){
                             $input[$key][$key_arr] = implode($multiple_sep, $val_arr);
                        } 
                    }
                }
                    
            }else{
                if(is_array($val)){
                     $input[$key] = implode($multiple_sep, $val);
                }
            }
                
        }


        if ($input == null) {
            if (!empty($redirect)) {
                redirect($redirect);
            }
        }
        if (!empty($name)) {
            if (!isset($input[$name])) {
                if (!empty($redirect)) {
                    redirect($redirect);
                }
            }else{
                return $input[$name];
            }
        }

        return $input;
    }
    public function set_input($name, $value){
        $_POST[$name] = $value;
    }
    public function remove_file($filepath){
        if(file_exists($filepath)){
            @unlink($filepath);
        }
    }
}

class ID_properties {

    public $title = '';
    public $icon = '';
    public $loading = '';
    public $id_prefix_grid = '';
    public $id_prefix_form = '';
    public $id_prefix_table = '';
    public $link_table_head = array();
    public $link_table_foot = array();
    public $link_form_head = array();
    public $link_form_foot = array();
    public $link = array();
    public $thumb = array();
    public $extension = array();

}

class ID_layout {

    public $panel = array();
    public $button = array();
    public $button_group = array();
    public $form_group = array();
    public $separator = "";

}

class ID_html {

    public $table = array();

}

class ID_view {

    public $columns = array();
    public $rows = array();
    public $index_main_row = 0;
    private $index_row = 0;
    private $index_link = 0;
    private $index_check = 0;
    
    public function add_column($fieldname, $title = '', $width = 'auto', $location = 'last', array $th_attr = [], array $td_attr = []) {

        /* @var $title type */
        $title = (empty($title)) ? $fieldname : $title;
        $this->columns[$this->index_row][$fieldname]['fieldname'] = $fieldname;
        $this->columns[$this->index_row][$fieldname]['title'] = $title;
        $this->columns[$this->index_row][$fieldname]['width'] = $width;
        $this->columns[$this->index_row][$fieldname]['th'] = (is_array($th_attr) && !is_null($th_attr)) ? $th_attr : array();
        $this->columns[$this->index_row][$fieldname]['td'] = (is_array($td_attr) && !is_null($td_attr)) ? $td_attr : array();
        $this->columns[$this->index_row][$fieldname]['filter'] = array();
        $this->columns[$this->index_row][$fieldname]['format'] = '';
        switch($location){
            case "first":
                $col = $this->columns[$this->index_row][$fieldname];
                unset($this->columns[$this->index_row][$fieldname]);
                array_unshift($this->columns[$this->index_row], $col);
                $this->columns[$this->index_row] = $this->update_key($this->columns[$this->index_row], 0, $fieldname);
             
                break;
        }
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
        
        foreach($this->columns as $key => $val){
            foreach($val as $key_dtl => $val_dtl){
                if($key_dtl == $fieldname){
                    unset($this->columns[$key][$key_dtl]);
                }
            }
        }
    }
    
    public function set_column($type ,$fieldname, $value,$option= ''){
         foreach($this->columns as $key => $val){
            foreach($val as $key_dtl => $val_dtl){
                
                if($key_dtl == $fieldname){
                    if($option != "add"){
                        $this->columns[$key][$key_dtl][$type] = $value;
                    }else{
                        if(!isset($this->columns[$key][$key_dtl][$type])){
                            $this->columns[$key][$key_dtl][$type] = $value;
                        }else{
                            $this->columns[$key][$key_dtl][$type] =array_merge($this->columns[$key][$key_dtl][$type],$value);
                        }
                       
                    }
                    
                    break;
                }
            }
        }
    }
    
    public function get_row_key($fieldname){
         foreach($this->columns as $key => $val){
            foreach($val as $key_dtl => $val_dtl){
                
                if($key_dtl == $fieldname){
                   
                    return $key;
                }
            }
        }
    }
    
    public function move_column($fieldname, $index){
        if(isset($this->columns[$this->get_row_key($fieldname)])){
            $array = $this->columns[$this->get_row_key($fieldname)];
            
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
            $this->columns[$this->get_row_key($fieldname)] = $output;
        }
    }
    
    
    public function set_column_title($fieldname,$title){
        $this->set_column('title',$fieldname, $title);
    }

    public function set_column_attr_th($fieldname, array $th_attr = []) {
        $this->set_column('th',$fieldname, (is_array($th_attr) && !is_null($th_attr)) ? $th_attr : array());
    }

    public function set_column_attr_td($fieldname, array $td_attr = []) {
        $this->set_column('td',$fieldname, (is_array($td_attr) && !is_null($td_attr)) ? $td_attr : array());
    }

    public function set_column_width($fieldname, $width = 'auto') {
        $this->set_column('width',$fieldname, $width);
    }

    public function set_column_filter($fieldname, array $filter = []) {
        $this->set_column('filter',$fieldname, $filter);
    }

    public function set_column_format($fieldname, $format) {
        $this->set_column('format',$fieldname, $format);
    }

    public function add_column_link($fieldname, $name ='', $uri = '') {
        $i = $this->index_link++;
    
        //$links = $this->columns[$this->index_row][$fieldname]['link'];
        $links = array();
        $links[$i]['name'] = $name;
        $links[$i]['uri'] = $uri;
        $this->set_column('link',$fieldname, $links,'add');
        
       
    }

    public function add_column_check($fieldname, array $check = []) {
        
    }

    public function add_row_index($row_index) {
        $this->index_row = $row_index;
    }

    public function add_row($row_index, array $column = array()) {
        $this->rows[$row_index] = $column;
        
    }

}

class ID_search{
    public $columns = array();
    public $main = array();
    public $button = array();
    public $type = array();
    
    public function add_column($fieldname,$title='', $type = 'text',array $data = []){
        $title = (empty($title)) ? $fieldname : $title;
        $this->columns[$fieldname]['title'] = $title;
        $this->columns[$fieldname]['type'] = $type;
        $this->columns[$fieldname]['data'] = $data;
        $this->columns[$fieldname]['group_open'] = '';
        $this->columns[$fieldname]['group_close'] = '';
        $this->columns[$fieldname]['label_open'] = '';
        $this->columns[$fieldname]['label_close'] = '';
        $this->columns[$fieldname]['operator'] = '';
        $this->columns[$fieldname]['logic'] = '';
        $this->columns[$fieldname]['logic_array'] = '';
        $this->columns[$fieldname]['attr'] = array();
    }
    
    public function set_column_title($fieldname,$title){
         $this->columns[$fieldname]['title'] = $title;
    }
    
    public function set_column_group($fieldname,$group_open, $group_close){
        $this->columns[$fieldname]['group_open'] = $group_open;
        $this->columns[$fieldname]['group_close'] = $group_close;
    }
    public function set_column_label($fieldname,$label_open, $label_close){
        $this->columns[$fieldname]['label_open'] = $label_open;
        $this->columns[$fieldname]['label_close'] = $label_close;
    }
    public function set_column_operator($fieldname,$operator){
        $this->columns[$fieldname]['operator'] = $operator;
        $this->columns[$fieldname]['logic'] = '';
        $this->columns[$fieldname]['logic_array'] = '';
    }
    public function set_column_logic($fieldname,$logic){
        $this->columns[$fieldname]['logic'] = $logic;
    }
    public function set_column_logic_array($fieldname,$logic_array){
        $this->columns[$fieldname]['logic_array'] = $logic_array;
    }
    public function set_column_attr($fieldname,array $attr = []){
        $this->columns[$fieldname]['attr'] = $attr;
    }
    public function set_column_attr_string($fieldname,$attr){
        $this->columns[$fieldname]['attr_str'] = $attr;
    }
}

