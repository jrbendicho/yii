<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DependencieTree
 *
 * @author fito
 * 
 * This class will create the dependency tree in a tree structure 
 * from the dependencies array from Stanford NLP
 */
class DependencieTree extends Tree {
    //put your code here
    private $dependencies = array();        
    
    public function __construct($dependencies) {
    
        $this->dependencies = $dependencies;    
        $this->rootNode = self::buildTree($dependencies);
    }
    
    public static function buildTree($dependencies){
        
        $mydep = $dependencies;
        $rootDep = self::locateDependeciebyType($dependencies, "ROOT");
        //die(print_r($rootDep));
        if($rootDep){
            
            $baseNode = new TreeNode($rootDep->dependentGloss,"ROOT");            
            $childs = self::getChildsbyGovernor($mydep,$rootDep->dependentGloss);
            
            $baseNode->setChilds($childs);
                        
            return $baseNode;
        }else{
            
            return null;
        }
    }
            
    public static function getChildsbyGovernor($dependencies,$gloss){
        
        $childs = array();
        foreach($dependencies as $key=>$d){
            
            if($d->governorGloss == $gloss){
                
                unset($dependencies[$key]); //to avoid infinite recursive where is the same element
                $newNode = new TreeNode($d->dependentGloss,$d->dep);
                $newNodeChilds = self::getChildsbyGovernor($dependencies, $d->dependentGloss);
                $newNode->setChilds($newNodeChilds);
                
                $childs[] = $newNode;
            }
        }
        return $childs;
    }
    
    public static function locateDependeciebyType($dependencies,$type){
        
        foreach($dependencies as $d){
            
            if($d->dep == $type){
                return $d;
            }
        }
        return false;
    }
    
    
    public static function show($node = false){
        
        if($node){
            
            $node->showit();
            foreach($node->childs as $child){
                self::show($child);
            }
        }  
    }
}
