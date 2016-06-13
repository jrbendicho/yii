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
        $pid = Yii::app()->request->getParam("pid");
        
        if(!$q or !$pid){
            die("error");
        }
        $tokens = explode(" ",$q);
        
        $model = tbl_patternTree::model()->findByPk($pid);
        $savedPattern = unserialize($model->object);
        
        $result = connectorStanfordParser::parse($q);
        
        $property = "basic-dependencies";            
        $matchs = array();
        foreach($result->sentences as $s){
            //build the dependenci tree from the stanford response
            $depTree = new DependencieTree($s->$property); 
            
            $matchResult = $savedPattern->matchAgainstTreeBest($depTree);
            $matchs[] = ( $matchResult / (float) count($tokens) ) * 100;
        }
        
        $togo = array(
            "result"=>"success",
            "depTree"=>  $depTree,
            "data"=>$matchs,
            "pattern"=>$savedPattern
        );
        header('Content-Type: application/json');
        echo (json_encode($togo));
        die;
    }
    
    public function action_ajaxMergeSentence(){
        
        $q = Yii::app()->request->getParam("q");
        $pid = Yii::app()->request->getParam("pid");
        if(!$q or !$pid){
            die("error");
        }
        
        $model = tbl_patternTree::model()->findByPk($pid);
        $model->saved_object = $model->object; //just in case
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
        $pid = Yii::app()->request->getParam("pid");
        
        $model = tbl_patternTree::model()->findByPk($pid);
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
            print_r($primary_tag);
        }
    }
    
    public function actionTestSpeed(){
        
        print_r(json_encode(array("hello","world")));
    }
    
    public function actionMarketplace(){
        
        $this->layout = "basic";
        $this->render("/CustomViews/Marketplace");
    }
    
    public function actionBots(){
                 
        $bots = tbl_bots::model()->findAll();
        
        $this->layout = "basic"; 
        $this->render("/CustomViews/Bots",array("bots"=>$bots));
    }
    
    public function actioneditBot(){
        
        $id = Yii::app()->request->getParam("id");        
        $bot = tbl_bots::model()->findByPk($id);
        
        $this->layout = "basic";
        $this->render("/CustomViews/editBot",array("bot"=>$bot));
    }
    
    public function actioncreateTree(){
        
        $q = Yii::app()->request->getParam("q");
        $action = Yii::app()->request->getParam("action");
        $depTree = null;
        $pathernTree = null;
        
        if(!$q or !$action){
            die("invalid request");
        }
        
        $result = connectorStanfordParser::parse($q);           

        $property = "basic-dependencies";            
        foreach($result->sentences as $s){
            //build the dependenci tree from the stanford response
            $depTree = new DependencieTree($s->$property); 
            $pathernTree = new PathernTree($depTree);

            $model = new tbl_patternTree();
            $model->action_id = $action;
            $model->initial_sentence = $q;
            $model->object = serialize($pathernTree);
            
            $model->save();
            $model->refresh();
        }       
        
        $modPathernTree = $pathernTree;        
        //$this->pageTitle= "Running on Custom Controller";
        $this->layout = "basic";
        $this->render("/CustomViews/StanfordTest",
                array(
                    "dependecieTree"=>$depTree,
                    "patherTree"=>$pathernTree,
                    "modPathernTree"=>$modPathernTree,
                    "sentence"=>$q,
                    "pid"=>$model->id
                )
                );
    }
    
    public function actionrefineTree(){
        
        $id = Yii::app()->request->getParam("id");
        
        $model = tbl_patternTree::model()->findByPk($id);
        $q = $model->initial_sentence;
        $result = connectorStanfordParser::parse($q);           

        $property = "basic-dependencies";            
        foreach($result->sentences as $s){
            //build the dependenci tree from the stanford response
            $depTree = new DependencieTree($s->$property);           
        }       
        $pathernTree = unserialize($model->object);
        $modPathernTree = $pathernTree;        
        //$this->pageTitle= "Running on Custom Controller";
        $this->layout = "basic";
        $this->render("/CustomViews/StanfordTest",array("dependecieTree"=>$depTree,"patherTree"=>$pathernTree,"modPathernTree"=>$modPathernTree,"sentence"=>$q,"pid"=>$model->id));
    }
    
    
    public function action_ajaxtrimNode(){
                
        $pid = Yii::app()->request->getParam("pid");
        $nid = Yii::app()->request->getParam("nid");
        
        if(!$pid or !$nid){
            die("invalid request");
        }
        $model = tbl_patternTree::model()->findByPk($pid);
        $patternTree = unserialize($model->object);
        
        $find = function($node)use($nid){           
            return ((int)$node->id == (int)$nid) ? true : false;                            
        };
        
        $patternTree->trim($find);
        $model->object = serialize($patternTree);
        $model->save();
        
        $togo = array(
            "patternTree"=>$patternTree
        );
        print_r(json_encode($togo));
        die;
    }
    
    public function actionGoogleImport(){
        
        $connector = new connectorGoogle();
        
        $location =array(
            "lat"=>27.773056,
            "lon"=>-82.639999,
            "radius"=>500);
        
        $places = $connector->getPlacesByLocation($location,0,array("type"=>"art_gallery,bakery,beauty_salon,bar"));
        
        if(isset($places->results) and is_array($places->results)){
            foreach($places->results as $place){
                
                print_r("<pre>" );
                print_r($place->name);
                print_r("<br>");
                print_R($place->place_id);
                
                $details = $connector->getPlaceDetails($place->place_id);                
                
                if(isset($details->result) and isset($details->result->website)){
                    print_r("<br>");
                    print_r("<a href={$details->result->website}>{$details->result->website}</a>");
                }
                
                print_r("</pre>" );
            }
        }
        
    }
}

