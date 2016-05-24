<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tree
 *
 * @author fito
 * base tree class
 */
class Tree {
    //put your code here
    
    public $rootNode;    
    
    public function locate($match,$searchNode=false){
        
        $searchNode = $searchNode ? $searchNode : $this->rootNode;
        $node = $this->_find($searchNode,$match);   
        return $node;        
    }
    
    public static function _find($node,$match){
                
        if($match($node) === true){
            return $node;
        }else{
            foreach($node->childs as $child){
                $result = self::_find($child, $match);
                if($result !== false){
                    return $result;
                }
            }
            return false;
        }
    }        
}
