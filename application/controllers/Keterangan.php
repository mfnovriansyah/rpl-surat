<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keterangan extends CI_CONTROLLER {
	private $c = null;
	public function __construct() {
		parent::__construct();
		$this->load->library('grocery_CRUD');
		$this->load->model('Usersmodel');
		$this->load->model('Keterangan_model');
		$this->load->helper('url');
		$this->load->helper(array('url','download'));
		if($this->session->login !== true) redirect(base_url('login?ref='.urlencode(current_url().'?'.$_SERVER['QUERY_STRING'])));

		

	}
	public function index($o='',$id=''){
		$keterangan = $this->Keterangan_model->get_all_surat();
		foreach ($keterangan as $surat){
			if($surat->status == "")
			{
				if($surat->check_status == "")
				{
					$emailadmin = $this->Keterangan_model->get_email_admin();
					$tanggal = $this->timestamp = date('Y-m-d H:i:s');
					if( time() - strtotime($surat->created) > (60*60*24*3)){
						$email = $this->session->email;
						$message = "Ada Pengajuan Surat (lebih dari 3 hari)";
		     			$subject = "Surat dengan nomor ".$surat->no_surat." belum diberikan keputusan lebih dari 3 hari. Mohon segera berikan keputusan.";
		     			$this->send_email($message,$subject,$emailadmin['email']);
		     			$this->Keterangan_model->update_check_surat($surat->id,"Checked");
                  		$this->Keterangan_model->insert_log("System","Admin",$tanggal,$subject,$message,"Pemberitahuan");
					}
				
				}
				
			}
		}
		$op4='';$op4=$this->uri->segment(4);
		$c = new grocery_CRUD();
		$this->c = $c;
		$c->unset_add();
		$c->unset_edit();
		$c->unset_read();
		if($this->session->role=='admin'){
			$c->add_action('Respon Surat','','keterangan/respon_surat','fa-reply');
		}
		$c->add_action('Edit','','keterangan/edit_surat','fa-edit');
		$c->add_action('View','','keterangan/view_surat','fa-eye');
		$output['session'] = $this->session;
		$email = $this->Keterangan_model->get_user($id);
		$c->set_table('keterangan');
		$c->set_relation('jenis','keterangan_jenis','name');
		if($this->session->role=='admin'){
			$c->set_relation('user','users','name');
		}
	   	else{
	   		if($this->uri->segment(3) =='add'|| $this->uri->segment(3) =='read') {
	   			
	   		}
	   		else{
	   			
	   		 	$c->set_relation('user','users','name');
	   		 }
	   	}
		$c->field_type('created','hidden',date('Y-m-d h:i:s')); 
		if($this->session->role=='admin'){
			$c->add_action('Cetak Surat','','keterangan/cetak','fa-print');
		}
		$c->unset_clone(); 
		$c->display_as('teks','keperluan');
		$c->unset_texteditor('teks');
		$c->set_field_upload('lampiran','assets/uploads/suratketerangan_attachment');
		if($o=='add'){ 
			if($this->session->role=='admin'){
				$c->field_type('status','hidden','Disetujui');
		  	}else{
				$c->field_type('status','hidden');
				$c->field_type('user','hidden', $this->session->id );
				
			}
		}
		$output['title'] = 'Pengajuan Surat Keterangan';
		if($this->session->role == 'admin'){
			$c->callback_after_insert(array($this, '_after_insert'));
			$c->callback_after_update(array($this, '_after_update'));
			}else{
			if($o=='read'||$o=='delete'||$o=='edit'||$o=='clone'){ //cant CRUD other's keterangan
				$r = $this->db->query("select user from keterangan where id=$id")->row();
				if(count($r)==1){
					if($r->user!=$this->session->id){redirect('login/logout');}
				}else{redirect('keterangan/index');}
			}
			$c->where('user',$this->session->id);
			$c->set_field_upload('lampiran','assets/uploads/suratketerangan_attachment');
			$c->field_type('user','hidden',$this->session->id); 
			$c->field_type('isi_fields','hidden'); 
			$c->unset_columns('isi_fields');
			$c->required_fields('jenis');
			$c->callback_before_insert(array($this, '_before_insert'));
			$c->callback_after_insert(array($this, '_after_insert'));

		}
		$output['output'] = $c->render();
		$this->load->view('common_crud',$output);
	}
	function _before_insert($post_array){
		$post_array['teks'] = $this->db->query("select template from keterangan_jenis where id=".$post_array['jenis'])->row()->template;
		return $post_array;
	}
	function _after_insert($post_array,$primary_key){
		
		return 0;
	}
	function add_template(){
		if($this->session->role!='admin'){
			redirect('keterangan');
		}
		$urlfields = 'add_fields';
		$user_jabatan = $this->Keterangan_model->get_user_jabatan();
		$template = $this->Keterangan_model->get_surat_template(null);
		$output['session'] = $this->session;
		$output['name'] = $template['name'];
		$output['status'] = $template['status'];
		$output['nomor'] = $template['nomor'];
		$output['fields'] = $template['fields'];
		$output['jenis_text'] = $template['jenis_text'];
		$output['hint'] = $template['hint'];
		$output['template'] = $template['template'];
		$output['penandatangan'] = $template['penandatangan'];
		$output['prosedur'] = $template['prosedur'];
		$output['session'] = $this->session;
		$output['user_jabatan'] = $user_jabatan;
		$output['urlfields'] = $urlfields;
		$output['title'] = 'Penambahan Template Surat';
		$this->load->view('v_add_template_admin',$output);
	}

	function edit_template(){
		if($this->session->role!='admin'){
			redirect('keterangan');
		}
		if($id_template = $this->uri->segment(3)== null){
			redirect('keterangan/add_template');
		}

		$id_template = $this->uri->segment(3);
		$urlfields = 'edit_fields/'.$id_template;
		$user_jabatan = $this->Keterangan_model->get_user_jabatan();
		$output['user_jabatan'] = $user_jabatan;
		$template = $this->Keterangan_model->get_surat_template($id_template);
		$output['session'] = $this->session;
		$output['name'] = $template['name'];
		$output['status'] = $template['status'];
		$output['nomor'] = $template['nomor'];
	
		$output['template'] = $template['template'];
		$output['penandatangan'] = $template['penandatangan'];
		$output['prosedur'] = $template['prosedur'];
		$output['urlfields'] = $urlfields;
		$output['title'] = 'Edit Template Surat';

		$this->load->view('v_add_template_admin',$output);

	}
	function add_fields(){
		if($this->session->role!='admin'){
			redirect('keterangan');
		}
		if($this->input->post('name') == null)
		{
			redirect('keterangan/add_template');
		}
		//input
		$urlfields = 'storeSementara';
		$name= $this->input->post('name');
		$status= $this->input->post('status');
		$nomor= $this->input->post('nomor');
		$penandatangan= $this->input->post('penandatangan');
		//mengambil isi fields null
		$template = $this->Keterangan_model->get_surat_template(0);
		$output['fields'] = explode(',',$template['fields']);
		$output['jenis_text'] = explode(',',$template['jenis_text']);
		$output['hint'] = explode(',',$template['hint']);

		//form input
		$output['name'] = $name;
		$output['status'] = $status;
		$output['nomor'] = $nomor;
		$output['penandatangan'] = $penandatangan;
		$output['session'] = $this->session;
		$output['urlfields'] = $urlfields;
		$output['title'] = 'Fields yang akan ada dalam Template Surat';
		$this->load->view('v_add_fields_admin',$output);
		}
	function edit_fields(){
		if($this->session->role!='admin'){
			redirect('keterangan');
		}
		//input
		$id_template = $this->uri->segment(3);
		$urlfields = 'storeSementara/'.$id_template;
		$name= $this->input->post('name');
		$status= $this->input->post('status');
		$nomor= $this->input->post('nomor');
		$penandatangan= $this->input->post('penandatangan');
		//mengambil isi fields 
		$template = $this->Keterangan_model->get_surat_template($id_template);
		$output['fields'] = explode(',',$template['fields']);
		$output['jenis_text'] = explode(',',$template['jenis_text']);
		$output['hint'] = explode(',',$template['hint']);

		//form input
		$output['name'] = $name;
		$output['status'] = $status;
		$output['nomor'] = $nomor;
		$output['penandatangan'] = $penandatangan;
		$output['session'] = $this->session;
		$output['urlfields'] = $urlfields;
		$output['title'] = 'Edit Fields yang akan ada dalam Template Surat';
		$this->load->view('v_add_fields_admin',$output);
		}

	function storeSementara(){
		if($this->session->role!='admin'){
			redirect('keterangan');
		}
		$id_template = $this->uri->segment(3);
		if($id_template)
		{
			$template = $this->Keterangan_model->get_surat_template($id_template);
			$output['textarea'] = $template['template'];
			$output['prosedur'] = $template['prosedur'];
			// $fields = explode(',', $field);
			$urlfields = 'storeFinal/'.$id_template;
		}
		else{
			$output['textarea'] = '';
			$output['prosedur'] = '';
			$urlfields = 'storeFinal/';
		}
		if($this->input->post('name') == null)
		{
			redirect('keterangan/add_fields');
		}
		$name= $this->input->post('name');
		$status= $this->input->post('status');
		$nomor= $this->input->post('nomor');
		$penandatangan= $this->input->post('penandatangan');
		$fields= $this->input->post('fields[]');
        $field = implode(',',$fields);
        $jenis_text = $this->input->post('jenis_text[]');
        $jenis = implode(',',$jenis_text);
        $hints= $this->input->post('hint[]');
        $hint = implode(',',$hints);

		$output['name'] = $name;
		$output['status'] = $status;
		$output['nomor'] = $nomor;
		$output['penandatangan'] = $penandatangan;
		$output['field'] = $field;
		$output['jenis'] = $jenis;
		$output['hint'] = $hint;
		$output['fields'] = $fields;
		$output['urlfields'] = $urlfields;
		$output['session'] = $this->session;
		$output['title'] = 'Template Surat';

		$this->load->view('v_add_final_template',$output);

	}
	function storeFinal(){
		$id_template = $this->uri->segment(3);

		$name= $this->input->post('name');
		$status= $this->input->post('status');
		$nomor= $this->input->post('nomor');
		$penandatangan= $this->input->post('penandatangan');
		$field= implode(',', $this->input->post('fields'));
		$jenis= implode(',', $this->input->post('jenis_text'));
		$hint= implode(',', $this->input->post('hint'));
		$textarea= $this->input->post('textarea');
		$prosedur= $this->input->post('prosedur');
		//jika mengedit template
		if($id_template == null){
			$this->Keterangan_model->insert_template_surat($name,$status,$nomor,$penandatangan,$field,$jenis,$hint,$textarea,$prosedur);
		}
		else{
			$this->Keterangan_model->update_template_surat($id_template,$name,$status,$nomor,$penandatangan,$field,$jenis,$hint,$textarea,$prosedur);
		}
		
		redirect('keterangan/jenis');

	}
	function add_surat(){
		if($this->session->role=='admin'){
			
		}
		else{

		}
		$urlfields = 'add_content_fields';
		$jenis_surat = $this->Keterangan_model->get_keterangan_jenis();
		$users = $this->Keterangan_model->get_users();
		$surat = $this->Keterangan_model->get_surat(null);
		$output['jenis'] = $surat['jenis'];
		$output['user'] = $surat['user'];
		$output['jenis_surat'] = $jenis_surat;
		$output['users'] = $users;
		$output['session'] = $this->session;
		$output['urlfields'] = $urlfields;
		$output['title'] = 'Penambahan Pengajuan Surat';
		$this->load->view('v_add_surat',$output);
	}
	function edit_surat(){
		if($this->session->role!='admin'){
			redirect('keterangan');
		}
		if($id_template = $this->uri->segment(3)== null){
			redirect('keterangan/add_surat');
		}
		$id_surat = $this->uri->segment(3);
		$urlfields = 'edit_content_fields/'.$id_surat;
		$jenis_surat = $this->Keterangan_model->get_keterangan_jenis();
		$users = $this->Keterangan_model->get_users();
		$user_jabatan = $this->Keterangan_model->get_user_jabatan();
		$surat = $this->Keterangan_model->get_surat($id_surat);
		$output['jenis'] = $surat['jenis'];
		$output['user'] = $surat['user'];
		$output['users'] = $users;
		$output['jenis_surat'] = $jenis_surat;
		$output['users'] = $users;
		$output['urlfields'] = $urlfields;
		$output['title'] = 'Edit Surat';
		$this->load->view('v_add_surat',$output);
	}

	function add_content_fields(){
		if($this->session->role=='admin'){
			$user = $this->input->post('user') ;
		}
		else{
			$user = $this->session->id;
		}
		$urlfields = 'add_surat_final';
		$id_jenis = $this->input->post('jenis') ;
		//mengambil field surat
		$template = $this->Keterangan_model->get_surat_template($id_jenis);
		$output['fields'] = explode(',',$template['fields']);
		$output['jenis_text'] = explode(',',$template['jenis_text']);
		$output['hint'] = explode(',',$template['hint']);

		$surat = $this->Keterangan_model->get_surat(null);
		$output['isi_fields'] = explode(',',$surat['isi_fields']);
		

		$output['session'] = $this->session;
		$output['urlfields'] = $urlfields;
		$output['id_jenis'] = $id_jenis;
		$output['user'] = $user;
		$output['title'] = 'Penambahan Isi Surat';
		$this->load->view('v_add_context_fields',$output);

	}
	function edit_content_fields(){
		//input
		$id_surat = $this->uri->segment(3);
		$urlfields = 'edit_surat_final/'.$id_surat;
		if($this->session->role=='admin'){
			$user = $this->input->post('user') ;
		}
		else{
			$user = $this->session->id;
		}
		$id_jenis = $this->input->post('jenis') ;
		//mengambil fields surat
		$template = $this->Keterangan_model->get_surat_template($id_jenis);
		$output['fields'] = explode(',',$template['fields']);
		$output['jenis_text'] = explode(',',$template['jenis_text']);
		$output['hint'] = explode(',',$template['hint']);
		//mengambil isi fields
		$surat = $this->Keterangan_model->get_surat($id_surat);
		$output['isi_fields'] = explode(',',$surat['isi_fields']);



		$output['session'] = $this->session;
		$output['urlfields'] = $urlfields;
		$output['id_jenis'] = $id_jenis;
		$output['user'] = $user;
		$output['title'] = 'Edit Isi Fields';
		$this->load->view('v_add_context_fields',$output);
		}
	function add_surat_final(){
		$id_surat = $this->uri->segment(3);
		$surat = $this->Keterangan_model->get_surat($id_surat);
		$output['status'] = $surat['status'];
		$output['lampiran'] = $surat['lampiran'];
		$urlfields = 'store_final_surat';
		$user = $this->input->post('user') ;
        $id_jenis = $this->input->post('id_jenis') ;
        $template_surat = $this->Keterangan_model->get_template_surat($id_jenis);
        $no_surat= $this->Keterangan_model->get_last_no_surat($id_jenis);
       	if($no_surat['last_no_surat'] == null){
				$nomor_surat = 1;
		}
		else{
			$nomor_surat = (int)$no_surat['last_no_surat'] + 1;
		}
		$nomor = $nomor_surat.$template_surat->nomor;

        $Data =  explode(',', $template_surat->fields);
        foreach ($Data as $row): ;
        ${$row} = $this->input->post($row);

        
        $isi_field{$row} = ${$row};
        endforeach;
        $output['id'] = "";
        $output['Data'] = $Data;
        $output['isi_field'] = $isi_field;
       	$output['isi_fields'] = implode(',',$isi_field);
		$output['fields'] = explode(',',$template_surat->fields);
		$output['template'] = $template_surat->template;
		$output['prosedur'] = $template_surat->prosedur;
        $output['session'] = $this->session;
		$output['urlfields'] = $urlfields;
		$output['id_jenis'] = $id_jenis;
		$output['nomor'] = $nomor;
		$output['user'] = $user;
		$output['title'] = 'Penambahan Surat';
        $this->load->view('v_add_final_surat',$output);

	}
	function edit_surat_final(){
		$id_surat = $this->uri->segment(3);
		$surat = $this->Keterangan_model->get_surat($id_surat);
		$output['status'] = $surat['status'];
		$output['nomor'] = $surat['no_surat'];
		$output['lampiran'] = $surat['lampiran'];
		$output['id'] = $surat['id'];
		$urlfields = 'store_final_surat/'.$id_surat;
		$user = $this->input->post('user') ;
        $id_jenis = $this->input->post('id_jenis') ;
        $template_surat = $this->Keterangan_model->get_template_surat($id_jenis);

        //membuat fields memiliki isi fields yang sesuai
        $Data =  explode(',', $template_surat->fields);
        foreach ($Data as $row): ;
        ${$row} = $this->input->post($row);        
        $isi_field{$row} = ${$row};
        endforeach;


        $output['Data'] = $Data;
        $output['isi_field'] = $isi_field;
       	$output['isi_fields'] = implode(',',$isi_field);
		$output['fields'] = explode(',',$template_surat->fields);
		$output['template'] = $template_surat->template;
		$output['prosedur'] = $template_surat->prosedur;

        $output['session'] = $this->session;
		$output['urlfields'] = $urlfields;
		$output['id_jenis'] = $id_jenis;
		$output['user'] = $user;
		$output['title'] = 'Edit Surat';
		
        $this->load->view('v_add_final_surat',$output);

	}
	function view_surat(){
		$id_surat = $this->uri->segment(3);
		$surat = $this->Keterangan_model->get_surat($id_surat);
		$output['status'] = $surat['status'];
		$output['nomor'] = $surat['no_surat'];
		$output['lampiran'] = $surat['lampiran'];
		$output['id'] = $surat['id'];
		$output['teks'] = $surat['teks'];

		$id_jenis = $surat['jenis'];
        $template = $this->Keterangan_model->get_surat_template($id_jenis);
        $output['penandatangan'] =$template['penandatangan'];
        $user_jabatan = $this->Keterangan_model->get_users_by_jabatan($output['penandatangan']);
        $user= $this->Keterangan_model->get_user($user_jabatan['user']);
        $nama =  $user['gelar_depan'].' '.$user['name'].' '.$user['gelar_belakang'];
        $output['nama'] = $nama;
        $output['NIP'] = $user['NIP'];
        if($surat['status']  != 'Disetujui'){
			die("Tak bisa print karena pengajuan belum disetujui.<br>Redirecting back...<script type='text/javascript'>setTimeout(function(){ window.location.href='".base_url('keterangan')."'; },3000);</script>"); 
			$this->session->set_flashdata('error','Status peminjaman belum disetujui.');redirect('meetings/index/edit/'.$id);
		}
		else{
			$this->load->view('v_surat',$output);
		} 
  	}

	function store_final_surat()
	{
		$id_surat = $this->uri->segment(3);
		$jenis= $this->input->post('id_jenis');
		$user= $this->input->post('user');
		$nomor= $this->input->post('nomor');
		$created = $this->timestamp = date('Y-m-d H:i:s');
		$isi_fields = $this->input->post('isi_fields');
		$teks = $this->input->post('textarea');
		// $lampiran= $this->input->post('lampiran');
		$status= $this->input->post('status');
		$catatan= $this->input->post('catatan');
		$lampiran =$this->input->post('oldfile');  
		//set file upload settings 

		$config['upload_path']          = './assets/uploads/suratketerangan_attachment/';
		$config['allowed_types']        = '*';
		$config['file_name']       		= $nomor ;
	   
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
        
		if (!empty($_FILES['lampiran'])) {
				if (!$this->upload->do_upload('lampiran')) {
					       // errors
					$error = $this->upload->display_errors();
			   
					  } else {
					       $upload_data = $this->upload->data();
					       $lampiran = $upload_data['file_name'];
					       
					  }
			
        }
        else{
        	echo "<pre>", 'gagal', "</pre>";
        }
        $oldfile2 = $this->input->post('oldfile2');
        if ($oldfile2 <> "" ) {
        	$lampiran = $this->input->post('oldfile2');
        }
		$emailadmin = $this->Keterangan_model->get_email_admin();
		if($id_surat == null){
			$this->Keterangan_model->insert_surat($jenis,$user,$nomor,$created,$isi_fields,$teks,$lampiran,$status);
			$id_surat = $this->db->insert_id();
			$jenis_surat = $this->Keterangan_model->get_surat_template($jenis);
			$keterangan = $this->Keterangan_model->get_keterangan($id_surat);
			if($this->session->role =='admin'){
					    $email = $this->session->email;
						$subject = "Penambahan ".$jenis_surat['name']." baru dengan nomor ".$nomor." telah disetujui";
			     		$message = "Surat dengan nomor ".$nomor." telah ditambahkan dan telah disetujui";
			     		$this->send_email($message,$subject,$email);
			     		$this->Keterangan_model->insert_log($emailadmin['email'],$emailadmin['id'],$created,$subject,$message,$keterangan['jenis']);
	         	   		$message = "Selamat surat pengajuan permohonan Anda dengan nomor ".$nomor." telah disetujui, Anda dapat melanjutkan proses ini ke tahap selanjutnya.";
	         	  		$subject = "Pengajuan Surat ".$jenis_surat['name']." baru dengan nomor ".$nomor." telah disetujui";
	         	   		$this->send_email($message,$subject,$keterangan['email']);
	                  	$this->Keterangan_model->insert_log($emailadmin['email'],$keterangan['id_user'],$created,$subject,$message,$keterangan['jenis']);
	         }
			else{
						$email = $this->session->email;
						$message = "Surat pengajuan permohonan Anda dengan nomor ".$nomor." telah dikirim, mohon tunggu jawaban surat disetujui atau ditolak.";
			     		$subject = "Pengajuan ".$jenis_surat['name']." dengan nomor ".$nomor." Berhasil";
			     		$this->send_email($message,$subject,$email);
			     		$this->Keterangan_model->insert_log($emailadmin['email'],$keterangan['id_user'],$created,$subject,$message,$keterangan['jenis']);     		
			     		$message = "Anda memiliki surat pengajuan permohonan dengan nomor ".$nomor.", harap berikan tanggapan disetujui atau ditolak.";
			     		$subject = "Ada Penganjuan ".$jenis_surat['name']." baru dengan nomor ".$nomor."";
			     		$this->send_email($message,$subject,$emailadmin['email']);
			     		$this->Keterangan_model->insert_log($emailadmin['email'],$emailadmin['id'],$created,$subject,$message,$keterangan['jenis']);
			}
		} else{
			$this->Keterangan_model->update_surat($id_surat,$jenis,$user,$nomor,$created,$isi_fields,$teks,$lampiran,$status,$catatan);
			$email = $this->session->email;
	  		$keterangan = $this->Keterangan_model->get_keterangan($id_surat);
			if($this->session->role=='admin'){	
				if($keterangan['status'] == 'Disetujui'){
					$message = "Selamat surat pengajuan permohonan Anda telah disetujui.";
	 				$subject = "Pengajuan Surat Disetujui";
				}
				else if($keterangan['status'] == 'Ditolak'){
					if($keterangan['catatan'] == ""){
						$message = "Maaf, surat pengajuan permohonan Anda belum dapat kami setujui";
	 					$subject = "Pengajuan Surat Ditolak";
					}
					else{
						$message = "Maaf, surat pengajuan permohonan Anda belum dapat kami setujui dengan catatan ".$keterangan['catatan'].".";
	 					$subject = "Pengajuan Surat Ditolak";
					}
				}
				else{
					if($keterangan['catatan'] == ""){
						$message = "Ada perubahan  pada surat pengajuan permohonan.";
	 					$subject = "Pengajuan Surat Diedit";
					}
					else{
						$message = "Ada perubahan  pada surat pengajuan permohonan dengan catatan ".$keterangan['catatan'].".";
	 					$subject = "Pengajuan Surat Diedit";
					}
				}
	 	   		$this->send_email($message,$subject,$keterangan['email']);
	 	   		$this->Keterangan_model->insert_log($emailadmin['email'],$keterangan['id_user'],$created,$subject,$message,$keterangan['jenis']);
	 	   	}
		}
		redirect('keterangan/');
	}	

	function get_fields(){
	    $id=$this->input->post('id');
       	$data = $this->Keterangan_model->get_surat_template($id);
        echo json_encode($data);
	}

	function respon_surat(){
		$id_surat = $this->uri->segment(3);
		$urlfields = 'store_final_surat/'.$id_surat;
		$jenis_surat = $this->Keterangan_model->get_keterangan_jenis();
		$users = $this->Keterangan_model->get_users();
		$user_jabatan = $this->Keterangan_model->get_user_jabatan();
		$surat = $this->Keterangan_model->get_surat($id_surat);
		$output['id_jenis'] = $surat['jenis'];
		$output['user'] = $surat['user'];
		$output['status'] = $surat['status'];
		$output['nomor'] = $surat['no_surat'];
		$output['lampiran'] = $surat['lampiran'];
		$output['user'] = $surat['user'];
		$output['id'] = $surat['id'];
		$output['catatan'] = $surat['catatan'];
		$output['users'] = $users;
		$output['jenis_surat'] = $jenis_surat;
		$output['users'] = $users;
		$output['urlfields'] = $urlfields;
		$output['session'] = $this->session;
		$output['title'] = 'Respon Surat';

        $template_surat = $this->Keterangan_model->get_template_surat($surat['jenis']);

        $Data =  explode(',', $template_surat->fields);
        $isi = explode(',', $surat['isi_fields']);
        $i= 0;
        foreach ($Data as $row): ;     
       		 $isi_field{$row} = $isi[$i];
       		 $i++;
        endforeach;
        $output['Data'] = $Data;
        $output['isi_field'] = $isi_field;
       	$output['isi_fields'] = implode(',',$isi_field);
		$output['fields'] = explode(',',$template_surat->fields);
		$output['template'] = $template_surat->template;
     	$output['prosedur'] = $template_surat->prosedur;      
		$this->load->view('v_respon_surat',$output);
	}
	function _after_update($post_array,$primary_key){

  		$email = $this->session->email;
  		$keterangan = $this->Keterangan_model->get_keterangan($primary_key);
		if($this->session->role=='admin'){	
			
			if($keterangan['status'] == 'Disetujui'){
				$message = "Selamat surat pengajuan permohonan Anda telah disetujui.";
 				$subject = "Pengajuan Surat Disetujui";
			}
			else if($keterangan['status'] == 'Ditolak'){
				$message = "Maaf, surat pengajuan permohonan Anda belum dapat kami setujui, Anda dapat merevisi surat pengajuan permohonan dan mengirimkannya kembali.";
 				$subject = "Pengajuan Surat Ditolak";
			}
			else{
				$message = "Maaf, surat pengajuan permohonan Anda belum dapat kami setujui, Sudah kami perbaiki	harap dicek dan lakukan revisi untuk dikirim kembali.";
 				$subject = "Pengajuan Surat Harap Direvisi";
			}
 	   		$this->send_email($message,$subject,$keterangan['email']);
 	   		$this->Keterangan_model->insert_log($emailadmin['email'],$keterangan['id_user'],$tanggal,$subject,$message,$keterangan['jenis']);
 	   	}
        else{
    	}
		redirect(base_url('keterangan/index/read/'.$primary_key)); }
	
	function jenis($o='',$id=''){
		$c = new grocery_CRUD();
		$this->c = $c;
		$c->set_table('keterangan_jenis');
		// $c->set_relation('penandatangan','user_jabatan','jabatan');
		$c->unset_add();
		$c->unset_edit();
		$c->add_action('Edit','','keterangan/edit_template','fa-edit');
		$output['session'] = $this->session;
		$output['title'] = 'Template Surat Keterangan';
		$output['output'] = $c->render();
		$this->load->view('common_crud',$output);
	}
	function activity_log($o='',$id=''){
		$c = new grocery_CRUD();
		$this->c = $c;
		$c->set_table('log_surat');
		$c->unset_add();
		$c->unset_edit();
		$c->unset_clone();
		$c->unset_delete();
		$c->set_relation('id_user','users','name');
		$c->display_as('id_user','Ke');
		$c->set_relation('jenis_surat','keterangan_jenis','name');
		$output['session'] = $this->session;
		$output['title'] = 'Log Aktifitas';
		$output['output'] = $c->render();
		$this->load->view('common_crud',$output);
	}
	function cetak($id){
		$r = $this->db->query("select
			k.teks, k.lampiran, k.status, kj.template, kj.prosedur,
			u.name, u.gelar_depan, u.gelar_belakang, u.NIP,
			ttd.name ttdName, ttd.gelar_depan ttdGdpn, ttd.gelar_belakang ttdGBlkg, ttd.NIP ttdNip
			from keterangan k 
			join keterangan_jenis kj on kj.id = k.jenis 
			join users u on u.id=k.user
			join users ttd on ttd.id=kj.penandatangan
			where k.id=$id")->row();
		if($r->status != 'Disetujui'){
			die("Tak bisa print karena pengajuan belum disetujui.<br>Redirecting back...<script type='text/javascript'>setTimeout(function(){ window.location.href='".base_url('keterangan')."'; },3000);</script>"); 
			// $this->session->set_flashdata('error','Status peminjaman belum disetujui.');redirect('meetings/index/edit/'.$id);
		}
		// $r->teks
		// $r->lampiran
		// $r->name
		// $r->gelar_depan
		// $r->gelar_belakang
		// $r->NIP
		// $r->template
		// $r->prosedur
		// $r->ttdName
		// $r->ttdGdpn
		// $r->ttdGBlkg
		// $r->ttdNip
		$output['body'] = $r->template;
		$this->load->view('common_surat',$output);
	}
	function send_email($message,$subject,$sendTo){
        require_once APPPATH.'libraries/mailer/class.phpmailer.php';
        require_once APPPATH.'libraries/mailer/class.smtp.php';
        require_once APPPATH.'libraries/mailer/mailer_config.php';
        include APPPATH.'libraries/mailer/template/template.php';
        
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        try
        {
            $mail->SMTPDebug = 1;  
            $mail->SMTPAuth = true; 
            $mail->SMTPSecure = 'ssl'; 
            $mail->Host = HOST;
            $mail->Port = PORT;  
            $mail->Username = GUSER;  
            $mail->Password = GPWD;     
            $mail->SetFrom(GUSER, 'Administrator');
            $mail->Subject = "DO NOT REPLY - ".$subject;
            $mail->IsHTML(true);   
            $mail->WordWrap = 0;


            $hello = '<h1 style="color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:300;padding:0;margin:10px 0 25px;text-align:center;line-height:1;word-break:normal;font-size:38px;letter-spacing:-1px">Pemberitahuan baru!</h1>';
            $thanks = "<br><br><i>Ini adalah email yang dibuat secara otomatis, mohon jangan dibalas.</i><br/><br/>Terimakasih,<br/>Admin<br/><br/>";

            $body = $hello.$message.$thanks;
            $mail->Body = $header.$body.$footer;
            $mail->AddAddress($sendTo);

            if(!$mail->Send()) {
                $error = 'Mail error: '.$mail->ErrorInfo;
                return array('status' => false, 'message' => $error);
            } else { 
                return array('status' => true, 'message' => '');
            }
        }
        catch (phpmailerException $e)
        {
            $error = 'Mail error: '.$e->errorMessage();
            return array('status' => false, 'message' => $error);
        }
        catch (Exception $e)
        {
            $error = 'Mail error: '.$e->getMessage();
            return array('status' => false, 'message' => $error);
        }
        
    }
    public function do_download($file){
    			$file =  $this->uri->segment(3);
                 $fullName = "./assets/uploads/suratketerangan_attachment/".$file;
			    force_download($fullName, null);
	}
    public function delete_file(){
    		$id =  $this->uri->segment(6);
    		$user =  $this->uri->segment(4);
    		$id_jenis =  $this->uri->segment(5);
			$file =  $this->uri->segment(3);	       
        	$fullName = "./assets/uploads/suratketerangan_attachment/".$file;
		  	if (is_readable($fullName) && unlink($fullName)) {
		   		//$file2="";
               $this->Keterangan_model->update_file($id);
	           // redirect('Keterangan/edit_surat_final/'.$id,$output); 
	           	redirect('keterangan/edit_surat_final/'.$id); 
                 $this->load->view('v_add_final_surat',$output);
		   	} else {
			    echo "The file was not found or not readable and could not be deleted";
		    } 


	}

	
}