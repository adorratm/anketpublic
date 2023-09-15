<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingsController extends MY_Controller
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
        $this->viewFolder = "settings";
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
                $settings = $this->general_model->get("settings", null, ["id" => $id]);
                $settings->address_informations = json_decode($settings->address_informations, true);
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("received");
                $this->alert["data"] = $settings;
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
            $this->alert["message"] = lang("notFound");
            $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
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
            $items = $this->general_model->getRows("settings", [], $this->post(null, true));
            $data = [];
            if (!empty($items)) :
                foreach ($items as $item) :
                    $data[] = ["rank" => $item->rank, "id" => $item->id, "company_name" => $item->company_name, "lang" => $item->lang, "isActive" => $item->isActive, "createdAt" => turkishDate("d F Y, l H:i:s", $item->createdAt), "updatedAt" => turkishDate("d F Y, l H:i:s", $item->updatedAt), "actions" => $item->id];
                endforeach;
            endif;
            $output = [
                "recordsTotal" => $this->general_model->rowCount("settings"),
                "recordsFiltered" => $this->general_model->countFiltered("settings", [], (!empty($this->post(null, true)) ? $this->post(null, true) : [])),
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
            if ($this->general_model->update("settings", ["id" => $id], ["rank" => $this->put('rank', true)])) :
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
            if ($this->general_model->update("settings", ["id" => $id], ["isActive" => $isActive])) :
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
            $logo = upload_picture("logo", "uploads/$this->viewFolder", [], "*");
            $mobile_logo = upload_picture("mobile_logo", "uploads/$this->viewFolder", [], "*");
            $favicon = upload_picture("favicon", "uploads/$this->viewFolder", [], "*");
            $banner = upload_picture("banner", "uploads/$this->viewFolder", [], "*");
            if ($logo["success"]) :
                $data["logo"] = $logo["file_name"];
            endif;
            if ($mobile_logo["success"]) :
                $data["mobile_logo"] = $mobile_logo["file_name"];
            endif;
            if ($favicon["success"]) :
                $data["favicon"] = $favicon["file_name"];
            endif;
            if ($banner["success"]) :
                $data["banner"] = $banner["file_name"];
            endif;
            if (!empty($data["address_informations"])) :
                foreach (json_decode($data["address_informations"]) as $adKey => $adValue) :
                    json_decode($data["address_informations"])[$adKey]->map = htmlspecialchars(html_entity_decode(json_decode($data["address_informations"])[$adKey]->map));
                endforeach;
                $data["address_informations"] = json_encode($data["address_informations"]);
            endif;
            $data["rank"] = $this->general_model->rowCount("settings") + 1;
            if ($this->general_model->add("settings", $data)) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("saved");
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
            if ($logo["success"]) :
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$data["logo"]}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$data["logo"]}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$data["logo"]}");
                endif;
            endif;
            if ($mobile_logo["success"]) :
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$data["mobile_logo"]}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$data["mobile_logo"]}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$data["mobile_logo"]}");
                endif;
            endif;
            if ($favicon["success"]) :
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$data["favicon"]}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$data["favicon"]}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$data["favicon"]}");
                endif;
            endif;
            if ($banner["success"]) :
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$data["banner"]}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$data["banner"]}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$data["banner"]}");
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
                $settings = $this->general_model->get("settings", null, ["id" => $id]);
                if (!empty($settings)) :
                    $data = $this->post(null, true);
                    $logo = upload_picture("logo", "uploads/$this->viewFolder", [], "*");
                    $mobile_logo = upload_picture("mobile_logo", "uploads/$this->viewFolder", [], "*");
                    $favicon = upload_picture("favicon", "uploads/$this->viewFolder", [], "*");
                    $banner = upload_picture("banner", "uploads/$this->viewFolder", [], "*");
                    if ($logo["success"]) :
                        $data["logo"] = $logo["file_name"];
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}");
                        endif;
                    endif;
                    if ($mobile_logo["success"]) :
                        $data["mobile_logo"] = $mobile_logo["file_name"];
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}");
                        endif;
                    endif;
                    if ($favicon["success"]) :
                        $data["favicon"] = $favicon["file_name"];
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}");
                        endif;
                    endif;
                    if ($banner["success"]) :
                        $data["banner"] = $banner["file_name"];
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->banner}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->banner}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->banner}");
                        endif;
                    endif;
                    if (!empty($data["address_informations"])) :
                        foreach (json_decode($data["address_informations"]) as $adKey => $adValue) :
                            json_decode($data["address_informations"])[$adKey]->map = htmlspecialchars(html_entity_decode(json_decode($data["address_informations"])[$adKey]->map));
                        endforeach;
                        $data["address_informations"] = json_encode($data["address_informations"]);
                    endif;
                    if ($this->general_model->update("settings", ["id" => $id], $data)) :
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
            $settings = $this->general_model->get("settings", null, ["id" => $id]);
            if ($this->general_model->delete("settings", ["id" => $id])) :
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}");
                endif;
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}");
                endif;
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}");
                endif;
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->banner}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->banner}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->banner}");
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
                $settings = $this->general_model->get_all("settings", null, null, [], [], [], [], $ids);
                if (!empty($settings)) :
                    foreach ($settings as $key => $value) :
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$value->logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$value->logo}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$value->logo}");
                        endif;
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$value->mobile_logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$value->mobile_logo}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$value->mobile_logo}");
                        endif;
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$value->favicon}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$value->favicon}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$value->favicon}");
                        endif;
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$value->banner}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$value->banner}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$value->banner}");
                        endif;
                    endforeach;
                endif;
                if ($this->general_model->deleteBulk("settings", "id", $ids)) :
                    $this->alert["status"] = TRUE;
                    $this->alert["title"] = lang("successfully");
                    $this->alert["message"] = lang("deleted");
                    $this->response($this->alert, MY_Controller::HTTP_OK);
                endif;
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function uploadImage_post()
    {
        if ($this->token) :
            $image = upload_picture("file", "uploads/$this->viewFolder/tinymce", [], "*");
            if ($image["success"]) :
                $this->alert["status"] = TRUE;
                $this->alert["title"] = lang("successfully");
                $this->alert["message"] = lang("uploaded");
                $this->alert["location"] = base_url("uploads/$this->viewFolder/tinymce/" . $image["file_name"]);
                $this->response($this->alert, MY_Controller::HTTP_OK);
            endif;
        endif;
        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }
}
