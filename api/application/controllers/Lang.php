<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lang extends MY_Controller
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
	public function index_get($lang_code = "tr")
	{
		/**
		 * Load Lang File
		 */
		if (file_exists(dirname(APPPATH, 1) . '/application/language/' . $lang_code)) :
			$lang_data = $this->lang->load($lang_code, $lang_code, TRUE, TRUE, dirname(APPPATH, 1) . '/application/');
			$locales = $this->general_model->get("languages", null, ["code" => strto("lower", $lang_code)]);
			setlocale(LC_ALL, $locales->code . "_" . (strto("lower|upper", $locales->code)));
			$this->response([
				"status" => TRUE,
				"message" => lang("langFileFound"),
				"data" => $lang_data
			], MY_Controller::HTTP_OK);
		endif;
		$this->response([
			"status" => FALSE,
			"message" => lang("langFileNotFound"),
			"data" => null
		], MY_Controller::HTTP_NOT_FOUND);
	}
}
