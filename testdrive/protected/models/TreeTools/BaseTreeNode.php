<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseTreeNode
 *
 * @author fito
 */
class BaseTreeNode {
    //put your code here
    public $id;
    public $info;
    public $childs;
    
    public function __construct() {
        
        $this->id = rand(0,100000);
        $this->childs = array();        
    }
    
    public function appendChild($child){
        
        $this->childs[] = $child;
    }
    
    public function truncateChilds(){
        
        $childs = $this->childs;
        $this->childs = array();
        return $childs;
    }
    
}
