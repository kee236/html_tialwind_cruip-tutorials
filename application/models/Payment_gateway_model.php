<?php
class Payment_gateway_model extends CI_Model {
    protected $table = 'payment_gateways';
    protected $primaryKey = 'gateway_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        return $this->db->get($this->table)->result_array();
    }

    public function get($id) {
        return $this->db->where($this->primaryKey, $id)->get($this->table)->row_array();
    }

    public function get_by_name($name) {
        return $this->db->where('gateway_name', $name)->get($this->table)->row_array();
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