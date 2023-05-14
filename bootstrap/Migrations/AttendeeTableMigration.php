<?php

namespace WordCamp\Bootstrap\Migrations;

use WordCamp\Bootstrap\Contracts\MigrationContact;
use wpdb;

class AttendeeTableMigration implements MigrationContact
{

    public function execute(): void
    {
        $this->createTable();
    }

    private function createTable(): void
    {
        global $wpdb;

        $tableName = $wpdb->prefix.'wep_attendees';

        $tableExists = $this->checkTableExist($wpdb, $tableName);
        if ($tableExists) {
            return;
        }

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $tableName (
        id bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        attendee_uid varchar(30) NOT NULL,
        ticket_type varchar(250) NOT NULL,
        attendee_type varchar(50) NOT NULL,
        tshirt_size varchar(100) NOT NULL,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone_number varchar(20) NOT NULL,
        country varchar(50) NOT NULL, 
        purchase_at timestamp  NULL DEFAULT NULL,
        last_modified_at timestamp NULL DEFAULT NULL,
        detals text NULL DEFAULT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function checkTableExist(wpdb $wpdb, string $tableName): bool
    {
        return $wpdb->get_var("SHOW TABLES LIKE '$tableName'") === $tableName;
    }

}