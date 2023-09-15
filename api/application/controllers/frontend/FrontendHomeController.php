<?php
defined('BASEPATH') or exit('No direct script access allowed');



class FrontendHomeController extends MY_Controller
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
        $this->alert = [
            "status" => FALSE,
            "title" => lang("error"),
            "message" => lang("unauthorized"),
            "data" => null
        ];
    }

    public function settings_get()
    {
        $settings = $this->general_model->get("settings", null, ["lang" => $this->defaultLang, "isActive" => 1]);
        if ($settings) :
            $settings->address_informations = json_decode(html_entity_decode($settings->address_informations));
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $settings;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }

    public function menus_get()
    {
        $joins = ["categories" => ["categories.id = menus.category_id", "left"], "polls" => ["polls.id =  menus.poll_id", "left"], "pages" => ["pages.id = menus.page_id", "left"]];
        $select = "menus.id,menus.title,menus.url,menus.target,menus.position,menus.top_id,menus.showCategories,categories.seo_url category_url,polls.seo_url poll_url,pages.seo_url page_url";
        $menus = $this->general_model->get_all("menus", $select, "menus.rank ASC", ["menus.lang" => $this->defaultLang, "menus.isActive" => 1, "menus.position" => "HEADER"], [], $joins, [], [], true, "menus.id");
        $footerMenus = $this->general_model->get_all("menus", $select, "menus.rank ASC", ["menus.lang" => $this->defaultLang, "menus.isActive" => 1, "menus.position" => "FOOTER"], [], $joins, [], [], true, "menus.id");
        if ($menus || $footerMenus) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["menus"] = $menus;
            $this->alert["footer_menus"] = $footerMenus;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }

    public function categories_get($seo_url = null)
    {
        if ($seo_url) :
            $categories = $this->general_model->get("categories", null, ['seo_url' => $seo_url, 'lang' => $this->defaultLang, "isActive" => 1]);
        else :
            $categories = $this->general_model->get_all("categories", null, null, ["lang" => $this->defaultLang, "isActive" => 1]);
        endif;

        if ($categories) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $categories;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }

    public function slides_get()
    {
        $joins = ["categories" => ["categories.id = slides.category_id", "left"], "polls" => ["polls.id = slides.poll_id", "left"], "pages" => ["pages.id = slides.page_id", "left"]];
        $select = "slides.id,slides.title,slides.description,slides.image,slides.url,polls.seo_url poll_url,categories.seo_url category_url,pages.seo_url page_url,slides.target";
        $slides = $this->general_model->get_all("slides", $select, "slides.rank", ["slides.lang" => $this->defaultLang, "slides.isActive" => 1], [], $joins, [], [], true, "slides.id");
        if ($slides) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $slides;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }

    public function pages_get()
    {
        $pages = $this->general_model->get_all("pages", "id,title,seo_url", null, ["lang" => $this->defaultLang, "isActive" => 1]);
        if ($pages) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $pages;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }


    public function featured_users_get()
    {
        $this->alert["message"] = lang("notFound");
        $wheres = ["users.lang" => $this->defaultLang, "users.isActive" => 1, "users.type" => 'CORPORATE', 'users.hidden' => 0, "users.role_id!=" => 1];
        $join = [];
        if ($this->token) :
            $con = !empty($this->token->id) ? array('id' => $this->token->id, 'isActive' => 1) : NULL;
            $user = $this->general_model->get("users", null, $con);
            $wheres["followings.user_id"] = null;
            $join = ["followings" => ["followings.following_id = users.id AND followings.user_id =" . $user->id, "LEFT"]];
        endif;
        $featured_users = $this->general_model->get_all(
            "users",
            "users.*,",
            "RAND()",
            $wheres,
            [],
            $join,
            [5],
            [],
            true,
            "users.id"
        );

        if ($featured_users) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $featured_users;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_BAD_REQUEST);
    }

    public function follow_users_get()
    {
        if ($this->token) {
            $wheres = ["users.lang" => $this->defaultLang, "users.isActive" => 1, 'users.hidden' => 0, "users.role_id!=" => 1];

            if (!empty($this->token->id)) {
                $con = ['id' => $this->token->id, 'isActive' => 1];
                $user = $this->general_model->get("users", null, $con);

                if ($user) {
                    $this->alert["message"] = lang("notFound");
                    $wheres["followings.user_id"] = null;

                    $featured_users = $this->general_model->get_all(
                        "users",
                        "users.*,
                    (SELECT COUNT(id) FROM followers WHERE followers.user_id = users.id) follower_count,
                    (SELECT COUNT(id) FROM followings WHERE followings.user_id = users.id) following_count,
                    (SELECT COUNT(id) FROM comments WHERE comments.user_id = users.id) comment_count,
                    (SELECT COUNT(id) FROM polls WHERE polls.author = users.id) poll_count",
                        "RAND()",
                        $wheres,
                        [],
                        ["followings" => ["followings.following_id = users.id AND followings.user_id =" . $user->id, "LEFT"]],
                        [20],
                        [],
                        true,
                        "users.id"
                    );

                    if ($featured_users) {
                        $this->alert["status"] = TRUE;
                        $this->alert["title"] = lang("successfully");
                        $this->alert["message"] = lang("successfully");
                        $this->alert["data"] = $featured_users;
                        $this->response($this->alert, MY_Controller::HTTP_OK);
                    }
                }
            }

            $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
        }

        $this->response($this->alert, MY_Controller::HTTP_UNAUTHORIZED);
    }

    public function featured_polls_get()
    {
        $this->alert["message"] = lang("notFound");
        $wheres = ["polls.lang" => $this->defaultLang, "polls.isActive" => 1, "polls.end_date>=" => date("Y-m-d H:i:s"),"polls.pinned" => 1];
        $featured_polls = $this->general_model->get_all("polls", "(SELECT COUNT(*) FROM comments WHERE poll_id = polls.id AND isActive = 1) comments_count,polls.*,categories.title category_title, categories.seo_url category_seo_url,categories.id category_id,categories.icon category_icon,users.username author_username,users.type author_type,users.picture author_photo,users.role_id role_id", "RAND()", $wheres, [], ["categories" => ["categories.id = polls.category_id", "LEFT"], "users" => ["polls.author = users.id", "LEFT"]], [3], [], true, "polls.id");
        if ($featured_polls) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $featured_polls;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
    }

    public function counters_get()
    {
        $categories = $this->general_model->rowCount("categories", ["lang" => $this->defaultLang, "isActive" => 1]);
        $polls = $this->general_model->rowCount("polls", ["lang" => $this->defaultLang, "isActive" => 1]);
        $comments = $this->general_model->rowCount("comments", ["lang" => $this->defaultLang, "isActive" => 1]);
        $votes = $this->general_model->rowCount("votes", ["lang" => $this->defaultLang, "isActive" => 1]);
        $data = ["categories" => $categories, "polls" => $polls, "comments" => $comments, "votes" => $votes];
        $this->alert["status"] = TRUE;
        $this->alert["title"] = lang("successfully");
        $this->alert["message"] = lang("successfully");
        $this->alert["data"] = $data;
        $this->response($this->alert, MY_Controller::HTTP_OK);
    }

    public function popular_categories_get()
    {
        $categories = $this->general_model->get_all("categories", null, null, ["lang" => $this->defaultLang, "isActive" => 1, "isPopular" => 1], [], [], [5]);

        if ($categories) :
            $this->alert["status"] = TRUE;
            $this->alert["title"] = lang("successfully");
            $this->alert["message"] = lang("successfully");
            $this->alert["data"] = $categories;
            $this->response($this->alert, MY_Controller::HTTP_OK);
        endif;
        $this->response($this->alert, MY_Controller::HTTP_NOT_FOUND);
    }
}
