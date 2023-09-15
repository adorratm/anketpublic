<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UsersController extends MY_Controller
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
        $this->check_level = check_level(@$this->token->role_id);
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

    public function datatable_post()
    {
        // return response if token is valid
        $output = [];
        if ($this->token) :
            if (!isAllowedViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $wheres = [];
            if ($this->check_level) :
                $wheres["role_id !="] = 1;
            endif;
            $items = $this->general_model->getRows("users", $wheres, $this->post(null, true));
            $data = [];
            if (!empty($items)) :
                foreach ($items as $item) :
                    $data[] = ["rank" => $item->rank, "id" => $item->id, "first_name" => $item->first_name, "last_name" => $item->last_name, "email" => $item->email, "phone" => $item->phone, "lang" => $item->lang, "isActive" => $item->isActive, "createdAt" => turkishDate("d F Y, l H:i:s", $item->createdAt), "updatedAt" => turkishDate("d F Y, l H:i:s", $item->updatedAt), "actions" => $item->id];
                endforeach;
            endif;
            $output = [
                "recordsTotal" => $this->general_model->rowCount("users"),
                "recordsFiltered" => $this->general_model->countFiltered("users", [], (!empty($this->post(null, true)) ? $this->post(null, true) : [])),
                "data" => $data,
            ];
            // Output to JSON format
            return $this->response($output, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function rank_put($id = null)
    {
        if ($this->token) :
            if (!isAllowedUpdateViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $user = $this->general_model->get("users", null, ["id" => $id]);
            if ($this->check_level && $user->role_id == 1) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if ($this->general_model->update("users", ["id" => $id], ["rank" => $this->put('rank', true)])) :
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
            $user = $this->general_model->get("users", null, ["id" => $id]);
            if ($this->check_level && $user->role_id == 1) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $isActive = boolval($this->put("isActive", true)) === true ? 1 : 0;
            if ($this->general_model->update("users", ["id" => $id], ["isActive" => $isActive])) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("status_updated");
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

    public function delete_delete($id = null)
    {
        if ($this->token) :
            if (!isAllowedDeleteViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $user = $this->general_model->get("users", null, ["id" => $id]);
            if ($this->check_level && $user->role_id == 1) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if ($id != $this->token->id) :
                if ($this->general_model->delete("users", ["id" => $id])) :
                    if (!empty($user->picture) && file_exists("uploads/$this->viewFolder/$user->picture")) :
                        unlink("uploads/$this->viewFolder/$user->picture");
                    endif;
                    if (!empty($user->cover) && file_exists("uploads/$this->viewFolder/$user->cover")) :
                        unlink("uploads/$this->viewFolder/$user->cover");
                    endif;
                    $this->alert["status"] = TRUE;
                    $this->alert["title"] = lang("successfully");
                    $this->alert["message"] = lang("deleted");
                    $this->response($this->alert, MY_Controller::HTTP_OK);
                endif;
            endif;
            $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
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
                // Remove Own User Id
                if (($key = array_search($this->token->id, $ids)) !== false) :
                    unset($ids[$key]);
                endif;
                $users = $this->general_model->get_all("users", null, null, [], [], [], [], ["id" => $ids]);
                if (!empty($ids) && $this->general_model->deleteBulk("users", "id", $ids)) :
                    if (!empty($users)) :
                        foreach ($users as $user) :
                            if (!empty($user->picture) && file_exists("uploads/$this->viewFolder/$user->picture")) :
                                unlink("uploads/$this->viewFolder/$user->picture");
                            endif;
                            if (!empty($user->cover) && file_exists("uploads/$this->viewFolder/$user->cover")) :
                                unlink("uploads/$this->viewFolder/$user->cover");
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

    public function cities_get()
    {
        if ($this->token) :
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
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function districts_get($city_id = null)
    {
        if ($this->token) :
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
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function neighborhoods_get($district_id = null)
    {
        if ($this->token) :
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
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function quarters_get($neighborhood_id = null)
    {
        if ($this->token) :
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
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }
}
