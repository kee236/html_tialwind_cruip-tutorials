<?php
class Promptpay_setting_model extends CI_Model {
    protected $table = 'promptpay_settings';
    protected $primaryKey = 'setting_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get($id) {
        return $this->db->where($this->primaryKey, $id)->get($this->table)->row_array();
    }

    public function get_by_gateway_id($gatewayId) {
        return $this->db->where('gateway_id', $gatewayId)->get($this->table)->row_array();
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