<?php
defined('BASEPATH') or exit('No direct script access allowed');
use \chriskacerguis\RestServer\RestController;
class MY_Controller extends RestController
{
    /**
     * ---------------------------------------------------------------------------------------------
     * ...:::!!! =============================== VARIABLES =============================== !!!:::...
     * ---------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        parent::__construct();
        $lang = (!empty($this->get("lang",true)) && mb_strlen($this->get("lang",true)) == 2 ? $this->get("lang",true) : "tr");
        /**
         * Load Lang File
         */
        if (file_exists(dirname(APPPATH, 1) . '/application/language/' . $lang)) :
            $this->lang->load($lang, $lang, FALSE, TRUE, dirname(APPPATH, 1) . '/application/');
        endif;
        $locales = $this->general_model->get("languages", null, ["code" => strto("lower", $lang)]);
        setlocale(LC_ALL, $locales->code . "_" . (strto("lower|upper", $locales->code)));
        $this->defaultLang = $lang;
    }
}
