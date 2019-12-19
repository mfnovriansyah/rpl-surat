<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keterangan_model extends CI_Model 
{
   
    public function get_status_surat($id_surat) {
    	$sql = "SELECT status FROM keterangan WHERE id='$id_surat'";
        $query = $this->db->query($sql);
        return $query->first_row('array');
    }
    public function get_keterangan($id_surat) {
        $sql = "SELECT u.id AS id_user, u.name, u.NIP, u.username, u.email,k.id AS id_surat, k.jenis, k.no_surat, k.teks, k.lampiran, k.status, k.catatan FROM keterangan k, users u WHERE u.id = k.user and k.id ='$id_surat'" ;
        $query = $this->db->query($sql);
        return $query->first_row('array');
    }
    public function get_email_admin()
    {
        $query = $this->db->query("SELECT email,id from users WHERE role='admin'");
        return $query->first_row('array');
    }
    public function get_user($id)
    {
        $query = $this->db->query("SELECT * from users WHERE id='$id'");
        return $query->first_row('array');
    }
     public function get_users_by_jabatan($penandatangan)
    {
        $query = $this->db->query("SELECT * from user_jabatan WHERE jabatan='$penandatangan'");
        return $query->first_row('array');
    }
    public function get_users()
    {
        $hsl = $this->db->query("SELECT * from users");
        return $hsl->result();
    }
    public function get_all_surat()
    {
        $hsl = $this->db->query("SELECT * from keterangan");
        return $hsl->result();
    }
    public function get_surat($id)
    {
        $query = $this->db->query("SELECT * from keterangan WHERE id='$id'");
        return $query->first_row('array');
    }
    
    public function insert_log($dari,$ke,$tanggal,$subject,$isi,$jenis_surat){
        $hsl=$this->db->query("INSERT INTO log_surat(dari,id_user,tanggal,subject,isi,jenis_surat) VALUES ('$dari','$ke','$tanggal','$subject','$isi','$jenis_surat')");
        return $hsl;
    }
    public function insert_template_surat($name,$status,$nomor,$penandatangan,$field,$jenis,$hint,$textarea,$prosedur){
        $hsl= $this->db->query("INSERT INTO keterangan_jenis(name,status,nomor,penandatangan,fields,jenis_text,hint,template,prosedur) VALUES ('$name','$status','$nomor','$penandatangan','$field','$jenis','$hint','$textarea','$prosedur')");
        return $hsl;
    }
    public function update_template_surat($id,$name,$status,$nomor,$penandatangan,$field,$jenis,$hint,$textarea,$prosedur){
        $hsl=$this->db->query("UPDATE keterangan_jenis SET 
            name='$name',status='$status',
            nomor='$nomor',penandatangan='$penandatangan',
            fields='$field',jenis_text='$jenis',
            hint='$hint',template='$textarea',
            prosedur='$prosedur'
             WHERE id='$id'");
        return $hsl;
    }
    public function insert_surat($jenis,$user,$no_surat,$created,$isi_fields,$teks,$lampiran,$status){
        $hsl= $this->db->query("INSERT INTO keterangan(jenis,user,no_surat,created,isi_fields,teks,lampiran,status) VALUES ('$jenis','$user','$no_surat','$created','$isi_fields','$teks','$lampiran','$status')");
        return $hsl;
    }
    public function update_surat($id,$jenis,$user,$no_surat,$created,$isi_fields,$teks,$lampiran,$status,$catatan){
        $hsl=$this->db->query("UPDATE keterangan SET 
            jenis='$jenis',user='$user',
            no_surat='$no_surat',created='$created',
            isi_fields='$isi_fields',teks='$teks',
            lampiran='$lampiran',status='$status',
            catatan='$catatan'
             WHERE id='$id'");
        return $hsl;
    }
    public function update_check_surat($id,$check_status){
        $hsl=$this->db->query("UPDATE keterangan SET 
            check_status='$check_status'
             WHERE id='$id'");
        return $hsl;
    }

    public function get_user_jabatan()
    {
        $hsl = $this->db->query("SELECT jabatan FROM user_jabatan");
        return $hsl->result();
    }
    public function get_surat_template($id_template)
    {
        $hsl = $this->db->query("SELECT * FROM keterangan_jenis WHERE id='$id_template'");
        return $hsl->first_row('array');
    }
    public function get_template_surat($id_template)
    {
        $hsl = $this->db->query("SELECT * FROM keterangan_jenis WHERE id='$id_template'");
        return $hsl->first_row();
    }
    public function get_keterangan_jenis()
    {   
        $hsl = $this->db->query("SELECT * FROM keterangan_jenis");
        return $hsl->result();
    }
    public function get_last_no_surat($jenis)
    {   
        $hsl = $this->db->query("SELECT max(no_surat) as last_no_surat FROM keterangan WHERE jenis='$jenis'");
        return $hsl->first_row('array');
    }

    function update_file($id)
    {

        $hsl=$this->db->query("UPDATE keterangan set lampiran=''  where id='$id'");
        return $hsl;
    }  
    
}