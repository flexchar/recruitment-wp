<?php

namespace App\Helpers;

use App\Methods\DatabaseApi;
/**
 * Trait helper to add $database property to the class
 */
trait UsesDatabase
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
        parent::__construct();

        $this->database = new DatabaseApi();
    }
}
