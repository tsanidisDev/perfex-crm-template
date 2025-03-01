<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_100 extends App_module_migration
{
    public function up()
    {
        // Get CI instance to access the database
        $CI = &get_instance();
        
        // Customer pricing table
        $CI->db->query("CREATE TABLE IF NOT EXISTS `tblcustomer_pricing` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `customer_id` INT(11) NOT NULL,
            `item_id` INT(11) NOT NULL,
            `price` DECIMAL(15,2) NULL DEFAULT NULL,
            `discount_percent` DECIMAL(15,2) NULL DEFAULT '0.00',
            `discount_amount` DECIMAL(15,2) NULL DEFAULT '0.00',
            `is_active` TINYINT(1) NOT NULL DEFAULT '1',
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            INDEX `customer_item_idx` (`customer_id`, `item_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        // Continue with the rest of your queries, replacing $this->db with $CI->db
        $CI->db->query("CREATE TABLE IF NOT EXISTS `tblcustomer_group_pricing` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `customer_group_id` INT(11) NOT NULL,
            `item_id` INT(11) NOT NULL,
            `price` DECIMAL(15,2) NULL DEFAULT NULL,
            `discount_percent` DECIMAL(15,2) NULL DEFAULT '0.00',
            `discount_amount` DECIMAL(15,2) NULL DEFAULT '0.00',
            `is_active` TINYINT(1) NOT NULL DEFAULT '1',
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            INDEX `group_item_idx` (`customer_group_id`, `item_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $CI->db->query("CREATE TABLE IF NOT EXISTS `tblitem_groups` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(100) NOT NULL,
            `description` TEXT NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT '1',
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $CI->db->query("CREATE TABLE IF NOT EXISTS `tblitem_group_items` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `group_id` INT(11) NOT NULL,
            `item_id` INT(11) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE INDEX `group_item_unique` (`group_id`, `item_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $CI->db->query("CREATE TABLE IF NOT EXISTS `tblitem_group_pricing` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `group_id` INT(11) NOT NULL,
            `discount_percent` DECIMAL(15,2) NULL DEFAULT '0.00',
            `discount_amount` DECIMAL(15,2) NULL DEFAULT '0.00',
            `is_active` TINYINT(1) NOT NULL DEFAULT '1',
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`),
            INDEX `group_idx` (`group_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }
}