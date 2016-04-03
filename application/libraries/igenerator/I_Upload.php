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
 * IDGenerator Upload Class
 *
 * This class enables the creation of form
 *
 * @package     CodeIgniter
 * @subpackage	Libraries
 * @category	IGenerator
 * @author	Iwan Setiawan
 * 
 */
class I_Upload{
    
    private $file_post = null;
    private $file_path;
    private $file_name;
    private $img_height = 0;
    private $img_width = 0;
    public $file_error = null;

    function __construct() {
    }
    
    /**
     * Upload Image
     * 
     * @access Public
     * @param String File Temporary
     * @param String Directory
     * @param int Width
     * @param int Heidht
     * 
     */
    public function image($config = array()){
    
        if (!is_array($config)) {
            return;
        }
        foreach($config as $key => $value){
            $this->$key = $value;
        }
        
        if (is_null($this->file_post)) {
            return false;
        }

        if (!file_exists($this->file_path)) {
            if (!mkdir($this->file_path, 0777, true)) {
                return false;
            }
        } 
        
        
        $img_size = getimagesize($this->file_post['tmp_name']);
        $create = false;
        $ori_w = $img_size[0];
        $ori_h = $img_size[1];
        
        
        if($this->img_width == 0 && $this->img_height == 0){
            $new_w = $ori_w;
            $new_h = $ori_h;
        }else{
            $new_w = $this->img_width;
            $new_h = $this->img_height;
        }
        $filename = "";
        $img_resized = imagecreatetruecolor($new_w,$new_h);
        switch($img_size[2]){
            case 1 :
                $filename = $this->file_path.'/'.$this->file_name.'.gif';
                imagecopyresized($img_resized,imagecreatefromgif($this->file_post['tmp_name']),0,0,0,0,$new_w,$new_h,$ori_w,$ori_h);
                $create = imagegif($img_resized,$filename);
                
                break;
            case 2 :
                $filename = $this->file_path.'/'.$this->file_name.'.jpg';
                imagecopyresized($img_resized,imagecreatefromjpeg($this->file_post['tmp_name']),0,0,0,0,$new_w,$new_h,$ori_w,$ori_h);
                $create = imagejpeg($img_resized,$filename);
                break;
            case 3 :
                $filename = $this->file_path.'/'.$this->file_name.'.png';
                imagefill($img_resized, 0, 0, imagecolorallocatealpha($img_resized, 255, 255, 255, 127));
                imagesavealpha($img_resized, true);

                imagecopyresized($img_resized,imagecreatefrompng($this->file_post['tmp_name']),0,0,0,0,$new_w,$new_h,$ori_w,$ori_h);
                
                $create = imagepng($img_resized,$filename,1);
                
                
                break;
        }
        imagedestroy($img_resized);
        if($create){
            return $filename;
        }else{
            return false;
        }

    }
    public function file($config = array()){
        if (!is_array($config)) {
            return;
        }
        foreach($config as $key => $value){
            $this->$key = $value;
        }
        
        if (is_null($this->file_post)) {
            return false;
        }

        if (!file_exists($this->file_path)) {
            if (!mkdir($this->file_path, 0777, true)) {
                return false;
            }
        }
        $filename = $this->file_path.'/'.$this->file_name.'.'.(pathinfo($this->file_post['name'], PATHINFO_EXTENSION));
        if (move_uploaded_file($this->file_post['tmp_name'], $filename)) {
            return $filename;
        }else{
            return false;
        }
        
    }
    
}