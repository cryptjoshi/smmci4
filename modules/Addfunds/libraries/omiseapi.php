<?php 
require_once('omise_php/lib/Omise.php');

/**
 * 
 */
class omiseapi{
    public function __construct($omise_secret_key = null, $omise_publishable_key = null, $mode = "") {
       
	}
    private function omise_process()
    {

    }
        // Assign Omise Key
    //     define('OMISE_PUBLIC_KEY', settings()->omise->public_key);
    //     define('OMISE_SECRET_KEY', settings()->omise->secret_key);

    //     /* Payment details */
    //     $product = $this->plan->name;
    //     $price = $base_amount = (float) $this->plan->{$_POST['payment_frequency'] . '_price'};
    //     $shipping = 0;
    //     $code = '';
    //     $discount_amount = 0;

    //     /* Check for code usage */
    //     if ($this->code) {

    //         /* Discount amount */
    //         $discount_amount = number_format(($price * $this->code->discount / 100), 2, '.', '');

    //         /* Calculate the new price */
    //         $price = $price - $discount_amount;

    //         $code = $this->code->code;
    //     }

    //     /* Taxes */
    //     $price = $this->calculate_price_with_taxes($price);

    //     /* Make sure the price is right depending on the currency */
    //     $price = in_array(settings()->payment->currency, ['JPY', 'TWD', 'HUF','TH']) ? number_format($price, 0, '.', '') : number_format($price, 2, '.', '');

    //     // Change Plan
    //     $payment = Database::get('*', 'payments', ['payer_id' => $this->user->user_id], 'id DESC');

    //     $amount = Omise::get_amount($this->user->user_id);
        
    //     try {
    //         $card_token = Omise::card_token([
    //         'omise_card_name' => $_POST['omise_card_name'],
    //         'omise_card_number' => preg_replace("/\s+/", "", $_POST['omise_card_number']),
    //         'omise_card_month' => $_POST['omise_card_month'],
    //         'omise_card_year' => $_POST['omise_card_year'],
    //         'omise_card_cvv' => preg_replace("/\s+/", "", $_POST['omise_card_cvv']),
    //       ]);
    //     } catch (Exception $e) {
    //         Alerts::add_error(language()->pay->error_message->omise_card."".$e);
    //         redirect('pay/' . $this->plan_id);
    //     }
    
    //     $user_credit_card = null;

    //     if (!$card_token) {
    //         Alerts::add_error(language()->pay->error_message->omise_card."".$e);
    //         redirect('pay/' . $this->plan_id);
    //     } else {
    //         $user_credit_card = [
    //         "name" => $_POST['omise_card_name'],
    //         "number" => $_POST['omise_card_number'],
    //         "month" => $_POST['omise_card_month'],
    //         "year" => $_POST['omise_card_year'],
    //         'token' => $card_token['id']
    //       ];
    //     }


    //     $is_destroy = Omise::destroy($this->user->user_id);

    //     if ($is_destroy->is_payment == true and $is_destroy->status == false) {
    //         Alerts::add_error(language()->pay->error_message->omise_destroy);
    //         redirect('pay/' . $this->plan_id);
    //     }
        
        
    //     $token_id = $card_token['id'];
    //     $omise_price = intval($price * 100);
    //     $new_price = 0;


        

    //     if (empty($this->user->omise_id)) {
    
    //         $customer = \OmiseCustomer::create([
    //           'email' => $this->user->email,
    //           'description' => $this->user->name,
    //           'card' => $token_id
    //         ]);
    //        // echo  $this->user->user_id;
    //        // echo $customer['id'];
    //         Database::update(
    //             'users',
    //             [
    //               'omise_id' => $customer['id'],
    //           ],
    //             [
    //               'user_id' => $this->user->user_id
    //           ]
    //         );

    //         $customer_id = $customer['id'];
           
    //     } else {
    //         $customer_id = $this->user->omise_id;
    //         $omise_customer = \OmiseCustomer::retrieve($this->user->omise_id);
    //         $cards = $omise_customer->getCards();
    //         if (empty($cards['data'])) {
    //             $omise_customer->update([
    //               'card' => $token_id,
    //             ]);
    //         }
    //     }
        
    //    // echo $this->user->credit_card->number;
    //   //  echo $_POST['omise_card_number'];
    //     if (!empty($this->user->credit_card)) {
           
    //         if ($this->user->credit_card->number != $_POST['omise_card_number']) {
    //              $omise_customer = \OmiseCustomer::retrieve($this->user->omise_id);
    //              $omise_customer->update([
    //                'card' => $token_id,
    //              ]);
    //             $cards = $omise_customer->getCards();
    //            // print_r($cards);
    //             if (isset($cards['data'])) {
    //                 $card = end($cards['data']);
    //                 $omise_customer->update([
    //                 'default_card' => $card['id']
    //               ]);
    //             }
    //         }
    //     }
    //     // print_r($this->user);
    //     // print_r($this->user->credit_card);
        
    //     $payment_id = '';
        
    //     switch ($_POST['payment_type']) {
    //       case 'one_time':
    //         try {
    //             $charge = \OmiseCharge::create([
    //               'amount' => $omise_price,
    //               'currency' => settings()->payment->currency,
    //               'customer' => $customer_id,
    //               'metadata' => [
    //                 'user_id' => $this->user->user_id,
    //                 'plan_id' => $this->plan_id,
    //                 'processor' => 'omise',
    //                 'type' => $_POST['payment_type'],
    //                 'frequency' => $_POST['payment_frequency'],
    //                 'code' => $code,
    //                 'discount_amount' => $discount_amount,
    //                 'base_amount' => $base_amount,
    //                 'email' => $this->user->email,
    //                 'payment_id' => $payment_id,
    //                 'subscription_id' => '',
    //                 'payer_id' => $this->user->user_id,
    //                 'name' => $this->user->name,
    //                 'billing' => settings()->payment->taxes_and_billing_is_enabled && $this->user->billing ? json_encode($this->user->billing) : null,
    //                 'taxes_ids' => !empty($this->applied_taxes_ids) ? json_encode($this->applied_taxes_ids) : null,
    //                 'total_amount' => $price,
    //                 'currency' => settings()->payment->currency
    //               ]
    //               // 'return_uri' => 'http://localhost:8000/'
    //             ]);
               
    //             $payment_id = $charge['id'];
    //         } catch (Exception $e) {
    //             Alerts::add_error(language()->pay->error_message->omise_card);
    //             redirect('pay/' . $this->plan_id);
    //         }
    //         break;

    //       case 'recurring':
    //         try {
    //             $charge = [
    //               // 'card' => $token_id,
    //               'customer' => $customer_id,
    //               'amount' => $omise_price,
    //               'currency' => settings()->payment->currency,
    //               'metadata' => [
    //                 'user_id' => $this->user->user_id,
    //                 'plan_id' => $this->plan_id,
    //                 'processor' => 'omise',
    //                 'type' => $_POST['payment_type'],
    //                 'frequency' => $_POST['payment_frequency'],
    //                 'code' => $code,
    //                 'discount_amount' => $discount_amount,
    //                 'base_amount' => $base_amount,
    //                 'email' => $this->user->email,
    //                 'payment_id' => $payment_id,
    //                 'subscription_id' => '',
    //                 'payer_id' => $this->user->user_id,
    //                 'name' => $this->user->name,
    //                 'billing' => settings()->payment->taxes_and_billing_is_enabled && $this->user->billing ? json_encode($this->user->billing) : null,
    //                 'taxes_ids' => !empty($this->applied_taxes_ids) ? json_encode($this->applied_taxes_ids) : null,
    //                 'total_amount' => $price,
    //                 'currency' => settings()->payment->currency
    //               ]
    //             ];

    //             switch ($_POST['payment_frequency']) {
    //                 case 'monthly':
    //                     $period = 30;
    //                     $start_date = (new \DateTime())->modify('+30 days')->format('Y-m-d');
    //                     $end_date = (new \DateTime())->modify('+12 months')->format('Y-m-d');
    //                     break;

    //                 case 'annual':
    //                     $period = 365;
    //                     $start_date = (new \DateTime())->modify('+365 days')->format('Y-m-d');
    //                     $end_date = (new \DateTime())->modify('+24 months')->format('Y-m-d');
    //                     break;
    //             }


    //             $schedule = \OmiseSchedule::create([
    //               'charge' => $charge,
    //               'every' => $period,
    //               'period' => 'day',
    //               // 'start_date' => $start_date,
    //               'end_date' => $end_date
    //             ]);

    //             $payment_id = $schedule['id'];
    //         } catch (Exception $e) {
    //             Alerts::add_error(language()->pay->error_message->omise_card."".$e);
    //             redirect('pay/' . $this->plan_id);
    //         }
    //         break;
    //     }
      
        
    //     if ($_POST['payment_frequency'] == 'lifetime' || $_POST['payment_type'] == 'one_time') {
    //         $charge = \OmiseCharge::retrieve($payment_id);

    //     } else {
    //         $schedule = \OmiseSchedule::retrieve($payment_id);
    //         $occurrences = $schedule['occurrences']['data'];
    //         if (empty($occurrences)) {
    //             Alerts::add_error(language()->pay->error_message->omise_card);
    //             redirect('pay/' . $this->plan_id);
    //         }
    //         $charge_id = $occurrences[0]['result'];
    //         $charge = \OmiseCharge::retrieve($charge_id);
    //     }

    //     if ($charge['status'] != 'successful') {
    //         Alerts::add_error($charge['failure_message']);
    //         redirect('pay/' . $this->plan_id);
    //     }

    //     /* Update the user with the new plan */
    //     switch ($_POST['payment_frequency']) {
    //         case 'monthly':
    //             $expire_date = (new \DateTime())->modify('+30 days')->format('Y-m-d H:i:s');
    //             $expire_text = '+30 days';
    //             break;

    //         case 'annual':
    //             $expire_date = (new \DateTime())->modify('+12 months')->format('Y-m-d H:i:s');
    //             $expire_text = '+12 months';
    //             break;

    //         case 'lifetime':
    //             $expire_date = (new \DateTime())->modify('+100 years')->format('Y-m-d H:i:s');
    //             $expire_text = '+100 years';
    //             break;
    //     }

    //     $plan_expiration_date = $expire_date;
    //     if (isset($this->user->plan_expiration_date) and is_numeric($this->user->plan_id)) {
    //         $plan_expiration_date = date_create($this->user->plan_expiration_date)->modify($expire_text)->format('Y-m-d H:i:s');
    //     }
       
    //     Database::update(
    //         'users',
    //         [
    //             'credit_card' => json_encode($user_credit_card)
    //         ],
    //         [
    //             'user_id' => $this->user->user_id
    //         ]
    //     );
    //     $payment = $charge['metadata'];
     
    //     Database::insert(
    //         'payments',
    //         [
    //             'user_id' => $payment['user_id'],
    //             'plan_id' => $payment['plan_id'],
    //             'processor' => 'omise',
    //             'type' => $payment['type'],
    //             'frequency' => $payment['frequency'],
    //             'code' => $payment['code'],
    //             'discount_amount' => $payment['discount_amount'],
    //             'base_amount' => $payment['base_amount'],
    //             'email' => $payment['email'],
    //             'payment_id' =>  $payment_id,
    //             'subscription_id' => '',
    //             'payer_id' => $payment['payer_id'],
    //             'name' => $payment['name'],
    //             'billing' => $payment['billing'],
    //             'taxes_ids' => $payment['taxes_ids'],
    //             'total_amount' => $payment['total_amount'],
    //             'currency' => $payment['currency'],
    //             'date' => Date::$date,
    //             'status'=>1,
    //             'omise_log' => json_encode($charge)
               
    //         ],
    //         false
    //     );
        
    //     db()->where('user_id', $this->user->user_id)->update('users', [
    //         'plan_id' => $this->plan_id,
    //         'plan_settings' => json_encode($this->plan->settings),
    //         'plan_expiration_date' => $plan_expiration_date
    //     ]);

    //     //echo "break";
    //     /* Clear the cache */
    //     \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $this->user->user_id);
    //     (new \Altum\Models\User())->clear_redis($this->user->user_id);

    //     /* Send notification to the user */
    //     /* Prepare the email */
    //     $email_template = get_email_template(
    //         [],
    //         language()->global->emails->user_payment->subject,
    //         [
    //             '{{PLAN_EXPIRATION_DATE}}' => Date::get($plan_expiration_date, 2),
    //             '{{USER_PACKAGE_LINK}}' => url('account-plan'),
    //             '{{USER_PAYMENTS_LINK}}' => url('account-payments'),
    //         ],
    //         language()->global->emails->user_payment->body
    //     );
        
    //     send_mail(
    //         $this->user->email,
    //         $email_template->subject,
    //         $email_template->body
    //     );

    //     /* Send notification to admin if needed */
    //     if (settings()->email_notifications->new_payment && !empty(settings()->email_notifications->emails)) {
    //         send_mail(
    //             explode(',', settings()->email_notifications->emails),
    //             sprintf(language()->global->emails->admin_new_payment_notification->subject, 'omise', $payment_total, $payment_currency),
    //             sprintf(language()->global->emails->admin_new_payment_notification->body, $payment_total, $payment_currency)
    //         );
    //     }
     
       
    //     /* Success message and redirect */
    //     $this->redirect_pay_thank_you();
    // }
}