<?php
class Shipping_method_model extends CI_Model {
    public $table = 'shipping_methods';
    public $primary_key = 'method_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        return $this->db->get($this->table)->result_array();
    }

    public function get($id) {
        return $this->db->where($this->primary_key, $id)->get($this->table)->row_array();
    }

    public function get_where($where) {
        return $this->db->where($where)->get($this->table)->result_array();
    }

    public function get_all_ordered() {
        $this->db->order_by('order', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function get_id_by_name($name) {
        $result = $this->db->where('method_name', $name)->get($this->table)->row_array();
        return $result ? $result['method_id'] : null;
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where($this->primary_key, $id);
        return $this->db->update($this->table, $data);
    }
}
