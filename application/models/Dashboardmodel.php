<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboardmodel extends CI_Model {
	public function getDashboardData() {
		$data['numAllUser'] = $this->db->query("SELECT count(*) as c from users")->row()->c;
		// $data['numActiveUser'] = $this->db->query("SELECT count(*) as c from users where status = 'active'")->row()->c;
		// $data['numSSOUser'] = $this->db->query("SELECT count(*) as c from users where role_SSO != ''")->row()->c;
		// $data['numStudent'] = $this->db->query("SELECT count(*) as c from users where role = 'mahasiswa'")->row()->c;
		// $data['numDosen'] = $this->db->query("SELECT count(*) as c from users where role = 'dosen'")->row()->c;
		$data['numRoom'] = $this->db->query("SELECT count(*) as c from rooms")->row()->c;
		// $data['allRooms'] = $this->db->query("SELECT name, capacity, color from rooms")->result(); //room capacity as pie chart
		$data['numAllBookings'] = $this->db->query("SELECT count(*) as c from meetings")->row()->c;
		$data['numAllPendingBookings'] = $this->db->query("SELECT count(*) as c from meetings where status = 'Menanti pengecekan'")->row()->c;
		$data['numAllRejectedBookings'] = $this->db->query("SELECT count(*) as c from meetings where status = 'Ditolak'")->row()->c;
		$data['numAllApprovedBookings'] = $this->db->query("SELECT count(*) as c from meetings where status = 'Disetujui'")->row()->c;
		// $q = $this->db->query("SELECT count(*) as c from meetings where status = 'Undangan sudah dikirim'");
		// $data['numUndanganDibuat'] = $q->row()->c;
		$data['numMeetingDone'] = $this->db->query("SELECT count(*) as c from meetings where end < NOW() and status = 'Disetujui'")->row()->c;
		// $q = $this->db->query("SELECT count(*) as c from meetings where status = 'Selesai, notulen OK'");
		$data['numMeetingWeekly'] = $this->db->query("SELECT WEEK(start) as week,count(*) waiting,count(if(status='Disetujui',1,NULL)) as approved,end as tgl from meetings
			where WEEK(start) >= WEEK(NOW())-5
			group by week
			order by week
			")->result_array(); //in all room
		$data['numMeetingMonthly'] = $this->db->query("SELECT MONTH(start) as month,count(*) waiting,count(if(status='Disetujui',1,NULL)) as approved,end as tgl from meetings
			where MONTH(start) >= MONTH(NOW())-3
			group by month
			order by month
			")->result_array(); //in all room
		$data['jmlMeetingLastMonthEachRoom'] = $this->db->query("SELECT rooms.name,count(*) as applied,count(if(status='Disetujui',1,NULL)) as approved from meetings
			join rooms on meetings.room=rooms.id
			where MONTH(end) = MONTH(NOW())-1
			group by room
			order by applied")->result();
		$data['avgInvitedUser'] = 0;
		$tmp = $this->db->query("
			SELECT rooms.name,sum(length(participants) - length(replace(participants,',','')) + 1) as jmlInvitedUser, count(*) as jmlRapat,
			sum(length(participants) - length(replace(participants,',','')) + 1)/count(*) as avgInvitedUserPerRapat
			from meetings
			join rooms on rooms.id=meetings.room
			where MONTH(end) = MONTH(NOW())-1
			and MONTH(start) = MONTH(NOW())-1
			and status = 'Disetujui'
			group by room;
			")->result_array(); //avg among all meeting yg Disetujui, last month, per room
		if(count($tmp)>0){
			$data['avgInvitedUser'] = array_sum(array_column($tmp,'avgInvitedUserPerRapat'))/count($tmp);
		}
		$data['waitingApprovalDelay'] =  $this->db->query("
			SELECT format(avg(datediff(modified,created)),2) as c
			from meetings
			where status != 'Menanti pengecekan'
			and modified != 0 
			")->row()->c;	//compare date modified & date created, where status is Ditolak/Disetujui
		$data['attendanceRatePerMeeting'] = 0;
		$tmp = $this->db->query("
			SELECT mp.meeting, count(*) as invited, count(if(mp.status=1,1,NULL)) as attend, format(count(if(mp.status=1,1,NULL))/count(*),2) as attendanceRate
			from meeting_participants mp
			join meetings m on m.id = mp.meeting
			where m.status = 'Disetujui'
			and m.end < NOW()
			group by mp.meeting
			")->result_array(); 
		if(count($tmp)){
			$data['attendanceRatePerMeeting'] = array_sum(array_column($tmp,'attendanceRate'))/count($tmp);
		}
		$data['avgNumMeetingWeekly'] = $this->db->query("select format(avg(c),2) as c from (SELECT count(*) c from meetings where status='Disetujui' group by WEEK(start)) as t")->row()->c;

		
		// yg paling sering diundang
		// yg paling sering hadir
		// yg paling sering ga hadir
		// yg paling sering dipake
		// rapat yg sequel nya paling banyak
		// jml orang yg diundang rapat rata2 per pekan
		// jml meeting per orang per bulan
		
		
		
		

		// $data['jmlMeetingLastMonthEachRoom_max'] = $q;
		
		//jml mhs per angkatan
		//jml meeting per durasi
		
		//jml booking (all status) per month
		///////     $dateField = 'created';
		///////     $q = $this->db->query("
		///////     	Select YEAR($dateField) AS year, MONTH($dateField) AS month, COUNT(*) as c
		///////     	FROM meetings WHERE $dateField != 0
		///////     	GROUP BY YEAR($dateField), MONTH($dateField) ORDER BY Year, Month");
		///////     $data['numBookingMadeMonthly'] = $q->result();
    ///////     
		///////     //jml approved booking per month
		///////     $dateField = 'start';
		///////     $q = $this->db->query("
		///////     	Select YEAR($dateField) AS year, MONTH($dateField) AS month, COUNT(*) as c
		///////     	FROM meetings WHERE $dateField != 0 AND status != 'Ditolak' AND status != 'Menanti pengecekan'
		///////     	GROUP BY YEAR($dateField), MONTH($dateField) ORDER BY Year, Month");
		///////     $data['numBookingMonthly'] = $q->result();
		///////     
		///////     //jml approved booking per month per room
		///////     $dateField = 'start';
		///////     $q = $this->db->query("
		///////     	Select YEAR($dateField) AS year, MONTH($dateField) AS month, COUNT(*) as c
		///////     	FROM meetings WHERE $dateField != 0 AND status != 'Ditolak' AND status != 'Menanti pengecekan'
		///////     	GROUP BY YEAR($dateField), MONTH($dateField), room ORDER BY Year, Month");
		///////     $data['numBookingMonthly'] = $q->result();
		///////     
		///////     // jml undangan rapat per month
		///////     $dateField = 'tgl_undangan';
		///////     $q = $this->db->query("
		///////     	Select YEAR($dateField) AS year, MONTH($dateField) AS month, COUNT(*) as c
		///////     	FROM meetings WHERE $dateField != 0 AND status = 'Undangan sudah dikirim'
		///////     	GROUP BY YEAR($dateField), MONTH($dateField) ORDER BY Year, Month");
		///////     $data['numUndanganMonthly'] = $q->result();
    ///////     
		///////     // jml undangan rapat per month per room
		///////     $dateField = 'tgl_undangan';
		///////     $q = $this->db->query("
		///////     	Select YEAR($dateField) AS year, MONTH($dateField) AS month, COUNT(*) as c
		///////     	FROM meetings WHERE $dateField != 0 AND status = 'Undangan sudah dikirim'
		///////     	GROUP BY YEAR($dateField), MONTH($dateField), room ORDER BY Year, Month");
		///////     $data['numUndanganMonthly'] = $q->result();
		
		// jml meeting yg didanai DTE
		// jml meeting yg didanai ventura
		// jml booking by dosen & admin
		// jml booking by mhs
		// jml meeting per pekan
		// average kehadiran per meeting tiap waktu
		// yg paling sering meeting
		// utilisasi tiap ruangan per pekan (unit: jam)

		// echo '<pre>';
		// print_r($data);
		// die();
		
		return $data;
	}

}