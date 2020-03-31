<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Login extends REST_Controller {
    
	function __construct() {
        parent::__construct();
	   $this->load->database();
        $this->load->model('user');
    }

	public function index_get(){
		$this->response([
				'status' => false,
				'message' => 'Index Login',
			], REST_Controller::HTTP_OK);
	}
	public function index_post()
	{

		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$key      = $this->input->post('key');

		$row = $this->user->getUserByUsername($username)->row();

		if(isset($row)){

				$stored_hash  = $row->password;
				$status       = $row->status;
				$id           = $row->id;

				if ($this->bcrypt->check_password($password, $stored_hash)){
					
					if($status=="1"){


							$tokenx = "b3rc4";
							
							if($tokenx == $key){

								     

									$arrData = array(
										'token'=>$this->bcrypt->hash_password(rand(10,100)),
										'logintime'=>strtotime(Date("Y-m-d H:i:s")),
										'exptime'=>strtotime(Date("Y-m-d H:i:s").' +15 minutes'),
									);

									$updateToken = $this->user->updateUser($id,$arrData);
									
									$data = $this->user->getUserByUsername($username)->row();
									$this->response([
											'status' => 200,
											'message' => 'Login Success',
											'data' => $data
										], REST_Controller::HTTP_OK); 
	
							}else{
									$this->response([
											'status' => 403,
											'message' => 'Invalid Token',
										], REST_Controller::HTTP_OK); 
							}


					}else{

							$this->response([
									'status' => 403,
									'message' => 'User Not Active',
								], REST_Controller::HTTP_OK);

					}

				}else{

								$this->response([
									'status' => 403,
									'message' => 'Password Not Match',
								], REST_Controller::HTTP_OK); 

				}
 
		}else{

					$this->response([
						'status' => 403,
						'message' => 'Username Not Found',
					], REST_Controller::HTTP_OK); 
		}
	

	}





	

}
