<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usersmodel extends CI_Model {
	public function login($data) {
		$username = $data['username'];
		$password = md5($data['password']);
		$dologin = $this->db->query("select * from users where username = '$username' and password = '$password' and password != '' and password is not null and status = 'active'");
		return $dologin;
	}

	public function register($data) {
		$doregister = $this->db->insert('users', $data);
		$user = $this->db->get_where('users', ['id' => $this->db->insert_id()]);
		return $user;
	}

	public function getAll() {
		$getAll = $this->db->get('users');
		return $getAll->result();
	}

	public function checkUsername($username) {
		$checkUsername = $this->db->get_where('users', ['username' => $username])->num_rows();
		return $checkUsername;
	}

	public function byId($id) {
		$byId = $this->db->get_where('users', ['id' => $id]);
		return $byId->row();
	}

	public function update($data, $id) {
		$this->db->set($data);
		$this->db->where('id', $id);
		$update = $this->db->update('users');
		return $update;
	}

	public function delete($id) {
		$this->db->where('id', $id);
		$delete = $this->db->delete('users');
		return $delete;
	}
	public function getOrInsert($u){
		$res = $this->db->limit(1)->where('username',$u['username'])->get('users')->result();
		if ( count($res) == 0 ){ // if not exist, create one
			$role = $u['peran_user'] == 'staff' ? 'dosen' : 'mahasiswa';
			$data = array();
			$data['username'] = $u['username'];
			$data['name'] = $u['nama'];
			$data['email'] = $u['username'].'@ui.ac.id';
			$data['role_SSO'] = $u['peran_user'];
			$data['status'] = 'new';
			$data['created'] = date('Y-m-d h:i:s');
			$data['role'] = $role;
			$this->db->insert('users', $data);
			$res = $this->db->limit(1)->where('username',$u['username'])->get('users')->result();
			// $data['id'] = $this->db->insert_id();
			// $res[0]=$data;
		}
		// echo '<pre>';print_r($res[0]);die();
		return $res[0];
	}
	public function byNameOrMail($k){
		$s = "SELECT id,name,email FROM users WHERE (name LIKE '%$k%' OR email LIKE '%$k%') ORDER BY name ASC";
		$r = $this->db->query($s);
		$list = array();
		$key=0;
		foreach($r->result() as $e){
			$list[$key]['id'] = $e->id;
			$list[$key]['text'] = $e->name.' ('.$e->email.')'; 
			$key++;
		}
		echo json_encode($list);
	}
	public function byMeeting($k){ //get invited users in a meeting
		$s = "SELECT user as id,name,email FROM meeting_participants WHERE name !='' AND meeting = $k 
			UNION SELECT p.user as id, u.name,u.email FROM meeting_participants p JOIN users u ON u.id=p.user WHERE p.meeting = $k 
			ORDER BY name ASC";
		$r = $this->db->query($s);
		$list = array();
		$key=0;
		foreach($r->result() as $e){
			$list[$key]['id'] = $e->id==0 ? $e->name : $e->id;
			$list[$key]['text'] = $e->email=='' ? $e->name : $e->name.' ('.$e->email.')'; 
			$key++;
		}
		echo json_encode($list);
	}
	public function byMeeting2($k){ //get invited users in a meeting
		$s = "SELECT meeting_participants.id, user as uid,name,email,status FROM meeting_participants WHERE name !='' AND meeting = $k
			UNION SELECT p.id,p.user as uid, u.name,u.email,p.status FROM meeting_participants p JOIN users u ON u.id=p.user WHERE p.meeting = $k 
			ORDER BY name ASC;";
		return $this->db->query($s)->result();
	}
}