แน่นอนครับ นี่คือตัวอย่างโค้ด SQL สำหรับสร้างตารางที่เกี่ยวข้องกับการจัดการขนส่งสินค้าในระบบของคุณ โดยอิงจากการออกแบบตารางที่เราได้คุยกันก่อนหน้านี้ และอาจมีการเพิ่มเติมบางส่วนเพื่อความสมบูรณ์ยิ่งขึ้น:
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

คำอธิบายตาราง:
 * shipping_methods: ตารางหลักที่เก็บข้อมูลพื้นฐานของวิธีการจัดส่งต่างๆ เช่น EMS, Kerry, ลงทะเบียน
 * ems_settings, kerry_settings: ตารางที่เก็บการตั้งค่าเฉพาะสำหรับแต่ละวิธีการจัดส่ง (สามารถสร้างตารางเพิ่มเติมสำหรับวิธีการอื่นๆ ได้)
 * shipping_zones: ตารางสำหรับจัดการโซนพื้นที่การจัดส่ง (ถ้าต้องการกำหนดอัตราค่าส่งแตกต่างกันตามพื้นที่)
 * zone_locations: ตารางสำหรับเชื่อมโยงโซนการจัดส่งกับรหัสไปรษณีย์, จังหวัด, หรืออำเภอ
 * shipping_rates: ตารางสำหรับกำหนดอัตราค่าจัดส่งตามวิธีการจัดส่งและโซน (สามารถกำหนดตามช่วงน้ำหนักได้)
 * order_shipments: ตารางสำหรับบันทึกรายละเอียดการจัดส่งของแต่ละคำสั่งซื้อ
การใช้งาน:
 * เมื่อมีการเพิ่มวิธีการจัดส่งใหม่ในระบบ ให้เพิ่มข้อมูลในตาราง shipping_methods และสร้างตารางการตั้งค่าเฉพาะ (ถ้ามี)
 * กำหนดโซนการจัดส่งในตาราง shipping_zones และเชื่อมโยงพื้นที่ด้วยรหัสไปรษณีย์หรือเงื่อนไขอื่นๆ ในตาราง zone_locations
 * กำหนดอัตราค่าจัดส่งสำหรับแต่ละวิธีการจัดส่งและโซน (ตามช่วงน้ำหนักถ้าต้องการ) ในตาราง shipping_rates
 * เมื่อมีคำสั่งซื้อเกิดขึ้น ให้บันทึกรายละเอียดการจัดส่ง (วิธีการจัดส่งที่เลือก, ที่อยู่, ค่าจัดส่ง) ในตาราง order_shipments
หมายเหตุ:
 * โครงสร้างนี้เป็นเพียงตัวอย่างพื้นฐาน คุณสามารถปรับเปลี่ยนและเพิ่มคอลัมน์หรือตารางเพิ่มเติมได้ตามความต้องการของระบบของคุณ
 * การจัดการอัตราค่าจัดส่งมีความซับซ้อน คุณอาจต้องพิจารณาปัจจัยอื่นๆ เช่น ขนาดสินค้า, จำนวนสินค้า, หรือโปรโมชั่น
 * การเชื่อมต่อกับ API ของผู้ให้บริการขนส่ง (เช่น Kerry, EMS) จะต้องมีการจัดการในส่วนของ Backend Application (PHP CodeIgniter ในกรณีของคุณ)
หวังว่าโค้ด SQL นี้จะเป็นประโยชน์ในการพัฒนาระบบจัดการขนส่งสินค้าของคุณนะครับ! หากมีคำถามเพิ่มเติม ถามได้เลยครับ
