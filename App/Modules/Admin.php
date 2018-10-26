<?php

namespace App\Modules;

use App\Employee;
use App\Methods\EmployeesTable;
use App\Methods\DatabaseApi;
use App\BaseController;
use App\Helpers\UsesDatabase;

/**
 * Registers WP admin menu option and layouts
 */

class Admin extends BaseController
{
    use UsesDatabase;

    /**
     * Integrates menu into WordPress
     *
     * @return void
     */
    public function register()
    {
        add_action('admin_menu', array($this, 'addAdminMenu'));
    }

    /**
     * Register menu
     */
    public function addAdminMenu()
    {
        add_menu_page(
            __('Employees', 'employees'),
            __('Employees', 'employees'),
            'manage_options',
            'employees',
            array($this, 'handleViews'),
            'dashicons-businessman',
            25
        );
    }

    /**
     * Returns the appropriate behavior and view
     *
     * @return void
     */
    public function handleViews()
    {
        // If no action is set, then it's plugin's root, which is the list view
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        // Fetch IF for single resources
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $template = $this->plugin_path . 'views/';

        switch ($action) {
            case 'edit':
                $entry = $this->database->get($id);
                $template .= 'edit.php';
                break;

            case 'new':
                $template .= 'new.php';
                break;

            default:
                $list_table = new EmployeesTable();
                $list_table->prepare_items();
                $template .= 'list.php';
                break;
        }

        if (file_exists($template)) {
            include $template;
        }
    }


}
