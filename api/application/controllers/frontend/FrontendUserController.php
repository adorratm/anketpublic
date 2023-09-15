<?php
defined('BASEPATH') or exit('No direct script access allowed');



class FrontendUserController extends MY_Controller
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
        if (!$this->token) {
            $this->alert["message"] = lang("unauthorized");
            $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function profile_get()
    {
        // return response if token is valid
        if ($this->token) :
            // Returns all the users data if the id not specified,
            // Otherwise, a single user will be returned.
            $con = !empty($this->token->id) ? array('id' => $this->token->id, 'isActive' => 1) : NULL;
            if (!empty($con)) :
                $user = $this->general_model->get("users", null, $con);
                // Check if the user data exists
                if (!empty($user)) :
                    unset($user->password);
                    $user->permissions = @$this->general_model->get("user_roles", null, ["id" => $user->role_id])->permissions;
                    // Set the response and exit
                    //OK (200) being the HTTP response code
                    $this->alert["status"] = TRUE;
                    $this->alert["title"] = lang("successfully");
                    $this->alert["message"] = lang("welcomeMessage") . " <b>" . $user->first_name . " " . $user->last_name . "</b>";
                    $this->alert["data"] = $user;
                    $this->response($this->alert, MY_Controller::HTTP_OK);
                endif;
                $this->alert["message"] = lang("notFound");
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            // Set the response and exit
            //NOT_FOUND (404) being the HTTP response code
            $this->alert["message"] = lang("notFound");
            $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
        endif;
        // return response if token is invalid
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }
}
