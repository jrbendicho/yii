<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    


?>

<div class="col-md-12">
    <div class="col-md-6 col-md-offset-3">        
            <div class="form-group col-md-8">                
                <input id="match-value" type="text" class="form-control" name="q" placeholder="Stirng to match here">
            </div>
            <div class="form-group col-md-4" align="center">                            
                <input class="btn btn-primary match-btn" type="button" value="Match ?">
            </div>        
    </div>
    <div class="col-md-3 match-result">
        none
    </div>
    <hr>
    <div class="col-md-6 col-md-offset-3">        
            <div class="form-group col-md-8">                
                <input id="merge-value" type="text" class="form-control" name="q" placeholder="Stirng to merge here">
            </div>
            <div class="form-group col-md-4" align="center">                            
                <input class="btn btn-primary merge-btn" type="button" value="Merge">
            </div>        
    </div>    
    
    <div class="col-md-4 col-md-offset-0">
        <div class="col-md-12" align="center">Dependencies Tree</div>
        <pre id="depTree">
            <?php print_r(json_encode($dependecieTree,JSON_PRETTY_PRINT)); ?>
        </pre>
    </div>
    <div class="col-md-8">
        <div class="col-md-12" align="center">Pattern Tree</div>
        <pre id="patternTree">
            <?php print_r(json_encode($patherTree,JSON_PRETTY_PRINT)); ?>
        </pre>
        <div class="col-md-6">
            <form id="form-refine-tree">
                <input type="text" name="r" class="hidden" value="/Custom/_ajaxRefineNode">
                <div class="form-group">
                    <label>Node id</label>
                    <input type="text" class="form-control" id="nodeid" name="nodeid">                    
                </div>
                <div class="form-group">
                    <label>Node value</label>
                    <input type="text" class="form-control" name="value" id="value">                    
                </div><div class="form-group">
                    <button type="button" id="btn-refine-tree" class="btn btn-primary">Set</button>                 
                </div>
                
            </form>
        </div>
    </div>
    <div class="col-md-4 hidden">
        <div class="col-md-12" align="center">Modified Pattern Tree</div>
        <pre class="merge-result">
            <?php print_r(json_encode($modPathernTree,JSON_PRETTY_PRINT)); ?>
        </pre>
    </div>
</div>

<div class="node-toclone" align="center" style="display: inline-block; border: 1px solid grey; vertical-align: top; margin:2px;">
    <span id="info">
    </span>
    <span id="lemmas">
        
    </span>
    <div id="childs" align="center">
        
    </div>
</div>

<script>

    $(document).ready(function(){
        
        $(document).on("click",".match-btn",function(){
            var q = $("#match-value").val();
            $.ajax({
                url: "index.php?",
                method:"POST",
                data:{r:"/Custom/_ajaxMatchSentence",q:q},
                context: document.body
              }).done(function(result) {
                  //result = JSON.parse(result);
                  console.log(result);
                  $(".match-result").html(result.data); 
                  $("#depTree").html((result.depTree));
              });
            
        });
        
        $(document).on("click",".merge-btn",function(){
            
            var q = $("#merge-value").val();
            $.ajax({
                url: "index.php?",
                method:"POST",
                data:{r:"/Custom/_ajaxMergeSentence",q:q},
                context: document.body
              }).done(function(result) {
                  //result = JSON.parse(result);
                  console.log(result);
                  //$(".merge-result").html(JSON.stringify(result.data));                                
                  $(".merge-result").html(result);        
                  var obj = JSON.parse(result);
                  $("#patternTree").html(drawTree(obj.data));
              });
        });
        
        $(document).on("click","#btn-refine-tree",function(){
            
            //alert("here");
            var form = $("#form-refine-tree");
            //console.log(form.serialize());
            
            $.ajax({
                url: "index.php",
                method:"POST",
                data:form.serialize(),
                context: document.body
              }).done(function(result) {
                  //result = JSON.parse(result);
                  console.log("back from controller");
                  console.log(result); 
                  var obj = JSON.parse(result);
                  console.log(obj);
                  $("#patternTree").html(drawTree(obj));
              });
        });
        
        function drawTree(tree){
            
            return drawNode(tree.rootNode);
        }
        
        function drawNode(node){
            var element = $(document).find(".node-toclone").clone();
            element.removeClass("node-toclone");
            
            var info = "";
            for(var i in node.info){
                info += "<small>"+node.id+"</small> <strong>" + node.info[i].type + "</strong>";
                var lemmas = "";
                for(var j in node.info[i].lemmaArray){
                    lemmas += node.info[i].lemmaArray[j] + ",";
                }
                info += "("+ lemmas +")";
            }
            element.find("#info").html(info);
            
            var childContainer = element.find("#childs");
            for(var i in node.childs){
                
                childContainer.append(drawNode(node.childs[i]));
            }
            return element;
        }
    })
</script>
    

