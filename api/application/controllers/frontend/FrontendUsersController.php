<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FrontendUsersController extends MY_Controller
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
    public function __construct()
    {
        parent::__construct();

        // Load the model
        $this->token = AUTHORIZATION::verifyHeaderToken();
        $this->moduleName = ucfirst($this->router->fetch_class());
        $this->viewFolder = "users";
        $this->alert = [
            "status" => FALSE,
            "title" => lang("error"),
            "message" => lang("unauthorized"),
            "data" => null
        ];
    }

    public function index_get($id = null)
    {
        if ($this->token) :
            if (!isAllowedViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if (!empty($id)) :
                $wheres = ["id" => $id];
                $user = $this->general_model->get("users", null, $wheres);
                if ($this->check_level && $user->role_id == 1) :
                    $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
                endif;
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("received");
                $this->alert["user"] = $user;
                $this->alert["cities"] = $this->general_model->get_all("iller");
                if (!empty($user->city)) :
                    $city = $this->general_model->get("iller", null, ["il_adi" => $user->city]);
                    $this->alert["districts"] = $this->general_model->get_all("ilceler", ["il_id" => $city->id]);
                    if (!empty($user->district)) :
                        $district = $this->general_model->get("ilceler", null, ["il_id" => $city->id, "ilce_adi" => $user->district]);
                        $this->alert["neighborhoods"] = $this->general_model->get_all("semtler", ["ilce_id" => $district->id]);
                    endif;
                    if (!empty($user->neighborhood)) :
                        $neighborhood = $this->general_model->get("semtler", null, ["ilce_id" => $district->id, "semt_adi" => $user->neighborhood]);
                        $this->alert["quarters"] = $this->general_model->get_all("mahalleler", ["semt_id" => $neighborhood->id]);
                    endif;
                endif;
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function save_post()
    {
        if ($this->token) :
            if (!isAllowedWriteViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $data = $this->post(null, true);
            if (!empty($data["password"]) && !empty($data["password_repeat"]) && $data["password"] == $data["password_repeat"]) :
                $picture = upload_picture("img_url", "uploads/$this->viewFolder", [], "*");
                if ($picture["success"]) :
                    $data["picture"] = $picture["file_name"];
                endif;
                $cover = upload_picture("cover_url", "uploads/$this->viewFolder", [], "*");
                if ($cover["success"]) :
                    $data["cover"] = $cover["file_name"];
                endif;
                $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
                unset($data["password_repeat"]);
                $data["rank"] = $this->general_model->rowCount("users") + 1;
                if ($this->general_model->add("users", $data)) :
                    $this->alert["status"] = TRUE;
                    $this->alert["title"] = lang("successfully");
                    $this->alert["message"] = lang("saved");
                    $this->response($this->alert, MY_Controller::HTTP_OK);
                endif;
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function update_post($id = null)
    {
        if ($this->token) :
            if (!isAllowedUpdateViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if (!empty($id)) :
                $user = $this->general_model->get("users", null, ["id" => $id]);
                if (!empty($user)) :
                    if ($this->check_level && $user->role_id == 1) :
                        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
                    endif;
                    $data = $this->post(null, true);
                    if (!empty($data["password"]) && !empty($data["password_repeat"]) && $data["password"] == $data["password_repeat"]) :
                        unset($data["password_repeat"]);
                        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
                    endif;
                    $picture = upload_picture("picture_url", "uploads/$this->viewFolder", [], "*");
                    if ($picture["success"]) :
                        if (!empty($user->picture) && file_exists("uploads/$this->viewFolder/$user->picture")) :
                            unlink("uploads/$this->viewFolder/$user->picture");
                        endif;
                        $data["picture"] = $picture["file_name"];
                    endif;
                    $cover = upload_picture("cover_url", "uploads/$this->viewFolder", [], "*");
                    if ($cover["success"]) :
                        if (!empty($user->cover) && file_exists("uploads/$this->viewFolder/$user->cover")) :
                            unlink("uploads/$this->viewFolder/$user->cover");
                        endif;
                        $data["cover"] = $cover["file_name"];
                    endif;
                    if ($this->general_model->update("users", ["id" => $id], $data)) :
                        $this->alert["status"] = TRUE;
                        $this->alert["title"] = lang("successfully");
                        $this->alert["message"] = lang("updated");
                        $this->response($this->alert, MY_Controller::HTTP_OK);
                    endif;
                endif;
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function cities_get()
    {
        if (!empty($this->get("city_name", true))) :
            $cities = $this->general_model->get("iller", null, ["il_adi" => $this->get("city_name", true)]);
        else :
            $cities = $this->general_model->get_all("iller");
        endif;
        if (!empty($cities)) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $cities;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
    }

    public function districts_get($city_id = null)
    {
        $district_name = $this->get("district_name", true);
        if (!empty($district_name)) :
            $districts = $this->general_model->get("ilceler", null, ["il_id" => $city_id, "ilce_adi" => $district_name]);
        else :
            $districts = $this->general_model->get_all("ilceler", null, null, ["il_id" => $city_id]);
        endif;
        if (!empty($districts)) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $districts;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
    }

    public function neighborhoods_get($district_id = null)
    {
        $neighborhood_name = $this->get("neighborhood_name", true);
        if (!empty($neighborhood_name)) :
            $neighborhoods = $this->general_model->get("semtler", null, ["ilce_id" => $district_id, "semt_adi" => $neighborhood_name]);
        else :
            $neighborhoods = $this->general_model->get_all("semtler", null, null, ["ilce_id" => $district_id]);
        endif;
        if (!empty($neighborhoods)) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $neighborhoods;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
    }

    public function quarters_get($neighborhood_id = null)
    {
        $quarter_name = $this->get("quarter_name", true);
        if (!empty($quarter_name)) :
            $quarters = $this->general_model->get("mahalleler", null, ["semt_id" => $neighborhood_id, "mahalle_adi" => $quarter_name]);
        else :
            $quarters = $this->general_model->get_all("mahalleler", null, null, ["semt_id" => $neighborhood_id]);
        endif;
        if (!empty($quarters)) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $quarters;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
    }

    public function follow_user_post()
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
                    $data = $this->post(null, true);
                    $data["user_id"] = $user->id;
                    if ($data["user_id"] == $data["following_id"]) :
                        $this->alert["message"] = lang("cantFollowYourself");
                        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                    endif;
                    if ($this->general_model->get("followings", null, $data)) :
                        $this->alert["message"] = lang("alreadyFollowed");
                        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                    endif;
                    if ($this->general_model->add("followings", $data)) :
                        $this->alert["status"] = TRUE;
                        $this->alert["title"] = lang("successfully");
                        $this->alert["message"] = lang("userFollowedSuccessfully");
                        $this->response($this->alert, MY_Controller::HTTP_OK);
                    endif;
                endif;
            endif;
        endif;
        return $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function unfollow_user_post()
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
                    $data = $this->post(null, true);
                    $data["user_id"] = $user->id;
                    if ($data["user_id"] == $data["following_id"]) :
                        $this->alert["message"] = lang("cantUnFollowYourself");
                        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                    endif;
                    if (!$this->general_model->get("followings", null, $data)) :
                        $this->alert["message"] = lang("alreadyUnFollowed");
                        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                    endif;
                    if ($this->general_model->delete("followings", $data)) :
                        $this->alert["status"] = TRUE;
                        $this->alert["title"] = lang("successfully");
                        $this->alert["message"] = lang("userUnFollowedSuccessfully");
                        $this->response($this->alert, MY_Controller::HTTP_OK);
                    endif;
                endif;
            endif;
        endif;
        return $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : Get Users
     */
    public function users_get()
    {
        if ($this->token) {
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1]);

            if (empty($user)) {
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            }

            $getData = $this->get(null, true);
            $users = $this->general_model->getRows("users", ["users.id!=" => $this->token->id, "users.isActive" => 1, "users.hidden" => 0, "users.role_id!=" => 1], $getData, "users.*", "users.id DESC", [], [], [], [], true, "users.id");

            // Check if the logged-in user is following each user and include a flag.
            foreach ($users as &$user) {
                $user->is_following = $this->general_model->get("followings", null, ["user_id" => $this->token->id, "following_id" => $user->id]) ? true : false;
            }

            $recordsFiltered = $this->general_model->countFiltered("users", ["users.id!=" => $this->token->id, "users.isActive" => 1, "users.hidden" => 0, "users.role_id!=" => 1], (!empty($getData) ? $getData : []), "users.*", "users.id DESC", [], [], [], [], true, "users.id");

            $recordsTotal = $this->general_model->rowCount("users", ["users.id!=" => $this->token->id, "users.isActive" => 1, "users.hidden" => 0, "users.role_id!=" => 1]);

            if (!empty($followings)) {
                foreach ($followings as $key => $value) {
                    $followings[$key]->date = time_ago($value->date);
                }
            }

            $this->alert["status"] = true;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $users;
            $this->alert["recordsTotal"] = $recordsTotal;
            $this->alert["recordsFiltered"] = $recordsFiltered;

            $this->response($this->alert, MY_Controller::HTTP_OK);
        }

        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : User Statistics
     */
    public function statistics_get($username = null)
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["username" => $username, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $data = [
                "polls" => $this->general_model->rowCount("polls", ["author" => $user->id, "isActive" => 1]),
                "votes" => $this->general_model->rowCount("votes", ["user_id" => $user->id,"voterType" =>1]),
                "comments" => $this->general_model->rowCount("comments", ["user_id" => $user->id, "isActive" => 1]),
                "followers" => $this->general_model->rowCount("followers", ["user_id" => $user->id]),
                "followings" => $this->general_model->rowCount("followings", ["user_id" => $user->id]),
            ];
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $data;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : Get Profile
     */
    public function profile_get($username = null)
    {
        if ($this->token) {
            $user = $this->general_model->get("users", null, ["username" => $username, "isActive" => 1, "hidden" => 0, "role_id!=" => 1]);

            if (empty($user)) {
                $this->alert["message"] = lang("userProfileIsHidden");
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            }

            unset($user->password);
            // Check if the logged-in user is following each user and include a flag.
            $user->is_following = $this->general_model->get("followings", null, ["user_id" => $user->id, "following_id" => $this->token->id]) ? true : false;

            $this->alert["status"] = true;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $user;

            $this->response($this->alert, MY_Controller::HTTP_OK);
        }

        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : Get User Comments
     */
    public function user_comments_get($username = null)
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["username" => $username, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $getData = $this->get(null, true);
            $comments = $this->general_model->getRows("comments", ["comments.user_id" => $user->id], $getData, "comments.*,polls.title poll_title,polls.seo_url poll_seo_url,polls.id poll_id,polls.author poll_author,users.username commenter_username,users.type commenter_type, users.picture commenter_photo,users.role_id role_id", "comments.id DESC", [], ["polls" => ["comments.poll_id = polls.id", "LEFT"], "users" => ["comments.user_id = users.id", "LEFT"]], [], [], true, "polls.id,comments.id");
            $recordsFiltered = $this->general_model->countFiltered("comments", ["comments.user_id" => $user->id], (!empty($getData) ? $getData : []), "comments.*,polls.title poll_title,polls.seo_url poll_seo_url,polls.id poll_id,polls.author poll_author,users.username commenter_username,users.type commenter_type, users.picture commenter_photo,users.role_id role_id", "comments.id DESC", [], ["polls" => ["comments.poll_id = polls.id", "LEFT"], "users" => ["comments.user_id = users.id", "LEFT"]], [], [], true, "polls.id,comments.id");
            $recordsTotal = $this->general_model->rowCount("comments", ["comments.user_id" => $user->id]);
            if (!empty($comments)) :
                foreach ($comments as $key => $value) :
                    $comments[$key]->updatedAt = time_ago($value->updatedAt);
                endforeach;
            endif;
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $comments;
            $this->alert["recordsTotal"] = $recordsTotal;
            $this->alert["recordsFiltered"] = $recordsFiltered;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : Get User Followers
     */
    public function user_followers_get($username = null)
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["username" => $username, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $getData = $this->get(null, true);
            $followers = $this->general_model->getRows("users", ["followers.user_id" => $user->id], $getData, "followers.*,users.username follower_username,users.type follower_type, users.picture follower_photo,users.role_id role_id", "followers.id DESC", [], ["followings" => ["followers.follower_id = users.id", "LEFT"]], [], [], true, "followers.id");

            // Check if the logged-in user is following each user and include a flag.
            foreach ($followers as $key => $value) {
                $followers[$key]->is_following = $this->general_model->get("followings", null, ["user_id" => $this->token->id, "following_id" => $user->id]) ? true : false;
            }

            $recordsFiltered = $this->general_model->countFiltered("users", ["followers.user_id" => $user->id], (!empty($getData) ? $getData : []), "followers.*,users.username follower_username,users.type follower_type, users.picture follower_photo,users.role_id role_id", "followers.id DESC", [], ["followings" => ["followers.follower_id = users.id", "LEFT"]], [], [], true, "followers.id");
            $recordsTotal = $this->general_model->rowCount("followers", ["followers.user_id" => $user->id]);
            if (!empty($followers)) :
                foreach ($followers as $key => $value) :
                    $followers[$key]->date = time_ago($value->date);
                endforeach;
            endif;
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $followers;
            $this->alert["recordsTotal"] = $recordsTotal;
            $this->alert["recordsFiltered"] = $recordsFiltered;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : Get User Followings
     */
    public function user_followings_get($username = null)
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["username" => $username, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $getData = $this->get(null, true);
            $followings = $this->general_model->getRows("users", ["followings.user_id" => $user->id], $getData, "followings.*,users.username following_username,users.type following_type, users.picture following_photo,users.role_id role_id", "followings.id DESC", [], ["followings" => ["followings.following_id = users.id", "LEFT"]], [], [], true, "followings.id");

            // Check if the logged-in user is following each user and include a flag.
            foreach ($followings as $key => $value) {
                $followings[$key]->is_following = $this->general_model->get("followings", null, ["user_id" => $this->token->id, "following_id" => $value->following_id]) ? true : false;
            }
            $recordsFiltered = $this->general_model->countFiltered("users", ["followings.user_id" => $user->id], (!empty($getData) ? $getData : []), "followings.*,users.username following_username,users.type following_type, users.picture following_photo,users.role_id role_id", "followings.id DESC", [], ["followings" => ["followings.following_id = users.id", "LEFT"]], [], [], true, "followings.id");
            $recordsTotal = $this->general_model->rowCount("followings", ["followings.user_id" => $user->id]);
            if (!empty($followings)) :
                foreach ($followings as $key => $value) :
                    $followings[$key]->date = time_ago($value->date);
                endforeach;
            endif;
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $followings;
            $this->alert["recordsTotal"] = $recordsTotal;
            $this->alert["recordsFiltered"] = $recordsFiltered;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : Get My Polls
     */
    public function user_polls_get($username = null)
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["username" => $username, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $getData = $this->get(null, true);
            if (!empty($getData["category"])) :
                $category_id = $this->general_model->get("categories", "id", ["seo_url" => $getData["category"], "isActive" => 1]);
                unset($getData["category"]);
            endif;
            if (!empty($category_id->id) && !$this->general_model->rowCount("polls", ["category_id" => $category_id->id])) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $wheres = ["polls.isActive" => 1, "polls.author" => $user->id];

            if (!empty($category_id->id)) :
                $wheres["polls.category_id"] = $category_id->id;
            endif;
            $data = $this->general_model->getRows("polls", $wheres, $getData, "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type,users.picture author_photo,users.role_id role_id", "polls.id DESC", [], ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]], [], [], true, "polls.id");
            $recordsFiltered = $this->general_model->countFiltered("polls", ["polls.isActive" => 1], (!empty($getData) ? $getData : []), "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type, users.picture author_photo,users.role_id role_id", "polls.id DESC", [], ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]], [], [], true, "polls.id");
            if (!empty($data)) :
                foreach ($data as $key => $value) :
                    $data[$key]->answers = $this->general_model->get_all("answers", null, "id ASC", ["poll_id" => $value->id]);
                    $data[$key]->voters = $this->general_model->get_all("votes", "votes.*,users.username voter_username,users.type voter_type, users.picture voter_photo,users.role_id role_id", "votes.id DESC", ["votes.poll_id" => $value->id, "users.hidden" => 0, "users.role_id!=" => 1], [], ["users" => ["votes.user_id = users.id", "LEFT"]], [], [], true, "votes.id");
                    if (!empty($data[$key]->answers)) :
                        foreach ($data[$key]->answers as $k => $v) :
                            $option = 0;
                            if ($this->general_model->get("votes", null, ["answer_id" => $v->id])) :
                                $answer_votes = $this->general_model->rowCount("votes", ["answer_id" => $v->id]) * 100;
                                $total_votes = $this->general_model->rowCount("votes", ["poll_id" => $value->id]);
                                $option = $answer_votes / $total_votes;
                                $option = round($option);
                            endif;
                            $data[$key]->answers[$k]->percentage = $option;
                            if ($this->general_model->get("votes", null, ["poll_id" => $value->id, "user_id" => $user->id])) :
                                $data[$key]->answers[$k]->voted = is_voted($value->id, $user->id, $value->author);
                                $data[$key]->answers[$k]->voted_answer = $this->general_model->get("votes", null, ["poll_id" => $value->id, "answer_id" => $v->id, "user_id" => $user->id]);
                            endif;
                        endforeach;
                    endif;
                    if (!empty($seo_url)) :
                        $data[$key]->comments = $this->general_model->get_all("comments", "comments.*,users.username commenter_username,users.type commenter_type, users.picture commenter_photo,users.role_id role_id", "comments.id DESC", ["comments.poll_id" => $value->id, "comments.isActive" => 1], [], ["users" => ["comments.user_id = users.id", "LEFT"]], [], [], true, "comments.id");
                        if (!empty($data[$key]->comments)) :
                            foreach ($data[$key]->comments as $commentKey => $commentValue) :
                                $data[$key]->comments[$commentKey]->updatedAt = time_ago($commentValue->updatedAt);
                            endforeach;
                        endif;
                    endif;
                endforeach;
            endif;
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $data;
            $this->alert["recordsTotal"] = $this->general_model->rowCount("polls", ["isActive" => 1, "author" => $this->token->id]);
            $this->alert["recordsFiltered"] = $recordsFiltered;

            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }
}
