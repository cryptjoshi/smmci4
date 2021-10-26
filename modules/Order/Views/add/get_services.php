

<label><?=lang("app.order_service")?></label>
<select name="service_id" class="chosen-select form-control square ajaxChangeService" data-url="<?=cn($module."/get_service/")?>">
  <option> <?=lang("app.choose_a_service")?></option>
  <?php
    if (!empty($services)) {
      $service_item_default = $services[0];
      $currency_symbol      = get_option('currency_symbol', "$");
      $decimal_places       = get_option('currency_decimal', 2);
      foreach ($services as $key => $service) {
        $service_price = $service->price;
        if (get_role('user') && isset($custom_rates[$service->id]) ) {
          $service_price = $custom_rates[$service->id]['service_price'];
        }
      	$price_per_1k = $currency_symbol.currency_format($service_price, $decimal_places);
  ?>
  <option value="<?=$service->id?>" data-type="<?=$service->type?>" data-dripfeed="<?=$service->dripfeed?>">
    <?php
      $service = 'ID:'. $service->id . ' - '. $service->name . ' - '.$price_per_1k;
      echo $service;
    ?>
  </option>
  <?php }}?>
</select>
<script>
   $(".chosen-select").chosen({width: "95%"})
</script>
