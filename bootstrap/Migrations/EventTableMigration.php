<?php

namespace WordCamp\Bootstrap\Migrations;

use WordCamp\Bootstrap\Contracts\MigrationContact;
use wpdb;

class EventTableMigration implements MigrationContact
{

    public function execute(): void
    {
        $this->createTable();
    }

    private function createTable(): void
    {
        global $wpdb;

        $tableName = $wpdb->prefix.'wep_events';

        $tableExists = $this->checkTableExist($wpdb, $tableName);
        if ($tableExists) {
            return;
        }

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $tableName (
        id bigint UNSIGNED NOT NULL AUTO_INCREMENT, 
        title varchar(50) NOT NULL,
        description text NULL DEFAULT NULL, 
        created_by timestamp NOT NULL,
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