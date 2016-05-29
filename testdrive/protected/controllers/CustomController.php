<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CustomController extends Controller{
    
    public function actionInit(){
        
        
    }
    
    public function actionIndex(){
        
        print_r("doing some stuff on index");
    }
    
    public function actionStanfordParser(){
        
        $q = Yii::app()->request->getParam("q");
        $depTree = null;
        $pathernTree = null;
        //$savedPattern = null;
        
        if($q){
            $result = connectorStanfordParser::parse($q);

            //$model = tbl_patternTree::model()->findByPk(1);
            //$savedPattern = unserialize($model->object);
                
            $property = "basic-dependencies";            
            foreach($result->sentences as $s){
                //build the dependenci tree from the stanford response
                $depTree = new DependencieTree($s->$property); 
                $pathernTree = new PathernTree($depTree);
                //$modPathernTree = new PathernTree($depTree);
                //show the tree
                //DependencieTree::show($depTree->rootNode);
                

                /*$type = "nsubj";
                $comparator = function($node) use($type){
                    return $node->info->type == $type ? true : false;
                };*/
                
                //$newTreeNode = new TreeNode("blue","xxxx");

                //$node = $depTree->locate($comparator);
                //$node->info->lemma = "foxie";
                //$node->appendChild($newTreeNode);
                
                //$modPathernTree->mergeWithTree($depTree);
                //$node->truncateChilds();
                
                //$value = $modPathernTree->matchAgainstTree($depTree);
                //print_r($value?$value:"dont match");
                //die;
                
                
                
               // $savedPattern->mergeWithTree($depTree);
                //$model->object = serialize($savedPattern);
                //$model->save();
                
                
               /* print_R($savedPattern);
                die;*/
                $model = tbl_patternTree::model()->findByPk(1);                
                $model->object = serialize($pathernTree);
                $model->save();
            }       
        }
        $modPathernTree = $pathernTree;
        
        $this->pageTitle= "Running on Custom Controller";
        $this->layout = "basic";
        $this->render("/CustomViews/StanfordTest",array("dependecieTree"=>$depTree,"patherTree"=>$pathernTree,"modPathernTree"=>$modPathernTree));
    }
    
    public function actionBotManager(){
        
        $this->layout = "basic";
        $this->render("/CustomViews/BotManager");
    }
    
    public function action_getBotsByName(){
        
        $query = Yii::app()->request->getParam("query");
                
        $crit = new CDbCriteria();
        $crit->addCondition("name like '%$query%'");
        $results = tbl_bots::model()->findAll($crit);
        print_r(json_encode($results));
        die;
    }
    
    public function action_loadBotInfo(){
        $botId = Yii::app()->request->getParam("id");
        
        $botModel = tbl_bots::model()->findByPk($botId);
       /* print_r($botId);
        print_R($botModel);
        die;*/
        
        $this->renderPartial("/CustomViews/partials/_botInfo",array("botModel"=>$botModel));
    }
    
    public function action_ajaxMatchSentence(){
        
        $q = Yii::app()->request->getParam("q");
       
        if(!$q){
            die("error");
        }
        $tokens = explode(" ",$q);
        
        $model = tbl_patternTree::model()->findByPk(1);
        $savedPattern = unserialize($model->object);
        
        $result = connectorStanfordParser::parse($q);
        
        $property = "basic-dependencies";            
        $matchs = array();
        foreach($result->sentences as $s){
            //build the dependenci tree from the stanford response
            $depTree = new DependencieTree($s->$property); 
            
            $matchResult = $savedPattern->matchAgainstTree($depTree);
            $matchs[] = ( $matchResult / (float) count($tokens) ) * 100;
        }
        
        $togo = array(
            "result"=>"success",
            "depTree"=>  json_encode($depTree,JSON_PRETTY_PRINT),
            "data"=>$matchs
        );
        header('Content-Type: application/json');
        echo (json_encode($togo));
        die;
    }
    
    public function action_ajaxMergeSentence(){
        
        $q = Yii::app()->request->getParam("q");
        if(!$q){
            die("error");
        }
        
        $model = tbl_patternTree::model()->findByPk(1);
        $savedPattern = unserialize($model->object);
        
        $result = connectorStanfordParser::parse($q);
        
        $property = "basic-dependencies";            
        $matchs = array();
        foreach($result->sentences as $s){
            //build the dependenci tree from the stanford response
            $depTree = new DependencieTree($s->$property); 
            
            $savedPattern->mergeWithTree($depTree);
        }
        
        $model->object = serialize($savedPattern);
        $model->save();
        $model->refresh();
        
        $togo = array(
            "result"=>"success", 
            "data"=>$savedPattern
        );
       // header('Content-Type: application/json');
        echo (json_encode($togo,JSON_PRETTY_PRINT));
        die;
    }
    
    public function action_ajaxRefineNode(){
                
        $nodeid = Yii::app()->request->getParam("nodeid");
        $value = Yii::app()->request->getParam("value");
        
        $model = tbl_patternTree::model()->findByPk(1);
        $patternTree = unserialize($model->object);
        
        $locateFunc = function($node) use ($nodeid){
            
            return $node->id == $nodeid ? true : false;
        };
        
        $node = $patternTree->locate($locateFunc);
        
        $value = $value == "*" ? $value : explode(",",$value);
        $node->info[0]->lemmaArray = $value;
        
        $model->object = serialize($patternTree);
        $model->save();
        
        $togo = array(
            "result"=>"success",
            "data" => $patternTree
        );
        print_r(json_encode($patternTree));
    }
    
    public function actionTreeTest(){
        $this->layout = "basic";
        $this->render("/CustomViews/TreeTest");
    }
    
    public function actionTestPrimaryTag(){
        
        $q = Yii::app()->request->getParam("q");
        
        if($q){
            $primary_tag = connectorStanfordParser::getPrimaryTag($q);
            print_R($primary_tag);
        }
    }
}

