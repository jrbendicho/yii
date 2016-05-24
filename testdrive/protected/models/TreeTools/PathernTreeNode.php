<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PathernTreeNode
 *
 * @author fito
 */
class PathernTreeNode extends BaseTreeNode{
    //put your code here
    
    const valEqualType = 0.5;
    const valEqualLemma = 0.5;
    
    public function __construct($treeNode = false) {
        parent::__construct();
                
        $this->info = array();        
        
        if($treeNode){
            $this->appendInfoTreeNode($treeNode);            
        }
    }     
    
    public function appendInfoTreeNode($treeNode){
        
        $infoElement = new stdClass();
        $infoElement->type = $treeNode->info->type;
        $infoElement->lemmaArray = array($treeNode->info->lemma);

        $this->info[] = $infoElement;
    }
   
    public function matchAgainstNode($treeNode){
        
        $match = 0;
        foreach($this->info as $s){
            if($s->type == $treeNode->info->type){
                $match = self::valEqualType;
                $match += self::matchStringToLemma($treeNode->info->lemma,$s->lemmaArray);
                /*if(in_array($treeNode->info->lemma, $s->lemmaArray)){
                    $match += self::valEqualLemma;
                }*/
                return $match;
            }
        }
        return $match;
    }
    
    public static function matchStringToLemma($str,$array){
        
        if(is_array($array)){
            return in_array($str, $array) ? self::valEqualLemma : 0;
        }else{
            if($array == "*")
                return self::valEqualLemma;
        }
        return 0;
    }
    
    public function mergeIn($treeNode){
        
        foreach($this->info as $s){
            if($s->info->type == $treeNode->info->type){
                //already exist just add lema
                if(!in_array($treeNode->info->lemma, $s->info->lemmaArray)){
                    $s->info->lemmaArray[] = $treeNode->info->lemma;
                }
                return;
            }
        }
        $newInfo = stdClass();
        $newInfo->type = $treeNode->info;
        $newInfo->lemmaArray = array($treeNode->lemma);
        $this->info[] = $newInfo;
    }
}
