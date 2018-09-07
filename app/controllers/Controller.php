<?php

namespace sa\controllers;

class Controller
{
    protected $container;
    protected $db;
    protected $user;
    protected $data;

    public function __construct($container)
    {
        $this->container = $container;
        //Get variables required to load every page
        $this->data = new \stdClass;
    }

    public function __get($prop)
    {
        if ($this->container->{$prop}) {
            return $this->container->{$prop};
        }
    }
}