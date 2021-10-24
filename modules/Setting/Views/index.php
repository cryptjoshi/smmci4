
 <?= $this->extend('template/layout') ?>
 <?=$this->section('content');?>

<div class="row settings justify-content-center">
  <div class="col-md-12 col-lg-12">
    <div class="row">
      <div class="col-md-2 col-lg-2">
        <?php
          $data = array(
            "module" => "setting",
          );
          echo view('Modules\Setting\Views\sidebar', $data);
        ?>
      </div>
      <div class="col-md-10 col-lg-10">
        <?php
          $data = array(
            "module" => "setting",
          );
          $payments_method = get_payments_method();
          //helper('common');
          if ($tab != "") {
            if (in_array($tab, $payments_method) && payment_method_exists($tab)) {
              
              echo view('Modules\Setting\Views\integrations/'.$tab, $data);
            }else{
             
             echo view('Modules\Setting\Views\\'.$tab, $data);
            }
          }
        ?>
      </div>
    </div>
  </div>
</div>
<?=$this->endSection()?>
