CREATE DATABASE IF NOT EXISTS `barberfiner` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `barberfiner`;








CREATE TABLE `admins` (
  `id` INT NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `profile_id` INT NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `profile_id`, `created_at`) VALUES
(1, 'admin', '$2y$10$FXrAL7NqvSCup/UiEaIyDeFdoFLUoPfWbf1nSwr44YLUqH3rs.2v2', 'مدیر سایت', 1, '2026-11-08 15:35:58');



CREATE TABLE `admin_permissions` (
  `id` INT NOT NULL,
  `permission_key` varchar(100) NOT NULL,
  `permission_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `admin_permissions` (`id`, `permission_key`, `permission_name`) VALUES
(1, 'dashboard', 'مشاهده داشبورد'),
(2, 'appointments', 'مدیریت نوبت‌ها'),
(3, 'services', 'مدیریت خدمات'),
(4, 'barbers', 'مدیریت آرایشگران');



CREATE TABLE `admin_profiles` (
  `id` INT NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `admin_profiles` (`id`, `name`) VALUES
(1, 'مدیر'),
(2, 'پذیرش');



CREATE TABLE `appointments` (
  `id` INT NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `service_id` INT NOT NULL,
  `barber_id` INT NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','partial','paid') DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `areas` (
  `id` INT NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `areas` (`id`, `area_name`, `is_active`) VALUES
(1, 'منطقه مرکزی', 1),
(2, 'منطقه شمالی', 1),
(3, 'منطقه جنوبی', 1);



CREATE TABLE `barbers` (
  `id` INT NOT NULL,
  `name` varchar(150) NOT NULL,
  `area_id` INT DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `barbers` (`id`, `name`, `area_id`, `description`, `image`, `is_active`, `created_at`) VALUES
(1, 'حمید رضا قربانی', NULL, 'آرایشگر متخصص کوتاهی مو', 'barber1.jpg', 1, '2026-11-08 15:28:13'),
(2, 'محمد احمدی', 2, 'متخصص اصلاح مو و ریش', 'barber2.jpg', 1, '2026-11-08 15:28:13'),
(3, 'امیر حسین نادری', NULL, 'آرایشگر و متخصص پاکسازی پوست', 'barber3.jpg', 1, '2026-11-08 15:28:13');



CREATE TABLE `barber_schedules` (
  `id` INT NOT NULL,
  `barber_id` INT NOT NULL,
  `day_of_week` TINYINT NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `discounts` (
  `id` INT NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percent` INT NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `usage_limit` INT DEFAULT 1,
  `used_count` INT DEFAULT 0,
  `is_active` TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `payments` (
  `id` INT NOT NULL,
  `appointment_id` INT NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_type` enum('deposit','full') NOT NULL,
  `payment_status` enum('pending','success','failed') DEFAULT 'pending',
  `transaction_code` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `payment_settings` (
  `id` INT NOT NULL,
  `method_name` varchar(100) NOT NULL,
  `account_holder` varchar(100) DEFAULT NULL,
  `card_number` varchar(30) DEFAULT NULL,
  `iban` varchar(40) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `payment_settings` (`id`, `method_name`, `account_holder`, `card_number`, `iban`, `description`, `is_active`) VALUES
(1, 'واریز بانکی', 'نام صاحب حساب', '0000-0000-0000-0000', 'IR000000000000000000000000', 'پس از واریز، رسید پرداخت خود را نگه دارید.', 1),
(2, 'rdfbdb', 'dfbbdbdf', '35345353434535', '33333333333333333333', '353rgdgrdzhbdfhd', 1);



CREATE TABLE `profile_permissions` (
  `id` INT NOT NULL,
  `profile_id` INT NOT NULL,
  `permission_id` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `profile_permissions` (`id`, `profile_id`, `permission_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(8, 2, 1),
(9, 2, 2);



CREATE TABLE `services` (
  `id` INT NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` INT DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `duration_minutes` INT NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `services` (`id`, `name`, `category_id`, `description`, `price`, `duration_minutes`, `image`, `is_active`, `created_at`) VALUES
(1, 'اصلاح مو', 1, 'اصلاح و کوتاهی مو', 250000.00, 30, 'haircut.jpg', 1, '2026-11-08 15:21:59'),
(2, 'اصلاح ریش', 2, 'اصلاح و مرتب کردن ریش', 150000.00, 20, 'beard.jpg', 1, '2026-11-08 15:21:59'),
(3, 'پاکسازی پوست', 3, 'پاکسازی و مراقبت از پوست', 350000.00, 45, 'facial.jpg', 1, '2026-11-08 15:21:59'),
(4, 'اصلاح مو و ریش', 4, 'اصلاح کامل مو و ریش', 350000.00, 45, 'fullstyle.jpg', 1, '2026-11-08 15:21:59');



CREATE TABLE `service_attributes` (
  `id` INT NOT NULL,
  `name` varchar(150) NOT NULL,
  `public_name` varchar(150) NOT NULL,
  `display_type` varchar(30) NOT NULL DEFAULT 'select',
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `service_attributes` (`id`, `name`, `public_name`, `display_type`, `is_active`, `created_at`) VALUES
(1, 'hair_style', 'مدل کوتاهی مو', 'select', 1, '2026-11-08 19:51:42'),
(2, 'hair_length', 'طول مو', 'select', 1, '2026-11-08 19:51:42'),
(3, 'beard_style', 'مدل اصلاح ریش', 'select', 1, '2026-11-08 19:51:42');



CREATE TABLE `service_attribute_values` (
  `id` INT NOT NULL,
  `attribute_id` INT NOT NULL,
  `value` varchar(150) NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `service_attribute_values` (`id`, `attribute_id`, `value`, `is_active`, `created_at`) VALUES
(1, 1, 'کلاسیک', 1, '2026-11-08 19:54:33'),
(2, 1, 'فید', 1, '2026-11-08 19:54:33'),
(3, 2, 'کوتاه', 1, '2026-11-08 19:54:33'),
(4, 2, 'متوسط', 1, '2026-11-08 19:54:33'),
(5, 2, 'بلند', 1, '2026-11-08 19:54:33'),
(6, 3, 'ساده', 1, '2026-11-08 19:54:33'),
(7, 3, 'خط‌گیری', 1, '2026-11-08 19:54:33'),
(8, 3, 'کامل', 1, '2026-11-08 19:54:33');



CREATE TABLE `service_categories` (
  `id` INT NOT NULL,
  `parent_id` INT DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `service_categories` (`id`, `parent_id`, `name`, `description`, `is_active`, `created_at`) VALUES
(1, NULL, 'خدمات مو', 'انواع کوتاهی و اصلاح مو', 1, '2026-11-08 19:43:48'),
(2, NULL, 'خدمات ریش', 'اصلاح و مرتب کردن ریش', 1, '2026-11-08 19:43:48'),
(3, NULL, 'خدمات پوست', 'پاکسازی و مراقبت از پوست', 1, '2026-11-08 19:43:48'),
(4, NULL, 'خدمات ترکیبی', 'خدمات شامل اصلاح مو و ریش', 1, '2026-11-08 19:46:16'),
(5, 1, 'کوتاهی کلاسیک', 'انواع کوتاهی کلاسیک مو', 1, '2026-11-08 19:50:28'),
(6, 1, 'فید', 'انواع مدل موی فید', 1, '2026-11-08 19:50:28'),
(7, 2, 'اصلاح ریش کلاسیک', 'اصلاح و مرتب کردن ریش', 1, '2026-11-08 19:50:28');



CREATE TABLE `service_features` (
  `id` INT NOT NULL,
  `service_id` INT NOT NULL,
  `feature_name` varchar(150) NOT NULL,
  `feature_value` varchar(255) NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `service_features` (`id`, `service_id`, `feature_name`, `feature_value`, `is_active`, `created_at`) VALUES
(1, 1, 'نوع خدمت', 'اصلاح و کوتاهی مو', 1, '2026-11-08 19:53:04'),
(2, 1, 'مناسب برای', 'آقایان', 1, '2026-11-08 19:53:04'),
(3, 2, 'نوع خدمت', 'اصلاح و مرتب‌سازی ریش', 1, '2026-11-08 19:53:04'),
(4, 2, 'مناسب برای', 'آقایان', 1, '2026-11-08 19:53:04'),
(5, 3, 'نوع خدمت', 'پاکسازی و مراقبت پوست', 1, '2026-11-08 19:53:04'),
(6, 3, 'مناسب برای', 'انواع پوست', 1, '2026-11-08 19:53:04'),
(7, 4, 'نوع خدمت', 'اصلاح کامل مو و ریش', 1, '2026-11-08 19:53:04'),
(8, 4, 'مناسب برای', 'اصلاح هم‌زمان مو و ریش', 1, '2026-11-08 19:53:04');



CREATE TABLE `site_settings` (
  `id` INT NOT NULL,
  `maintenance_mode` TINYINT(1) NOT NULL DEFAULT 0,
  `maintenance_message` varchar(255) NOT NULL DEFAULT 'سایت در حال به‌روزرسانی است.',
  `site_name` varchar(150) NOT NULL DEFAULT 'آرایشگاه آنلاین',
  `site_phone` varchar(20) DEFAULT NULL,
  `site_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `site_settings` (`id`, `maintenance_mode`, `maintenance_message`, `site_name`, `site_phone`, `site_address`) VALUES
(1, 1, 'سایت در حال به‌روزرسانی است', 'آرایشگاه آنلاین', '09120000000', 'آدرس آرایشگاه');



CREATE TABLE `suppliers` (
  `id` INT NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `suppliers` (`id`, `name`, `phone`, `address`, `description`, `created_at`) VALUES
(1, 'Ali Ghorbani', '0898908908', 'gjjtyjtdyjtyj', 'jdjdyjdytjtdj', '2026-11-13 17:36:30');


ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_admins_profile` (`profile_id`);

ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `admin_profiles`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `barber_id` (`barber_id`);

ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `barbers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_barbers_area_id` (`area_id`);

ALTER TABLE `barber_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barber_id` (`barber_id`);

ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`);

ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `profile_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profile_id` (`profile_id`),
  ADD KEY `permission_id` (`permission_id`);

ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_services_category_id` (`category_id`);

ALTER TABLE `service_attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `service_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attribute_value` (`attribute_id`,`value`);

ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category_parent` (`parent_id`);

ALTER TABLE `service_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_service_id` (`service_id`);

ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `admins`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `admin_permissions`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `admin_profiles`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `appointments`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `areas`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `barbers`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `barber_schedules`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `discounts`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `payments`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `payment_settings`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `profile_permissions`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `services`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `service_attributes`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `service_attribute_values`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `service_categories`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `service_features`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `site_settings`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `suppliers`
  MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `admins`
  ADD CONSTRAINT `fk_admins_profile` FOREIGN KEY (`profile_id`) REFERENCES `admin_profiles` (`id`) ON UPDATE CASCADE;

ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`barber_id`) REFERENCES `barbers` (`id`);

ALTER TABLE `barbers`
  ADD CONSTRAINT `fk_barbers_area` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `barber_schedules`
  ADD CONSTRAINT `barber_schedules_ibfk_1` FOREIGN KEY (`barber_id`) REFERENCES `barbers` (`id`);

ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);

ALTER TABLE `profile_permissions`
  ADD CONSTRAINT `profile_permissions_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `admin_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `profile_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `admin_permissions` (`id`) ON DELETE CASCADE;

ALTER TABLE `services`
  ADD CONSTRAINT `fk_services_category` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `service_attribute_values`
  ADD CONSTRAINT `fk_attribute_values` FOREIGN KEY (`attribute_id`) REFERENCES `service_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `service_categories`
  ADD CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `service_features`
  ADD CONSTRAINT `fk_service_features` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

