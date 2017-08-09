<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'/controllers/base_controller.php');

class Forgot_password extends Base_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->user_id = $this->session->userdata('user_id');	// note: it is possible this value will not be set

		$this->facebook = new Facebook();
		$this->load->library('User');
	}
	
	public function index()
	{
		$data = array('facebook_login_url' => $this->facebook->get_login_url()['url']);
		
		if($this->user_id) {
			$common_data = $this->get_common_data();
			$data = array_merge($data, $common_data);
		}
		
		$this->load->view('header_login', $data);
		$this->load->view('forgot_password');
		$this->load->view('footer');
	}
	
	public function reset()
	{
		$email_address = $this->input->post('email_address');
		$ret = $this->user->send_password_reset_email($email_address);
		
		if($ret['success'])
		{
		   $this->display_message->success('Please check your email to reset your password.');
		}
		else 
		{
		   $this->display_message->error($ret['message']);
		}
		
		redirect('forgot-password');
	}
	
	public function update($user_id, $token)
	{
		$ret = $this->user->verify_forgot_password_token($user_id, $token, false);
		
		if($ret['success'])
		{
			$data = array('facebook_login_url' => $this->facebook->get_login_url()['url'],
				'user_id' => $user_id,
				'token' => $token);
			
			if($this->user_id) {
				$common_data = $this->get_common_data();
				$data = array_merge($data, $common_data);
			}

			$this->load->view('header_login', $data);
			$this->load->view('update_password', $data);
			$this->load->view('footer');
		}
		else 
		{
			$this->display_message->error($ret['message']);
			redirect('forgot-password');
		}
	}
	
	public function update_password()
	{
		$user_id = $this->input->post('user_id');
		$token = $this->input->post('token');
		$password = $this->input->post('password');

		$ret = $this->user->verify_forgot_password_token($user_id, $token, false);
		
		if($ret['success'])
		{
		   $ret = $this->user->update_forgotten_password($user_id, $password);
		   
		   if($ret['success'])
		   {
		   	  $this->user->verify_forgot_password_token($user_id, $token, true);
		   	  $this->display_message->success('Password successfully updated.');
		   	  redirect('/index');
		   }
		   else
		   {
		   	  $this->display_message->error($ret['message']);
		   	  redirect('/forgot-password/update/' . $user_id . '/' . $token);
		   }
		}
		else
		{
			$this->display_message->error($ret['message']);
			redirect('/forgot-password');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */