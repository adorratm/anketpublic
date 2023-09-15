<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FrontendProfileController extends MY_Controller
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

    /**
     * Description : My Statistics
     */
    public function statistics_get()
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $data = [
                "polls" => $this->general_model->rowCount("polls", ["author" => $user->id, "isActive" => 1]),
                "votes" => $this->general_model->rowCount("votes", ["user_id" => $user->id, "voterType" => 1]),
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
     * Description : Get My Polls
     */
    public function my_polls_get($seo_url = null)
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1]);
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
            $wheres = ["polls.isActive" => 1, "polls.author" => $this->token->id];
            if (!empty($seo_url)) :
                $wheres["polls.seo_url"] = $seo_url;
            endif;

            if (!empty($category_id->id)) :
                $wheres["polls.category_id"] = $category_id->id;
            endif;
            $data = $this->general_model->getRows("polls", $wheres, $getData, "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,polls.votes votes_count,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type,users.picture author_photo,users.role_id role_id", "polls.id DESC", [], ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]], [], [], true, "polls.id");
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

    /**
     * Description : Get My One Poll
     */
    public function my_poll_get($seo_url = null)
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $wheres = ["polls.isActive" => 1, "polls.author" => $this->token->id];
            if (!empty($seo_url)) :
                $wheres["polls.seo_url"] = $seo_url;
            endif;
            $data = $this->general_model->get("polls", "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,polls.votes votes_count,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type,users.picture author_photo,users.role_id role_id", $wheres, ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]]);
            if (!empty($data)) :
                $data->answers = $this->general_model->get_all("answers", null, "id ASC", ["poll_id" => $data->id]);
                $data->voters = $this->general_model->get_all("votes", "votes.*,users.username voter_username,users.type voter_type, users.picture voter_photo,users.role_id role_id,answers.answer answer", "votes.id DESC", ["votes.poll_id" => $data->id, "users.hidden" => 0, "users.role_id!=" => 1], [], ["users" => ["votes.user_id = users.id", "LEFT"], "answers" => ["votes.answer_id = answers.id", "LEFT"]], [], [], true, "votes.id");
                $data->realVoters = $this->general_model->get_all("votes", "votes.*", "votes.id DESC", ["votes.poll_id" => $data->id], [], ["users" => ["votes.user_id = users.id", "LEFT"]], [], [], true, "votes.id");
                if (!empty($data->answers)) :
                    foreach ($data->answers as $k => $v) :
                        $option = 0;
                        if ($this->general_model->get("votes", null, ["answer_id" => $v->id])) :
                            $answer_votes = $this->general_model->rowCount("votes", ["answer_id" => $v->id]) * 100;
                            $total_votes = $this->general_model->rowCount("votes", ["poll_id" => $data->id]);
                            $option = $answer_votes / $total_votes;
                            $option = round($option);
                        endif;
                        $data->answers[$k]->percentage = $option;
                        $data->answers[$k]->color = randomColor();
                        if ($this->general_model->get("votes", null, ["poll_id" => $data->id, "user_id" => $user->id])) :
                            $data->answers[$k]->voted = is_voted($data->id, $user->id, $data->author);
                            $data->answers[$k]->voted_answer = $this->general_model->get("votes", null, ["poll_id" => $data->id, "answer_id" => $v->id, "user_id" => $user->id]);
                        endif;
                    endforeach;
                endif;
                $data->comments = $this->general_model->get_all("comments", "comments.*,users.username commenter_username,users.type commenter_type, users.picture commenter_photo,users.role_id role_id", "comments.id DESC", ["comments.poll_id" => $data->id, "comments.isActive" => 1], [], ["users" => ["comments.user_id = users.id", "LEFT"]], [], [], true, "comments.id");
                if (!empty($data->comments)) :
                    foreach ($data->comments as $commentKey => $commentValue) :
                        $data->comments[$commentKey]->updatedAt = time_ago($commentValue->updatedAt);
                    endforeach;
                endif;
                // Crete Gender Chart Data
                $data->genders = new stdClass();
                $data->genders->male = fh_stats_sex($data->id, count($data->realVoters), lang("male"));
                $data->genders->female = fh_stats_sex($data->id, count($data->realVoters), lang("female"));
                $data->genders->other = fh_stats_sex($data->id, count($data->realVoters), lang("other"));
                $genders = [1 => lang("male"), 2 => lang("female"), 3 => lang("other")];
                $chartData = [];
                foreach ($genders as $k => $gender) :
                    $chartData['data'][] = $this->general_model->rowCount("votes",  ["gender" => $gender, "poll_id" => $data->id]);
                    $chartData['labels'][] = $gender;
                    $colors = randomColor();
                    $chartData['colors'][] = "#" . $colors['hex'];
                endforeach;
                $chartData['title'] = "";
                $chartData['id'] = $data->id;
                $data->genders->chart = $chartData;
                // Create Age Chart Data
                $chartData = [];
                $data->ages = new stdClass();
                $chartData['labels'][] = lang("age_13");
                $colors = randomColor();
                $chartData['colors'][] = "#" . $colors['hex'];
                $data->ages->age_13 = $chartData['data'][] = $this->general_model->rowCount("votes",  ["age<" => 13, "poll_id" => $data->id]);
                $data->ages->age_13_percentage = fh_stats_percentage(count($data->realVoters), $data->ages->age_13);
                $chartData['labels'][] = lang("age_13_18");
                $colors = randomColor();
                $chartData['colors'][] = "#" . $colors['hex'];
                $data->ages->age_13_18 = $chartData['data'][] = $this->general_model->rowCount("votes",  ["age>=" => 13, "age<" => 18, "poll_id" => $data->id]);
                $data->ages->age_13_18_percentage = fh_stats_percentage(count($data->realVoters), $data->ages->age_13_18);
                $chartData['labels'][] = lang("age_18_25");
                $colors = randomColor();
                $chartData['colors'][] = "#" . $colors['hex'];
                $data->ages->age_18_25 = $chartData['data'][] = $this->general_model->rowCount("votes",  ["age>=" => 18, "age<" => 25, "poll_id" => $data->id]);
                $data->ages->age_18_25_percentage = fh_stats_percentage(count($data->realVoters), $data->ages->age_18_25);
                $chartData['labels'][] = lang("age_25_35");
                $colors = randomColor();
                $chartData['colors'][] = "#" . $colors['hex'];
                $data->ages->age_25_35 = $chartData['data'][] = $this->general_model->rowCount("votes",  ["age>=" => 25, "age<" => 35, "poll_id" => $data->id]);
                $data->ages->age_25_35_percentage = fh_stats_percentage(count($data->realVoters), $data->ages->age_25_35);
                $chartData['labels'][] = lang("age_35_50");
                $colors = randomColor();
                $chartData['colors'][] = "#" . $colors['hex'];
                $data->ages->age_35_50 = $chartData['data'][] = $this->general_model->rowCount("votes",  ["age>=" => 35, "age<" => 50, "poll_id" => $data->id]);
                $data->ages->age_35_50_percentage = fh_stats_percentage(count($data->realVoters), $data->ages->age_35_50);
                $chartData['labels'][] = lang("age_50");
                $colors = randomColor();
                $chartData['colors'][] = "#" . $colors['hex'];
                $data->ages->age_50 = $chartData['data'][] = $this->general_model->rowCount("votes",  ["age>=" => 50, "poll_id" => $data->id]);
                $data->ages->age_50_percentage = fh_stats_percentage(count($data->realVoters), $data->ages->age_50);
                $chartData['title'] = lang("votesByAges");
                $data->ages->chart = $chartData;
                // Create Cities and Districts Chart Data
                $data->cities_and_districts = $this->general_model->get_all("votes", "city, district, COUNT(*) as total", "total DESC", ["poll_id" => $data->id], [], [], [], [], true, "city, district");
                if (!empty($data->cities_and_districts)) :
                    foreach ($data->cities_and_districts as $k => $v) :
                        $data->cities_and_districts[$k]->percentage = fh_stats_percentage(count($data->realVoters), $v->total);
                    endforeach;
                endif;
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("successfully");
                $this->alert["data"] = $data;
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
            $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : Get My Comments
     */
    public function my_comments_get()
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $getData = $this->get(null, true);
            $comments = $this->general_model->getRows("comments", ["comments.user_id" => $this->token->id], $getData, "comments.*,polls.title poll_title,polls.seo_url poll_seo_url,polls.id poll_id,polls.author poll_author,users.username commenter_username,users.type commenter_type, users.picture commenter_photo,users.role_id role_id", "comments.id DESC", [], ["polls" => ["comments.poll_id = polls.id", "LEFT"], "users" => ["comments.user_id = users.id", "LEFT"]], [], [], true, "polls.id,comments.id");
            $recordsFiltered = $this->general_model->countFiltered("comments", ["comments.user_id" => $this->token->id], (!empty($getData) ? $getData : []), "comments.*,polls.title poll_title,polls.seo_url poll_seo_url,polls.id poll_id,polls.author poll_author,users.username commenter_username,users.type commenter_type, users.picture commenter_photo,users.role_id role_id", "comments.id DESC", [], ["polls" => ["comments.poll_id = polls.id", "LEFT"], "users" => ["comments.user_id = users.id", "LEFT"]], [], [], true, "polls.id,comments.id");
            $recordsTotal = $this->general_model->rowCount("comments", ["comments.user_id" => $this->token->id]);
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
     * Description : Delete My Comment
     */
    public function delete_my_comment_delete($id = null)
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            if (empty($id)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $comment = $this->general_model->get("comments", null, ["id" => $id, "user_id" => $this->token->id]);
            if (empty($comment)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $this->general_model->delete("comments", ["id" => $id]);
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("deleted");
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : Get My Followers
     */
    public function my_followers_get()
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $getData = $this->get(null, true);
            $followers = $this->general_model->getRows("users", ["followers.user_id" => $this->token->id], $getData, "followers.*,users.username follower_username,users.type follower_type, users.picture follower_photo,users.role_id role_id", "followers.id DESC", [], ["followings" => ["followers.follower_id = users.id", "LEFT"]], [], [], true, "followers.id");

            // Check if the logged-in user is following each user and include a flag.
            foreach ($followers as $key => $value) {
                $followers[$key]->is_following = $this->general_model->get("followings", null, ["user_id" => $this->token->id, "following_id" => $user->id]) ? true : false;
            }

            $recordsFiltered = $this->general_model->countFiltered("users", ["followers.user_id" => $this->token->id], (!empty($getData) ? $getData : []), "followers.*,users.username follower_username,users.type follower_type, users.picture follower_photo,users.role_id role_id", "followers.id DESC", [], ["followings" => ["followers.follower_id = users.id", "LEFT"]], [], [], true, "followers.id");
            $recordsTotal = $this->general_model->rowCount("followers", ["followers.user_id" => $this->token->id]);
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
     * Description : Get My Followings
     */
    public function my_followings_get()
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $getData = $this->get(null, true);
            $followings = $this->general_model->getRows("users", ["followings.user_id" => $this->token->id], $getData, "followings.*,users.username following_username,users.type following_type, users.picture following_photo,users.role_id role_id", "followings.id DESC", [], ["followings" => ["followings.following_id = users.id", "LEFT"]], [], [], true, "followings.id");

            // Check if the logged-in user is following each user and include a flag.
            foreach ($followings as $key => $value) {
                $followings[$key]->is_following = $this->general_model->get("followings", null, ["user_id" => $this->token->id, "following_id" => $value->following_id]) ? true : false;
            }
            $recordsFiltered = $this->general_model->countFiltered("users", ["followings.user_id" => $this->token->id], (!empty($getData) ? $getData : []), "followings.*,users.username following_username,users.type following_type, users.picture following_photo,users.role_id role_id", "followings.id DESC", [], ["followings" => ["followings.following_id = users.id", "LEFT"]], [], [], true, "followings.id");
            $recordsTotal = $this->general_model->rowCount("followings", ["followings.user_id" => $this->token->id]);
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
     * Update Profile
     */
    public function update_post()
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $data = $this->post(null, true);
            // Default response
            $this->alert["message"] = lang("errorOnProfileUpdate");
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules("first_name", lang("first_name"), "required|trim|clean");
            $this->form_validation->set_rules("last_name", lang("last_name"), "required|trim|clean");
            $this->form_validation->set_rules("gender", lang("gender"), "required|trim|clean");
            $this->form_validation->set_rules("birth_date", lang("birth_date"), "required|trim|clean");
            if ($user->username != $data["username"]) :
                $this->form_validation->set_rules("username", lang("username"), "required|trim|clean|is_unique[users.username]");
                $data["username"] = strto("lower", $data["username"]);
            else :
                unset($data["username"]);
            endif;
            $this->form_validation->set_rules("phone", lang("phone"), "required|trim|clean");
            $this->form_validation->set_rules("city", lang("city"), "required|trim|clean");
            $this->form_validation->set_rules("district", lang("district"), "required|trim|clean");
            $this->form_validation->set_rules("neighborhood", lang("neighborhood"), "trim|clean");
            $this->form_validation->set_rules("quarter", lang("quarter"), "trim|clean");
            $this->form_validation->set_rules("address", lang("address"), "required|trim|clean");
            if ($user->email != $data["email"]) :
                $this->form_validation->set_rules("email", lang("email"), "required|trim|valid_email|clean|is_unique[users.email]");
            else :
                unset($data["email"]);
            endif;
            if (!empty($data["password"])) :
                $this->form_validation->set_rules("password", lang("password"), "trim|min_length[6]|clean");
                $this->form_validation->set_rules("passwordRepeat", lang("password_repeat"), "trim|min_length[6]|matches[password]|clean");
            endif;
            $this->form_validation->set_message(["required"  => lang("required"), "valid_email" => lang("valid_email"), "min_length" => lang("min_length"), "matches" => lang("matches"), "is_unique" => lang("is_unique")]);
            if ($data["type"] === "CORPORATE") :
                $this->form_validation->set_rules("company_name", lang("company_name"), "required|trim|clean");
                $this->form_validation->set_rules("tax_administration", lang("tax_administration"), "required|trim|clean");
                $this->form_validation->set_rules("tax_number", lang("tax_number"), "required|trim|clean");
            endif;
            $this->form_validation->set_error_delimiters('', ',');
            // Validate form input
            if ($this->form_validation->run()) :
                unset($data["passwordRepeat"]);
                if (!empty($data["password"])) :
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
                $update = $this->general_model->update("users", ["id" => $this->token->id], $data);
                if ($update) :
                    // Set the response and exit
                    $this->alert["status"] = TRUE;
                    $this->alert["title"] = lang("successfully");
                    $this->alert["message"] = lang("profileUpdatedSuccessfully");
                    $this->response($this->alert, MY_Controller::HTTP_OK);
                endif;
            endif;
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            if (validation_errors()) :
                $this->alert["message"] =  str_replace("<br />\n", "", nl2br(implode(",", array_filter(explode(",", validation_errors()), 'clean'))));
            endif;
            $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }
}
