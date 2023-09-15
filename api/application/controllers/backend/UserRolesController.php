<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserRolesController extends MY_Controller
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

            $wheres = ["isActive" => 1];
            if ($this->check_level) :
                $wheres["id !="] = 1;
            endif;

            $roles = $this->general_model->get_all("user_roles", null, null, $wheres);
            if (!empty($id)) :
                if ($this->check_level && $id == 1) :
                    $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
                endif;
                $wheres["id"] = $id;
                $roles = $this->general_model->get("user_roles", null, $wheres);
            endif;
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("received");
            $this->alert["user_roles"] = $roles;
            $this->alert["controllers"] = getControllerList();
            $this->response($this->alert, MY_Controller::HTTP_OK);
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
                $wheres["id !="] = 1;
            endif;
            $items = $this->general_model->getRows("user_roles", $wheres, $this->post(null, true));
            $data = [];
            if (!empty($items)) :
                foreach ($items as $item) :
                    $data[] = ["rank" => $item->rank, "id" => $item->id, "title" => $item->title, "permissions" => $item->permissions, "isActive" => $item->isActive, "createdAt" => turkishDate("d F Y, l H:i:s", $item->createdAt), "updatedAt" => turkishDate("d F Y, l H:i:s", $item->updatedAt), "actions" => $item->id];
                endforeach;
            endif;
            $output = [
                "recordsTotal" => $this->general_model->rowCount("user_roles", $wheres),
                "recordsFiltered" => $this->general_model->countFiltered("user_roles", $wheres, (!empty($this->post(null, true)) ? $this->post(null, true) : [])),
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
            if ($this->check_level && $id == 1) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if ($this->general_model->update("user_roles", ["id" => $id], ["rank" => $this->put('rank', true)])) :
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
            if ($this->check_level && $id == 1) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $isActive = boolval($this->put("isActive", true)) === true ? 1 : 0;
            if ($this->general_model->update("user_roles", ["id" => $id], ["isActive" => $isActive])) :
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
            $data["rank"] = $this->general_model->rowCount("user_roles") + 1;
            if (!empty($data["permissions"])) :
                $data["permissions"] = json_encode($data["permissions"]);
            endif;
            if ($this->general_model->add("user_roles", $data)) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("saved");
                $this->response($this->alert, MY_Controller::HTTP_OK);
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
                if ($this->check_level && $id == 1) :
                    $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
                endif;
                $userRole = $this->general_model->get("user_roles", null, ["id" => $id]);
                if (!empty($userRole)) :
                    $data = $this->post(null, true);
                    if (!empty($data["permissions"])) :
                        $data["permissions"] = json_encode($data["permissions"]);
                    endif;
                    if ($this->general_model->update("user_roles", ["id" => $id], $data)) :
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
            if ($this->check_level && $id == 1) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            if ($this->general_model->delete("user_roles", ["id" => $id, "isCover" => 0])) :
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
                if ($this->check_level && in_array(1, $ids)) :
                    $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
                endif;
                if ($this->general_model->deleteBulk("user_roles", "id", $ids)) :
                    $this->alert["status"] = TRUE;
                    $this->alert["title"] = lang("successfully");
                    $this->alert["message"] = lang("deleted");
                    $this->response($this->alert, MY_Controller::HTTP_OK);
                endif;
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }
}
