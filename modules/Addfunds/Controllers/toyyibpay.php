<?php
defined('BASEPATH') or exit('No direct script access allowed');

class toyyibpay extends MX_Controller
{
    public $tb_users;
    public $tb_transaction_logs;
    public $tb_payments;
    public $tb_payments_bonuses;
    public $paypal;
    public $payment_type;
    public $payment_id;
    public $currency_code;
    public $payment_lib;
    public $mode;
    public $client_id;
    public $secret_key;
    public $take_fee_from_user;

    public function __construct($payment = "")
    {
        parent::__construct();
        $this->load->model('add_funds_model', 'model');

        $this->tb_users = USERS;
        $this->payment_type = 'toyyibpay';
        $this->tb_transaction_logs = TRANSACTION_LOGS;
        $this->tb_payments = PAYMENTS_METHOD;
        $this->tb_payments_bonuses = PAYMENTS_BONUSES;
        $this->currency_code = get_option("currency_code", "RM");
        if ($this->currency_code == "") {
            $this->currency_code = 'USD';
        }

        if (!$payment) {
            $payment = $this->model->get('id, type, name, params', $this->tb_payments, ['type' => $this->payment_type]);
        }

        $this->payment_id = $payment->id;
        $params = $payment->params;
        $option = get_value($params, 'option');
        $this->mode = get_value($option, 'environment');
        $this->payment_fee = get_value($option, 'tnx_fee');
        // options
        $this->secret_key = get_value($option, 'secret_key');
        $this->category_code = get_value($option, 'category_code');
    }

    public function index()
    {
        redirect(cn("add_funds"));
    }

    /**
     *
     * Create payment
     *
     */
    public function create_payment($data_payment = "")
    {
        _is_ajax($data_payment['module']);
        $amount = $data_payment['amount'];

        if (!$amount) {
            _validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
        }

        if (!$this->secret_key || !$this->category_code) {
            _validation('error', lang('this_payment_is_not_active_please_choose_another_payment_or_contact_us_for_more_detail'));
        }

        $users = session('user_current_info');

        // request toyyibpay payment url
        // insert this info base on your sistem
        $secret_key = $this->secret_key;
        $category_code = $this->category_code;
        $billName = lang('Deposit_to_') . get_option('website_name');
        $billDescription = lang("Balance_recharge") . " - " . $users['email'];
        $billExternalReferenceNo = session("uid") . time();

        $txn_fee = $amount * ($this->payment_fee / 100);
        $billAmount = ($amount - $txn_fee) * 100;

        $payment_data = array(
            'userSecretKey' => $secret_key,
            'categoryCode' => $category_code,
            'billName' => $billName,
            'billDescription' => $billDescription,
            'billPriceSetting' => 1,
            'billPayorInfo' => 0,
            'billAmount' => $amount * 100,
            'billReturnUrl' => cn("add_funds/toyyibpay/complete"),
            'billCallbackUrl' => cn("add_funds/toyyibpay/callback"),
            'billExternalReferenceNo' => $billExternalReferenceNo,
            'billTo' => $users['first_name'] . ' ' . $users['last_name'],
            'billEmail' => $users['email'],
            'billPhone' => '',
            'billSplitPayment' => 0,
            'billSplitPaymentArgs' => '',
            'billPaymentChannel' => 0,
        );

        // echo '<pre>';
        // print_r($payment_data);
        // echo '</pre>';

        // php curl to post data to payment gateway
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payment_data);

        $payment_result = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $payment_result = json_decode($payment_result, true);

        // echo '<pre>';
        // print_r($payment_result);
        // echo '</pre>';
        // exit;

        $payment_data['billCode'] = $payment_result[0]['BillCode'];
        $payment_data['paymentURL'] = 'https://toyyibpay.com/' . $payment_result[0]['BillCode'];

        if (!isset($payment_result['status']) && $payment_result['status'] != 'error') {

            $data_tnx_log = array(
                "ids" => ids(),
                "uid" => session("uid"),
                "payer_email" => $users['email'],
                "type" => $this->payment_type,
                "transaction_id" => $billExternalReferenceNo,
                "txn_fee" => $txn_fee,
                "amount" => $billAmount / 100,
                "status" => 0,
                "created" => NOW,
            );
            $transaction_log_id = $this->db->insert($this->tb_transaction_logs, $data_tnx_log);

            echo '<div class="text-center">';
            echo '<a href="' . $payment_data['paymentURL'] . '" class="btn btn-primary">Continue Payment</a>';
            echo '</div>';
            exit;
        } else {
            _validation('error', $payment_result['msg']);
        }
    }

    /**
     *
     * Call Execute payment after creating payment
     *
     */
    public function complete()
    {
        $transid = $_REQUEST['transaction_id'];
        $pstatus = $_REQUEST['status_id'];

        $transaction = $this->model->get('*', $this->tb_transaction_logs, ['transaction_id' => $_REQUEST["order_id"], 'status' => 0, 'type' => $this->payment_type]);

        if (!empty($transaction) && $pstatus == 1) {
            $data_tnx_log = array(
                "data" => 'ToyyibPay Transaction Number : ' . $transid,
                "status" => 1,
            );

            $this->db->update($this->tb_transaction_logs, $data_tnx_log, ['id' => $transaction->id]);

            // Update Balance
            require_once 'add_funds.php';
            $add_funds = new add_funds();
            $add_funds->add_funds_bonus_email($transaction, $this->payment_id);

            set_session("transaction_id", $transaction->id);
            redirect(cn("add_funds/success"));
        } else {
            $transaction2 = $this->model->get('*', $this->tb_transaction_logs, ['transaction_id' => $_REQUEST["order_id"], 'status' => 1, 'type' => $this->payment_type]);

            if (!empty($transaction2) && $pstatus == 1) {
                set_session("transaction_id", $transaction2->id);
                redirect(cn("add_funds/success"));
            } else {
                redirect(cn("add_funds/unsuccess"));
            }
        }
    }

    public function callback()
    {
        $transid = $_REQUEST['transaction_id'];
        $pstatus = $_REQUEST['status_id'];

        $transaction = $this->model->get('*', $this->tb_transaction_logs, ['transaction_id' => $_REQUEST["order_id"], 'status' => 0, 'type' => $this->payment_type]);

        if (!empty($transaction) && $pstatus == 1) {
            $data_tnx_log = array(
                "data" => 'ToyyibPay Transaction Number : ' . $transid,
                "status" => 1,
            );
            $this->db->update($this->tb_transaction_logs, $data_tnx_log, ['id' => $transaction->id]);

            // Update Balance
            require_once 'add_funds.php';
            $add_funds = new add_funds();
            $add_funds->add_funds_bonus_email($transaction, $this->payment_id);

            echo 'OK';
            exit;
        }
    }
}
