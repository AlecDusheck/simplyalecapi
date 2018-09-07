<?php

namespace sa\controllers;

class Home extends Controller
{
    public function index($request, $response)
    {
        @$this->data->page->name = "Home";
        $this->container->view->getEnvironment()->addGlobal('data', $this->data);
        return $this->view->render($response, "index.twig");
    }
}