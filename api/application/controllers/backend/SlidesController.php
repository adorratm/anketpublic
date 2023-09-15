<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SlidesController extends MY_Controller
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
        $this->viewFolder = "slides";
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
            if (!isAllowedViewModule($this->token, $this->moduleName)) {
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            }
            if (!empty($id)) :
                $slides = $this->general_model->get("slides", null, ["id" => $id]);
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("received");
                $this->alert["data"] = $slides;
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
            $items = $this->general_model->getRows("slides", [], $this->post(null, true));
            $data = [];
            if (!empty($items)) :
                foreach ($items as $item) :
                    $data[] = ["rank" => $item->rank, "id" => $item->id, "title" => $item->title, "lang" => $item->lang, "isActive" => $item->isActive, "createdAt" => turkishDate("d F Y, l H:i:s", $item->createdAt), "updatedAt" => turkishDate("d F Y, l H:i:s", $item->updatedAt), "actions" => $item->id];
                endforeach;
            endif;
            $output = [
                "recordsTotal" => $this->general_model->rowCount("slides"),
                "recordsFiltered" => $this->general_model->countFiltered("slides", [], (!empty($this->post(null, true)) ? $this->post(null, true) : [])),
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
            if ($this->general_model->update("slides", ["id" => $id], ["rank" => $this->put('rank', true)])) :
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
            if ($this->general_model->update("slides", ["id" => $id], ["isActive" => $isActive])) :
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
            $data["page_id"] = $this->post("page_id", true) ? $this->post("page_id", true) : null;
            $data["poll_id"] = $this->post("poll_id", true) ? $this->post("poll_id", true) : null;
            $data["category_id"] = $this->post("category_id", true) ? $this->post("category_id", true) : null;
            $image = upload_picture("img_url", "uploads/$this->viewFolder", ["width" => 1920, "height" => 700], "*");
            if ($image["success"]) :
                $data["image"] = $image["file_name"];
            endif;
            $data["rank"] = $this->general_model->rowCount("slides") + 1;
            if ($this->general_model->add("slides", $data)) :
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
                $slide = $this->general_model->get("slides", null, ["id" => $id]);
                if (!empty($slide)) :
                    $data = $this->post(null, true);
                    $data["page_id"] = $this->post("page_id", true) ? $this->post("page_id", true) : null;
                    $data["poll_id"] = $this->post("poll_id", true) ? $this->post("poll_id", true) : null;
                    $data["category_id"] = $this->post("category_id", true) ? $this->post("category_id", true) : null;
                    $image = upload_picture("img_url", "uploads/$this->viewFolder", ["width" => 1920, "height" => 700], "*");
                    if ($image["success"]) :
                        if ($slide->image && file_exists("uploads/$this->viewFolder/$slide->image")) :
                            unlink("uploads/$this->viewFolder/$slide->image");
                        endif;
                        $data["image"] = $image["file_name"];
                    endif;
                    if ($this->general_model->update("slides", ["id" => $id], $data)) :
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
            $slide = $this->general_model->get("slides", null, ["id" => $id]);
            if ($this->general_model->delete("slides", ["id" => $id])) :
                if ($slide->image && file_exists("uploads/$this->viewFolder/$slide->image")) :
                    unlink("uploads/$this->viewFolder/$slide->image");
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
                $slides = $this->general_model->get_all("slides", null, null, [], [], [], [], ["id" => $ids]);
                if (!empty($ids) && $this->general_model->deleteBulk("slides", "id", $ids)) :
                    if (!empty($slides)) :
                        foreach ($slides as $slide) :
                            if ($slide->image && file_exists("uploads/$this->viewFolder/$slide->image")) :
                                unlink("uploads/$this->viewFolder/$slide->image");
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
}
