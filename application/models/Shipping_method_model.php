  <?php
class Shipping_method_model extends CI_Model {
    public $table = 'shipping_methods';
    public $primary_key = 'method_id';
    // ... methods get_all(), get(), insert(), update(), get_id_by_name(), get_all_ordered() ...
}

 * application/models/Ems_setting_model.php:
   <?php
class Ems_setting_model extends CI_Model {
    public $table = 'ems_settings';
    public $primary_key = 'setting_id';
    // ... methods get_settings(), insert(), update() ...
}