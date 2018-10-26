<?php

/**
 * Handle the form submissions from Admin modules
 */

namespace App\Modules;

use App\Methods\DatabaseApi;
use App\BaseController;

class FormData extends BaseController
{
    /**
     * Returns url to redirect after form processing done
     *
     * @var string
     */
    protected $redirectTo;

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
        parent::__construct();

        $this->database = new DatabaseApi();
    }

    /**
     * Integrates form handler into WordPress
     *
     * @return void
     */
    public function register()
    {
        add_action('admin_init', array($this, 'handleForm'));
        add_action('admin_init', array($this, 'handleDelete'));
    }

    /**
     * Parse the form submission
     * Process both: new and update methods
     *
     * @return void
     */
    public function handleForm()
    {
        if (!isset($_POST['submit_employees'])) return;

        if (!wp_verify_nonce($_POST['_wpnonce'])) die(__('Your session has expired. Please try again', $this->text_domain));

        if (!current_user_can('read')) wp_die(__('Permission Denied!', $this->text_domain));

        $errors = array();

        // Sanitize
        $field_id = isset($_POST['field_id']) ? intval($_POST['field_id']) : null;
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $occupation = isset($_POST['occupation']) ? sanitize_text_field($_POST['occupation']) : '';
        $email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
        $avatar = isset($_FILES['avatar']) && $_FILES['avatar']['size'] ? $_FILES['avatar'] : false;

        // Validate
        if (!$name) $errors[] = __('Name is required', $this->text_domain);

        if (!$occupation) $errors[] = __('Occupation is required', $this->text_domain);

        if (!$email) $errors[] = __('Email Address is required', $this->text_domain);

        if (!$avatar && !$field_id) $errors[] = __('Avatar is required', $this->text_domain);

        // Won't validate much here, since it's behind admin wall anyway
        if ($avatar && !in_array($avatar['type'], array('image/jpg', 'image/jpeg', 'image/png')))
            $errors[] = __('Avatar must be image', $this->text_domain);

        // Upload avatar
        if ($avatar) {
            $avatar = media_handle_upload('avatar', 0);
            if (is_wp_error($avatar)) $errors[] = __('Error uploading avatar', $this->text_domain);

            // Delete old avatar
            if ($current = $this->database->get($field_id)) {
                if ($current->avatar) {
                    wp_delete_attachment($current->avatar);
                }
            }
        }

        // If exists, return first error
        if ($errors) {
            $first_error = reset($errors);
            $this->redirectTo = add_query_arg(array('error' => $first_error), $this->page_url);
            // $this->redirect();
        }

        // Set fields for DB update
        $fields = [
            'id' => $field_id,
            'name' => $name,
            'occupation' => $occupation,
            'email' => $email,
        ];
        // Only update avatart field, if it's provided
        if ($avatar) $fields['avatar'] = $avatar;

        // Update record
        $record = $this->database->store($fields);

        if (is_wp_error($record)) {
            $this->redirectTo = add_query_arg(array('message' => 'error'), $this->page_url);
        } else {
            $this->redirectTo = add_query_arg(array('message' => 'success'), $this->page_url);
        }

        $this->redirect();
        exit;
    }

    /**
     * Deletes entry from database
     *
     * @return void
     */
    public function handleDelete()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'delete') {

            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id !== 0) {

                $this->database->delete($id);

                $this->redirectTo = add_query_arg(
                    array('message' => 'success'),
                    $this->page_url
                );

                $this->redirect();
            }
        }
    }

    public function redirect()
    {
        return wp_safe_redirect($this->redirectTo);
    }
}
