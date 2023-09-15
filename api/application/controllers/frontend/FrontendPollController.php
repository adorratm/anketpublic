<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Predis\Client;

class FrontendPollController extends MY_Controller
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
        $this->viewFolder = "polls";
        $this->alert = [
            "status" => FALSE,
            "title" => lang("error"),
            "message" => lang("unauthorized"),
            "data" => null
        ];
        $this->predis = new Client();
    }

    public function polls_get($seo_url = null)
    {
        $getData = $this->get(null, true);
        if (!empty($getData["category"])) :
            $category_id = $this->general_model->get("categories", "id", ["seo_url" => $getData["category"], "isActive" => 1]);
            unset($getData["category"]);
        endif;
        if (!empty($category_id->id) && !$this->general_model->rowCount("polls", ["category_id" => $category_id->id])) :
            $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
        endif;
        $wheres = ["polls.isActive" => 1];
        if (!empty($seo_url)) :
            $wheres["polls.seo_url"] = $seo_url;
        endif;
        if (!empty($getData["finished"])) :
            $wheres["polls.end_date<="] = date("Y-m-d H:i:s");
            unset($getData["finished"]);
        endif;
        
        
        if (!empty($category_id->id)) :
            $wheres["polls.category_id"] = $category_id->id;
        endif;
        $data = $this->general_model->getRows("polls", $wheres, (!empty($getData) ? $getData : []), "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type,users.picture author_photo,users.role_id role_id", "polls.id DESC", [], ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]], [], [], true, "polls.id");
        $recordsFiltered = $this->general_model->countFiltered("polls", $wheres, (!empty($getData) ? $getData : []), "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type, users.picture author_photo,users.role_id role_id", "polls.id DESC", [], ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]], [], [], true, "polls.id");
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
                        if ($this->token) :
                            // Returns all the users data if the id not specified,
                            // Otherwise, a single user will be returned.
                            $con = !empty($this->token->id) ? array('id' => $this->token->id, 'isActive' => 1) : NULL;
                            if (!empty($con)) :
                                $user = $this->general_model->get("users", null, $con);
                                // Check if the user data exists
                                if (!empty($user)) :
                                    if ($this->general_model->get("votes", null, ["poll_id" => $value->id, "user_id" => $user->id])) :
                                        $data[$key]->answers[$k]->voted = is_voted($value->id, $user->id, $value->author);
                                        $data[$key]->answers[$k]->voted_answer = $this->general_model->get("votes", null, ["poll_id" => $value->id, "answer_id" => $v->id, "user_id" => $user->id]);
                                    endif;
                                endif;
                            endif;
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
        $this->alert["recordsTotal"] = $this->general_model->rowCount("polls", ["isActive" => 1]);
        $this->alert["recordsFiltered"] = $recordsFiltered;

        $this->response($this->alert, MY_Controller::HTTP_OK);
    }

    public function polls_percentage_get()
    {
        $data = $this->get(null, true);
        if ($data) :
            $option = 0;
            if ($this->general_model->get("votes", null, ["answer_id" => $data["answer_id"]])) :
                $answer_votes = $this->general_model->rowCount("votes", ["answer_id" => $data["answer_id"]]) * 100;
                $total_votes = $this->general_model->rowCount("votes", ["poll_id" => $data["poll_id"]]);
                $option = $answer_votes / $total_votes;
                $option = round($option);
            endif;
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $option;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
    }

    public function poll_voters_get($seo_url = null)
    {

        $getData = $this->get(null, true);
        $poll = $this->general_model->get("polls", null, ["seo_url" => $seo_url, "isActive" => 1]);
        $voters = $this->general_model->get_all("votes", "votes.*,users.username voter_username,users.type voter_type, users.picture voter_photo,users.role_id role_id", "votes.id DESC", ["votes.poll_id" => $poll->id, "users.hidden" => 0, "users.role_id!=" => 1], [], ["users" => ["votes.user_id = users.id", "LEFT"]], [], [], true, "votes.id");
        $listOfVoters = [];
        if (!empty($voters)) :
            foreach ($voters as $key => $value) :
                $listOfVoters[] = $value->user_id;
            endforeach;
        endif;
        $users = $this->general_model->getRows("users", ["users.id!=" => $this->token->id, "users.isActive" => 1, "users.hidden" => 0, "users.role_id!=" => 1], $getData, "users.*", "users.id DESC", [], [], [], ["users.id" => $listOfVoters], true, "users.id");
        if ($this->token) {
            // Check if the logged-in user is following each user and include a flag.
            foreach ($users as $user) {
                $user->is_following = $this->general_model->get("followings", null, ["user_id" => $this->token->id, "following_id" => $user->id]) ? true : false;
            }
        }
        if (!empty($followings)) {
            foreach ($followings as $key => $value) {
                $followings[$key]->date = time_ago($value->date);
            }
        }
        $recordsFiltered = $this->general_model->countFiltered("users", ["users.id!=" => $this->token->id, "users.isActive" => 1, "users.hidden" => 0, "users.role_id!=" => 1], (!empty($getData) ? $getData : []), "users.*", "users.id DESC", [], [], [], [], true, "users.id");

        $recordsTotal = $this->general_model->rowCount("users", ["users.id!=" => $this->token->id, "users.isActive" => 1, "users.hidden" => 0, "users.role_id!=" => 1]);



        $this->alert["status"] = true;
        $this->alert["title"] = lang("successfully");
        $this->alert["message"] = lang("successfully");
        $this->alert["data"] = $users;
        $this->alert["recordsTotal"] = $recordsTotal;
        $this->alert["recordsFiltered"] = $recordsFiltered;

        $this->response($this->alert, MY_Controller::HTTP_OK);


        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function save_post()
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
                    $answers = @json_decode($data["answers"], true);
                    $images = [];
                    if (!empty($_FILES["file"])) :
                        for ($i = 0; $i <= count($_FILES["file"]); $i++) :
                            if (!empty($_FILES["file"]["name"][$i])) :
                                $_FILES["uploaded_file"]["name"] = $_FILES["file"]["name"][$i];
                                $_FILES["uploaded_file"]["type"] = $_FILES["file"]["type"][$i];
                                $_FILES["uploaded_file"]["tmp_name"] = $_FILES["file"]["tmp_name"][$i];
                                $_FILES["uploaded_file"]["error"] = $_FILES["file"]["error"][$i];
                                $_FILES["uploaded_file"]["size"] = $_FILES["file"]["size"][$i];
                                $image = upload_picture("uploaded_file", "uploads/$this->viewFolder", ["width" => 1600, "height" => 900], "*");
                                if ($image["success"]) :
                                    $images[] = $image["file_name"];
                                endif;
                            endif;
                        endfor;
                    endif;
                    if (!empty($images)) :
                        $data["images"] = json_encode($images);
                    endif;
                    $data["author"] = $user->id;
                    $data["seo_url"] = seo($data["title"]);
                    $data["isActive"] = 0;
                    $data["rank"] = $this->general_model->rowCount("polls") + 1;
                    unset($data["answers"]);
                    unset($data["thumbnails"]);
                    if ($data["polltype"] != 2 && empty($answers)) :
                        $this->alert["message"] = lang("pollNotSaved");
                        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                    endif;
                    if (strtotime($data["end_date"]) < strtotime("+1 Hour")) :
                        $this->alert["message"] = lang("pollEndDateError");
                        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                    endif;
                    $insert = $this->general_model->add("polls", $data);
                    if ($insert) :
                        if ($data["polltype"] == 2) :
                            // YES NO POLL
                            $this->general_model->insert_batch("answers", [["poll_id" => $insert, "answer" => "YES", "thumbnail" => null], ["poll_id" => $insert, "answer" => "NO", "thumbnail" => null]]);
                        else :
                            // POLL W QUESTIONS
                            if (!empty($answers)) :
                                foreach ($answers as $key => $value) :
                                    $answer = [
                                        "poll_id" => $insert,
                                        "answer" => $value
                                    ];
                                    // POLL W THUMBNAILS
                                    if (!empty($_FILES["thumbnails"])) :
                                        if (!empty($_FILES["thumbnails"]["name"][$key])) :
                                            $_FILES["uploaded_file"]["name"] = $_FILES["thumbnails"]["name"][$key];
                                            $_FILES["uploaded_file"]["type"] = $_FILES["thumbnails"]["type"][$key];
                                            $_FILES["uploaded_file"]["tmp_name"] = $_FILES["thumbnails"]["tmp_name"][$key];
                                            $_FILES["uploaded_file"]["error"] = $_FILES["thumbnails"]["error"][$key];
                                            $_FILES["uploaded_file"]["size"] = $_FILES["thumbnails"]["size"][$key];
                                            $image = upload_picture("uploaded_file", "uploads/$this->viewFolder/answers", ["width" => 1600, "height" => 900], "*");
                                            if ($image["success"]) :
                                                $answer["thumbnail"] = $image["file_name"];
                                            endif;
                                        endif;
                                    endif;
                                    $this->general_model->add("answers", $answer);
                                endforeach;
                            endif;
                        endif;
                        // UPDATE POLL COUNT IN CATEGORY
                        $this->general_model->update("categories", ["id" => $data["category_id"]], ["polls" => $this->general_model->rowCount("polls", ["category_id" => $data["category_id"]]) + 1]);

                        $this->alert["status"] = TRUE;
                        $this->alert["title"] = lang("successfully");
                        $this->alert["message"] = lang("pollSaved");
                        $this->alert["data"] = $data;
                        $this->response($this->alert, MY_Controller::HTTP_OK);
                    endif;
                    $this->alert["message"] = lang("pollNotSaved");
                    $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
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

    public function vote_post()
    {
        if ($this->token) :
            $data = $this->post(null, true);
            $poll = $this->general_model->get("polls", null, ["id" => $data["poll_id"], "isActive" => 1, "end_date >=" => date("Y-m-d H:i:s")]);
            if ($poll) :
                $answer = $this->general_model->get("answers", null, ["id" => $data["answer_id"]]);
                if ($answer) :
                    $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1, "role_id!=" => 1]);
                    if ($user) :
                        if ($this->general_model->get("votes", null, ["poll_id" => $data["poll_id"], "user_id" => $user->id])) :
                            $this->alert["message"] = lang("pollAlreadyVoted");
                            $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                        endif;
                        if ($poll->author == $user->id) :
                            $this->alert["message"] = lang("pollAuthorCantVote");
                            $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                        endif;
                        if (empty($user->city) || empty($user->district) || empty($user->birth_date) || empty($user->phone)) :
                            $this->alert["message"] = lang("youMustCompleteYourProfile");
                            $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                        endif;
                        $vote_data = [
                            "user_id" => $user->id,
                            "poll_id" => $data["poll_id"],
                            "answer_id" => $data["answer_id"],
                            "author_id" => $poll->author,
                            "gender" => $user->gender,
                            "age" => calculate_age($user->birth_date),
                            "city" => $user->city,
                            "district" => $user->district,
                            "neighborhood" => $user->neighborhood,
                            "quarter" => $user->quarter,
                            "address" => $user->address,
                            "birth_date" => $user->birth_date,
                            "email" => $user->email,
                            "device" => $this->agent->is_mobile() ? $this->agent->mobile() : $this->agent->platform(),
                            "browser" => $this->agent->browser(),
                            "os" => $this->agent->platform(),
                            "ip" => check_ip(),
                            "isActive" => 1
                        ];
                        $insert = $this->general_model->add("votes", $vote_data);
                        if ($insert) :
                            $this->general_model->update("polls", ["id" => $data["poll_id"]], ["votes" => $poll->votes + 1]);
                            $this->alert["status"] = TRUE;
                            $this->alert["title"] = lang("successfully");
                            $this->alert["message"] = lang("pollVoted");
                            $this->alert["data"] = $data;
                            $this->response($this->alert, MY_Controller::HTTP_OK);
                        endif;
                    endif;
                endif;
            endif;
        endif;
        $this->alert["message"] = lang("unauthorizedPoll");
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function comment_post()
    {
        if ($this->token) :
            $data = $this->post(null, true);
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules("comment", lang("comment"), "required|trim|clean|min_length[2]|max_length[255]");
            $this->form_validation->set_message(["required"  => lang("required"), "min_length" => lang("min_length"), "max_length" => lang("max_length")]);
            $this->form_validation->set_error_delimiters('', ',');
            // Default response
            $this->alert["message"] = lang("errorOnComment");
            // Validate form input
            if ($this->form_validation->run()) :
                // Returns all the users data if the id not specified,
                // Otherwise, a single user will be returned.
                $con = !empty($this->token->id) ? array('id' => $this->token->id, 'isActive' => 1) : NULL;
                if (!empty($con)) :
                    $user = $this->general_model->get("users", null, $con);
                    // Check if the user data exists
                    if (!empty($user)) :
                        $poll = $this->general_model->get("polls", null, ["id" => $data["poll_id"], "isActive" => 1]);
                        if ($poll) :
                            $comment_data = [
                                "user_id" => $user->id,
                                "poll_id" => $data["poll_id"],
                                "comment" => $data["comment"],
                                "isActive" => 1
                            ];
                            $insert = $this->general_model->add("comments", $comment_data);
                            if ($insert) :
                                $this->general_model->add("notifications", [
                                    "title" => "<b>" . $user->username . "</b> " . lang("commentedYourPoll"),
                                    "author_id" => $poll->author,
                                    "user_id" => $user->id,
                                    "table_id" => $poll->id,
                                    "table_name" => "polls",
                                ]);
                                $this->alert["status"] = TRUE;
                                $this->alert["title"] = lang("successfully");
                                $this->alert["message"] = lang("commentSaved");
                                $this->alert["data"] = $data;
                                $this->response($this->alert, MY_Controller::HTTP_OK);
                            endif;
                        endif;
                    endif;
                endif;
                $this->alert["message"] = lang("notFound");
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            // Set the response and exit
            //BAD_REQUEST (400) being the HTTP response code
            if (validation_errors()) :
                $this->alert["message"] =  str_replace("<br />\n", "", nl2br(implode(",", array_filter(explode(",", validation_errors()), 'clean'))));
            endif;
        endif;
        // return response if token is invalid
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function add_sms_queue_post()
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "isActive" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            // Default response
            $data = $this->post(null, true);
            $poll = $this->general_model->get("polls", null, ["id" => $data["poll_id"], "isActive" => 1, "end_date >=" => date("Y-m-d H:i:s")]);
            if (empty($poll)) :
                $this->alert["message"] = lang("youCantGetVoteResultSms");
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            if (empty($user->phone)) :
                $this->alert["message"] = lang("youNeedToHavePhoneToGetVoteResultSms");
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $phone = $user->phone;
            $phone = strlen($phone) == 10 ? "90" . $phone : $phone;
            $phone = strlen($phone) == 11 ? "9" . $phone : $phone;
            $phone = strlen($phone) == 12 ? $phone : $phone;
            $phone = strlen($phone) >= 13 ? str_replace("+", "", $phone) : $phone;
            $followLink = $data["url"] . $poll->seo_url;
            $message = $poll->title . " " . str_replace("{followLink}", $followLink, lang("smsVoteResult"));
            if (!empty($this->general_model->get("sms", null, ["poll_id" => $poll->id, "phone" => $phone]))) :
                $this->alert["message"] = lang("youCantGetVoteResultSms");
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $insert = $this->general_model->add("sms", ["poll_id" => $poll->id, "phone" => $phone, "message" => $message, "sendedAt" => $poll->end_date]);
            if ($insert) :
                $this->predis->lpush('sms_queue', json_encode(["id" => $insert, "phone" => $phone, "message" => $message, "sendedAt" => $poll->end_date]));
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("addedSmsQueue");
                $this->alert["data"] = null;
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
            $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
        endif;

        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function queues_get()
    {
        $this->predis->FLUSHALL();
        //$this->response($this->alert, MY_Controller::HTTP_OK);
    }
}
