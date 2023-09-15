<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CategoriesController extends MY_Controller
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
        $this->viewFolder = "categories";
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
                $categories = $this->general_model->get("categories", null, ["id" => $id]);
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("received");
                $this->alert["data"] = $categories;
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
            $items = $this->general_model->getRows("categories", [], $this->post(null, true));
            $data = [];
            if (!empty($items)) :
                foreach ($items as $item) :
                    $data[] = ["rank" => $item->rank, "id" => $item->id, "title" => $item->title, "lang" => $item->lang, "isActive" => $item->isActive, "createdAt" => turkishDate("d F Y, l H:i:s", $item->createdAt), "updatedAt" => turkishDate("d F Y, l H:i:s", $item->updatedAt), "actions" => $item->id];
                endforeach;
            endif;
            $output = [
                "recordsTotal" => $this->general_model->rowCount("categories"),
                "recordsFiltered" => $this->general_model->countFiltered("categories", [], (!empty($this->post(null, true)) ? $this->post(null, true) : [])),
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
            if ($this->general_model->update("categories", ["id" => $id], ["rank" => $this->put('rank', true)])) :
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
            if ($this->general_model->update("categories", ["id" => $id], ["isActive" => $isActive])) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("status_updated");
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function ispopular_put($id = null)
    {
        if ($this->token) :
            if (!isAllowedUpdateViewModule($this->token, $this->moduleName)) :
                $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
            endif;
            $isPopular = boolval($this->put("isPopular", true)) === true ? 1 : 0;
            if ($this->general_model->update("categories", ["id" => $id], ["isPopular" => $isPopular])) :
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
            $icon = upload_picture("icon_url", "uploads/$this->viewFolder", [], "*");
            if ($icon["success"]) :
                $data["icon"] = $icon["file_name"];
            endif;
            $bg = upload_picture("bg_url", "uploads/$this->viewFolder", [], "*");
            if ($bg["success"]) :
                $data["bg"] = $bg["file_name"];
            endif;
            $data["seo_url"] = seo($data["title"]);
            $data["rank"] = $this->general_model->rowCount("categories") + 1;
            if ($this->general_model->add("categories", $data)) :
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
                $category = $this->general_model->get("categories", null, ["id" => $id]);
                if (!empty($category)) :
                    $data = $this->post(null, true);
                    $icon = upload_picture("icon_url", "uploads/$this->viewFolder", [], "*");
                    if ($icon["success"]) :
                        if ($category->icon && file_exists("uploads/$this->viewFolder/$category->icon")) :
                            unlink("uploads/$this->viewFolder/$category->icon");
                        endif;
                        $data["icon"] = $icon["file_name"];
                    endif;
                    $bg = upload_picture("bg_url", "uploads/$this->viewFolder", [], "*");
                    if ($bg["success"]) :
                        if ($category->bg && file_exists("uploads/$this->viewFolder/$category->bg")) :
                            unlink("uploads/$this->viewFolder/$category->bg");
                        endif;
                        $data["bg"] = $bg["file_name"];
                    endif;
                    $data["seo_url"] = seo($data["title"]);
                    if ($this->general_model->update("categories", ["id" => $id], $data)) :
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
            $category = $this->general_model->get("categories", null, ["id" => $id]);
            if ($this->general_model->delete("categories", ["id" => $id])) :
                if ($category->icon && file_exists("uploads/$this->viewFolder/$category->icon")) :
                    unlink("uploads/$this->viewFolder/$category->icon");
                endif;
                if ($category->bg && file_exists("uploads/$this->viewFolder/$category->bg")) :
                    unlink("uploads/$this->viewFolder/$category->bg");
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
                $categories = $this->general_model->get_all("categories", null, null, [], [], [], [], ["id" => $ids]);
                if (!empty($ids) && $this->general_model->deleteBulk("categories", "id", $ids)) :
                    if (!empty($categories)) :
                        foreach ($categories as $category) :
                            if ($category->icon && file_exists("uploads/$this->viewFolder/$category->icon")) :
                                unlink("uploads/$this->viewFolder/$category->icon");
                            endif;
                            if ($category->bg &&file_exists("uploads/$this->viewFolder/$category->bg")) :
                                unlink("uploads/$this->viewFolder/$category->bg");
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
