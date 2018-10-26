<?php 

/**
 * Shortcode for displaying grid of employees on frontend
 * 
 */
namespace App\Modules;

use App\Methods\DatabaseApi;
use App\BaseController;

class Shortcode extends BaseController
{

    protected $employees;

    public function __construct()
    {
        parent::__construct();

        $db = new DatabaseApi();
        $this->employees = $db->all();
    }

    public function register()
    {
        add_shortcode('employees', array($this, 'getEmployeesGrid'));
        add_action('wp_enqueue_scripts', array($this, 'addStylesheet'));
    }

    public function getEmployeesGrid()
    {
        $template = '<div class="employees-grid">';
        foreach ($this->employees as $single) {
            $template .= '<div class="grid-element">';
            $template .= "<img src='" . wp_get_attachment_image_url($single->avatar, 'medium') . "' alt='{$single->name}'>";
            $template .= "<p class='name'>{$single->name}</p>";
            $template .= "<p class='occupation'>{$single->occupation}</p>";
            $template .= "<a href='mailto:{$single->email}'>{$single->email}</a>";
            $template .= '</div>';
        }
        $template .= '</div>';
        return $template;
    }

    public function addStylesheet()
    {
        wp_enqueue_style($this->plugin, $this->plugin_url . 'public/css/app.css');
    }

}