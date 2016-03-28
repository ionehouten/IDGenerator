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
 * IDGenerator Generator Class
 *
 * This class enables the initial generator
 *
 * @package     CodeIgniter
 * @subpackage	Libraries
 * @category	IGenerator
 * @author	Iwan Setiawan
 * 
 */

require_once APPPATH."libraries/igenerator/I_GeneratorBridge.php";
class I_Generator  {
    private static $instance;
    private static $registry;
    private $count = 0;
    private $vars ;
    
    public $CI;
    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library(array('igenerator/I_Template','igenerator/I_Grid','igenerator/I_Form','igenerator/I_Pagination','igenerator/I_Upload'));
        $this->vars = new ArrayObject(array(), ArrayObject::STD_PROP_LIST);
        return $this;
    }
    
    public static function init_grid(){
        if(self::$registry == null){
            self::$registry = new self();
        }
        self::$registry->count = self::$registry->count + 1;
        self::$registry->CI->session->set_userdata('i_grid_count',self::$registry->count);
        self::$instance = new I_Grid();
        self::$instance->id = self::$registry->count;
        self::$instance->init_grid();
        
        return self::$instance;
    }
    
    public static function init_form(){
        if(self::$registry == null){
            self::$registry = new self();
        }
        self::$registry->count = self::$registry->count + 1;
        self::$registry->CI->session->set_userdata('i_grid_count',self::$registry->count);
        self::$instance = new I_Form();
        self::$instance->id = self::$registry->count;
        self::$instance->init_form();
        
        return self::$instance;
    }
    
    public static function get_uri(){
        $router =& load_class('Router', 'core');
        $uri = array();
        $segments = array();
        
        if (method_exists($router, 'fetch_module')) {
            $segments[0] = $router->fetch_module();
        }
        if (method_exists($router, 'fetch_class')) {
            $segments[1] = $router->fetch_class();
        }
        if (method_exists($router, 'fetch_method')) {
            $segments[2] = $router->fetch_method();
        }

        $uri['segments'] = $segments;
        $uri['count_segments'] = count($segments) + 1;
        return $uri ;
  
    }
    
    public function __reg($index, $object)
    {
        $this->vars->$index = $object;
    }
    
    public function __set($index, $value){
        $this->vars->$index = $value;
    }
    public function __get($index)
    {
        if (property_exists($this->vars, $index)) {
            return $this->vars->$index;
        }
        foreach($this->vars as $extend)
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
    
}
