<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<style>

 .node circle {
   fill: #fff;
   stroke: steelblue;
   stroke-width: 3px;
 }

 .node text { font: 12px sans-serif; }

 .link {
   fill: none;
   stroke: #ccc;
   stroke-width: 2px;
 }
 
    </style>
    
    <div class="row">
        <div class="col-md-6">
            <div id="treeContainer" class="col-md-2">
     
            </div>
        </div>
        <div class="col-md-6">
            <div id="treeContainer1" class="col-md-2">
     
            </div>
        </div>
    </div>

    
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="/yii/testdrive/js/jquery.tree.js"></script>


<script>
var treeData = [
  {
    "name": "Top Level",
    "parent": "null",
    "children": [
      {
        "name": "Level 2: A",
        "parent": "Top Level",
        "children": [
          {
            "name": "Son of A",
            "parent": "Level 2: A"
          },
          {
            "name": "Daughter of A",
            "parent": "Level 2: A"
          }
        ]
      },
      {
        "name": "Level 2: B",
        "parent": "Top Level"
      }
    ]
  }
];

$(document).ready(function(){
    
    var treeObject = tree({
        tags:{
            
        },
        data:treeData
    });
    
    var treeObject1 = tree({
        tags:{
            container:"#treeContainer1"
        },
        data:treeData
    });
    
    treeObject.actions.draw();
    treeObject1.actions.draw();
})

</script>
    