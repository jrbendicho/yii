<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PathernTree
 *
 * @author fito
 * this class represent the pather tree to be used to match a regular tree and must be build based o a tree
 */
class PathernTree extends Tree{
    //put your code here
    
    public function __construct($tree) {
        
        $this->rootNode = self::buildFromTree($tree->rootNode);
    }
    
    //this function expects the root node of the tree and the root node of the pather tree
    private static function buildFromTree($root,$rootNode = false){
        
        if(!$root) 
            return null;                
        
        if(!$rootNode){
            $rootNode = new PathernTreeNode($root);
        }
        
        foreach($root->childs as $child){
                
            $newChild = new PathernTreeNode($child);
            self::buildFromTree($child,$newChild);

            $rootNode->appendChild($newChild);                
        }
        
        return $rootNode;
    }
    
    public function matchAgainstTree($tree){
        
        return self::_match($tree->rootNode,$this->rootNode);
    }
    
    //this function will recursivelly try to match the tree with the pather
    private static function _match($treeNode,$pathernNode){
        
        $type = $treeNode->info->type;
        $match = function($pN) use ($type){
            
            foreach($pN->info as $s){
                if($s->type == $type)
                    return true;
            }
            return false;
        };
        
        $found = parent::_find($pathernNode, $match);
        if($found){
            $value = $found->matchAgainstNode($treeNode);
            
            foreach($treeNode->childs as $child){
                $chfoundVal = self::_match($child , $found);
                
                if($chfoundVal === false){
                    return false;
                }
                $value += $chfoundVal;
            }
            return $value;
        }else{
            return false;
        }
    }
    
    public function mergeWithTree($tree){
        
        self::_merge($tree->rootNode,$this->rootNode);
    }
 
    //this function will recursively merge the tree with the pathern
    //working
    public static function _merge($treeNode,$pathernNode){
        
        $type = $treeNode->info->type;
        $match = function($pN) use ($type){
            
            foreach($pN->info as $s){
                if($s->type == $type)
                    return true;
            }
            return false;
        };
        
        $found = parent::_find($pathernNode, $match);

        if($found){
            
            foreach($found->info as $s){
                if($s->type == $treeNode->info->type){
                    if(!is_array($s->lemmaArray)){
                        if($s->lemmaArray=="*")
                            continue;
                    }else{
                       if(!in_array($treeNode->info->lemma, $s->lemmaArray)){
                            $s->lemmaArray[] = $treeNode->info->lemma;
                        }  
                    }                    
                }
            }                       
        }else{
            
            $newChild = new PathernTreeNode($treeNode);
            $pathernNode->appendChild($newChild);
            $found = $newChild;
            
            //$pathernNode->appendInfoTreeNode($treeNode);
            //$found = $pathernNode;            
        }

        foreach($treeNode->childs as $child){

            self::_merge($child , $found);                                
        }  
    }
}
