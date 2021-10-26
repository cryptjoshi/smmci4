 <?= $this->extend('template/layout') ?>
 <?=$this->section('content');?>
 <div class="col-md-12">
     <div class="card">
         <div class="card-header">
             <h3 class="card-title"><?=lang("app.lists")?></h3>
             <div class="card-options">
                 <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                         class="fe fe-chevron-up"></i></a>
                 <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
             </div>
         </div>
         <div id="result_ajaxSearch">
             <?php if(!empty($order_logs)){?>
             
             <?php } else {
   view("Modules\Blocks\Views\\empty_data");
  }?>
         </div>
     </div>
 </div>
 <?=$this->endSection();?>