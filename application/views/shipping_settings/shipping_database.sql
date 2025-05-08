
-- ตารางหลักสำหรับวิธีการจัดส่ง
CREATE TABLE `shipping_methods` (
  `method_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `method_name` VARCHAR(255) UNIQUE NOT NULL COMMENT 'ชื่อวิธีการจัดส่ง (ใช้ภายในระบบ)',
  `display_name` VARCHAR(255) NOT NULL COMMENT 'ชื่อที่แสดงให้ผู้ใช้เห็น',
  `description` TEXT NULL COMMENT 'คำอธิบายเพิ่มเติม',
  `is_enabled` BOOLEAN NOT NULL DEFAULT 0 COMMENT 'สถานะเปิด/ปิดใช้งาน',
  `settings_table` VARCHAR(255) NULL COMMENT 'ชื่อตารางเก็บการตั้งค่าเฉพาะ',
  `order` INT(11) NOT NULL DEFAULT 0 COMMENT 'ลำดับการแสดงผล',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตารางสำหรับการตั้งค่า EMS
CREATE TABLE `ems_settings` (
  `setting_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `method_id` INT(11) UNSIGNED NOT NULL COMMENT 'อ้างอิง shipping_methods',
  `api_key` VARCHAR(255) NULL COMMENT 'API Key (ถ้ามี)',
  `account_id` VARCHAR(255) NULL COMMENT 'Account ID (ถ้ามี)',
  `base_rate` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'อัตราค่าจัดส่งพื้นฐาน',
  `additional_rate_kg` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'อัตราเพิ่มเติมต่อ KG',
  `free_over_amount` DECIMAL(10, 2) NULL COMMENT 'ยอดสั่งซื้อขั้นต่ำส่งฟรี',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`method_id`) REFERENCES `shipping_methods` (`method_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตารางสำหรับการตั้งค่า Kerry
CREATE TABLE `kerry_settings` (
  `setting_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `method_id` INT(11) UNSIGNED NOT NULL COMMENT 'อ้างอิง shipping_methods',
  `api_key` VARCHAR(255) NULL COMMENT 'API Key (ถ้ามี)',
  `account_number` VARCHAR(255) NULL COMMENT 'หมายเลขบัญชี (ถ้ามี)',
  `rate_table_id` INT(11) NULL COMMENT 'ID ตารางอัตราค่าจัดส่ง (ถ้ามี)',
  `default_rate` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'อัตราค่าจัดส่งเริ่มต้น',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`method_id`) REFERENCES `shipping_methods` (`method_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตารางสำหรับจัดการโซนการจัดส่ง (ถ้าต้องการแบ่งพื้นที่คิดค่าส่งต่างกัน)
CREATE TABLE `shipping_zones` (
  `zone_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `zone_name` VARCHAR(255) NOT NULL COMMENT 'ชื่อโซนการจัดส่ง (เช่น กรุงเทพฯ, ปริมณฑล, ต่างจังหวัด)',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตารางสำหรับเชื่อมโยงโซนการจัดส่งกับรหัสไปรษณีย์ (หรือเงื่อนไขอื่นๆ)
CREATE TABLE `zone_locations` (
  `location_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `zone_id` INT(11) UNSIGNED NOT NULL COMMENT 'อ้างอิง shipping_zones',
  `location_code` VARCHAR(255) NOT NULL COMMENT 'รหัสไปรษณีย์ หรือเงื่อนไขอื่นๆ',
  `location_type` ENUM('postcode', 'province', 'amphur') NOT NULL DEFAULT 'postcode' COMMENT 'ประเภทของรหัส (postcode, province, amphur)',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`zone_id`) REFERENCES `shipping_zones` (`zone_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตารางสำหรับกำหนดอัตราค่าจัดส่งตามโซนและวิธีการจัดส่ง
CREATE TABLE `shipping_rates` (
  `rate_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `method_id` INT(11) UNSIGNED NOT NULL COMMENT 'อ้างอิง shipping_methods',
  `zone_id` INT(11) UNSIGNED NOT NULL COMMENT 'อ้างอิง shipping_zones',
  `min_weight_kg` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'น้ำหนักต่ำสุด (KG)',
  `max_weight_kg` DECIMAL(10, 2) NULL COMMENT 'น้ำหนักสูงสุด (KG)',
  `rate` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'อัตราค่าจัดส่ง',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`method_id`) REFERENCES `shipping_methods` (`method_id`) ON DELETE CASCADE,
  FOREIGN KEY (`zone_id`) REFERENCES `shipping_zones` (`zone_id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_rate` (`method_id`, `zone_id`, `min_weight_kg`, `max_weight_kg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ตารางสำหรับบันทึกการจัดส่งของแต่ละคำสั่งซื้อ
CREATE TABLE `order_shipments` (
  `shipment_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT(11) UNSIGNED NOT NULL COMMENT 'อ้างอิง orders (สมมติว่ามีตาราง orders)',
  `method_id` INT(11) UNSIGNED NOT NULL COMMENT 'วิธีการจัดส่งที่เลือก',
  `tracking_number` VARCHAR(255) NULL COMMENT 'หมายเลขติดตามพัสดุ',
  `shipping_cost` DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'ค่าจัดส่ง',
  `shipping_address` TEXT NOT NULL COMMENT 'ที่อยู่จัดส่ง',
  `shipping_status` VARCHAR(255) NULL COMMENT 'สถานะการจัดส่ง',
  `shipped_at` TIMESTAMP NULL COMMENT 'วันที่จัดส่ง',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`method_id`) REFERENCES `shipping_methods` (`method_id`) ON DELETE RESTRICT
  -- FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE (ถ้ามีตาราง orders)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




/////
CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY,
    order_number VARCHAR(128),
    order_data  VARCHAR(4096),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS invoices (
    invoice_id INT PRIMARY KEY,
        order_id INT UNIQUE,
    INDEX ord_id (order_id),
        FOREIGN KEY (order_id)
        REFERENCES orders(order_id)
                ON DELETE CASCADE,    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=INNODB;