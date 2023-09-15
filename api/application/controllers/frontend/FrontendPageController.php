<?php
defined('BASEPATH') or exit('No direct script access allowed');



class FrontendPageController extends MY_Controller
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
        $this->alert = [
            "status" => FALSE,
            "title" => lang("error"),
            "message" => lang("unauthorized"),
            "data" => null
        ];
    }

    public function page_get($seo_url = null)
    {
        $page = $this->general_model->get("pages", null, ["lang" => $this->defaultLang, "isActive" => 1, "seo_url" => $seo_url]);
        if ($page) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $page;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }
}
