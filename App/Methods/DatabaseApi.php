<?php

/**
 * Class that speaks with our table in the database
 * Queries are defined in Queries trait
 */

namespace App\Methods;

use App\Employee;
use App\Methods\Queries;
use App\BaseController;

class DatabaseApi extends BaseController
{
    use Queries;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table;

    /**
     * Initialize class
     */
    public static function getInstance()
    {
        return new self();
    }

    /**
     * Construct class
     */
    public function __construct()
    {
        parent::__construct();

        $this->populateTableName();
    }

    /**
     * Register with WordPress
     *
     * @return void
     */
    public function register()
    {
        // Migrate database on plugin activation
        register_activation_hook($this->plugin_path, 'createTable');

        $this->ensureTableExists();
    }

    /**
     * Prepare database
     * 
     * @return void
     */
    private function populateTableName()
    {
        global $table_prefix;
        $this->table = $table_prefix . $this->plugin_table;
    }

    /**
     * Ensures that the table exists in the database
     *
     * @return void
     */
    private function ensureTableExists()
    {
        if (!$this->tableExists()) $this->createTable();
    }

    /**
     * Returns boolean if table exists
     *
     * @return boolean
     */
    private function tableExists()
    {
        global $wpdb;
        return (boolean)$wpdb->get_var("show tables like '{$this->table}'") == $this->table;
    }

    /**
     * Creates a new table in database for storing plugin's data
     */
    protected function createTable()
    {
        if ($this->tableExists()) return;

        $sql = ' CREATE TABLE `' . $this->table . '` (
            `id` integer unsigned auto_increment primary key,	
            `name` varchar(255),
            `email` varchar(255),
            `occupation` varchar(255),
            `avatar` varchar(255)
            ) ENGINE=InnoDB; ';

        // Migrate
        require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Drops plugin table
     */
    public static function dropTable()
    {
        global $wpdb;

        if (!self::getInstance()->tableExists()) return;
        // Drop
        $wpdb->query('DROP TABLE IF EXISTS ' . parent::TABLE_NAME);
    }
}