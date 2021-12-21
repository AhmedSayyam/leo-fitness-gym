<?php

class Admin extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        $rate_container = array();
       
        $this->load->model('Admin_Model');
        // $this->currency_rates(1);
    }


    public function myHash($pass){
        return  password_hash($pass, PASSWORD_DEFAULT);
    }
    // public function currency_rates($status, $label=null, $amount=null){
    //     global $rate_container;
    //     if($status == 1 && $label == null && $amount == null){
    //         $req_url = 'https://openexchangerates.org/api/latest.json?app_id=dd97823ac61f4d11a1cfdfbc90ef0629';
    //         $response_json = file_get_contents($req_url);
    //         if(false !== $response_json) {
    //             try {
    //                 $response = json_decode($response_json);
    //                 if(isset($response->rates)){
    //                     foreach($response->rates as $key=>$rate){
    //                         $rate_container[$key] = $rate;
    //                     }
    //                 }
    //             }
    //             catch(Exception $e) {
                
    //             }
    //         }
    //     }
    //     elseif($status == 2){
    //         $from_currency = 'AED';
    //         $to_currency = $label;
    //         $usd_price = round($amount / $rate_container[$from_currency], 2);
    //         $req_price = round($usd_price * $rate_container[$to_currency], 2);
    //         return $req_price;
    //     }
        
    // }
    
    
    public function getRate(){
        echo $this->currency_rates(2,'PKR',1200);
        // var_dump($rate_container['AED']);
        // $base_price = 10000;
        // echo "PKR rate = ";
        // echo $response->rates->PKR;
        // echo "->";
        // echo "AED rate = ";  
        // echo $response->rates->AED;
        // echo "->";
        // echo "USD price = ";
        // $USD_price = round(($base_price /  $response->rates->PKR), 2);
        // echo $USD_price;
        // echo "->";
        // echo "AED price = ";
        // $AED_price = round(($USD_price *  $response->rates->AED), 2);
        // echo $AED_price;
    }

    public function index(){

        // $csrf = array(
        //     'name' => $this->security->get_csrf_token_name(),
        //     'hash' => $this->security->get_csrf_hash()
        // );

        if(isset($_SESSION['is_logged_in']) == true){
            $this->load->view('index');
        }
        else{
            redirect('admin/login');
        }
    }

    public function login(){
        
        if($this->input->method(true) == 'POST'){
            $this->form_validation->set_rules('username', 'username', 'trim|required');
            $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]|max_length[12]');
            if($this->form_validation->run() === false){
                $errors = $this->form_validation->error_array();
                $response = [
                    'status'   => false,
                    'messages' => $errors,
                    'data'     => null,
                ];    
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    $response
                ));
            }
            else{
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $result = $this->Admin_Model->login($username, $password);
                if ($result) {
                    redirect('admin');
                } else {
                   $this->session->set_flashdata('error','wrong login credentials');
                    redirect('admin/login');
                }
            }
        }
        $this->load->view('login');
    }

    function logout(){
        $this->session->unset_userdata('is_logged_in');
        redirect(base_url() . 'admin/login');
    }

    public function recover(){
        $this->load->view('recover');
    }

    public function random(){
        // $digits = 4;
        // return rand(pow(10, $digits-1), pow(10, $digits)-1);
        return rand ( 1000 , 9999 );
    }

    public function send_mail($user_email, $rand_num) { 
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = 'ahmedsayyam19@gmail.com';
        $config['smtp_pass'] = 'vonczffdxcreffwj';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html'; // or html'' => TRUE
        $config['validation'] = true;

        // Email Content Starts
        $emailContent = "<!DOCTYPE>
        <html>
        <head></head>
        <body>
            <div>
                <span style='color:#17a2b8;border-bottom:2px solid #17a2b8;text-transform:capitalize;font-size:28px;'>
                    4 digit code verification for change password 
                </span>
                <p>Hi Admin,</p>
                <p>
                    As you have requested for forgot password, here there is 4 digit code. 
                    <br />
                    Please enter this code and continue to change password procedure.     
                </p>
            </div>
            <div>
                <h3>4 Digit Code: &nbsp; &nbsp;{$rand_num}</h3>
            </div>

        <body>
        <html>";

        // Email Content Ends
        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from("ahmedsayyam19@gmail.com");
        $this->email->to($user_email);
        $this->email->subject("Forgot Password of LEO Fitness GYM");
        $this->email->message($emailContent);
        $flag = $this->email->send();
        if($flag){
            return true;
        }else{
            return false;
        }
    }

    public function forgot(){
        if($this->input->method(true) == 'POST'){
            $this->form_validation->set_rules('email', 'email', 'trim|required');
            $user_email = $this->input->post('email');
            if($this->form_validation->run() == true){
                $email_check = $this->Admin_Model->check_email($user_email);
                if($email_check){
                    $rand_num = $this->random();
                    $forgot_insert = $this->Admin_Model->update_forgot_code($user_email, $rand_num);
                    if($forgot_insert){
                        if($this->send_mail($user_email, $rand_num)){
                            redirect('admin/verification');
                        }
                        else{
                            $this->session->set_flashdata('mail_not_sent','Mail Sending Failed! Try Again');
                            redirect('admin/forgot');
                        }
                    }
                    else{
                        $this->session->set_flashdata('request_error','Request Error! Try Again');
                        redirect('admin/forgot');
                    }
                }
                else{
                    $this->session->set_flashdata('email_not_found','Email Not Found! Try Again');
                    redirect('admin/forgot');
                }
            }
            else{
                $errors = $this->form_validation->error_array();
                $response = [
                    'status'   => false,
                    'messages' => $errors,
                    'data'     => null,
                ];    
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    $response
                ));
            }
        }
        else{
            $this->load->view('forgot');
        }

    }

    public function verify(){
        if($this->input->method(true) == 'POST'){
            $this->form_validation->set_rules('code', 'code', 'trim|required|is_numeric|min_length[4]|max_length[4]');
            $code = $this->input->post('code');
            if($this->form_validation->run() == true){
                $code_verify = $this->Admin_Model->verify_code($code);
                if($code_verify){
                    redirect('admin/change_password');
                }
                else{
                    $this->session->set_flashdata('code_not_verify',"Code doesn't found! Try Again");
                    $this->load->view('verify');
                }
            }
            else{
                $errors = $this->form_validation->error_array();
                $response = [
                    'status'   => false,
                    'messages' => $errors,
                    'data'     => null,
                ];    
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    $response
                ));
            }
        }
    }

    public function change_pass(){
        if($this->input->method(true) == 'POST'){
            $this->form_validation->set_rules('pass', 'pass', 'trim|required|min_length[8]|max_length[12]');
            $new_pass = $this->input->post('pass');
            $new_hashed_pass = $this->myHash($new_pass);
            if($this->form_validation->run() == true){
                $pass_change = $this->Admin_Model->change_password($new_hashed_pass);
                if($pass_change){
                    // echo "Code Verified";
                    redirect('admin/login');
                    // $this->load->view('login');
                }
                else{
                    $this->load->view('change');
                    // echo "Password updation failed";
                }
            }
            else{
                $errors = $this->form_validation->error_array();
                $response = [
                    'status'   => false,
                    'messages' => $errors,
                    'data'     => null,
                ];    
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                    $response
                ));
            }
        }
    }

    public function verification(){
        $this->load->view('verify');
    }

    public function change_password(){
        $this->load->view('change');
    }

    // Create New Member
    public function createMember(){

        $new_member = array();
        if($this->input->method(true) == 'POST'){
            if($this->input->post('fullname')){
                $this->form_validation->set_rules('fullname', 'fullname', 'trim|required|is_unique[tbl_members.fullname]');
                $new_member['fullname'] = $this->input->post('fullname');
            }
            if($this->input->post('phone')){
                $this->form_validation->set_rules('phone', 'phone', 'trim|required|exact_length[11]|is_numeric|is_unique[tbl_members.phone]');
                $new_member['phone'] = $this->input->post('phone');
            }
            if($this->input->post('address')){
                $this->form_validation->set_rules('address', 'address', 'trim|required');
                $new_member['address'] = $this->input->post('address');
            }
            if($this->input->post('blood_group')){
                $this->form_validation->set_rules('blood_group', 'blood_group', 'trim|required');
                $new_member['blood_group'] = $this->input->post('blood_group');
            }
            if($this->input->post('age')){
                $this->form_validation->set_rules('age', 'age', 'trim|required|is_numeric');
                $new_member['age'] = $this->input->post('age');
            }
            if($this->input->post('gender')){
                $this->form_validation->set_rules('gender', 'gender', 'trim|required');
                $new_member['gender'] = $this->input->post('gender');
            }
            if($this->input->post('cnic')){
                $this->form_validation->set_rules('cnic', 'cnic', 'trim|is_numeric|exact_length[13]|is_unique[tbl_members.cnic]');
                $new_member['cnic'] = $this->input->post('cnic');
            }
            if($this->input->post('pack_id')){
                $this->form_validation->set_rules('pack_id', 'pack_id', 'trim|is_numeric');
                $new_member['pack_id'] = $this->input->post('pack_id');
            }
            if($this->input->post('ref_id')){
                $this->form_validation->set_rules('ref_id', 'ref_id', 'trim|is_numeric');
                $new_member['ref_id'] = $this->input->post('ref_id');
            }
            if($this->input->post('image')){
                $this->form_validation->set_rules('image', 'image', 'trim');
                $new_member['image'] = $this->input->post('image');
            }

            if($this->form_validation->run()){
                // echo "Validation Successfull";
                // var_dump($new_member);
                $member_id = $this->Admin_Model->add_new_member($new_member);
                if($member_id && $member_id > 0){
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => true,
                        'data' => $member_id,
                        'error' => 'New Member Created Successfully'
                    )));
                }
                else{
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => false,
                        'data' => [],
                        'error' => 'New Member has not been created'
                    )));
                }
            }
            else{
                // echo "error in validation";
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                    array(
                        'status' => false,
                        'data' => [],
                        'error' => $this->form_validation->error_string(" ", " ")
                        )
                ));
            }
        }
    }

    // Create New Package
    public function createPackage(){

        $new_package = array();
        if($this->input->method(true) == 'POST'){
            if($this->input->post('label')){
                $this->form_validation->set_rules('label', 'label', 'trim|required|is_unique[tbl_packages.package_name]');
                $new_package['package_name'] = $this->input->post('label');
            }
            if($this->input->post('amount')){
                $this->form_validation->set_rules('amount', 'amount', 'trim|required|is_numeric');
                $new_package['package_amount'] = $this->input->post('amount');
            }
            if($this->input->post('period')){
                $this->form_validation->set_rules('period', 'period', 'trim|required');
                $new_package['package_period'] = $this->input->post('period');
            }
            

            if($this->form_validation->run()){
                // echo "Validation Successfull";
                // var_dump($new_package);
                $package_id = $this->Admin_Model->add_new_package($new_package);
                if($package_id && $package_id > 0){
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => true,
                        'data' => $package_id,
                        'error' => 'New Package Created Successfully'
                    )));
                }
                else{
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => false,
                        'data' => [],
                        'error' => 'New Package has not been created'
                    )));
                }
            }
            else{
                // echo "error in validation";
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                    array(
                        'status' => false,
                        'data' => [],
                        'error' => $this->form_validation->error_string(" ", " ")
                        )
                ));
            }
        }
    }

    // Create New Staff
    public function createStaff(){

        $new_staff = array();
        if($this->input->method(true) == 'POST'){
            if($this->input->post('fullname')){
                $this->form_validation->set_rules('fullname', 'fullname', 'trim|required|is_unique[tbl_instructors.instructor_name]');
                $new_staff['instructor_name'] = $this->input->post('fullname');
            }
            if($this->input->post('phone')){
                $this->form_validation->set_rules('phone', 'phone', 'trim|required|exact_length[11]|is_numeric|is_unique[tbl_instructors.phone]');
                $new_staff['phone'] = $this->input->post('phone');
            }
            if($this->input->post('address')){
                $this->form_validation->set_rules('address', 'address', 'trim|required');
                $new_staff['address'] = $this->input->post('address');
            }
            if($this->input->post('blood_group')){
                $this->form_validation->set_rules('blood_group', 'blood_group', 'trim|required');
                $new_staff['blood_group'] = $this->input->post('blood_group');
            }
            if($this->input->post('age')){
                $this->form_validation->set_rules('age', 'age', 'trim|required|is_numeric');
                $new_staff['age'] = $this->input->post('age');
            }
            if($this->input->post('gender')){
                $this->form_validation->set_rules('gender', 'gender', 'trim|required');
                $new_staff['gender'] = $this->input->post('gender');
            }
            if($this->input->post('cnic')){
                $this->form_validation->set_rules('cnic', 'cnic', 'trim|is_numeric|exact_length[13]|is_unique[tbl_instructors.cnic]');
                $new_staff['cnic'] = $this->input->post('cnic');
            }
            if($this->input->post('image')){
                $this->form_validation->set_rules('image', 'image', 'trim');
                $new_staff['image'] = $this->input->post('image');
            }

            if($this->form_validation->run()){
                // echo "Validation Successfull";
                // var_dump($new_staff);
                $staff_id = $this->Admin_Model->add_new_staff($new_staff);
                if($staff_id && $staff_id > 0){
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => true,
                        'data' => $staff_id,
                        'error' => 'New Staff Created Successfully'
                    )));
                }
                else{
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => false,
                        'data' => [],
                        'error' => 'New Staff has not been created'
                    )));
                }
            }
            else{
                // echo "error in validation";
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                    array(
                        'status' => false,
                        'data' => [],
                        'error' => $this->form_validation->error_string(" ", " ")
                        )
                ));
            }
        }
    }

    // Get Members
    public function getMembers(){
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(
        array(
        'status' => true,
        'data' => $this->Admin_Model->get_members(),
        'error' => ''
        )
        ));
    }

    // Get Staff
    public function getStaff(){
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(
        array(
        'status' => true,
        'data' => $this->Admin_Model->get_staff(),
        'error' => ''
        )
        ));
    }

    // Get Package
    public function getPackage(){
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(
        array(
        'status' => true,
        'data' => $this->Admin_Model->get_packages(),
        'error' => ''
        )
        ));
    }

    // Delete Member
    public function delMember($id){
        if($this->input->method(true) == 'DELETE'){
            $del_mem_id = $this->Admin_Model->delete_member($id);
            if($del_mem_id){
                return $this->output->set_content_type('application/json')->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => true,
                    'data' => $del_mem_id,
                    'error' => 'Member Deleted Successfully'
                )));
            }
            else{
                return $this->output->set_content_type('application/json')->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'data' => [],
                    'error' => 'Member has not been deleted'
                )));
            }
        }
    }

    // Delete Staff
    public function delStaff($id){
        if($this->input->method(true) == 'DELETE'){
            $del_staff_id = $this->Admin_Model->delete_staff($id);
            if($del_staff_id){
                return $this->output->set_content_type('application/json')->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => true,
                    'data' => $del_staff_id,
                    'error' => 'Staff Deleted Successfully'
                )));
            }
            else{
                return $this->output->set_content_type('application/json')->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'data' => [],
                    'error' => 'Staff has not been deleted'
                )));
            }
        }
    }

    // Delete Package
    public function delPackage($id){
        if($this->input->method(true) == 'DELETE'){
            $del_pack_id = $this->Admin_Model->delete_package($id);
            if($del_pack_id){
                return $this->output->set_content_type('application/json')->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => true,
                    'data' => $del_pack_id,
                    'error' => 'Package Deleted Successfully'
                )));
            }
            else{
                return $this->output->set_content_type('application/json')->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'data' => [],
                    'error' => 'Package has not been deleted'
                )));
            }
        }
    }

    // Upload Image
    function image_upload(){
        if ($this->input->method(true) == 'POST') {
            if (isset($_FILES['image'])) {
                $config['upload_path']          = './public/uploads/';
                $config['allowed_types']        = 'jpg|png|jpeg';
                $config['max_size']             = 2048;
                $config['file_name'] = time() . $_FILES['image']['name'];
                $config['file_name'] = $this->security->sanitize_filename($config['file_name']);
                $this->upload->initialize($config);
                            
                if (!$this->upload->do_upload('image')) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                    array(
                        'status' => false,
                        'data' => [],
                        'error' => $this->upload->display_errors()
                    )
                    ));
                }
                $base=base_url('/public/uploads/');
                $file_name = $base.$this->upload->data('file_name');

                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(
                array(
                    'status' => true,
                    'data' => $file_name,
                    'error' => ''
                )
                ));
            } 
        }
    }

    // Update Package
     public function updatePackage(){

        $update_package = array();
        $update_package_id = '';
        if($this->input->method(true) == 'POST'){
            if($this->input->post('id')){
                $this->form_validation->set_rules('id', 'id', 'trim|required|is_numeric');
                $update_package['package_id'] = $this->input->post('id');
                $update_package_id = $this->input->post('id');
            }
            if($this->input->post('label')){
                $this->form_validation->set_rules('label', 'label', 'trim|required');
                $update_package['package_name'] = $this->input->post('label');
            }
            if($this->input->post('amount')){
                $this->form_validation->set_rules('amount', 'amount', 'trim|required|is_numeric');
                $update_package['package_amount'] = $this->input->post('amount');
            }
            if($this->input->post('period')){
                $this->form_validation->set_rules('period', 'period', 'trim|required');
                $update_package['package_period'] = $this->input->post('period');
            }
            

            if($this->form_validation->run()){
                $package_id = $this->Admin_Model->update_package($update_package_id, $update_package);
                if($package_id){
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => true,
                        'data' => $package_id,
                        'error' => 'Package Updated Successfully'
                    )));
                }
                else{
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => false,
                        'data' => [],
                        'error' => 'Package has not been updated'
                    )));
                }
            }
            else{
                // echo "error in validation";
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                    array(
                        'status' => false,
                        'data' => [],
                        'error' => $this->form_validation->error_string(" ", " ")
                        )
                ));
            }
        }
    }

    // Update Staff
    public function updateStaff(){

        $update_staff = array();
        $update_staff_id = '';
        if($this->input->method(true) == 'POST'){
            if($this->input->post('id')){
                $this->form_validation->set_rules('id', 'id', 'trim|required');
                $update_staff['instructor_id'] = $this->input->post('id');
                $update_staff_id = $this->input->post('id');
            }
            if($this->input->post('fullname')){
                $this->form_validation->set_rules('fullname', 'fullname', 'trim|required');
                $update_staff['instructor_name'] = $this->input->post('fullname');
            }
            if($this->input->post('phone')){
                $this->form_validation->set_rules('phone', 'phone', 'trim|required|exact_length[11]|is_numeric');
                $update_staff['phone'] = $this->input->post('phone');
            }
            if($this->input->post('address')){
                $this->form_validation->set_rules('address', 'address', 'trim|required');
                $update_staff['address'] = $this->input->post('address');
            }
            if($this->input->post('blood_group')){
                $this->form_validation->set_rules('blood_group', 'blood_group', 'trim|required');
                $update_staff['blood_group'] = $this->input->post('blood_group');
            }
            if($this->input->post('age')){
                $this->form_validation->set_rules('age', 'age', 'trim|required|is_numeric');
                $update_staff['age'] = $this->input->post('age');
            }
            if($this->input->post('gender')){
                $this->form_validation->set_rules('gender', 'gender', 'trim|required');
                $update_staff['gender'] = $this->input->post('gender');
            }
            if($this->input->post('cnic')){
                $this->form_validation->set_rules('cnic', 'cnic', 'trim|is_numeric|exact_length[13]');
                $update_staff['cnic'] = $this->input->post('cnic');
            }
            if($this->input->post('image')){
                $this->form_validation->set_rules('image', 'image', 'trim');
                $update_staff['image'] = $this->input->post('image');
            }

            if($this->form_validation->run()){
                // echo "Validation Successfull";
                // var_dump($new_staff);
                $staff_id = $this->Admin_Model->update_staff($update_staff_id, $update_staff);
                if($staff_id){
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => true,
                        'data' => $staff_id,
                        'error' => 'Staff Updated Successfully'
                    )));
                }
                else{
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => false,
                        'data' => [],
                        'error' => 'Staff has not been updated'
                    )));
                }
            }
            else{
                // echo "error in validation";
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                    array(
                        'status' => false,
                        'data' => [],
                        'error' => $this->form_validation->error_string(" ", " ")
                        )
                ));
            }
        }
    }

    // Update Member
    public function updateMember(){
        $update_member_id = '';
        $update_member = array();
        if($this->input->method(true) == 'POST'){
            if($this->input->post('id')){
                $this->form_validation->set_rules('id', 'id', 'trim|required|is_numeric');
                $update_member['member_id'] = $this->input->post('id');
                $update_member_id = $this->input->post('id');
            }
            if($this->input->post('fullname')){
                $this->form_validation->set_rules('fullname', 'fullname', 'trim|required');
                $update_member['fullname'] = $this->input->post('fullname');
            }
            if($this->input->post('phone')){
                $this->form_validation->set_rules('phone', 'phone', 'trim|required|exact_length[11]|is_numeric');
                $update_member['phone'] = $this->input->post('phone');
            }
            if($this->input->post('address')){
                $this->form_validation->set_rules('address', 'address', 'trim|required');
                $update_member['address'] = $this->input->post('address');
            }
            if($this->input->post('blood_group')){
                $this->form_validation->set_rules('blood_group', 'blood_group', 'trim|required');
                $update_member['blood_group'] = $this->input->post('blood_group');
            }
            if($this->input->post('age')){
                $this->form_validation->set_rules('age', 'age', 'trim|required|is_numeric');
                $update_member['age'] = $this->input->post('age');
            }
            if($this->input->post('gender')){
                $this->form_validation->set_rules('gender', 'gender', 'trim|required');
                $update_member['gender'] = $this->input->post('gender');
            }
            if($this->input->post('cnic')){
                $this->form_validation->set_rules('cnic', 'cnic', 'trim|is_numeric|exact_length[13]');
                $update_member['cnic'] = $this->input->post('cnic');
            }
            if($this->input->post('pack_id')){
                $this->form_validation->set_rules('pack_id', 'pack_id', 'trim|is_numeric');
                $update_member['pack_id'] = $this->input->post('pack_id');
            }
            if($this->input->post('ref_id')){
                $this->form_validation->set_rules('ref_id', 'ref_id', 'trim|is_numeric');
                $update_member['ref_id'] = $this->input->post('ref_id');
            }
            if($this->input->post('image')){
                $this->form_validation->set_rules('image', 'image', 'trim');
                $update_member['image'] = $this->input->post('image');
            }

            if($this->form_validation->run()){
                // echo "Validation Successfull";
                // var_dump($update_member);
                $member_id = $this->Admin_Model->update_member($update_member_id, $update_member);
                if($member_id){
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => true,
                        'data' => $member_id,
                        'error' => 'Member Updated Successfully'
                    )));
                }
                else{
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => false,
                        'data' => [],
                        'error' => 'Member has not been updated'
                    )));
                }
            }
            else{
                // echo "error in validation";
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                    array(
                        'status' => false,
                        'data' => [],
                        'error' => $this->form_validation->error_string(" ", " ")
                        )
                ));
            }
        }
    }

    // Collect fee
    public function collectFee(){
        $collect_fee = array();
        if($this->input->method(true) == 'POST'){
            if($this->input->post('mem_id')){
                $this->form_validation->set_rules('mem_id', 'mem_id', 'trim|required|is_numeric');
                $collect_fee['member_id'] = $this->input->post('mem_id');
            }
            if($this->input->post('pack_name')){
                $this->form_validation->set_rules('pack_name', 'pack_name', 'trim|required');
                $collect_fee['pack_name'] = $this->input->post('pack_name');
            }
            if($this->input->post('fee')){
                $this->form_validation->set_rules('fee', 'fee', 'trim|required|is_numeric');
                $collect_fee['fee'] = $this->input->post('fee');
            }
            if($this->input->post('status')){
                $this->form_validation->set_rules('status', 'status', 'trim|required|is_numeric');
                $collect_fee['status'] = $this->input->post('status');
            }
            $collect_fee['deposit_date'] = $this->input->post('deposit_date');
            

            if($this->form_validation->run()){
                // echo "Validation Successfull";
                // var_dump($new_package);
                $collect_fee_id = $this->Admin_Model->deposit_fee($collect_fee);
                if($collect_fee_id && $collect_fee_id > 0){
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => true,
                        'data' => $collect_fee_id,
                        'error' => 'Fee Collected Successfully'
                    )));
                }
                else{
                    return $this->output->set_content_type('application/json')->set_status_header(200)
                    ->set_output(json_encode(array(
                        'status' => false,
                        'data' => [],
                        'error' => 'Fee has not been collected'
                    )));
                }
            }
            else{
                // echo "error in validation";
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(
                    array(
                        'status' => false,
                        'data' => [],
                        'error' => $this->form_validation->error_string(" ", " ")
                        )
                ));
            }
        }
    }

    public function myData(){
        $member_id = $this->Admin_Model->getData();
        if($member_id && $member_id > 0){
            return $this->output->set_content_type('application/json')->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => true,
                'data' => $member_id,
                'error' => 'New Member Created Successfully'
            )));
        }
        else{
            return $this->output->set_content_type('application/json')->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'data' => [],
                'error' => 'New Member has not been created'
            )));
        }
    }
}

?>