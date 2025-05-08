<?php
class Bank_account_model extends CI_Model {
    protected $table = 'bank_accounts';
    protected $primaryKey = 'account_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get($id) {
        return $this->db->where($this->primaryKey, $id)->get($this->table)->row_array();
    }

    public function get_by_gateway_setting_id($gatewaySettingId) {
        return $this->db->where('gateway_setting_id', $gatewaySettingId)->get($this->table)->result_array();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where($this->primaryKey, $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where($this->primaryKey, $id)->delete($this->table);
    }
}