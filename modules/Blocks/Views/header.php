
<style>
  .search-box input.form-control{
    margin: -1px;
  }
  .search-box select.form-control{
    border-radius: 0px;
    border: 1px solid #fff;
  }
</style>

<div class="header top  py-4">
  <div class="container">
    <div class="d-flex">
      <a class="" href="<?=cn(`{$locale}/statistics`)?>">
        <img src="<?=get_option('website_logo_white', BASE."assets/images/logo-white.png")?>" alt="Website logo" style="max-height: 40px;">
      </a>
      <div class="d-flex order-lg-2 ml-auto my-auto search-box">
        <div class="search-box m-r-30 d-none d-lg-block">
          <?php
            if ( allowed_search_bar(segment(1)) || allowed_search_bar(segment(2)) ) {
              //$module = Modules\Blocks\Controllers\Blocks();
              echo $module->search_box();
              // echo Modules::run("blocks/search_box");
            }
          ?>
        </div>
        
        <?php
          if (session('uid_tmp')) {
        ?>
        <div class="notifcation m-r-10">
          <a href="<?=cn("blocks/back_to_admin")?>" data-toggle="tooltip" data-placement="bottom" title="<?=lang('app.back_to_Admin')?>" class="text-white ajaxBackToAdmin">
            <i class="fe fe-log-out"></i>
          </a>
        </div>
        <?php } ?>
        <?php
          if (get_option("enable_news_announcement") == 1) {
        ?>
        <div class="notifcation">
          <a href="<?=cn("news/ajax_notification")?>" data-toggle="tooltip" data-placement="bottom" title="<?=lang("app.news__announcement")?>" class="ajaxModal text-white">
            <i class="fe fe-bell"></i>
            <div class="test">
              <span class="nav-unread <?=(isset($_COOKIE["news_annoucement"]) && $_COOKIE["news_annoucement"] == "clicked") ? "" : "change_color"?>"></span>
            </div>
          </a>
        </div>
        <?php }?>

        <?php
            $redirect = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
          ?>
          <?php 
            if (!empty($languages)) {
          ?>
          <select class="footer-lang-selector ajaxChangeLanguage" name="ids" data-url="<?=cn('language/set_language/')?>" data-redirect="<?=$redirect?>">
            <?php 
              foreach ($languages as $key => $row) {
                echo $row->code.'->'.session('langCurrent')->code;

            ?>
            <option value="<?=$row->ids?>" <?=(!empty($lang_current) && $lang_current->code == $row->code) ? 'selected' : '' ?> ><?=language_codes($row->code)?></option>
            <?php }?>
          </select>
            <?php }?>
        <div class="dropdown">
          <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
            <span class="avatar" style="background-image: url(<?=BASE?>assets/images/user-avatar.png)"></span>
            <span class="ml-2 d-none d-lg-block">
              <span class="text-default text-white"><?=lang("app.hi")?>, <span class="text-uppercase"><?php _echo(get_field(USERS, ["id" => session('uid')], 'first_name'))?></span>!</span>
              <small class="text-muted  text-white d-block mt-1">
                <?php
                  // !get_role("admin")
                  if (!get_role("admin")) {
                    $balance = get_field(USERS, ["id" => session('uid')], 'balance');

                    switch (get_option('currency_decimal_separator', 'dot')) {
                      case 'dot':
                        $decimalpoint = '.';
                        break;
                      case 'comma':
                        $decimalpoint = ',';
                        break;
                      default:
                        $decimalpoint = '';
                        break;
                    } 

                    switch (get_option('currency_thousand_separator', 'comma')) {
                      case 'dot':
                        $separator = '.';
                        break;
                      case 'comma':
                        $separator = ',';
                        break;
                      case 'space':
                        $separator = ' ';
                        break;
                      default:
                        $separator = '';
                        break;
                    }
                    if (empty($balance) || $balance == 0) {
                      $balance = 0.00;
                    }else{
                      $balance = currency_format($balance,  get_option('currency_decimal', 2), $decimalpoint, $separator);
                    }
                ?>
                <?=lang("app.balance")?>: <?=get_option('currency_symbol',"$")?><?=$balance?>
                <?php }else{?> 
                  <?=lang("app.admin_account")?>
                <?php }?> 
              </small>
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
            <a class="dropdown-item" href="<?=cn('profile')?>">
              <i class="dropdown-icon fe fe-user"></i> <?=lang("app.profile")?>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?=cn("auth/logout")?>">
              <i class="dropdown-icon fe fe-log-out"></i> <?=lang("app.sign_out")?>
            </a>
          </div>
        </div>
      </div>
      <a href="#" class="header-toggler text-white d-lg-none ml-3 ml-lg-0 my-auto" data-toggle="collapse" data-target="#headerMenuCollapse">
        <span class="header-toggler-icon"></span>
      </a>
    </div>
  </div>
</div>
<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg order-lg-first">
        <ul class="nav nav-tabs border-0 flex-column flex-lg-row">

          <li class="nav-item">
            <a href="<?=cn('statistics')?>" class="nav-link <?=(segment(1) == 'statistics')?"active":""?>"><i class="fe fe-bar-chart-2"></i> <?=lang("app.statistics")?></a>
          </li>   

          <li class="nav-item">
            <a href="<?=cn('order/add')?>" class="nav-link <?=(segment(1) == 'order' && segment(2) == 'add')?"active":""?>"><i class="fe fe fe-shopping-cart"></i> <?=lang("app.new_order")?></a>
          </li>

          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link  <?=(segment(1) == 'dripfeed' || (segment(1) == 'order'  && segment(2) == 'log')|| segment(1) == 'subscriptions')?"active":""?>" data-toggle="dropdown"><i class="fe fe-inbox"></i><?=lang('app.order')?></a>
            <div class="dropdown-menu dropdown-menu-arrow">
              <a href="<?=cn('order/log')?>" class="dropdown-item "><?=lang("app.order_logs")?></a>
			  <a href="<?=cn('refill/log')?>" class="dropdown-item "><?=lang("app.refill_logs")?></a>
              <a href="<?=cn('dripfeed')?>" class="dropdown-item "><?=lang("app.dripfeed")?></a>
              <a href="<?=cn('subscriptions')?>" class="dropdown-item "><?=lang("app.subscriptions")?></a>
            </div>
          </li>
          
          <?php
            if (get_role("admin") || get_role("supporter")) {
          ?>
          <li class="nav-item">
            <a href="<?=cn('category')?>" class="nav-link <?=(segment(1) == 'category')?"active":""?>"><i class="fa fa-table"></i> <?=lang("app.category")?></a>
          </li>
          
          <?php }?>

          <li class="nav-item dropdown">
            <a href="<?=cn('services')?>" class="nav-link <?=(segment(1) == 'services')?"active":""?>"><i class="fe fe-list"></i> <?=lang('app.services')?></a>
          </li>  

          <?php 
            if (get_option('enable_api_tab') && !get_role("admin")) {
          ?>      
          <li class="nav-item dropdown">
            <a href="<?=cn('api/docs')?>" class="nav-link <?=(segment(2) == 'docs')?"active":""?>"><i class="fe fe-share-2"></i> <?=lang("app.api")?></a>
          </li>
          <?php }?>   
                 
          <?php
            if (get_role("user")) {
          ?>           
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link <?=(segment(1) == 'tickets' || segment(1) == 'faqs')?"active":""?>" data-toggle="dropdown"><i class="fa fa-comments-o"></i>
              <?=lang('support')?> <span class="badge badge-info"><?=$total_unread_tickets?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow">
              <a href="<?=cn('tickets')?>" class="dropdown-item ">
                <?=lang("app.tickets")?>
                <span class="badge badge-info"><?=$total_unread_tickets?></span>
              </a>
              <a href="<?=cn('faq')?>" class="dropdown-item "><?=lang("app.faqs")?></a>
            </div>
          </li>
          <?php }else{?>
            <li class="nav-item dropdown">
              <a href="<?=cn('tickets')?>" class="nav-link <?=(segment(1) == 'tickets') ? "active": ""?>"><i class="fa fa-comments-o"></i><?=lang("app.tickets")?> <span class="badge badge-info"><?=$total_unread_tickets?></span></a>
            </li>  
          <?php } ?>

          <?php
            if (get_role("user")) {
          ?>
          <li class="nav-item dropdown">
            <a href="<?=cn('add_funds')?>" class="nav-link <?=(segment(1) == 'add_funds')?"active":""?>"><i class="fa fa-money"></i> <?=lang("app.add_funds")?></a>
          </li>   
          <?php }?>
          
          <li class="nav-item dropdown">
            <a href="<?=cn('transactions')?>" class="nav-link <?=(segment(1) == 'transactions')?"active":""?>"><i class="fe fe-calendar"></i> <?=lang("app.transaction_logs")?></a>
          </li>

          <?php if(get_option("enable_affiliate") == "1"){?>
          <li class="nav-item dropdown">
            <a href="<?=cn('affiliate')?>" class="nav-link <?=(segment(1) == 'affiliate')?"active":""?>"><i class="fa fa-handshake-o"></i> &nbsp;<?=lang("app.affiliate")?></a>
          </li>
          <?php }?>
          
          <?php if(get_option("is_childpanel_status") == "1"){?>
	
          <li class="nav-item">
                      <a href="javascript:void(0)" class="nav-link <?=(segment(1) == 'childpanel' || segment(1) == 'childpanel/log')?"active":""?>" data-toggle="dropdown"><i class="fa fa-briefcase" aria-hidden="true"></i>
                  Childpanel</a>
                      <div class="dropdown-menu dropdown-menu-arrow">
                        <a href="<?=cn('childpanel/add')?>" class="dropdown-item ">
                      New Panel
                        </a>
                        <a href="<?=cn('childpanel/log')?>" class="dropdown-item ">Panel Logs</a>
                      </div>
                    </li>
          <?php }?>

          <?php 
          if(get_role("admin") || get_role("supporter")){
            $user_manager = array(
              'users',
              'subscribers',
              'add_funds',
              'user_logs',
              'user_block_ip',
              'user_mail_logs',
            );
          ?>
         
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link <?=(in_array(segment(1), $user_manager)) ? "active":""?>" data-toggle="dropdown"><i class="fe fe-users"></i><?=lang("app.user_manager")?></a>
            <div class="dropdown-menu dropdown-menu-arrow">
              <a href="<?=cn('users')?>" class="dropdown-item"><?=lang("app.users")?></a>
              <a href="<?=cn('subscribers')?>" class="dropdown-item"><?php echo lang("app.subscribers"); ?></a>
              <div class="dropdown-divider"></div>
              <a href="<?=cn('add_funds')?>" class="dropdown-item"><?=lang("app.add_funds")?></a>
            </div>
          </li>
          <?php }?>

          <?php if(get_role("admin") ||  get_role("supporter")){
            $setting_system = array(
              'setting',
              'api_provider',
              'news',
              'payments',
              'payments_bonuses',
              'faqs',
              'language',
              'module',
            );
          ?>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link <?=(in_array(segment(1), $setting_system))?"active":""?>" data-toggle="dropdown"><i class="fe fe-settings"></i><?=lang("app.system_settings")?></a>
            <div class="dropdown-menu dropdown-menu-arrow">
              <?php if(get_role("admin")){?>
              <a href="<?=cn('setting')?>" class="dropdown-item"><?=lang("app.settings")?></a>
              <a href="<?=cn('api_provider')?>" class="dropdown-item"><?=lang("app.api_providers")?></a>
              <a href="<?=cn('payments')?>" class="dropdown-item"><?php echo lang("app.payments"); ?></a>
              <a href="<?=cn('payments_bonuses')?>" class="dropdown-item"><?php echo lang("app.payment_bonuses"); ?></a>
              <div class="dropdown-divider"></div>
              <?php } ?>
              <?php if(get_role("admin") ||  get_role("supporter")){?>
              <a href="<?=cn('news')?>" class="dropdown-item"><?=lang("app.news__announcement")?></a>
              <a href="<?=cn('faqs')?>" class="dropdown-item"><?=lang("app.faqs")?></a>
              <a href="<?=cn('language')?>" class="dropdown-item"><?=lang("app.language")?></a>
              <?php } ?>

              <?php if(get_role("admin")){?>
              <div class="dropdown-divider"></div>
              <a href="<?=cn('module')?>" class="dropdown-item"><?=lang("app.modules")?></a>
              <a href="https://smartpanelsmm.com/docs/" class="dropdown-item"><?=lang("app.documentation")?></a>
              <?php } ?>
            </div>
          </li>
          <?php } ?>

        </ul>
      </div>
    </div>
  </div>
</div>

