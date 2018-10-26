<?php 

/**
 * BaseController
 */

namespace App;

class BaseController
{

    const TEXT_DOMAIN = 'ls_employees';
    const TABLE_NAME = 'ls_employees';

    /**
     * Plugin name
     *
     * @var string
     */
    protected $plugin;

    /**
     * Plugin URL
     *
     * @var string
     */
    protected $plugin_url;

    /**
     * Plugin Path
     *
     * @var string
     */
    protected $plugin_path;

    /**
     * Plugin Path
     *
     * @var string
     */
    protected $plugin_table;

    /**
     * Plugin Path
     *
     * @var string
     */
    protected $text_domain;

    /**
     * Plugin Path
     *
     * @var string
     */
    protected $page_url;

    /**
     * Construct class variables
     */
    public function __construct()
    {
        $this->plugin = plugin_basename(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 1));
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 1));
        $this->text_domain = SELF::TEXT_DOMAIN;
        $this->plugin_table = SELF::TABLE_NAME;
        $this->page_url = admin_url('admin.php?page=employees');
    }
}