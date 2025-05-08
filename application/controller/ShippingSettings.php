<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ShippingSettings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('shipping_method_model');
        $this->load->model('ems_setting_model');
        $this->load->model('kerry_setting_model');
        $this->load->model('integration_setting_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'ตั้งค่าการจัดส่งสินค้าและการเชื่อมต่อระบบ';
        $data['shipping_methods'] = $this->shipping_method_model->get_all_ordered(); // ดึงวิธีการจัดส่งเรียงตามลำดับ
        $data['ems_settings'] = $this->ems_setting_model->get_settings();
        $data['kerry_settings'] = $this->kerry_setting_model->get_settings();
        $data['integration_settings'] = $this->integration_setting_model->get_all();

        $this->load->view('shipping_settings/index', $data);
    }

    public function save_shipping_settings()
    {
        // Logic สำหรับบันทึกการตั้งค่าวิธีการจัดส่ง
        if ($this->input->post('shipping_methods')) {
            foreach ($this->input->post('shipping_methods') as $method_id => $data) {
                $is_enabled = isset($data['is_enabled']) ? 1 : 0;
                $order = $data['order'] ?? 0;
                $this->shipping_method_model->update($method_id, array('is_enabled' => $is_enabled, 'order' => $order));

                // บันทึกการตั้งค่าเฉพาะของแต่ละวิธี (คุณต้องมี Logic สำหรับแต่ละวิธี)
                if ($this->input->post('ems_settings') && $method_id == $this->shipping_method_model->get_id_by_name('ems')) {
                    $ems_data = $this->input->post('ems_settings');
                    $existing_settings = $this->ems_setting_model->get_settings();
                    if ($existing_settings) {
                        $this->ems_setting_model->update($existing_settings['setting_id'], $ems_data);
                    } else {
                        $ems_data['method_id'] = $method_id;
                        $this->ems_setting_model->insert($ems_data);
                    }
                }

                if ($this->input->post('kerry_settings') && $method_id == $this->shipping_method_model->get_id_by_name('kerry')) {
                    $kerry_data = $this->input->post('kerry_settings');
                    $existing_settings = $this->kerry_setting_model->get_settings();
                    if ($existing_settings) {
                        $this->kerry_setting_model->update($existing_settings['setting_id'], $kerry_data);
                    } else {
                        $kerry_data['method_id'] = $method_id;
                        $this->kerry_setting_model->insert($kerry_data);
                    }
                }
                // ... Logic สำหรับวิธีการจัดส่งอื่นๆ ...
            }
        }

        $this->session->set_flashdata('success', 'บันทึกการตั้งค่าการจัดส่งสำเร็จ');
        redirect('shipping-settings');
    }

    public function save_integration_settings()
    {
        // Logic สำหรับบันทึกการตั้งค่าการเชื่อมต่อระบบอื่นๆ
        if ($this->input->post('integrations')) {
            foreach ($this->input->post('integrations') as $integration_id => $data) {
                $is_enabled = isset($data['is_enabled']) ? 1 : 0;
                $config_json = json_encode($data['config']); // แปลง Array เป็น JSON
                $this->integration_setting_model->update($integration_id, array('is_enabled' => $is_enabled, 'config_json' => $config_json));
            }
        }

        $this->session->set_flashdata('success', 'บันทึกการตั้งค่าการเชื่อมต่อระบบสำเร็จ');
        redirect('shipping-settings');
    }
}