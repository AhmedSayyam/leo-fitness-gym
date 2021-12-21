<?php

class Admin_Model extends CI_Model{
    function __construct(){
		parent:: __construct();
	}

    public function login($email, $password){
        $query = $this->db->where('tbl_admin.email', $email)->get('tbl_admin')->row();
       
        if($query){
          
            $hash = $query->password;
            $id = $query->id;
            if(password_verify($password, $hash)){
                $session_data = array(
                    'username' => $email,
                    'id' => $id,
                    'is_logged_in' => true
                );
                $today = date("Y-m-d H:i:s");
                $this->db->where('id',$id)->update('tbl_admin',array('last_login'=>$today)); 
                $this->session->set_userdata($session_data);
                return array(
                    'status' => true,
                        'id' => $query->id,
                        'username' => $query->email,
                    'message' => 'Login Successfull'
                );
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function check_email($email){
        $query = $this->db->where('tbl_admin.email', $email)->get('tbl_admin')->row();
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function update_forgot_code($email, $rand_num){
        $query = $this->db->where('tbl_admin.email',$email)->update('tbl_admin', ['forgot_code'=>$rand_num]);
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function verify_code($code){
        $query = $this->db->where('tbl_admin.forgot_code', $code)->get('tbl_admin')->row();
        if($query){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function change_password($pass){
        $query = $this->db->where('tbl_admin.id', 1)->update('tbl_admin', ['password'=>$pass]);
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function getData(){
        echo "Working";
    }

    public function add_new_member($data){
        $this->db->insert('tbl_members', $data);
        return $this->db->insert_id();
    }
    
    public function add_new_package($data){
        $this->db->insert('tbl_packages', $data);
        return $this->db->insert_id();
    }

    public function add_new_staff($data){
        $this->db->insert('tbl_instructors', $data);
        return $this->db->insert_id();
    }

    public function get_members(){
        return $this->db->select('tbl_packages.package_name,tbl_packages.package_amount,tbl_members.*,tbl_instructors.instructor_name')
        ->join('tbl_packages','tbl_packages.package_id = tbl_members.pack_id')
        ->join('tbl_instructors','tbl_instructors.instructor_id = tbl_members.ref_id')
        ->get('tbl_members')
        ->result_array();
    }

    public function get_staff(){
        $staff = $this->db->get('tbl_instructors');
        return $staff->result_array();
    }

    public function get_packages(){
        $packages = $this->db->get('tbl_packages');
        return $packages->result_array();
    }

    public function delete_member($id){
        $this->db->where('member_id', $id)->delete('tbl_members');
        return $this->db->affected_rows()>0;
    }
    
    public function delete_staff($id){
        $this->db->where('instructor_id', $id)->delete('tbl_instructors');
        return $this->db->affected_rows()>0;
    }

    public function delete_package($id){
        $this->db->where('package_id', $id)->delete('tbl_packages');
        return $this->db->affected_rows()>0;
    }
    
    public function update_package($id, $data){
        $this->db->where(array('package_id'=>$id))->update('tbl_packages',$data);
        return $this->db->affected_rows()>0;
    }

    public function update_staff($id, $data){
        $this->db->where(array('instructor_id'=>$id))->update('tbl_instructors',$data);
        return $this->db->affected_rows()>0;
    }

    public function update_member($id, $data){
        $this->db->where(array('member_id'=>$id))->update('tbl_members',$data);
        return $this->db->affected_rows()>0;
    }

    public function deposit_fee($data){
        $this->db->insert('tbl_deposit', $data);
        return $this->db->insert_id();
    }
    
}


?>