<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TreeNode
 *
 * @author fito
 * 
 * this class represent a node on the dependencies tree
 */
class TreeNode extends BaseTreeNode {
    //put your code here        
    //public $childs = array();
    
    public function __construct($lema,$t="root") {
        parent::__construct();

        $this->info = new stdClass();
        $this->info->type = $t;
        $this->info->lemma = $lema;
    }
       
    public function setChilds($nodes){
        
        $this->childs = $nodes;
    }
    
    public function showit(){
        print_r("<br> Node: {$this->info->type} , lema: {$this->info->lemma}");
        print_r("<br>&nbsp;&nbsp;&nbsp; Childs: ");
        foreach($this->childs as $child){
            print_r(" -  ".$child->info->lemma);
        }
        print_r("<br>");
    }
        
}
