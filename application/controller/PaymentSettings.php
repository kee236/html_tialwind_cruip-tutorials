<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PaymentSettings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('payment_gateway_model');
        $this->load->model('paypal_setting_model');
        $this->load->model('stripe_setting_model');
        $this->load->model('bank_transfer_setting_model');
        $this->load->model('bank_account_model');
        $this->load->model('promptpay_setting_model');
        $this->load->model('ewallet_setting_model');
        $this->load->model('payment_gateway_provider_model');
        $this->load->model('payment_gateway_setting_model');
        $this->load->model('custom_payment_setting_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation'); // โหลด Form Validation Library
    }

    public function index()
    {
        $data['title'] = 'ตั้งค่าระบบชำระเงิน';

        // ดึงข้อมูลการตั้งค่าปัจจุบันจาก Models ต่างๆ
        $data['payment_gateways'] = $this->payment_gateway_model->get_all();
        $data['paypal_settings'] = $this->paypal_setting_model->get_by_gateway_id($this->payment_gateway_model->get_by_name('paypal')['gateway_id'] ?? null);
        $data['stripe_settings'] = $this->stripe_setting_model->get_by_gateway_id($this->payment_gateway_model->get_by_name('stripe')['gateway_id'] ?? null);
        $data['bank_transfer_settings'] = $this->bank_transfer_setting_model->get_by_gateway_id($this->payment_gateway_model->get_by_name('bank_transfer')['gateway_id'] ?? null);
        $bank_transfer_setting = $this->bank_transfer_setting_model->get_by_gateway_id($this->payment_gateway_model->get_by_name('bank_transfer')['gateway_id'] ?? null);
        $data['bank_accounts'] = $this->bank_account_model->get_by_gateway_setting_id($bank_transfer_setting['setting_id'] ?? null);
        $data['promptpay_settings'] = $this->promptpay_setting_model->get_by_gateway_id($this->payment_gateway_model->get_by_name('promptpay')['gateway_id'] ?? null);
        $data['ewallet_settings'] = $this->ewallet_setting_model->get_by_gateway_id($this->payment_gateway_model->get_by_name('ewallets')['gateway_id'] ?? null);
        $data['payment_gateway_providers'] = $this->payment_gateway_provider_model->get_all();
        $data['payment_gateway_settings'] = $this->payment_gateway_setting_model->get_by_gateway_id($this->payment_gateway_model->get_by_name('payment_gateways')['gateway_id'] ?? null);
        $data['custom_payment_settings'] = $this->custom_payment_setting_model->get_by_gateway_id($this->payment_gateway_model->get_by_name('custom')['gateway_id'] ?? null);

        $this->load->view('payment_settings/index', $data);
    }

    public function save_settings()
    {
        // บันทึกการตั้งค่า PayPal
        if ($this->input->post('paypal_status') !== null) {
            $paypal_gateway = $this->payment_gateway_model->get_by_name('paypal');
            if ($paypal_gateway) {
                $paypal_data = array(
                    'gateway_id' => $paypal_gateway['gateway_id'],
                    'client_id' => $this->input->post('paypal_client_id'),
                    'secret' => $this->input->post('paypal_secret'),
                    'mode' => $this->input->post('paypal_mode'),
                    'currency' => $this->input->post('paypal_currency')
                );

                $existing_paypal_settings = $this->paypal_setting_model->get_by_gateway_id($paypal_gateway['gateway_id']);
                if ($existing_paypal_settings) {
                    $this->paypal_setting_model->update($existing_paypal_settings['setting_id'], $paypal_data);
                } else {
                    $this->paypal_setting_model->insert($paypal_data);
                }
                $this->payment_gateway_model->update($paypal_gateway['gateway_id'], array('is_enabled' => $this->input->post('paypal_status')));
            }
        }

        // บันทึกการตั้งค่า Stripe
        if ($this->input->post('stripe_status') !== null) {
            $stripe_gateway = $this->payment_gateway_model->get_by_name('stripe');
            if ($stripe_gateway) {
                $stripe_data = array(
                    'gateway_id' => $stripe_gateway['gateway_id'],
                    'publishable_key' => $this->input->post('stripe_publishable_key'),
                    'secret_key' => $this->input->post('stripe_secret_key'),
                    'webhook_secret' => $this->input->post('stripe_webhook_secret'),
                    'mode' => $this->input->post('stripe_mode'),
                    'currency' => $this->input->post('stripe_currency'),
                    'enable_card' => $this->input->post('stripe_enable_card') ? 1 : 0,
                    'enable_apple_pay' => $this->input->post('stripe_enable_apple_pay') ? 1 : 0,
                    'enable_google_pay' => $this->input->post('stripe_enable_google_pay') ? 1 : 0
                );

                $existing_stripe_settings = $this->stripe_setting_model->get_by_gateway_id($stripe_gateway['gateway_id']);
                if ($existing_stripe_settings) {
                    $this->stripe_setting_model->update($existing_stripe_settings['setting_id'], $stripe_data);
                } else {
                    $this->stripe_setting_model->insert($stripe_data);
                }
                $this->payment_gateway_model->update($stripe_gateway['gateway_id'], array('is_enabled' => $this->input->post('stripe_status')));
            }
        }

        // บันทึกการตั้งค่า Bank Transfer
        if ($this->input->post('bank_transfer_status') !== null) {
            $bank_transfer_gateway = $this->payment_gateway_model->get_by_name('bank_transfer');
            if ($bank_transfer_gateway) {
                $bank_transfer_data = array(
                    'gateway_id' => $bank_transfer_gateway['gateway_id'],
                    'instructions' => $this->input->post('bank_transfer_instructions'),
                    'enable_notification' => $this->input->post('bank_transfer_notification') ? 1 : 0,
                    'notification_description' => $this->input->post('bank_transfer_notification_description')
                );

                $existing_bank_transfer_settings = $this->bank_transfer_setting_model->get_by_gateway_id($bank_transfer_gateway['gateway_id']);
                $bank_transfer_setting_id = null;
                if ($existing_bank_transfer_settings) {
                    $this->bank_transfer_setting_model->update($existing_bank_transfer_settings['setting_id'], $bank_transfer_data);
                    $bank_transfer_setting_id = $existing_bank_transfer_settings['setting_id'];
                } else {
                    $this->bank_transfer_setting_model->insert($bank_transfer_data);
                    $bank_transfer_setting_id = $this->db->insert_id();
                }

                // จัดการบัญชีธนาคาร
                $bank_names = $this->input->post('bank_name');
                $account_names = $this->input->post('account_name');
                $account_numbers = $this->input->post('account_number');
                $is_active_accounts = $this->input->post('is_active');
                $account_ids = $this->input->post('account_id');

                if ($bank_transfer_setting_id && is_array($bank_names)) {
                    if (!is_array($account_ids)) {
                        $account_ids = array_fill(0, count($bank_names), null);
                    }

                    $existing_account_ids = array_column($this->bank_account_model->get_by_gateway_setting_id($bank_transfer_setting_id), 'account_id');
                    $submitted_account_ids = array_filter($account_ids, 'is_numeric');

                    // ลบบัญชีที่ไม่ได้ถูกส่งมา
                    $accounts_to_delete = array_diff($existing_account_ids, $submitted_account_ids);
                    foreach ($accounts_to_delete as $delete_id) {
                        $this->bank_account_model->delete($delete_id);
                    }

                    foreach ($bank_names as $key => $bank_name) {
                        $account_data = array(
                            'gateway_setting_id' => $bank_transfer_setting_id,
                            'bank_name' => $bank_name,
                            'account_name' => $account_names[$key],
                            'account_number' => $account_numbers[$key],
                            'is_active' => isset($is_active_accounts[$key]) ? 1 : 0
                        );

                        $account_id = $account_ids[$key];
                        if (is_numeric($account_id)) {
                            $this->bank_account_model->update($account_id, $account_data);
                        } else if ($account_id === 'new') {
                            $this->bank_account_model->insert($account_data);
                        }
                    }
                }
                $this->payment_gateway_model->update($bank_transfer_gateway['gateway_id'], array('is_enabled' => $this->input->post('bank_transfer_status')));
            }
        }

        // บันทึกการตั้งค่า PromptPay
        if ($this->input->post('promptpay_status') !== null) {
            $promptpay_gateway = $this->payment_gateway_model->get_by_name('promptpay');
            if ($promptpay_gateway) {
                $promptpay_data = array(
                    'gateway_id' => $promptpay_gateway['gateway_id'],
                    'type' => $this->input->post('promptpay_type'),
                    'merchant_id' => $this->input->post('promptpay_merchant_id'),
                    'store_name' => $this->input->post('promptpay_store_name'),
                    'mobile_number' => $this->input->post('promptpay_mobile_number')
                );

                $existing_promptpay_settings = $this->promptpay_setting_model->get_by_gateway_id($promptpay_gateway['gateway_id']);
                if ($existing_promptpay_settings) {
                    $this->promptpay_setting_model->update($existing_promptpay_settings['setting_id'], $promptpay_data);
                } else {
                    $this->promptpay_setting_model->insert($promptpay_data);
                }
                $this->payment_gateway_model->update($promptpay_gateway['gateway_id'], array('is_enabled' => $this->input->post('promptpay_status')));
            }
        }

        // บันทึกการตั้งค่า E-wallets
        if ($this->input->post('ewallets_status') !== null) {
            $ewallets_gateway = $this->payment_gateway_model->get_by_name('ewallets');
            if ($ewallets_gateway) {
                $ewallets_data = array(
                    'gateway_id' => $ewallets_gateway['gateway_id'],
                    'enable_truemoney' => $this->input->post('truemoney_enabled') ? 1 : 0,
                    'truemoney_config' => $this->input->post('truemoney_config'),
                    'enable_rabbitlinepay' => $this->input->post('rabbitlinepay_enabled') ? 1 : 0,
                    'rabbitlinepay_config' => $this->input->post('rabbitlinepay_config')
                );

                $existing_ewallet_settings = $this->ewallet_setting_model->get_by_gateway_id($ewallets_gateway['gateway_id']);
                if ($existing_ewallet_settings) {
                    $this->ewallet_setting_model->update($existing_ewallet_settings['setting_id'], $ewallets_data);
                } else {
                    $this->ewallet_setting_model->insert($ewallets_data);
                }
                $this->payment_gateway_model->update($ewallets_gateway['gateway_id'], array('is_enabled' => $this->input->post('ewallets_status')));
            }
        }

        // บันทึกการตั้งค่า Payment Gateways
        if ($this->input->post('payment_gateways_status') !== null && $this->input->post('payment_gateway_provider')) {
            $payment_gateways_gateway = $this->payment_gateway_model->get_by_name('payment_gateways');
            $provider = $this->payment_gateway_provider_model->get_by_name($this->input->post('payment_gateway_provider'));
            if ($payment_gateways_gateway && $provider) {
                $payment_gateway_data = array(
                    'gateway_id' => $payment_gateways_gateway['gateway_id'],
                    'provider_id' => $provider['provider_id'],
                    'config_json' => $this->input->post('payment_gateway_config'),
                    'mode' => $this->input->post('payment_gateway_mode'),
                    'currency' => $this->input->post('payment_gateway_currency'),
                    'enabled_channels' => $this->input->post('payment_gateway_channels')
                );

                $existing_payment_gateway_settings = $this->payment_gateway_setting_model->get_by_gateway_id($payment_gateways_gateway['gateway_id']);
                if ($existing_payment_gateway_settings) {
                    $this->payment_gateway_setting_model->update($existing_payment_gateway_settings['setting_id'], $payment_gateway_data);
                } else {
                    $this->payment_gateway_setting_model->insert($payment_gateway_data);
                }
                $this->payment_gateway_model->update($payment_gateways_gateway['gateway_id'], array('is_enabled' => $this->input->post('payment_gateways_status')));
            }
        }

        // บันทึกการตั้งค่า Custom กำหนดเอง
        if ($this->input->post('custom_status') !== null) {
            $custom_gateway = $this->payment_gateway_model->get_by_name('custom');
            if ($custom_gateway) {
                $custom_data = array(
                    'gateway_id' => $custom_gateway['gateway_id'],
                    'payment_name' => $this->input->post('custom_payment_name'),
                    'description' => $this->input->post('custom_description'),
                    'config_json' => $this->input->post('custom_config')
                );

                $existing_custom_settings = $this->custom_payment_setting_model->get_by_gateway_id($custom_gateway['gateway_id']);
                if ($existing_custom_settings) {
                    $this->custom_payment_setting_model->update($existing_custom_settings['setting_id'], $custom_data);
                } else {
                    $this->custom_payment_setting_model->insert($custom_data);
                }
                $this->payment_gateway_model->update($custom_gateway['gateway_id'], array('is_enabled' => $this->input->post('custom_status')));
            }
        }

        $this->session->set_flashdata('success', 'บันทึกการตั้งค่าสำเร็จ');
        redirect('payment-settings');
    }

    public function delete_bank_account($id)
    {
        if ($this->input->is_ajax_request()) {
            if ($id) {
                $this->bank_account_model->delete($id);
                $response = array('success' => true, 'message' => 'ลบบัญชีธนาคารสำเร็จ');
            }
            } else {
                $response = array('success' => false, 'message' => 'ไม่พบ ID บัญชีที่ต้องการลบ');
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            redirect('payment-settings');
        }
    }
}
