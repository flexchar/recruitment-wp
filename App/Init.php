<?php

/**
 * Registers services and integrates plugin into WordPress
 */

namespace App;

use App\Modules\Admin;
use App\Modules\FormData;
use App\Modules\Shortcode;
use App\Methods\DatabaseApi;


final class Init
{
    /**
     * Initialize the plugin
     */
    public function __construct()
    {
        $this->registerServices();
    }

    /**
     * Returns an array of class
     *
     * @return array
     */
    public static function getServices()
    {
        return [
            DatabaseApi::class,
            Admin::class,
            FormData::class,
            Shortcode::class,
        ];
    }

    /**
     * Loops and registers through classes 
     *
     * @return void
     */
    public static function registerServices()
    {
        foreach (self::getServices() as $class) {
            $service = self::initialize($class);
            $service->register();
        }
    }

    /**
     * Initializes provided class
     * 
     * @param class $class
     *
     * @return class instance
     */
    private static function initialize($class)
    {
        return new $class();
    }


}