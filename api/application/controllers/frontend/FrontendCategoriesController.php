<?php
defined('BASEPATH') or exit('No direct script access allowed');



class FrontendCategoriesController extends MY_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/userguide3/general/urls.html
     */
    public $token = false;

    public function __construct()
    {
        parent::__construct();
        $this->token = AUTHORIZATION::verifyHeaderToken();
        $this->viewFolder = "categories";
        $this->alert = [
            "status" => FALSE,
            "title" => lang("error"),
            "message" => lang("unauthorized"),
            "data" => null
        ];
    }

    public function categories_get()
    {
        $data = $this->general_model->getRows("categories", ["isActive" => 1], $this->get(null, true));
        $recordsFiltered = $this->general_model->countFiltered("categories", ["isActive" => 1], (!empty($this->get(null, true)) ? $this->get(null, true) : []));
        $this->alert["status"] = TRUE;
        $this->alert["title"] = lang("successfully");
        $this->alert["message"] = lang("successfully");
        $this->alert["data"] = $data;
        $this->alert["recordsTotal"] = $this->general_model->rowCount("categories", ["isActive" => 1]);
        $this->alert["recordsFiltered"] = $recordsFiltered;

        $this->response($this->alert, MY_Controller::HTTP_OK);
    }
}
