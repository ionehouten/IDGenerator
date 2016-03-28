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
 * IDGenerator Bridge Class
 *
 * This class enables the creation of generator
 *
 * @package     CodeIgniter
 * @subpackage	Libraries
 * @category	IGenerator
 * @author	Iwan Setiawan
 * 
 */

Abstract class I_GeneratorBridge  {
    public $CI;
    public $registry;
    public $table;
    public $vars;

    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->config('i_generator');
        $this->CI->load->library(array('igenerator/I_Table'));
        $this->registry = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
        $this->table = new I_Table();
        return  $this;
    }
    
    
    
    public function __reg($index, $object)
    {
        $this->registry->$index = $object;
    }
    
    public function __set($index, $value){
        $this->registry->$index = $value;
    }
    public function __get($index)
    {
        if (property_exists($this->registry, $index)) {
            return $this->registry->$index;
        }
        foreach($this->registry as $extend)
        {
            if (property_exists($extend, $index)) {
                return $extend->$index;
            }
        }
        throw new Exception("This Variable/Object '{$index}' doesn't exists");
    }
    
    public function get_vars(){
        return $this->vars;
    }
    
    public function set_vars($index,$value){
        $this->vars[$index][$value] = '';
    }
   
    public function __call($method,$param)
    {
        foreach($this->registry as $ext)
        {
            if (method_exists($ext, $method)) {
                return call_user_func_array(array($ext, $method), $param);
            }
        }
        throw new Exception("This Method '{$method}' doesn't exists");
    }
    
}

