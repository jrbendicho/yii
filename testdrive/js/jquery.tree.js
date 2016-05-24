/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function tree(settings){
    
    var obj = {
        tags:{
            container:"#treeContainer"
        },
        data:null,
        actions:{
            draw:function(){
                var treeData = obj.data;
                
                var margin = {top: 20, right: 120, bottom: 20, left: 120},
                width = 960 - margin.right - margin.left,
                height = 500 - margin.top - margin.bottom;

               var i = 0;

               var tree = d3.layout.tree()
                .size([height, width]);

               var diagonal = d3.svg.diagonal()
                .projection(function(d) { return [d.y, d.x]; });

               var svg = d3.select(obj.tags.container).append("svg")
                .attr("width", width + margin.right + margin.left)
                .attr("height", height + margin.top + margin.bottom)
                 .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

               root = treeData[0];

               update(root);

               function update(source) {

                 // Compute the new tree layout.
                 var nodes = tree.nodes(root).reverse(),
                  links = tree.links(nodes);

                 // Normalize for fixed-depth.
                 nodes.forEach(function(d) { d.y = d.depth * 80; });

                 // Declare the nodesâ€¦
                 var node = svg.selectAll("g.node")
                  .data(nodes, function(d) { return d.id || (d.id = ++i); });

                 // Enter the nodes.
                 var nodeEnter = node.enter().append("g")
                  .attr("class", "node")
                  .attr("transform", function(d) { 
                   return "translate(" + d.y + "," + d.x + ")"; });

                 nodeEnter.append("circle")
                  .attr("r", 10)
                  .style("fill", "#fff");

                 nodeEnter.append("text")
                  .attr("x", function(d) { 
                   return d.children || d._children ? -13 : 13; })
                  .attr("dy", ".35em")
                  .attr("text-anchor", function(d) { 
                   return d.children || d._children ? "end" : "start"; })
                  .text(function(d) { return d.name; })
                  .style("fill-opacity", 1);

                 // Declare the linksâ€¦
                 var link = svg.selectAll("path.link")
                  .data(links, function(d) { return d.target.id; });

                 // Enter the links.
                 link.enter().insert("path", "g")
                  .attr("class", "link")
                  .attr("d", diagonal);

               }
            }
        }
    }
    
    $.extend(true,obj,settings);
    return obj;
}

