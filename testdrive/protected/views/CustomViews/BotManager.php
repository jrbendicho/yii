<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="container">
    <div class="col-md-6 col-md-offset-3">
        <input type="text" class="typeahead form-control">
    </div>
    
    <div class="col-md-6 col-md-offset-3">
        <div id="patternsContainer">
            
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/bootstrap-typeahead.js"></script>

<script>

$(document).ready(function(){
    
    $('input.typeahead').typeahead({        
        ajax:{
            url:"<?php echo Yii::app()->baseUrl;?>/index.php/Custom/_getBotsByName",            
        },
        onSelect:function(element){
            console.log(element);
            $.ajax({
                url: "<?php echo Yii::app()->baseUrl;?>/index.php/Custom/_loadBotInfo",
                method:"POST",
                data:{id:element.value},                
              }).done(function(result) {
                  //result = JSON.parse(result);
                  $("#patternsContainer").html(result);               
              });
        }
    });
})
</script>
