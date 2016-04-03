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
 * IDGenerator Table Class
 *
 * This class enables the creation of crud
 *
 * @package     CodeIgniter
 * @subpackage	Libraries
 * @category	IGenerator
 * @author	Iwan Setiawan
 * 
 */
class I_Table {
    private $CI;
    public $name = '';
    public $view = '';
    public $join = array();
    public $where = '';
    public $like = '';
    public $or_like = '';
    public $group = '';
    public $order = '';
    public $page = 0;
    public $limit = 5;
    public $offset = 0;
    public $query = "";
    public $query_count = "";
    public $params = array();
    public $params_count = array();
    public $fields = array();
    public $primary = array();
    
    
    private $field_list = array();
    private $field_data = array();
    private $field_length = 0;
    private $index = '*';

    public function __construct() {
        $this->CI = &get_instance();
        $this->initialize($this->CI->config->item('i_table'));
        
    }

    public function initialize(array $config = array()) {
        if (isset($config)) {
            foreach ($config as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->$key = $val;
                }
            }
        }
    }

    public function get_fields() {
        $this->field_list = array();
        $this->field_data = array();
        $this->field_length = 0 ;
        
        if(!empty($this->query)){
            $this->fields = array();
            $field_data = $this->data('fielddata');
        }else{
            
           
            if(count($this->join) > 0 || count($this->fields) > 0 ){
                $field_data = $this->data('fielddata');
                
            }else{
                if(!empty($this->view)){
                    $field_data = $this->CI->db->field_data($this->view);
                }else{
                    $field_data = $this->CI->db->field_data($this->name);
                }
            }
        }
        
        
        foreach ($field_data as $key => $val) {
            switch ($val->type){
                case "1": $val->type = 'tinyint'; break;
                case "2": $val->type = 'smallint';break;
                case "3": $val->type = 'int';break;
                case "4": $val->type = 'float';break;
                case "5": $val->type = 'double';break;
                case "7": $val->type = 'timestamp';break;
                case "8": $val->type = 'bigint';break;
                case "9": $val->type = 'mediumint';break;
                case "10": $val->type = 'date';break;   
                case "11": $val->type = 'time';break;
                case "12": $val->type = 'datetime';break;
                case "13": $val->type = 'year';break;
                case "16": $val->type = 'bit'; break;
                case "246": $val->type = 'decimal';break;
                case "252": $val->type = 'text';break;
                case "253": $val->type = 'varchar';break;
                case "254": $val->type = 'char';break;
                case "date": $val->max_length = 10;
            }
            $val->name = strtolower($val->name);
            $this->field_list[$val->name] = $val->name;
            $this->field_data[$val->name]['name'] = $val->name;
            $this->field_data[$val->name]['type'] = $val->type;
            $this->field_data[$val->name]['max_length'] = $val->max_length;
            $this->field_data[$val->name]['primary_key'] = $val->primary_key;

            if ($val->primary_key > 0) {
                $this->index = $val->name;
                $this->primary[count($this->primary)] = '$'.$val->name;
            }
            
        }
        if (is_array($this->fields) && count($this->fields) > 0) {
            $this->field_list = array();
            foreach ($this->fields as $key => $val) {
                $this->fields[$key] = strtolower($val);
                $val = explode(' as ', strtolower($val));
                $val_key = strtolower($val[0]);
                $val_val = strtolower($val[count($val) - 1]);
                $val_key = explode('.', $val_key);
                $val_key = strtolower($val_key[count($val_key) - 1]);
                
                $this->field_list[trim($val_key)] =  trim($val_val);
            }
        } else {
            $this->fields = $this->field_list;
        }
        
        foreach ($this->field_list as $key => $val) {
            if(isset( $this->field_data[$val])){
                $this->field_data[$val]['max_length'] = ($this->field_data[$val]['max_length'] > 50) ? 10 : $this->field_data[$val]['max_length'] ;
                $this->field_length = $this->field_length + $this->field_data[$val]['max_length'];
            }
        }
    }
    
    public function get_lookup($table, $fields = array(), $where = "", $order = ""){
     
        $output =  array();
        if(array_key_exists(0,$fields) && array_key_exists(1,$fields)){
            
            $field_key_value = strtolower($fields[0]);
            $field_key_text = strtolower($fields[1]);
            
            if(array_key_exists(2,$fields)){
                $output[''] = $fields[2];
            }
            
            $field_key_value_ex = explode(' as ',$field_key_value);
            $field_key_text_ex = explode(' as ',$field_key_text);
            
            switch(count($field_key_value_ex)){
                case 1:
                    $field_key_value = trim($field_key_value_ex[0]);
                    break;
                case 2:
                    $field_key_value = trim($field_key_value_ex[1]);
                    break;
            }
            
            switch(count($field_key_text_ex)){
                case 1:
                    $field_key_text = trim($field_key_text_ex[0]);
                    break;
                case 2:
                    $field_key_text = trim($field_key_text_ex[1]);
                    break;
            }
            
            
            $this->CI->db->select(array(strtolower($fields[0]),strtolower($fields[1]) ));
            $this->CI->db->from($table);
            if (!empty($where)) {
                $this->CI->db->where($where);
            }
            if (!empty($order)) {
                $this->CI->db->order_by($order);
            }

            $results = $this->CI->db->get()->result_array();
            
            foreach($results as $key => $val){
                $output[$val[$field_key_value]] = $val[$field_key_text];
            }
            
        }
        return $output;
        
    }

    public function get_field_list(){
        return $this->field_list;
    }
    public function get_field_data(){
        return $this->field_data;
    }
    public function get_field_length(){
        return $this->field_length;
    }
    public function field_to_title($field){
        $output = "";
        $field =  str_replace('-','_',$field);
        $field = explode('_',$field);
        $field =  implode(' ',$field);
        return ucwords(strtolower($field));
    }


    public function data($return = 'result') {
        
        $output = null;
        $tmp_offset = $this->offset;
        $tmp_limit = $this->limit;
        if($return != "fielddata"){
            if (count($this->field_list) == 0) {
                $this->get_fields();
            }
        }else{
            $this->offset = 0;
            $this->limit = 1;
        }
        $this->CI->db->cache_off();

        if (empty($this->query)) {
            if ($return == 'total' || $return == 'count') {
                $this->CI->db->select($this->index);
            } else {
                $this->CI->db->select($this->fields);
            }
            if(!empty($this->view)){
                $this->CI->db->from($this->view);
            }else{
                $this->CI->db->from($this->name);
            }

            if (is_array($this->join)) {
                foreach ($this->join as $key => $val) {
                    if (isset($val['table']) && isset($val['cond'])) {
                        if (isset($val['type'])) {
                            $this->CI->db->join($val['table'], $val['cond'], $val['type']);
                        } else {
                            $this->CI->db->join($val['table'], $val['cond']);
                        }
                    }
                }
            }

            if (!empty($this->where)) {
                $this->CI->db->where($this->where);
            }

            if (!empty($this->like)) {
                $this->CI->db->like($this->like);
            }

            if (!empty($this->or_like)) {
                $this->CI->db->or_like($this->like);
            }

            if (!empty($this->group)) {
                $this->CI->db->group_by($this->group);
            }

            if (!empty($this->order)) {
                $this->CI->db->order_by($this->order);
            }

            if ($return != 'total' && $return != 'fielddata') {
                if ($this->limit > 0) {
                    $this->page = ($this->page > 0) ? ($this->page - 1) : $this->page;
                    $this->offset = $this->limit * $this->page;
                    $this->CI->db->limit($this->limit, $this->offset);
                }
            }
            
            

            switch ($return) {
                case 'null' :
                    $output = array();
                    foreach ($this->field_list as $key => $val) {
                        $output[$val] = '';
                    }
                    break;
                case 'result' :
                    $output = $this->CI->db->get()->result_array();
                    break;
                case 'row' :
                    $output = $this->CI->db->get()->row_array();
                    $output = ($output == NULL) ? $this->data('null') : $output;
                    break;
                case 'total':
                    $output = $this->CI->db->count_all_results();
                    break;
                case 'count':
                    $output = $this->CI->db->get()->num_rows();
                    break;
                case 'query' :
                    $output = $this->CI->db->get_compiled_select();
                    break;
                case 'fielddata' :
                    $this->query =  $this->CI->db->get_compiled_select();
                    
                    $output = $this->data('fielddata');
                    $this->query = "";
                    break;
                default :
                    $output = NULL;
                    break;
            }
        } else {
            $driver =  !empty($this->CI->db->subdriver) ? $this->CI->db->subdriver : $this->CI->db->dbdriver;
            
            $query = $this->query;
            $params = $this->params;
             switch ($return) {
                case 'count' :
                case 'total' :
                    if((!empty($this->query_count))){
                        $query = $this->query_count;
                        $params = $this->params_count;
                    }
                    break;
             }
            
            if ($this->CI->db->dbprefix !== '' && $this->CI->db->swap_pre !== '' && $this->CI->db->dbprefix !== $this->CI->db->swap_pre) {
                $query = preg_replace('/(\W)' . $this->CI->db->swap_pre . '(\S+?)/', '\\1' . $this->CI->db->dbprefix . '\\2', $sql);
            }
            
            if (count($params) > 0) {
                $query  = $this->CI->db->compile_binds($query, $params);
            }
            
            if (!empty($this->where)) {
                 $query .= " WHERE " .$this->where.' ';
            }

            if (!empty($this->group)) {
                $query.= " GROUP BY " .$this->group.' ';
            }

            if (!empty($this->order)) {
               $query .= " ORDER BY " .$this->order.' ';
            }
            if ($return != 'total') {
                if ($this->limit > 0) {
                    $this->page = ($this->page > 0) ? ($this->page - 1) : $this->page;
                    $this->offset = $this->limit * $this->page;
                    
                    switch ($driver) {
                        case "mysql":
                        case "mysqli":
                        case "postgre":
                        case "sqlite":
                        case "sqlite3":
                        case "cubrid":
                            $query .= " LIMIT " . $this->offset . "," .$this->limit;
                            break;
                        case "ibase" :
                            $explode = explode(' ', $query);
                            if(count($explode) > 0){
                                $explode[0] = "SELECT FIRST " . $this->offset . " SKIP " .$this->limit;
                                $query = implode(" ", $explode);
                            }
                            
                            break;
                        case "oci8" :
                            if((empty($this->where))){
                                $query .=  " WHERE (ROWNUM >= " .$this->offset . " AND ROWNUM <= ".$this->limit .")";
                            }else{
                                $query .=  " AND (ROWNUM >= " .$this->offset . " AND ROWNUM <= ".$this->limit .")";
                            }
                            
                            break;
                        case "sqlsrv":
                        case "mssql":
                            break;
                    }
                }
            }
           
            $query = $this->CI->db->query($query);
            
            switch ($return) {
                case 'null' :
                    $output = array();
                    foreach ($this->field_list as $key => $val) {
                        $output[$val] = '';
                    }
                    break;
                case 'result' :
                    $output = $query->result_array();
                    break;
                case 'row' :
                    $output = $query->row_array();
                    $output = ($output == NULL) ? $this->data('null') : $output;
                    break;
                case 'total':
                    if(!empty($this->query_count)){
                        $output = $query->row_array();
                        $output = ($output == NULL) ? array(0) : $output;
                        foreach ($output as $key => $val) {
                            $output = $val;
                        }
                    }else{
                        $output = $query->num_rows();
                    }
                    
                    break;
                case 'count':
                    $output = $query->num_rows();
                    break;
                case 'query' :
                    $output = $this->query ;
                    break;
                case 'fielddata':
                    $output =  $query->field_data();
                    break;
                default :
                    $output = NULL;
                    break;
            }           
        }
        
        if($return == 'fielddata'){
            $this->offset =  $tmp_offset;
            $this->limit = $tmp_limit;
        }
        
        return $output;
    }

    public function save($data = array(), $where = null) {
        $this->CI->db->cache_off();
        $output = array();

        $data_filter = array();

        foreach ($data as $key => $val) {
            if (in_array($key, $this->field_list)) {
                $data_filter[$key] = $val;
                $data_filter[$key] = str_replace('xxxxx', 'style', $data_filter[$key]);
            }
        }

        if ($where == null) {
            $save = $this->CI->db->insert($this->name, $data_filter);
        } else {
            $this->CI->db->where($where);
            $save = $this->CI->db->update($this->name, $data_filter);
        }
        if ($save) {
            $output['status'] = $this->CI->config->item('status_success');
            $output['message'] = $this->CI->config->item('msg_save_success');
        } else {
            $output['status'] = $this->CI->config->item('status_error');
            $output['message'] = $this->CI->config->item('msg_save_error');
        }
        return $output;
    }

    public function delete($where = null) {
        $this->CI->db->cache_off();
        $output = array();

        $this->CI->db->where($where);
        $delete = $this->CI->db->delete($this->name);

        if ($delete) {
            $output['status'] = $this->CI->config->item('status_success');
            $output['message'] = $this->CI->config->item('msg_delete_success');
        } else {
            $output['status'] = $this->CI->config->item('status_error');
            $output['message'] = $this->CI->config->item('msg_delete_error');
        }
        return $output;
    }

    
}
