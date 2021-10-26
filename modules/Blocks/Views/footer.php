<?= helper('language')?>
<div class="footer footer_top dark">
  <div class="container m-t-60 m-b-50">
    <div class="row">
      <div class="col-lg-12">
        <div class="site-logo m-b-30">
          <a href="<?=cn()?>" class="m-r-20">
            <img src="<?=get_option('website_logo_white', BASE."assets/images/logo-white.png")?>" alt="Website logo">
          </a>
         
        </div>
      </div>
      <div class="col-lg-8 m-t-30  mt-lg-0">
        <h4 class="title"><?=lang("app.quick_links")?></h4>
        <div class="row">
          <div class="col-6 col-md-3  mt-lg-0">
            <ul class="list-unstyled quick-link mb-0">
              <li><a href="<?=cn('{locale}')?>"><?=lang("app.home")?></a></li>
              <?php 
                if (!session('uid')) {
              ?>
              <li><a href="<?=cn('auth/login')?>"><?=lang("app.login")?></a></li>
              <li><a href="<?=cn('auth/signup')?>"><?=lang("app.sign_up")?></a></li>
              <?php }else{?>
              <li><a href="<?=cn('services')?>"><?=lang("app.services")?></a></li>
              <li><a href="<?=cn('tickets')?>"><?=lang("app.tickets")?></a></li>  
              <?php }?>
            </ul>
          </div>
          <div class="col-6 col-md-3">
            <ul class="list-unstyled quick-link mb-0">
              <li><a href="<?=cn('terms')?>"><?=lang("app.terms__conditions")?></a></li>
              <?php 
                if (get_option('is_cookie_policy_page')) {
              ?>
              <li><a href="<?=cn('cookie-policy')?>"><?=lang("app.cookie_Policy")?></a></li>
              <?php }?>
              <?php 
                if (get_option('enable_api_tab')) {
              ?>
              <li><a href="<?=cn('api/docs')?>"><?=lang("app.api_documentation")?></a></li>
              <?php }?>
              <li><a href="<?=cn('faq')?>"><?=lang("app.faqs")?></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-4 m-t-30 mt-lg-0">
        <h4 class="title"><?=lang("app.contact_informations")?></h4>
        <ul class="list-unstyled">
          <li><?=lang("app.tel")?>: <?=get_option('contact_tel',"+12345678")?> </li>
          <li><?=lang("app.email")?>: <?=get_option('contact_email',"do-not-reply@smartpanel.com")?> </li>
          <li><?=lang("app.working_hour")?>: <?=get_option('contact_work_hour',"Mon - Sat 09 am - 10 pm")?> </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<footer class="footer footer_bottom dark">
  <div class="container">
    <div class="row align-items-center flex-row-reverse">
      <div class="col-auto ml-lg-auto">
        <div class="row align-items-center">
          <div class="col-auto">
            <ul class="list-inline mb-0">
              <?php 
                if (get_option('social_facebook_link','') != '') {
              ?>
              <li class="list-inline-item"><a href="<?=get_option('social_facebook_link','')?>" target="_blank" class="btn btn-icon btn-facebook"><i class="fa fa-facebook"></i></a></li>
              <?php }?>
              <?php 
                if (get_option('social_twitter_link','') != '') {
              ?>
              <li class="list-inline-item"><a href="<?=get_option('social_twitter_link','')?>" target="_blank" class="btn btn-icon btn-twitter"><i class="fa fa-twitter"></i></a></li>
              <?php }?>
              <?php 
                if (get_option('social_instagram_link','') != '') {
              ?>
              <li class="list-inline-item"><a href="<?=get_option('social_instagram_link','')?>" target="_blank" class="btn btn-icon btn-instagram"><i class="fa fa-instagram"></i></a></li>
              <?php }?>

              <?php 
                if (get_option('social_pinterest_link','') != '') {
              ?>
              <li class="list-inline-item"><a href="<?=get_option('social_pinterest_link','')?>" target="_blank" class="btn btn-icon btn-pinterest"><i class="fa fa-pinterest"></i></a></li>
              <?php }?>

              <?php 
                if (get_option('social_tumblr_link','') != '') {
              ?>
              <li class="list-inline-item"><a href="<?=get_option('social_tumblr_link','')?>" target="_blank" class="btn btn-icon btn-vk"><i class="fa fa-tumblr"></i></a></li>
              <?php }?>

              <?php 
                if (get_option('social_youtube_link','') != '') {
              ?>
              <li class="list-inline-item"><a href="<?=get_option('social_youtube_link','')?>" target="_blank" class="btn btn-icon btn-youtube"><i class="fa fa-youtube"></i></a></li>
              <?php }?>

            </ul>
          </div>
        </div>
      </div>
      
      <?php
        $version = get_field(PURCHASE, ['pid' => 23595718], 'version');
        $version = 'Ver'.$version;
      ?>
      <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
        <?=get_option('copy_right_content',"Copyright &copy; 2020 - SmartPanel")?> <?=(get_role("admin")) ? $version : "" ?>
      </div>
    </div>
  </div>
</footer>
<script type="text/javascript">
    var count = 20; // Timer
    var redirect = "/add_funds"; // Target URL

    function countDown() {
        var timer = document.getElementById("timer"); // Timer ID
        if (count > 0) {
            count--;
            timer.innerHTML = "<h3>This page will redirect in " + count + " seconds. </h3>"; // Timer Message
            setTimeout("countDown()", 1000);
        } else {
            window.location.href = redirect;
        }
    }
 
</script>

