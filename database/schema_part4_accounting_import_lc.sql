-- SENA.ERP ACCOUNTING, IMPORT/EXPORT, LC TABLES

-- Accounting Module
CREATE TABLE IF NOT EXISTS `account_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `category` enum('Asset','Liability','Equity','Income','Expense') NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_types_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chart_of_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_code` varchar(50) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_type_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `opening_balance` decimal(15,2) DEFAULT 0.00,
  `current_balance` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_of_accounts_account_code_unique` (`account_code`),
  KEY `chart_of_accounts_account_type_id_foreign` (`account_type_id`),
  KEY `chart_of_accounts_parent_id_foreign` (`parent_id`),
  CONSTRAINT `chart_of_accounts_account_type_id_foreign` FOREIGN KEY (`account_type_id`) REFERENCES `account_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `voucher_no` varchar(50) NOT NULL,
  `voucher_type` enum('Journal','Payment','Receipt','Contra') NOT NULL,
  `date` date NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL COMMENT 'Sales Invoice, Purchase Invoice, etc',
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total_debit` decimal(15,2) DEFAULT 0.00,
  `total_credit` decimal(15,2) DEFAULT 0.00,
  `status` enum('Draft','Posted','Cancelled') DEFAULT 'Draft',
  `posted_by` bigint(20) unsigned DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vouchers_voucher_no_unique` (`voucher_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `voucher_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `voucher_id` bigint(20) unsigned NOT NULL,
  `account_id` bigint(20) unsigned NOT NULL,
  `debit` decimal(15,2) DEFAULT 0.00,
  `credit` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `voucher_details_voucher_id_foreign` (`voucher_id`),
  KEY `voucher_details_account_id_foreign` (`account_id`),
  CONSTRAINT `voucher_details_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `voucher_details_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ledgers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) unsigned NOT NULL,
  `voucher_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `debit` decimal(15,2) DEFAULT 0.00,
  `credit` decimal(15,2) DEFAULT 0.00,
  `balance` decimal(15,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ledgers_account_id_foreign` (`account_id`),
  KEY `ledgers_voucher_id_foreign` (`voucher_id`),
  KEY `ledgers_date_index` (`date`),
  CONSTRAINT `ledgers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ledgers_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `fiscal_years` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `is_closed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `bank_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) unsigned NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) NOT NULL,
  `account_holder` varchar(255) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'BDT',
  `opening_balance` decimal(15,2) DEFAULT 0.00,
  `current_balance` decimal(15,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_accounts_account_id_foreign` (`account_id`),
  CONSTRAINT `bank_accounts_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `bank_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bank_account_id` bigint(20) unsigned NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_type` enum('Deposit','Withdrawal','Transfer') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `voucher_id` bigint(20) unsigned DEFAULT NULL,
  `is_reconciled` tinyint(1) DEFAULT 0,
  `reconciled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_transactions_bank_account_id_foreign` (`bank_account_id`),
  KEY `bank_transactions_voucher_id_foreign` (`voucher_id`),
  CONSTRAINT `bank_transactions_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bank_transactions_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Import/Export Module
CREATE TABLE IF NOT EXISTS `import_indents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `indent_no` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_country` varchar(100) DEFAULT NULL,
  `incoterm` varchar(50) DEFAULT NULL COMMENT 'FOB, CIF, CFR, etc',
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'USD',
  `status` enum('Draft','Approved','LC Opened','In Transit','Received','Completed') DEFAULT 'Draft',
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `import_indents_indent_no_unique` (`indent_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `import_indent_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `indent_id` bigint(20) unsigned NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `specifications` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `import_indent_details_indent_id_foreign` (`indent_id`),
  KEY `import_indent_details_item_id_foreign` (`item_id`),
  CONSTRAINT `import_indent_details_indent_id_foreign` FOREIGN KEY (`indent_id`) REFERENCES `import_indents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `import_indent_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `shipments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shipment_no` varchar(50) NOT NULL,
  `import_indent_id` bigint(20) unsigned DEFAULT NULL,
  `bill_of_lading_no` varchar(100) DEFAULT NULL,
  `vessel_name` varchar(255) DEFAULT NULL,
  `port_of_loading` varchar(100) DEFAULT NULL,
  `port_of_discharge` varchar(100) DEFAULT NULL,
  `etd` date DEFAULT NULL COMMENT 'Estimated Time of Departure',
  `eta` date DEFAULT NULL COMMENT 'Estimated Time of Arrival',
  `actual_arrival_date` date DEFAULT NULL,
  `container_no` varchar(100) DEFAULT NULL,
  `cnf_agent` varchar(255) DEFAULT NULL,
  `clearing_status` enum('Pending','In Progress','Cleared','Delivered') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shipments_shipment_no_unique` (`shipment_no`),
  KEY `shipments_import_indent_id_foreign` (`import_indent_id`),
  CONSTRAINT `shipments_import_indent_id_foreign` FOREIGN KEY (`import_indent_id`) REFERENCES `import_indents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `import_costs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `import_indent_id` bigint(20) unsigned NOT NULL,
  `cost_type` varchar(100) NOT NULL COMMENT 'Freight, Insurance, Customs Duty, C&F Charges, etc',
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'BDT',
  `exchange_rate` decimal(10,4) DEFAULT 1.0000,
  `amount_bdt` decimal(15,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `import_costs_import_indent_id_foreign` (`import_indent_id`),
  CONSTRAINT `import_costs_import_indent_id_foreign` FOREIGN KEY (`import_indent_id`) REFERENCES `import_indents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `export_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_country` varchar(100) DEFAULT NULL,
  `buyer_address` text DEFAULT NULL,
  `incoterm` varchar(50) DEFAULT NULL,
  `payment_terms` text DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT 0.00,
  `currency` varchar(10) DEFAULT 'USD',
  `shipment_date` date DEFAULT NULL,
  `status` enum('Draft','Confirmed','Processing','Shipped','Completed') DEFAULT 'Draft',
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `export_orders_order_no_unique` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- LC (Letter of Credit) Module
CREATE TABLE IF NOT EXISTS `lc_applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lc_no` varchar(50) NOT NULL,
  `application_date` date NOT NULL,
  `import_indent_id` bigint(20) unsigned DEFAULT NULL,
  `vendor_id` bigint(20) unsigned NOT NULL,
  `bank_id` bigint(20) unsigned NOT NULL,
  `lc_type` enum('Sight','Usance','Deferred Payment','Red Clause') DEFAULT 'Sight',
  `lc_value` decimal(15,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'USD',
  `opening_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `last_shipment_date` date DEFAULT NULL,
  `port_of_loading` varchar(100) DEFAULT NULL,
  `port_of_discharge` varchar(100) DEFAULT NULL,
  `margin_percentage` decimal(5,2) DEFAULT 0.00,
  `margin_amount` decimal(15,2) DEFAULT 0.00,
  `insurance_amount` decimal(15,2) DEFAULT 0.00,
  `bank_charges` decimal(15,2) DEFAULT 0.00,
  `status` enum('Draft','Applied','Opened','Amended','Accepted','Matured','Retired','Cancelled') DEFAULT 'Draft',
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lc_applications_lc_no_unique` (`lc_no`),
  KEY `lc_applications_import_indent_id_foreign` (`import_indent_id`),
  KEY `lc_applications_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `lc_applications_import_indent_id_foreign` FOREIGN KEY (`import_indent_id`) REFERENCES `import_indents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lc_applications_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lc_amendments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lc_id` bigint(20) unsigned NOT NULL,
  `amendment_no` int(11) NOT NULL,
  `amendment_date` date NOT NULL,
  `amendment_type` varchar(100) DEFAULT NULL COMMENT 'Value Change, Date Extension, etc',
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `charges` decimal(15,2) DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lc_amendments_lc_id_foreign` (`lc_id`),
  CONSTRAINT `lc_amendments_lc_id_foreign` FOREIGN KEY (`lc_id`) REFERENCES `lc_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lc_payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lc_id` bigint(20) unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `payment_type` enum('Margin','Acceptance','Retirement','Charges') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `bank_account_id` bigint(20) unsigned DEFAULT NULL,
  `voucher_id` bigint(20) unsigned DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lc_payments_lc_id_foreign` (`lc_id`),
  KEY `lc_payments_bank_account_id_foreign` (`bank_account_id`),
  CONSTRAINT `lc_payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lc_payments_lc_id_foreign` FOREIGN KEY (`lc_id`) REFERENCES `lc_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System Settings
CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Sample Data
INSERT INTO `units` (`name`, `symbol`, `is_active`, `created_at`) VALUES
('Piece', 'PCS', 1, NOW()),
('Kilogram', 'KG', 1, NOW()),
('Liter', 'L', 1, NOW()),
('Meter', 'M', 1, NOW()),
('Box', 'BOX', 1, NOW()),
('Carton', 'CTN', 1, NOW());

INSERT INTO `leave_types` (`name`, `code`, `days_per_year`, `is_paid`, `is_carry_forward`, `created_at`) VALUES
('Annual Leave', 'AL', 20, 1, 1, NOW()),
('Sick Leave', 'SL', 14, 1, 0, NOW()),
('Casual Leave', 'CL', 10, 1, 0, NOW()),
('Maternity Leave', 'ML', 120, 1, 0, NOW()),
('Paternity Leave', 'PL', 10, 1, 0, NOW());

INSERT INTO `shifts` (`name`, `start_time`, `end_time`, `break_duration`, `grace_time`, `created_at`) VALUES
('Day Shift', '09:00:00', '18:00:00', 60, 15, NOW()),
('Night Shift', '21:00:00', '06:00:00', 60, 15, NOW()),
('Morning Shift', '07:00:00', '16:00:00', 60, 15, NOW());

INSERT INTO `account_types` (`name`, `code`, `category`, `created_at`) VALUES
('Current Assets', 'CA', 'Asset', NOW()),
('Fixed Assets', 'FA', 'Asset', NOW()),
('Current Liabilities', 'CL', 'Liability', NOW()),
('Long Term Liabilities', 'LTL', 'Liability', NOW()),
('Owner Equity', 'OE', 'Equity', NOW()),
('Revenue', 'REV', 'Income', NOW()),
('Operating Expense', 'OPEX', 'Expense', NOW()),
('Cost of Goods Sold', 'COGS', 'Expense', NOW());
