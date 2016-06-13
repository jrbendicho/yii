<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of connectorGoogle
 *
 * @author fito
 */
class connectorGoogle {
    //put your code here
    const KEY = "AIzaSyBJpPFbTxblcXA1UPZFsERxWmPBq6XLCm0";        
    
    public function __construct() {
        ;
    }
    
    public function getPlacesByLocation($location,$type = null,$name=null,$page=0){
                
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?";
        
        $params['key'] = self::KEY;
        $loc = $location['lat'].",".$location['lon'];//-33.8670,151.1957
        $params['location']= $loc;        
        $params['radius'] = isset($location['radius'])?$location['radius']:500;
        $params['page']=$page;
        
        if($type)
            $params['types'] = $type;
        
        if($name)
            $params['name'] = $name;
        
        $url = $url . http_build_query($params);
        $json = @file_get_contents($url);
        
        if(!$json or empty($json) or trim($json)== "")
            return array();
        
        $data = json_decode($json);
        return $data;
    }
    
    public function getPlaceDetails($id){
        
        $url = "https://maps.googleapis.com/maps/api/place/details/json?";
        
        $params['placeid'] = $id;
        $params['key'] = self::KEY;        
        
        $url = $url . http_build_query($params);        
        $json = @file_get_contents($url);
        
        if(!$json or empty($json) or trim($json)== "")
            return array();
        
        $data = json_decode($json);
        return $data;
    }
}
