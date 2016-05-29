<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$patterns = tbl_patternTree::model()->findAllByAttributes(array("bot_id"=>$botModel->id));
?>


<div class="col-md-12">
    <div class="col-md-12">
        <?php foreach($patterns as $p){ ?>
        <div class="row">
            <div class="col-md-1"><?php echo $p->id;?></div> 
            <div class="col-md-4"><?php echo $p->initial_sentence;?></div>    
        </div>
        <?php } ?>
    </div>    
</div>