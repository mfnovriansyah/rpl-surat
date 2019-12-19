<?php
setlocale (LC_TIME, 'id_ID');
$date = strftime( "%A, %d %B %Y", time());
$tmp = $post_array['room'];
$roomResult = $this->db->query("select name, code from rooms where id=$tmp")->row();
$room = $roomResult->name . ' (kode ruangan: '. $roomResult->code .')';

// require('kop.php');
$fromTemplate .= 
"
<div style='float:right'>".$date."</div>
<div style='clean:both'></div>

Kepada Yth.
<ol>
";
foreach(json_decode($post_array['participants']) as $a){
	if(is_numeric($a)){
		$r = $this->db->query("select concat(gelar_depan,' ',name) as name, gelar_belakang from users where id=$a")->row();
		$txt = $r->name;
		if($r->gelar_belakang!=''){
			$txt .= ', '.$r->gelar_belakang;
		}
		$fromTemplate .= '<li>'.$txt.'</li>';
	}else{
		$ar = explode(' (',$a);
		$fromTemplate .= '<li>'.$ar[0].'</li>';
	}
}
$fromTemplate .="
</ol>
di tempat.
<br>
<br>
Diharap kehadirannya dalam ".$post_array['agenda']." yang akan diselenggarakan pada<br>
<table>
<tr>
	<td>Waktu</td>
	<td>&nbsp;&nbsp;&nbsp; : </td>
	<td>".str_replace(" "," pukul ",$post_array['start'])."</td>
</tr>
<tr>
	<td>Tempat</td>
	<td>&nbsp;&nbsp;&nbsp; : </td>
	<td>".$room."</td>
</tr>
</table>
Demikian undangan ini kami sampaikan. Terima kasih.<br>";

if($post_array['sumber_dana']=='Ventura'){
	$qres = $this->db->query("select uj.jabatan, u.gelar_depan, u.gelar_belakang, u.name, u.NIP from users u join user_jabatan uj on uj.user = u.id where uj.id=6")->row();
}elseif($post_array['sumber_dana']=='DTE'){
	$qres = $this->db->query("select uj.jabatan, u.gelar_depan, u.gelar_belakang, u.name, u.NIP from users u join user_jabatan uj on uj.user = u.id where uj.id=1")->row();
}

$txt = $qres->gelar_depan.' '.$qres->name;
if($qres->gelar_belakang!=''){
	$txt .= ', '.$qres->gelar_belakang;
}
$fromTemplate .= '<div style="text-align:center"><br>'.$qres->jabatan .'<br><br><br><br><br><u>'.$txt.'</u><br>'.$qres->NIP.'</div>';

?>