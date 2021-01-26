<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));
		$this->load->model('master_model','ion_auth_model', 'admin_model');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
	}
	
	function index() {
		header('Location:http://103.146.244.139/cosmic_dev');
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
            return show_error('You must be an administrator to view this page.');
		} else {
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user) {
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
			$this->_render_page('auth/index', $this->data);
		}
	}

	function mtc(){
        $this->load->view('auth/login_mtc');
    }

	function login() {
	    $this->load->model('master_model');
	    $this->data['title'] = "Login";
	    $this->data['provinsi'] = $this->master_model->mst_provinsi($cari='')->result();
	    $this->data['jenis_industri'] = $this->master_model->mst_jenis_industri($cari='')->result();
	    $this->form_validation->set_rules('identity', 'Username', 'required');
	    $this->form_validation->set_rules('password', 'Password', 'required');
	    
	    if ($this->form_validation->run() == true) {
	        $remember = (bool) $this->input->post('remember');
	        $userdata = $this->ion_auth_model->cek_lastlogin_byusername($this->input->post('identity'));
	 	
            if($userdata!=NULL){
                $password_default = $this->bcrypt->verify('P@ssw0rd',$userdata->password);
	            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember=true)) {
	                if($password_default=='P@ssw0rd'){
	                    $url_default="/auth/change_password";
	                }else{
	                    $url_default="/dashmin/index";
	                }
	                
	                if (isset($this->ion_auth->user()->row()->url_default)) {
	                    $url_default=$this->ion_auth->user()->row()->url_default;
	                    if($url_default=="") {
	                        $url_default="/";
	                    }
	                }
	                
	                $this->session->set_flashdata('message', $this->ion_auth->messages());
	                if( $this->session->userdata('redirect_url') ) {
	                    $redirect_url = $this->session->userdata('redirect_url');
	                    $this->session->unset_userdata('redirect_url');
	                    redirect( $redirect_url , 'refresh');
	                } else if ($this->ion_auth->is_admin()){
	                    $this->session->set_userdata('url_default', '/admin/');
	                    redirect('/dashmin/index', 'refresh');
	                } else {
	                    $this->session->set_userdata('url_default', $url_default);
	                    redirect($url_default, 'refresh');
	                }
	            } else {
	                $this->session->set_flashdata('message',$this->ion_auth->errors());
	                
	                $this->data['message']='Invalid Username or Password';
	                $this->_render_page('auth/login', $this->data);
	            }
	        }else{
	            $this->session->set_flashdata('message',$this->ion_auth->errors());
	            
	            $this->data['message']='Username Not Found';
	            $this->_render_page('auth/login', $this->data);
	        }
	    } else {
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	        
	        $this->data['identity'] = array('name' => 'identity',
	            'id' => 'identity',
	            'type' => 'text',
	            'value' => $this->form_validation->set_value('identity'),
	        );
	        $this->data['password'] = array('name' => 'password',
	            'id' => 'password',
	            'type' => 'password',
	        );
	        
	        $this->_render_page('auth/login', $this->data);
	    }
	}

	function logout() {
		$this->data['title'] = "Logout";
		$logout = $this->ion_auth->logout(); 
		
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('auth/login', 'refresh');
	}

	//change password
	function change_password()
	{
		$this->load->model('master_model');
		$this->data['title']="User Page";
	    $this->data['subtitle']="Ganti Password";
	    $group = $this->ion_auth->get_users_groups()->row()->id;
	    $this->data['group'] =$group;
	    $this->data['user'] = $this->ion_auth->user()->row();
	    $this->data['menu'] = $this->master_model->menus($group);
	    $this->data['menu_hide']="yes";
	    $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
	    $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
	    $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');
	    
	    if (!$this->ion_auth->logged_in())
	    {
	        redirect('auth/login', 'refresh');
	    }
	    
	    $user = $this->ion_auth->user()->row();
	    
	    if ($this->form_validation->run() == false)
	    {
	        //display the form
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	        
	        $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
	        $this->data['old_password'] = array(
	            'name' => 'old',
	            'id'   => 'old',
	            'type' => 'password',
	        );
	        $this->data['new_password'] = array(
	            'name' => 'new',
	            'id'   => 'new',
	            'type' => 'password',
	            'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
	        );
	        $this->data['new_password_confirm'] = array(
	            'name' => 'new_confirm',
	            'id'   => 'new_confirm',
	            'type' => 'password',
	            'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
	        );
	        $this->data['user_id'] = array(
	            'name'  => 'user_id',
	            'id'    => 'user_id',
	            'type'  => 'hidden',
	            'value' => $user->id,
	        );
	        //render
	        // $this->_render_page('auth/change_password', $this->data);
	        
	        $this->load->view('header_tanpamenu',$this->data);
		    $this->load->view('auth/change_password',$this->data);
		    $this->load->view('footer',$this->data);
	    }
	    else
	    {
	        $identity = $this->session->userdata('identity');
	        
	        $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));
	        
	        //var_dump($this->input->post('new'));die;
	        
	        if ($change)
	        {
	            //if the password was successfully changed
	            $this->session->set_flashdata('message', $this->ion_auth->messages());
	            $this->logout();
	        }
	        else
	        {
	            $this->session->set_flashdata('message', $this->ion_auth->errors());
	            // redirect('auth/change_password', 'refresh');
	            $this->load->view('header',$data);
			    $this->load->view('auth/change_password',$data);
			    $this->load->view('footer',$data);
	        }
	    }
	}

	//forgot password
	function forgot_password() {
		//setting validation rules by checking wheather identity is username or email
		if($this->config->item('identity', 'ion_auth') == 'username' ) {
		   $this->form_validation->set_rules('email', $this->lang->line('forgot_password_username_identity_label'), 'required');
		} else {
		   $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}

		if ($this->form_validation->run() == false) {
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			} else {
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data);
		} else {
			// get identity from username or email
			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$identity = $this->ion_auth->where('username', strtolower($this->input->post('email')))->users()->row();
			} else {
				$identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
			}
			
        	if(empty($identity)) {

        		if($this->config->item('identity', 'ion_auth') == 'username') {
                    $this->ion_auth->set_message('forgot_password_username_not_found');
            	} else {
            	   $this->ion_auth->set_message('forgot_password_email_not_found');
            	}

                $this->session->set_flashdata('message', $this->ion_auth->messages());
        		redirect("auth/forgot_password", 'refresh');
    		}

			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten) {
				//if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL) {
		if (!$code) {
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user) {
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false) {
				//display the form

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
				'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				//render
				$this->_render_page('auth/reset_password', $this->data);
			} else {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));
				} else {
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change) {
						//if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					} else {
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		} else {
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}


	//activate the user
	function activate($id, $code=false) {
		if ($code !== false) {
			$activation = $this->ion_auth->activate($id, $code);
		} else if ($this->ion_auth->is_admin()) {
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation) {
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		} else {
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL) {
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE) {
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->_render_page('auth/deactivate_user', $this->data);
		} else {
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
					$this->ion_auth->deactivate($id);
				}
			}
			//redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}

	//create a new user
	function create_user() {
	    //var_dump(password_hash("123456", PASSWORD_DEFAULT));die;

		$this->data['title'] = "Create User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$tables = $this->config->item('tables','ion_auth');

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique['.$tables['users'].'.email]');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true) {
			$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
			$email    = strtolower($this->input->post('email'));
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone'),
			);
		}
		
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		} else {
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['last_name'] = array(
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['company'] = array(
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company'),
			);
			$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			$this->_render_page('auth/create_user', $this->data);
		}
	}

	//edit a user
	function edit_user($id) {
		$this->data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
				show_error($this->lang->line('error_csrf'));
			}

			//update the password if it was posted
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE) {
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
				);

				//update the password if it was posted
				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}
				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin()) {
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}
					}
				}

			//check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data)) {
			    	//redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->messages() );
				    if ($this->ion_auth->is_admin()) {
						redirect('auth', 'refresh');
					} else {
						redirect('/', 'refresh');
					}
			    }  else {
			    	//redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
				    if ($this->ion_auth->is_admin()) {
						redirect('auth', 'refresh');
					} else {
						redirect('/', 'refresh');
					}
			    }

			}
		}

		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->_render_page('auth/edit_user', $this->data);
	}

	// create a new group
	function create_group() {
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE) {
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id) {
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		} else {
			//display the create group form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('auth/create_group', $this->data);
		}
	}

	//edit a group
	function edit_group($id) {
		// bail if no group id given
		if(!$id || empty($id)) {
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST)) {
			if ($this->form_validation->run() === TRUE) {
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update) {
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				} else {
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth", 'refresh');
			}
		}

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page('auth/edit_group', $this->data);
	}


	function _get_csrf_nonce() {
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce() {
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $render=false) {
		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
	}
	
	function randomPassword() {
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ";
	    $number = "0123456789";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    $numberLength = strlen($number) - 1;
	    for ($i = 0; $i < 6; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    for ($i = 0; $i < 3; $i++) {
	        $n = rand(0, $numberLength);
	        $pass[].= $number[$n];
	    }
	    return implode($pass); //turn the array into a string
	}

	public function register_non_bumn() {
		$this->load->model('master_model');
		$this->load->library('session');
	    date_default_timezone_set('Asia/Jakarta');
	    $data = array();
	    $data['error_string'] = array();
	    $data['inputerror'] = array();
	    $data['status'] = TRUE;
	    if (empty($_FILES['modal_foto']['name'])){
	        $data['inputerror'][] = 'modal_foto';
	        $data['error_string'][] = 'Foto 1 is required';
	        $data['status'] = FALSE;
	        
	        if($data['status'] === FALSE) {
	            echo json_encode($data);
	            exit();
	        }
	    }else{
	    	$jenis = $this->input->post('jenis');
	        $nama = $this->input->post('nama');
	        $modal_tgl = date('Y-m-d', strtotime($this->input->post('modal_tgl')));
	        $lokasi = $this->input->post('lokasi');
	        $provinsi = $this->input->post('provinsi');
	        $kabupaten = $this->input->post('kabupaten');
	        $jumlah_pegawai = $this->input->post('jumlah_pegawai');
	        $nama_penanggungjawab = $this->input->post('nama_penanggungjawab');
	        $kontak_penanggungjawab = $this->input->post('kontak_penanggungjawab');
	        $email_penanggungjawab = $this->input->post('email_penanggungjawab');
	        $website = $this->input->post('website');
	        $username = $this->input->post('username');
	        $password = $this->input->post('password');
	        $email_penanggungjawab = $this->input->post('email_penanggungjawab');
	        
	        if ($_FILES['modal_foto']['tmp_name']!='') {
	            $file_name1 =$_FILES['modal_foto']['name'];
	            $file_ext1 =  pathinfo($file_name1, PATHINFO_EXTENSION);
	            $file_tmp1= $_FILES['modal_foto']['tmp_name'];
	            $type1 = pathinfo($file_tmp1, PATHINFO_EXTENSION);
	            $data1 = file_get_contents($file_tmp1);
	            //$file = 'data:image/'.$type1.';base64,'.base64_encode($data1);
	            $file = preg_replace("/[^a-zA-Z0-9.]/", "_",$file_name1);
	        }else{
	            $file = NULL;
	        }

	        if (isset($_FILES['modal_foto2']['tmp_name'])) {
	         	$file_name1 =$_FILES['modal_foto2']['name'];
	            $file2 = preg_replace("/[^a-zA-Z0-9.]/", "_",$file_name1);
	        }else{
	            $file2 = NULL;
	        }

	        if (isset($_FILES['modal_foto3']['tmp_name'])) {
	         	$file_name1 =$_FILES['modal_foto3']['name'];
	            $file3 = preg_replace("/[^a-zA-Z0-9.]/", "_",$file_name1);
	        }else{
	            $file3 = NULL;
	        }
	        
	        if(!empty($file)){
		        $this->_do_upload(date('Y-m-d'), $file,'modal_foto');
		    }
		    if(!empty($file2)){
		        $this->_do_upload(date('Y-m-d'), $file2,'modal_foto2');
		    }
		    if(!empty($file3)){
		        $this->_do_upload(date('Y-m-d'), $file3,'modal_foto3');
		    }

	        $max_id = $this->master_model->max_id_company();
	        $max_id = (int)$max_id+1;
	         $password = password_hash($password,true);
	        $data = array(
	        	"mc_id"=>$max_id,
	        	"mc_name"=>$nama,
	        	"mc_ms_id"=>$jenis,
	        	"mc_lokasi"=>$lokasi,
	        	"mc_prov_id"=>$provinsi,
	        	"mc_kota_id"=>$kabupaten,
	        	"mc_jumlah_pegawai"=>$jumlah_pegawai,
	        	"mc_nama_pic"=>$nama_penanggungjawab,
	        	"mc_kontak"=>$kontak_penanggungjawab,
	        	"mc_email"=>$email_penanggungjawab,
	        	"mc_foto" => $file,
	        	"mc_foto2" => $file2,
	        	"mc_foto3" => $file3,
	        	"mc_website" => $website,
	        	"mc_username" => $username,
	        	"mc_password" => $password,
	        	"mc_status" => "Belum terverifikasi",
	        	"mc_flag" => 2,
	        	"mc_level" => 1,
	        	"mc_date_insert" => date('Y-m-d')
	        );
	        // echo"<pre>";print_r($data);echo"</pre>";die;
	        if($this->db->insert('master_company', $data))
	        {
	        	$data = array(
                    'username' => $username,
                    'password' => $password,
                    'first_name' => $username,
                    'mc_id' => $max_id,
                    'msc_id' => '2',
                    'active' => '1'
                );
                $this->db->insert('app_users', $data);

                $cek = $this->db->query("SELECT max(id) id_max from app_users")->row();
                if($cek->id_max)
                {
                    //INSERT ke user groups
                    $datax['user_id'] = $cek->id_max;
                    $datax['group_id']=12;
                    //group id 12 adalah onboarding, nnt di update setelah verifikasi perusahaan
                    
                    $this->db->insert('app_users_groups', $datax);
                }
	        }
	        
		    $this->session->set_flashdata('msg', 'Anda Berhasil Register Perusahaan, Silahkan Login dengan akun anda');
		    $msg = 'Anda Berhasil Register Perusahaan, Silahkan Login dengan akun anda';
		    $credentials = array('msg2' => $msg);
			$this->session->set_userdata($credentials);
			/*$this->data['provinsi'] = $this->master_model->mst_provinsi($cari='')->result();
	    	$this->data['jenis_industri'] = $this->master_model->mst_jenis_industri($cari='')->result();
	    	$this->data['message']='';
	        $this->_render_page('auth/login', $this->data);*/
			
	        redirect('auth/onboarding', 'location');	
	    }
	}

	private function _do_upload($tanggal, $file, $name) {
	    set_time_limit(0);
	    ini_set('max_execution_time', 0);
	    ini_set('memory_limit', '-1');
	    ini_set('max_input_time', 3600);
	    
	    if(!is_dir("uploads/companynonbumn/")) {
	        mkdir("uploads/companynonbumn/");
	    }
	    
	    $config['upload_path']          = 'uploads/companynonbumn/';
	    if(is_file($config['upload_path']))
		{
		    chmod($config['upload_path'], 777); ## this should change the permissions
		}
		$config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
	    $config['max_size']             = 2*1024; //set max size allowed in Kilobyte
	    $config['file_name']            = $file; //just milisecond timestamp fot unique name
	    
	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);
	    if(!$this->upload->do_upload($name)) {
	      
	        $data['inputerror'][] = $name;
	        $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
	        $data['status'] = FALSE;
	        echo json_encode($data);
	        exit();
	    }
	    return $this->upload->data('file_name');
	}

	public function checkUsername(){
		$this->load->model('admin_model');
		$cnt_user = $this->admin_model->count_username($this->input->post('username'));
    	    if(count($cnt_user) > 0){
				echo "ada";
			}
	}

	function onboarding() {
		$this->data['title'] = "OnBoarding";
	    $this->_render_page('auth/onboard', $this->data);    
	}
}

