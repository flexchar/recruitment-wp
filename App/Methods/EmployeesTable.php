<?php

/**
 * Extends built-in WP list table class
 * Ref: https://wordpress.org/plugins/custom-list-table-example/
 */

namespace App\Methods;

use App\Employee;
use App\Methods\DatabaseApi;
use App\BaseController;

class EmployeesTable extends \WP_List_Table
{
    /**
     * Database Instance
     *
     * @var Object 
     */
    protected $database;

    /**
     * Construct class
     */
    function __construct()
    {
        $this->database = new DatabaseApi();

        // Not the nicest possible way to do so
        $this->text_domain = BaseController::TEXT_DOMAIN;

        parent::__construct(array(
            'singular' => 'employee',
            'plural' => 'employees',
            'ajax' => false,
            'screen' => null,

        ));
    }

    function get_table_classes()
    {
        return array('widefat', 'fixed', 'striped', $this->_args['plural']);
    }

    /**
     * Message to show if no designation found
     *
     * @return void
     */
    function no_items()
    {
        _e('It\' s quite empty here', $this->text_domain);
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default($item, $column_name)
    {

        switch ($column_name) {
            case 'name':
                return $item->name;

            case 'email':
                return $item->email;

            case 'occupation':
                return $item->occupation;

            default:
                return isset($item->$column_name) ? $item->$column_name : '';
        }
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns()
    {
        return array(
            'cb' => '<input type="checkbox"/>',
            'name' => __('Name', $this->text_domain),
            'email' => __('Email Address', $this->text_domain),
            'occupation' => __('Occupation', $this->text_domain),

        );
    }

    /**
     * Render the designation name column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_name($item)
    {

        $actions = array();

        $actions['edit'] = sprintf(
            '<a href="%s" data-id="%d" title="%s">%s</a>',
            admin_url('admin.php?page=employees&action=edit&id=' . $item->id),
            $item->id,
            __('Edit this item', $this->text_domain),
            __('Edit', $this->text_domain)
        );

        $actions['delete'] = sprintf(
            '<a href="%s" class="delete" data-id="%d" title="%s">%s</a>',
            admin_url('admin.php?page=employees&action=delete&id=' . $item->id),
            $item->id,
            __('Delete this item', $this->text_domain),
            __('Delete', $this->text_domain)
        );

        return sprintf(
            '<a href="%1$s"><strong>%2$s</strong></a>%3$s',
            admin_url('admin.php?page=employees&action=view&id=' . $item->id),
            $item->name,
            $this->row_actions($actions)
        );
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    function get_sortable_columns()
    {
        return array(
            'name' => array('name', true),
        );
    }

    /**
     * Set the bulk actions
     *
     * @return array
     */
    function get_bulk_actions()
    {
        return [];
    }

    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="employee_id[] "value="%d"/>',
            $item->id
        );
    }

    /**
     * Prepare the class items
     *
     * @return void
     */
    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page = 8;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;
        $this->page_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : ' 2 ';

        $args = array(
            'offset' => $offset,
            'number' => $per_page,
        );

        if (isset($_REQUEST['orderby']) && isset($_REQUEST['order'])) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'];
        }

        $this->items = $this->database->all($args);

        $this->set_pagination_args(array(
            'total_items' => $this->database->getCount(),
            'per_page' => $per_page
        ));
    }
}