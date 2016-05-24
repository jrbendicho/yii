<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class connectorStanfordParser{
    
    private static $url = "http://corenlp.run/";
    
    public static function makeCall($sentence){
        
        $params = array(
            "properties"=>json_encode(array(
                "annotators"=>"tokenize,ssplit,pos,ner,depparse,openie",
                "coref.md.type"=> "dep", 
                "coref.mode"=> "statistical"
            ))
            );
        //open connection
        $ch = curl_init();
        $url = self::$url."?".http_build_query($params);
        //die($url);
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($sentence));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $sentence);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);
        
        return $result;
    }
    
    public static function parse($string){
        
        $back = self::makeCall($string);
        $object = json_decode($back);
        return $object;
    }
    
   
}

