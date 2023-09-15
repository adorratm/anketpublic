<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Predis\Client;

class Welcome extends CI_Controller
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
		$this->predis = new Client();
	}


	public function index()
	{
		$this->load->view('welcome_message');
	}



	public function queue_worker()
	{
		if (!is_cli()) {
			exit("This script can only be run from the command line.");
		}
		$apiURL = $this->config->item("SMS_API_URL");
		$apiKEY = $this->config->item("SMS_API_KEY");
		$apiSECRET = $this->config->item("SMS_API_SECRET");
		$apiPASSWORD = $this->config->item("SMS_API_PASSWORD");
		$apiORIGINATOR = $this->config->item("SMS_API_ORIGINATOR");
		$endpoint = "smspost/v1";
		$task_json_array = $this->predis->lrange("sms_queue", 0, -1);
		$messages = [];
		if (!empty($task_json_array)) :
			foreach ($task_json_array as $key => $value) :
				$task_json = json_decode($value);
				if ($task_json->sendedAt <= date("Y-m-d H:i:s")) :
					// Implement callback handling and status updates as needed
					echo "Sending SMS to $task_json->phone: $task_json->message\n";
					$messageData = new stdClass();
					$messageData->to = $task_json->phone;
					$messageData->message = $task_json->message;
					$messageData->sendedAt = $task_json->sendedAt;
					$messages[] = $messageData;
					$this->general_model->update("sms", ["id" => $task_json->id], ["status" => 1]);

					// Remove the task from the queue
					$this->predis->lrem('sms_queue', 0, $value);
				endif;
			endforeach;
			if (!empty($messages)) :
				$i = 0;
				foreach ($messages as $key => $value) :
					$xmlData = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><sms/>');
					$xmlData->addChild("username", $apiKEY);
					$xmlData->addChild("password", $apiSECRET);
					$xmlData->addChild("header", $apiORIGINATOR);
					$xmlData->addChild("validity", "2880");
					$xmlData->addChild("sendDateTime", date("Y.m.d.H.i.s", @strtotime($value->sendedAt)));
					// Create the 'messages' element here
					$messagesElement = $xmlData->addChild("messages");
					// Add child elements to 'messages' element
					$mbElement = $messagesElement->addChild("mb");
					$mbElement->addChild("no", $value->to);
					$mbElement->addChild("msg", $value->message);
					// Send the SMS using your SMS service provider's API here
					@guzzle_request($apiURL, null, $endpoint, $xmlData->asXML(), ['Content-Type' => 'text/html;charset=UTF-8', 'Accept' => 'text/html;charset=UTF-8', 'Content-Length' => strlen($xmlData->asXML()),]);
					$this->general_model->delete("sms", ["phone" => $value->to, "status" => 1]);
					$i++;
				endforeach;
			endif;
		endif;
		echo "Done\n";
	}
}
