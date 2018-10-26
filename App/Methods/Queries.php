<?php

/**
 * This file hosts helpers for Database class
 * It is intentionally separated for better readability
 */

namespace App\Methods;

trait Queries
{

    /**
     * Returns base query with format-able select option
     *
     * @param string $select default to everything
     * 
     * @return string
     */
    private function getBaseQuery($select = null)
    {
        return ($select === null)
            ? 'SELECT * FROM ' . $this->table
            : 'SELECT ' . $select . ' FROM ' . $this->table;
    }

    /**
     * Returns all employees
     *
     * @param $args array
     *
     * @return array
     */
    public function all($args = array())
    {
        global $wpdb;
        $database = self::getInstance();

        $defaults = array(
            'number' => 20,
            'offset' => 0,
            'orderby' => 'id',
            'order' => 'ASC',
        );
        $args = wp_parse_args($args, $defaults);

        // Cache results
        $cache_key = $this->plugin_table . '::all';
        $items = wp_cache_get($cache_key, $this->plugin_table);

        if (false === $items) {
            $items = $wpdb->get_results(
                $wpdb->prepare(
                    $database->getBaseQuery() .
                        ' ORDER BY %s %s LIMIT %d, %d',
                    $args['orderby'],
                    $args['order'],
                    $args['offset'],
                    $args['number']
                )
            );

            wp_cache_set($cache_key, $items, $this->plugin_table);
        }
        return $items;
    }

    /**
     * Returns a count of employees
     *
     * @return integer
     */
    public function getCount()
    {
        global $wpdb;
        $database = self::getInstance();
        return (int)$wpdb->get_var($database->getBaseQuery('count(*)'));
    }

    /**
     * Returns a single employee
     *
     * @param int   $id
     *
     * @return array
     */
    public function get($id = 0)
    {
        global $wpdb;
        $database = self::getInstance();
        return $wpdb->get_row($wpdb->prepare($database->getBaseQuery() . ' WHERE id = %d', $id));
    }

    /**
     * Store new or update existing employee
     *
     * @param array $args
     * 
     * @return mixed returns Int on success, false on failure
     */
    public function store($args = array())
    {
        global $wpdb;
        $database = self::getInstance();

        $defaults = array(
            'id' => null,
            'name' => '',
            'occupation' => '',
            'email' => '',
        );

        $data = wp_parse_args($args, $defaults);

        // some basic validation
        if (empty($data['name'])) {
            return new WP_Error('no-name', __('No Name provided.', $this->text_domain));
        }
        if (empty($data['occupation'])) {
            return new WP_Error('no-occupation', __('No Occupation provided.', $this->text_domain));
        }
        if (empty($data['email'])) {
            return new WP_Error('no-email', __('No Email Address provided.', $this->text_domain));
        }
        // If ID exists, then update and vice versa
        if ($data['id'] === 0) {
            if ($wpdb->insert($database->table, $data)) {
                return $wpdb->insert_id;
            }
        } else {
            if ($wpdb->update($database->table, $data, ['id' => $data['id']])) {
                return $data['id'];
            }
        }
        return false;
    }

    /**
     * Delete employee
     *
     * @param int $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        global $wpdb;
        $current = $this->get($id);
        wp_delete_attachment($current->avatar);
        return $wpdb->delete($this->table, ['id' => $id]);
    }

}