<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Abraham\TwitterOAuth\TwitterOAuth;


class AuthController extends MY_Controller
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
        if ($this->uri->segment(2) != "login" && $this->uri->segment(2) != "google-login" && $this->uri->segment(2) != "facebook-login" && $this->uri->segment(2) != "twitter-login") :
            $this->token = AUTHORIZATION::verifyHeaderToken();
        endif;
        $this->alert = [
            "status" => FALSE,
            "title" => lang("error"),
            "message" => lang("unauthorized"),
            "data" => null
        ];
    }

    /**
     * Default Login
     */
    public function login_post()
    {
        // Validate the post data
        $data = $this->post(null, true);
        if ($data["loginType"] == "google") :
            $this->googleLogin_post();
        elseif ($data["loginType"] == "facebook") :
            $this->facebookLogin_post();
        elseif ($data["loginType"] == "twitter") :
            $this->twitterLogin_post();
        else :
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules("email", lang("email"), "required|trim|valid_email|clean");
            $this->form_validation->set_rules("password", lang("password"), "required|trim|min_length[6]|clean");
            $this->form_validation->set_message(["required"  => lang("required"), "valid_email" => lang("valid_email"), "min_length" => lang("min_length"),]);
            $this->form_validation->set_error_delimiters('', ',');
            // Default response
            $this->alert["message"] = lang("errorOnLogin");
            // Validate form input
            if ($this->form_validation->run()) :
                // Get the post data
                $email = @clean($this->post('email', true));
                $password = @clean($this->post('password', true));
                // Check if user exists in database
                $user = $this->general_model->get("users", null, [
                    'email' => $email,
                    'isActive' => 1,
                    'role_id!=' => 3
                ]);
                if (!$user) :
                    $this->alert["message"] = lang("notFound");
                    $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
                endif;
				if(!password_verify($password, $user->password)):
					$this->alert["message"] = lang("yourPasswordIsWrong");
                    $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
				endif;
                $user->timestamp = time();
                $user->permissions = @$this->general_model->get("user_roles", null, ["id" => $user->role_id])->permissions;
                $user->token = AUTHORIZATION::generateToken((array)$user);
                // Set the response and exit
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("welcomeMessage") . " <b>" . $user->first_name . " " . $user->last_name . "</b>";
                $this->alert["data"] = $user;
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;

            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            if (validation_errors()) :
                $this->alert["message"] =  str_replace("<br />\n", "", nl2br(implode(",", array_filter(explode(",", validation_errors()), 'clean'))));
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }

    /**
     * Google Login
     */
    public function googleLogin_post()
    {
        $data = $this->post(null, true);
        unset($data["loginType"]);
        // Default response
        $this->alert["message"] = lang("errorOnLogin");
        if (isset($data["access_token"])) :
            $access_token = $data["access_token"];
            $response = guzzle_request("https://www.googleapis.com/oauth2/v3/userinfo?access_token=" . $access_token);
            if (!empty($response->email)) :
                // Check if user exists in database
                $user = $this->general_model->get("users", null, [
                    'email' => $response->email,
                    'isActive' => 1,
                    'role_id!=' => 3
                ]);
                if (!$user) :
                    /**
                     * If user doesn't exist
                     */
                    $this->alert["message"] = lang("notFound");
                    $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
                endif;
                /**
                 * If user exists, update user data from google
                 */
                $this->general_model->update("users", ["id" => $user->id], [
                    "first_name" => $response->given_name,
                    "last_name" => $response->family_name,
                    "email" => $response->email,
                    "oauth_provider" => "google",
                    "oauth_uid" => $response->sub,
                ]);
                // Get the updated user data
                $user = $this->general_model->get("users", null, [
                    'email' => $response->email,
                    'isActive' => 1,
                    'role_id!=' => 3
                ]);
                $user->timestamp = time();
                $user->permissions = @$this->general_model->get("user_roles", null, ["id" => $user->role_id])->permissions;
                $user->token = AUTHORIZATION::generateToken((array)$user);
                // Set the response and exit
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("welcomeMessage") . " <b>" . $user->first_name . " " . $user->last_name . "</b>";
                $this->alert["data"] = $user;
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;


        // Set the response and exit
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }

    /**
     * Facebook Login
     */
    public function facebookLogin_post()
    {
        $data = $this->post(null, true);
        unset($data["loginType"]);
        // Default response
        $this->alert["message"] = lang("errorOnLogin");
        if (isset($data["access_token"])) :
            $access_token = $data["access_token"];
            $response = guzzle_request("https://graph.facebook.com/v6.0/me?fields=id,name,first_name,last_name,email,picture{url}&access_token=" . $access_token);
            if (!empty($response->email)) :
                // Check if user exists in database
                $user = $this->general_model->get("users", null, [
                    'email' => $response->email,
                    'isActive' => 1,
                    'role_id!=' => 3
                ]);
                if (!$user) :
                    /**
                     * If user doesn't exist
                     */
                    $this->alert["message"] = lang("notFound");
                    $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
                endif;
                /**
                 * If user exists, update user data from facebook
                 */
                $this->general_model->update("users", ["id" => $user->id], [
                    "first_name" => $response->first_name,
                    "last_name" => $response->last_name,
                    "email" => $response->email,
                    "oauth_provider" => "facebook",
                    "oauth_uid" => $response->id,
                ]);

                // Get the updated user data
                $user = $this->general_model->get("users", null, [
                    'email' => $response->email,
                    'isActive' => 1,
                    'role_id!=' => 3
                ]);

                $user->timestamp = time();
                $user->permissions = @$this->general_model->get("user_roles", null, ["id" => $user->role_id])->permissions;
                $user->token = AUTHORIZATION::generateToken((array)$user);
                // Set the response and exit
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("welcomeMessage") . " <b>" . $user->first_name . " " . $user->last_name . "</b>";
                $this->alert["data"] = $user;
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;


        // Set the response and exit
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }

    /**
     * Twitter Login
     */
    public function twitterLogin_get()
    {
        try {
            $data = $this->get(null, true);
            // Default response
            $this->alert['status'] = FALSE;
            $this->alert["title"] = lang("error");
            $this->alert["message"] = lang("errorOnLogin");
            $this->alert["data"] = [];

            $connection = new TwitterOAuth($this->config->item("TWITTER_CONSUMER_ID"), $this->config->item("TWITTER_CONSUMER_SECRET"), $this->config->item("TWITTER_ACCESS_TOKEN"), $this->config->item("TWITTER_ACCESS_TOKEN_SECRET"));

            $temporary_credentials = $connection->oauth('oauth/request_token', array("oauth_callback" => $data["redirect_uri"]));
            $url = $connection->url("oauth/authorize", array("oauth_token" => $temporary_credentials['oauth_token'], "oauth_token_secret" => $temporary_credentials['oauth_token_secret'], "oauth_callback" => $data["redirect_uri"]));

            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("loginSuccessfully");
            $this->alert["data"] = ["url" => $url];
            $this->response($this->alert, MY_Controller::HTTP_OK);
        } catch (Exception $e) {
            //echo $e->getMessage();
            // Set the response and exit
            // Default response
            $this->alert['status'] = FALSE;
            $this->alert["title"] = lang("error");
            $this->alert["message"] = lang("errorOnLogin");
            $this->alert["data"] = [];
            $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Twitter Login
     */
    public function twitterLogin_post()
    {
        try {
            $data = $this->post(null, true);
            unset($data["loginType"]);
            // Default response
            $this->alert['status'] = FALSE;
            $this->alert["title"] = lang("error");
            $this->alert["message"] = lang("errorOnLogin");
            $this->alert["data"] = [];
            if (!empty($data["oauth_token"]) && !empty($data["oauth_verifier"])) :
                $connection = new TwitterOAuth($this->config->item("TWITTER_CONSUMER_ID"), $this->config->item("TWITTER_CONSUMER_SECRET"), $data["oauth_token"], $this->config->item("TWITTER_ACCESS_TOKEN_SECRET"));
                $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $data["oauth_verifier"]]);
                if (!empty($access_token)) :
                    $connection = new TwitterOAuth($this->config->item("TWITTER_CONSUMER_ID"), $this->config->item("TWITTER_CONSUMER_SECRET"), $access_token["oauth_token"], $access_token["oauth_token_secret"]);
                    $response = $connection->get("account/verify_credentials", [
                        "include_email" => 'true',
                        "include_entities" => 'true',
                        "skip_status" => 'true'
                    ]);

                    if (!empty($response->email)) :
                        $nameArray = explode(" ", $response->name);
                        $lastName = array_pop($nameArray);
                        $firstName = implode(" ", $nameArray);
                        // Check if user exists in database
                        $user = $this->general_model->get("users", null, [
                            'email' => $response->email,
                            'isActive' => 1,
                            'role_id!=' => 3
                        ]);
                        if (!$user) :
                            /**
                             * If user doesn't exist
                             */
                            $this->alert["message"] = lang("notFound");
                            $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
                        endif;
                        /**
                         * If user exists, update user data from twitter
                         */
                        $this->general_model->update("users", ["id" => $user->id], [
                            "first_name" => $firstName,
                            "last_name" => $lastName,
                            "email" => $response->email,
                            "oauth_provider" => "twitter",
                            "oauth_uid" => $response->id
                        ]);

                        // Get the updated user data
                        $user = $this->general_model->get("users", null, [
                            'email' => $response->email,
                            'isActive' => 1,
                            'role_id!=' => 3
                        ]);

                        $user->timestamp = time();
                        $user->permissions = @$this->general_model->get("user_roles", null, ["id" => $user->role_id])->permissions;
                        $user->token = AUTHORIZATION::generateToken((array)$user);
                        // Set the response and exit
                        $this->alert["status"] = TRUE;
                        $this->alert["title"] = lang("successfully");
                        $this->alert["message"] = lang("welcomeMessage") . " <b>" . $user->first_name . " " . $user->last_name . "</b>";
                        $this->alert["data"] = $user;
                        $this->response($this->alert, MY_Controller::HTTP_OK);
                    endif;
                endif;
            endif;



            // Set the response and exit
            $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            //echo $e->getMessage();
            // Set the response and exit
            // Default response
            $this->alert['status'] = FALSE;
            $this->alert["title"] = lang("error");
            $this->alert["message"] = lang("errorOnLogin");
            $this->alert["data"] = [];
            $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
        }
    }



    /**
     * Refresh Token
     */
    public function refresh_post()
    {
        $this->alert["message"] = lang("errorOnRefresh");
        $decodedToken = AUTHORIZATION::validateToken($this->post("data", true));
        if ($decodedToken) :
            $user = $this->general_model->get("users", null, ["id" => $decodedToken->id, "isActive" => 1]);
            if (!empty($user)) :
                $user->timestamp = time();
                $user->permissions = @$this->general_model->get("user_roles", null, ["id" => $user->role_id])->permissions;
                $user->token = AUTHORIZATION::generateToken((array)$user);
                // Set the response and exit
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("welcomeMessage") . " <b>" . $user->first_name . " " . $user->last_name . "</b>";
                $this->alert["data"] = $user;
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
            $this->alert["message"] = lang("notFound");
            $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Userinfo
     */
    public function me_get()
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
