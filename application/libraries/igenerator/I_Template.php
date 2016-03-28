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
 * IDGenerator Template Class
 *
 * This class enables the creation of view
 *
 * @package     CodeIgniter
 * @subpackage	Libraries
 * @category	IGenerator
 * @author	Iwan Setiawan
 * 
 */
class I_Template {
    var $CI = null;
    var $view_data = array();
    
    function __construct() {
        $this->CI = &get_instance();
    }
    
    function set($name,$value) {
        $this->view_data[$name] = $value;
    }
    
    function load($tpl_main = '',$view = '',$data = array(),$return = false) {
        
        if($this->CI->input->is_ajax_request()){
            return $this->CI->load->view($view,$data,$return);
        }else{
            $this->set('contents',$this->CI->load->view($view,$data,true));
            return $this->CI->load->view($tpl_main,$this->view_data,$return);
        }
        
    }
}
