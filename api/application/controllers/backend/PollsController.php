<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PollsController extends MY_Controller
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
        $this->moduleName = ucfirst($this->router->fetch_class());
        $this->viewFolder = "polls";
        $this->alert = [
            "status" => FALSE,
            "title" => lang("error"),
            "message" => lang("unauthorized"),
            "data" => null
        ];
    }

    public function datatable_post()
    {
        // return response if token is valid
        $output = [];
        if ($this->token) :
            if (!isAllowedViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $items = $this->general_model->getRows("polls", [], $this->post(null, true), "polls.*,categories.title category_title,users.username author_username,users.type author_type,users.picture author_photo,users.role_id role_id", "polls.id DESC", [], ["users" => ["polls.author = users.id", "LEFT"], "categories" => ["polls.category_id = categories.id", "LEFT"]], [], [], true, "polls.id");
            $data = [];
            if (!empty($items)) :
                foreach ($items as $item) :
                    $data[] = ["rank" => $item->rank, "id" => $item->id, "title" => $item->title, "author" => $item->author_username, "lang" => $item->lang, "pinned" => $item->pinned, "isActive" => $item->isActive, "createdAt" => turkishDate("d F Y, l H:i:s", $item->createdAt), "updatedAt" => turkishDate("d F Y, l H:i:s", $item->updatedAt), "actions" => $item->id];
                endforeach;
            endif;
            $output = [
                "recordsTotal" => $this->general_model->rowCount("polls"),
                "recordsFiltered" => $this->general_model->countFiltered("polls", [], (!empty($this->post(null, true)) ? $this->post(null, true) : [])),
                "data" => $data,
            ];
        endif;
        // Output to JSON format
        return $this->response($output, MY_Controller::HTTP_OK);
    }

    public function rank_put($id = null)
    {
        if ($this->token) :
            if (!isAllowedUpdateViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if ($this->general_model->update("polls", ["id" => $id], ["rank" => $this->put('rank', true)])) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("rank_updated");
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function isactive_put($id = null)
    {
        if ($this->token) :
            if (!isAllowedUpdateViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $isActive = boolval($this->put("isActive", true)) === true ? 1 : 0;
            if ($this->general_model->update("polls", ["id" => $id], ["isActive" => $isActive])) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("status_updated");
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function pinned_put($id = null)
    {
        if ($this->token) :
            if (!isAllowedUpdateViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $pinned = boolval($this->put("pinned", true)) === true ? 1 : 0;
            if ($this->general_model->update("polls", ["id" => $id], ["pinned" => $pinned])) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("status_updated");
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function authors_get()
    {
        if ($this->token) :
            $authors = $this->general_model->get_all("users", null, "id DESC", ["isActive" => 1, "role_id!=" => 1]);
            if (!empty($authors)) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("successfully");
                $this->alert["data"] = $authors;
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function save_post()
    {
        // return response if token is valid
        if ($this->token) :
            // Returns all the users data if the id not specified,
            // Otherwise, a single user will be returned.
            $con = !empty($this->token->id) ? array('id' => $this->token->id, 'isActive' => 1, 'role_id' => 1) : NULL;
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
                    $data["seo_url"] = seo($data["title"]);
                    $data["isActive"] = 1;
                    $data["rank"] = $this->general_model->rowCount("polls") + 1;
                    unset($data["answers"]);
                    unset($data["thumbnails"]);
                    if ($data["polltype"] != 2 && empty($answers)) :
                        $this->alert["message"] = lang("pollNotSaved");
                        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                    endif;
                    if (strtotime($data['end_date']) < strtotime("+1 Hour")) :
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

    public function update_post($id = null)
    {
        if ($this->token) :
            if (!isAllowedUpdateViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if (!empty($id)) :
                $poll = $this->general_model->get("polls", null, ["id" => $id]);
                if (!empty($poll)) :
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
                        $pollImages = @json_decode($poll->images);
                        if (!empty($pollImages)) :
                            foreach ($pollImages as $pollImage) :
                                if ($pollImage && @file_exists("uploads/$this->viewFolder/$pollImage")) :
                                    @unlink("uploads/$this->viewFolder/$pollImage");
                                endif;
                            endforeach;
                        endif;
                    endif;
                    if (!empty($images)) :
                        $data["images"] = json_encode($images);
                    endif;
                    $data["author"] = $poll->author;
                    $data["seo_url"] = seo($data["title"]);
                    unset($data["answers"]);
                    unset($data["thumbnails"]);
                    if ($data["polltype"] != 2 && empty($answers)) :
                        $this->alert["message"] = lang("pollNotSaved");
                        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                    endif;
                    if (strtotime($data['end_date']) < strtotime("+1 Hour")) :
                        $this->alert["message"] = lang("pollEndDateError");
                        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
                    endif;
                    $data["seo_url"] = seo($data["title"]);
                    if ($this->general_model->update("polls", ["id" => $id], $data)) :
                        // POLL W QUESTIONS
                        if (!empty($answers)) :
                            foreach ($answers as $key => $value) :
                                // POLL W THUMBNAILS
                                unset($value["thumbnail"]);
                                unset($value["percentage"]);
                                if (!empty($_FILES["thumbnails"])) :
                                    if (!empty($_FILES["thumbnails"]["name"][$key])) :
                                        $_FILES["uploaded_file"]["name"] = $_FILES["thumbnails"]["name"][$key];
                                        $_FILES["uploaded_file"]["type"] = $_FILES["thumbnails"]["type"][$key];
                                        $_FILES["uploaded_file"]["tmp_name"] = $_FILES["thumbnails"]["tmp_name"][$key];
                                        $_FILES["uploaded_file"]["error"] = $_FILES["thumbnails"]["error"][$key];
                                        $_FILES["uploaded_file"]["size"] = $_FILES["thumbnails"]["size"][$key];
                                        $image = upload_picture("uploaded_file", "uploads/$this->viewFolder/answers", ["width" => 1600, "height" => 900], "*");
                                        if (!empty($value["thumbnail"])) :
                                            if ($value["thumbnail"] && @file_exists("uploads/$this->viewFolder/answers/{$value["thumbnail"]}")) :
                                                @unlink("uploads/$this->viewFolder/answers/{$value["thumbnail"]}");
                                            endif;
                                        endif;
                                        if ($image["success"]) :
                                            $value["thumbnail"] = $image["file_name"];
                                        endif;
                                    endif;
                                endif;
                                $this->general_model->update("answers", ["id" => $value["id"]], $value);
                            endforeach;
                        endif;
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

    public function delete_delete($id = null)
    {
        if ($this->token) :
            if (!isAllowedDeleteViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $poll = $this->general_model->get("polls", null, ["id" => $id]);
            if ($this->general_model->delete("polls", ["id" => $id])) :
                /**
                 * Delete Poll Images
                 */
                $pollImages = @json_decode($poll->images);
                if (!empty($pollImages)) :
                    foreach ($pollImages as $pollImage) :
                        if ($pollImage && @file_exists("uploads/$this->viewFolder/$pollImage")) :
                            @unlink("uploads/$this->viewFolder/$pollImage");
                        endif;
                    endforeach;
                endif;
                /**
                 * Delete Poll Answers With Thumbnails
                 */
                $answers = $this->general_model->get_all("answers", null, null, ["poll_id" => $poll->id]);
                $answer_ids = [];
                if (!empty($answers)) :
                    foreach ($answers as $answer) :
                        $answer_ids[] = $answer->id;
                        if ($answer->thumbnail && @file_exists("uploads/$this->viewFolder/answers/$answer->thumbnail")) :
                            @unlink("uploads/$this->viewFolder/answers/$answer->thumbnail");
                        endif;
                    endforeach;
                    $this->general_model->deleteBulk("answers", "id", $answer_ids);
                endif;
                /**
                 * Delete Poll Comments
                 */
                $comments = $this->general_model->get_all("comments", null, null, ["poll_id" => $poll->id]);
                $comment_ids = [];
                if (!empty($comments)) :
                    foreach ($comments as $comment) :
                        $comment_ids[] = $comment->id;
                    endforeach;
                    $this->general_model->deleteBulk("comments", "id", $comment_ids);
                endif;
                /**
                 * Delete Poll Votes
                 */
                $votes = $this->general_model->get_all("votes", null, null, ["poll_id" => $poll->id]);
                $vote_ids = [];
                if (!empty($votes)) :
                    foreach ($votes as $vote) :
                        $vote_ids[] = $vote->id;
                    endforeach;
                    $this->general_model->deleteBulk("votes", "id", $vote_ids);
                endif;
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("deleted");
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function delete_post()
    {
        if ($this->token) :
            if (!isAllowedDeleteViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if (!empty($this->post("id", true))) :
                $ids = @explode(",", $this->post("id", true));
                $polls = $this->general_model->get_all("polls", null, null, [], [], [], [], ["id" => $ids]);
                if (!empty($ids) && $this->general_model->deleteBulk("polls", "id", $ids)) :
                    if (!empty($polls)) :
                        foreach ($polls as $poll) :
                            /**
                             * Delete Poll Images
                             */
                            $pollImages = @json_decode($poll->images);
                            if (!empty($pollImages)) :
                                foreach ($pollImages as $pollImage) :
                                    if ($pollImage && @file_exists("uploads/$this->viewFolder/$pollImage")) :
                                        @unlink("uploads/$this->viewFolder/$pollImage");
                                    endif;
                                endforeach;
                            endif;
                            /**
                             * Delete Poll Answers With Thumbnails
                             */
                            $answers = $this->general_model->get_all("answers", null, null, ["poll_id" => $poll->id]);
                            $answer_ids = [];
                            if (!empty($answers)) :
                                foreach ($answers as $answer) :
                                    $answer_ids[] = $answer->id;
                                    if ($answer->thumbnail && @file_exists("uploads/$this->viewFolder/answers/$answer->thumbnail")) :
                                        @unlink("uploads/$this->viewFolder/answers/$answer->thumbnail");
                                    endif;
                                endforeach;
                                $this->general_model->deleteBulk("answers", "id", $answer_ids);
                            endif;
                            /**
                             * Delete Poll Comments
                             */
                            $comments = $this->general_model->get_all("comments", null, null, ["poll_id" => $poll->id]);
                            $comment_ids = [];
                            if (!empty($comments)) :
                                foreach ($comments as $comment) :
                                    $comment_ids[] = $comment->id;
                                endforeach;
                                $this->general_model->deleteBulk("comments", "id", $comment_ids);
                            endif;
                            /**
                             * Delete Poll Votes
                             */
                            $votes = $this->general_model->get_all("votes", null, null, ["poll_id" => $poll->id]);
                            $vote_ids = [];
                            if (!empty($votes)) :
                                foreach ($votes as $vote) :
                                    $vote_ids[] = $vote->id;
                                endforeach;
                                $this->general_model->deleteBulk("votes", "id", $vote_ids);
                            endif;
                        endforeach;
                    endif;
                    $this->alert["status"] = TRUE;
                    $this->alert["title"] = lang("successfully");
                    $this->alert["message"] = lang("deleted");
                    $this->response($this->alert, MY_Controller::HTTP_OK);
                endif;
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function polls_get($id = null)
    {
        $getData = $this->get(null, true);
        if (!empty($getData["category"])) :
            $category_id = $this->general_model->get("categories", "id", ["seo_url" => $getData["category"], "isActive" => 1]);
            unset($getData["category"]);
        endif;
        if (!empty($category_id->id) && !$this->general_model->rowCount("polls", ["category_id" => $category_id->id])) :
            $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
        endif;
        $wheres = [];
        if (!empty($id)) :
            $wheres["polls.id"] = $id;
        endif;

        if (!empty($category_id->id)) :
            $wheres["polls.category_id"] = $category_id->id;
        endif;
        $data = $this->general_model->getRows("polls", $wheres, $getData, "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type,users.picture author_photo,users.role_id role_id", "polls.id DESC", [], ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]], [], [], true, "polls.id");
        $recordsFiltered = $this->general_model->countFiltered("polls", [], (!empty($getData) ? $getData : []), "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type, users.picture author_photo,users.role_id role_id", "polls.id DESC", [], ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]], [], [], true, "polls.id");
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
        $this->alert["recordsTotal"] = $this->general_model->rowCount("polls", []);
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

    public function poll_voters_get($id = null)
    {

        $getData = $this->get(null, true);
        $poll = $this->general_model->get("polls", null, ["id" => $id, "isActive" => 1]);
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

    /**
     * Comments
     */
    public function comments_datatable_post($id = null)
    {
        // return response if token is valid
        $output = [];
        if ($this->token) :
            if (!isAllowedViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $items = $this->general_model->getRows("comments", ["comments.poll_id" => $id], $this->post(null, true), "comments.*,users.username commenter_username", "comments.id DESC", [], ["users" => ["comments.user_id = users.id", "LEFT"]], [], [], true, "comments.id");
            $data = [];
            if (!empty($items)) :
                foreach ($items as $item) :
                    $data[] = ["id" => $item->id, "commenter_username" => $item->commenter_username, "comment" => $item->comment, "lang" => $item->lang, "isActive" => $item->isActive, "createdAt" => turkishDate("d F Y, l H:i:s", $item->createdAt), "updatedAt" => turkishDate("d F Y, l H:i:s", $item->updatedAt), "actions" => $item->id];
                endforeach;
            endif;
            $output = [
                "recordsTotal" => $this->general_model->rowCount("comments", ["poll_id" => $id]),
                "recordsFiltered" => $this->general_model->countFiltered("comments", ["poll_id" => $id], (!empty($this->post(null, true)) ? $this->post(null, true) : [])),
                "data" => $data,
            ];
        endif;
        // Output to JSON format
        return $this->response($output, MY_Controller::HTTP_OK);
    }

    public function comments_isactive_put($id = null)
    {
        if ($this->token) :
            if (!isAllowedUpdateViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $isActive = boolval($this->put("isActive", true)) === true ? 1 : 0;
            if ($this->general_model->update("comments", ["id" => $id], ["isActive" => $isActive])) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("status_updated");
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function comments_delete_delete($id = null)
    {
        if ($this->token) :
            if (!isAllowedDeleteViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if ($this->general_model->delete("comments", ["id" => $id])) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("deleted");
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function comments_delete_post()
    {
        if ($this->token) :
            if (!isAllowedDeleteViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if (!empty($this->post("id", true))) :
                $ids = @explode(",", $this->post("id", true));
                if (!empty($ids) && $this->general_model->deleteBulk("comments", "id", $ids)) :
                    $this->alert["status"] = TRUE;
                    $this->alert["title"] = lang("successfully");
                    $this->alert["message"] = lang("deleted");
                    $this->response($this->alert, MY_Controller::HTTP_OK);
                endif;
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Votes
     */
    public function votes_datatable_post($id = null)
    {
        // return response if token is valid
        $output = [];
        if ($this->token) :
            if (!isAllowedViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $wheres = ["votes.poll_id" => $id];
            if ($this->token->role_id != 1) :
                $wheres["votes.voterType"] = 1;
            endif;
            $items = $this->general_model->getRows("votes", $wheres, $this->post(null, true), "votes.*,users.username voter_username,users.type voter_type, users.picture voter_photo,users.role_id role_id,answers.answer voter_answer", "votes.id DESC", [], ["users" => ["votes.user_id = users.id", "LEFT"], "answers" => ["answers.id = votes.answer_id", "LEFT"]], [], [], true, "votes.id");
            $data = [];
            if (!empty($items)) :
                foreach ($items as $item) :
                    $data[] = ["id" => $item->id, "voter_username" => $item->voter_username, "voter_answer" => $item->voter_answer, "gender" => $item->gender, "age" => $item->age, "city" => $item->city, "district" => $item->district, "neighborhood" => $item->neighborhood, "quarter" => $item->quarter, "address" => $item->address, "device" => $item->device, "os" => $item->os, "browser" => $item->browser, "ip" => $item->ip, "createdAt" => turkishDate("d F Y, l H:i:s", $item->createdAt), "updatedAt" => turkishDate("d F Y, l H:i:s", $item->updatedAt), "actions" => $item->id];
                endforeach;
            endif;
            $output = [
                "recordsTotal" => $this->general_model->rowCount("votes", $wheres),
                "recordsFiltered" => $this->general_model->countFiltered("votes", $wheres, (!empty($this->post(null, true)) ? $this->post(null, true) : []), "votes.*,users.username voter_username,users.type voter_type, users.picture voter_photo,users.role_id role_id", "votes.id DESC", [], ["users" => ["votes.user_id = users.id", "LEFT"], "answers" => ["answers.id = votes.answer_id", "LEFT"]], [], [], true, "votes.id"),
                "data" => $data,
            ];
        endif;
        // Output to JSON format
        return $this->response($output, MY_Controller::HTTP_OK);
    }

    /**
     * Export To Excel Votes
     */
    public function votes_export_to_excel_get($id = null)
    {
        if ($this->token) :
            if (!isAllowedViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $user = $this->general_model->get("users", null, ["id" => $this->token->id]);
            if ($user->role_id == 1) :
                $poll = $this->general_model->get("polls", null, ["id" => $id]);
                $votes = $this->general_model->get_all("votes", "votes.*,users.username voter_username,users.type voter_type, users.picture voter_photo,users.role_id role_id,answers.answer voter_answer,users.phone voter_phone", "votes.id DESC", ["votes.poll_id" => $poll->id, "users.role_id!=" => 1, "votes.voterType" => 1], [], ["users" => ["votes.user_id = users.id", "LEFT"], "answers" => ["answers.id = votes.answer_id", "LEFT"]], [], [], true, "votes.id");
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', lang("username"));
                $sheet->setCellValue('B1', lang("answer"));
                $sheet->setCellValue('C1', lang("email"));
                $sheet->setCellValue('D1', lang("phone"));
                $sheet->setCellValue('E1', lang("age"));
                $sheet->setCellValue('F1', lang("gender"));
                $sheet->setCellValue('G1', lang("city"));
                $sheet->setCellValue('H1', lang("district"));
                $sheet->setCellValue('I1', lang("neighborhood"));
                $sheet->setCellValue('J1', lang("quarter"));
                $sheet->setCellValue('K1', lang("address"));
                $sheet->setCellValue('L1', lang("device"));
                $sheet->setCellValue('M1', lang("os"));
                $sheet->setCellValue('N1', lang("browser"));
                $sheet->setCellValue('O1', lang("ip"));
                $rows = 2;
                if (!empty($votes)) :
                    foreach ($votes as $excelData) :
                        $sheet->setCellValue('A' . $rows, $excelData->voter_username);
                        $sheet->setCellValue('B' . $rows, $excelData->voter_answer);
                        $sheet->setCellValue('C' . $rows, $excelData->email);
                        $sheet->setCellValue('D' . $rows, $excelData->voter_phone);
                        $sheet->setCellValue('E' . $rows, $excelData->age);
                        $sheet->setCellValue('F' . $rows, $excelData->gender);
                        $sheet->setCellValue('G' . $rows, $excelData->city);
                        $sheet->setCellValue('H' . $rows, $excelData->district);
                        $sheet->setCellValue('I' . $rows, $excelData->neighborhood);
                        $sheet->setCellValue('J' . $rows, $excelData->quarter);
                        $sheet->setCellValue('K' . $rows, $excelData->address);
                        $sheet->setCellValue('L' . $rows, $excelData->device);
                        $sheet->setCellValue('M' . $rows, $excelData->os);
                        $sheet->setCellValue('N' . $rows, $excelData->browser);
                        $sheet->setCellValue('O' . $rows, $excelData->ip);
                        $rows++;
                    endforeach;
                    $writer = new Xlsx($spreadsheet);
                    $filename = $poll->seo_url . "-votes-" . time() . ".xlsx";
                    $writer->save(FCPATH . "uploads/$this->viewFolder/voters/$filename");
                    $this->alert["status"] = TRUE;
                    $this->alert["title"] = lang("successfully");
                    $this->alert["message"] = lang("successfully");
                    $this->alert["data"] = ["url" => base_url("uploads/$this->viewFolder/voters/$filename")];
                    $this->response($this->alert, MY_Controller::HTTP_OK);
                endif;
                $this->alert["message"] = lang("notFound");
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Add Fake Votes
     */
    public function add_fake_votes_post($id = null)
    {
        if ($this->token) :
            $user = $this->general_model->get("users", null, ["id" => $this->token->id, "role_id" => 1]);
            if (empty($user)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $poll = $this->general_model->get("polls", null, ["id" => $id]);
            if (empty($poll)) :
                $this->alert["message"] = lang("notFound");
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $answers = $this->general_model->get_all("answers", null, "id ASC", ["poll_id" => $poll->id]);
            if (empty($answers)) :
                $this->alert["message"] = lang("notFound");
                $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
            endif;
            $fakeVotes = $this->post("fake_votes", true);
            $answer = $this->post("answer", true);

            if (empty($fakeVotes) || empty($answer)) :
                $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
            endif;

            $fakeVotes = intval($fakeVotes);
            $answer = intval($answer);
            $batchData = [];
            $genders = ["Erkek", "Kadın", "Diğer"];
            for ($i = 0; $i < $fakeVotes; $i++) :
                $randomCity = @$this->general_model->get_all("iller", null, "RAND()", [])[0]->il_adi;
                $randomDistrict = @$this->general_model->get_all("ilceler", null, "RAND()", ["il_id" => $randomCity->id])[0]->ilce_adi;
                $randomNeighborhood = @$this->general_model->get_all("semtler", null, "RAND()", ["ilce_id" => $randomDistrict->id])[0]->semt_adi;
                $randomQuarter = @$this->general_model->get_all("mahalleler", null, "RAND()", ["semt_id" => $randomNeighborhood->id])[0]->mahalle_adi;
                // Generate random values for each field
                $randomGender = $genders[array_rand($genders)];
                $randomBirthDate = date("Y-m-d", strtotime("-" . mt_rand(0, 60) . " years"));
                $randomAge = @calculate_age($randomBirthDate);
                $batchData[] = [
                    "user_id" => $user->id,
                    "poll_id" => $poll->id,
                    "answer_id" => $answer,
                    "author_id" => $poll->author,
                    "isActive" => 1,
                    "gender" => $randomGender,
                    "birth_date" => $randomBirthDate,
                    "age" => $randomAge,
                    "city" => $randomCity,
                    "district" => $randomDistrict,
                    "neighborhood" => $randomNeighborhood,
                    "quarter" => $randomQuarter,
                    "address" => null,
                    "device" => $this->agent->is_mobile() ? $this->agent->mobile() : $this->agent->platform(),
                    "browser" => $this->agent->browser(),
                    "os" => $this->agent->platform(),
                    "ip" => check_ip(),
                    "voterType" => 0,
                ];
            endfor;
            if (!empty($batchData)) :
                $this->general_model->insert_batch("votes", $batchData);
                $this->general_model->update("polls", ["id" => $poll->id], ["votes" => $poll->votes + $fakeVotes]);
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("fakeVotesSaved");
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
            $this->alert["message"] = lang("notFound");
            $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    /**
     * Description : Get Poll and its statistics
     */
    public function poll_statistic_get($id = null)
    {
        if ($this->token) :
            $wheres = [];
            if (!empty($id)) :
                $wheres["polls.id"] = $id;
            endif;
            $data = $this->general_model->get("polls", "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,polls.votes votes_count,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type,users.picture author_photo,users.role_id role_id", $wheres, ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]]);
            if (!empty($data)) :
                $data->answers = $this->general_model->get_all("answers", null, "id ASC", ["poll_id" => $data->id]);
                $data->voters = $this->general_model->get_all("votes", "votes.*,users.username voter_username,users.type voter_type, users.picture voter_photo,users.role_id role_id,answers.answer answer", "votes.id DESC", ["votes.poll_id" => $data->id, "votes.voterType" => 1], [], ["users" => ["votes.user_id = users.id", "LEFT"], "answers" => ["votes.answer_id = answers.id", "LEFT"]], [], [], true, "votes.id");
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
                        if ($this->general_model->get("votes", null, ["poll_id" => $data->id])) :
                            $data->answers[$k]->voted_answer = $this->general_model->get("votes", null, ["poll_id" => $data->id, "answer_id" => $v->id]);
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
}
