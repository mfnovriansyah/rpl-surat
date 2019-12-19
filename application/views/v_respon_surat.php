<?php 
if(!isset($scriptBeforeJQuery)){$scriptBeforeJQuery = '';}
if(!isset($scriptAfterJQuery)){$scriptAfterJQuery = '';}
if(!isset($scriptFooter)){$scriptFooter = '';}
$this->load->view('parts/header',['add' => $scriptBeforeJQuery, 'add2' => $scriptAfterJQuery]); ?>
 <link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="<?php echo base_url('assets/css/')?>summernote.css" rel="stylesheet">
   <!-- Main content -->
    <section class="content">
      <div class="row">
	      <div class="col-md-12">
		      <div class="box">
		      	<div class="box-body">
		      		<form class="form-horizontal" method="post" action="<?php echo base_url().'keterangan/'.$urlfields?>" enctype="multipart/form-data">

                <table class="table table-bordered table-condensed" style="font-size:11px;">
                  <div class="table-responsive"> 
                    <tr>
                      <td colspan="3">Jenis Surat</td>
                      <td colspan="2">
                        <select  id="jenis" name="jenis" class="form-control" required="">
                          <option disabled selected value> -- Silahkan pilih -- </option>
                          <?php foreach($jenis_surat as $row):?>
                            <option value="<?php echo $row->id;?>" <?php echo ($id_jenis == $row->id) ? "selected": "" ?>><?php echo $row->name;?></option>

                          <?php endforeach;?>    
                        </select>
                      </td>
                    </tr>
                    <?php if($this->session->role=='admin'){ ?>
                      <tr>
                        <td colspan="3">User</td>
                        <td colspan="1">
                          <select  id="user" name="user" class="form-control" required="">
                            <option disabled selected value> -- Silahkan pilih -- </option>
                            <?php 
                            // $user = "";
                            foreach($users as $row):?>
                              <option value="<?php echo $row->id;?>" <?php echo ($user == $row->id) ? "selected": "" ?>><?php echo $row->name;?></option>

                            <?php endforeach;?>    
                          </select>
                        </td>
                      <?php }  ?> 
                    </tr>
                    <tr>  
                      <th colspan="3">Isi Surat</th>
                      <td  colspan="1"><textarea id="textarea" name="textarea""><?php echo $template ?></textarea></td>
                    </tr> 
                    <tr> 
                    <tr>
                        <th colspan="3" >Lampiran</th>
                        <td class="form-group" id="download">
                            
                                   <a href="<?php echo base_url(); ?>keterangan/do_download/<?php echo $lampiran;?>"><?php echo $lampiran;?></a> | <a href="<?php echo base_url(); ?>Keterangan/delete_file/<?php echo $lampiran;?>/<?php echo $user;?>/<?php echo $id_jenis;?>/<?php echo $id;?>">Delete</a>
                           
                        </td>
                        <td class="form-group" id="upload">
                            
                                    <label>Upload File</label>
                                        <input type="file" class="form-control" name="lampiran" id="lampiran" />
                                        <input type="hidden" class="form-control" name="oldfile" id="oldfile" value='<?php echo $lampiran;?>'/>
                             
                        </td>
                    </tr>
                    <?php if($this->session->role=='admin'){ ?>
                    <tr>
                        <td colspan="3">Status</td>
                        <td colspan="1">
                             <select  id="status" name="status" class="form-control">
                                      <option disabled selected value> -- Silahkan pilih -- </option>
                                      <option value="Disetujui"<?php echo ($status == 'Disetujui') ? "selected": "" ?>>Disetujui</option>
                                      <option value="Ditolak"<?php echo ($status== 'Ditolak') ? "selected": "" ?>>Ditolak</option>
                              </select>
                          </td> 
                    </tr>
                    <tr>  
                      <th colspan="3">Catatan</th>
                      <td  colspan="1"><?php echo $catatan ?><textarea id="catatan" name="catatan""></textarea></td>
                    </tr> 
                     <?php } ?>

                </table>

                    <div class="modal-footer">
                     <a href="<?php echo site_url('keterangan/') ?>" class="btn btn-sm btn-success" ><span class="fa fa-close"></span> Back</a>
                     <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" /> 
                     <input type="hidden" name="id_jenis" value="<?php echo $id_jenis?>">
                     <input type="hidden" name="user" value="<?php echo $user?>">
                     <input type="hidden" name="isi_fields" value="<?php echo $isi_fields?>">
                    <input type="hidden" name="nomor" value="<?php echo $nomor?>">
                   </div>  
                 </form>
		      	</div>
		      </div>
	      </div>
  		</div>
  	</section>

<?php $this->load->view('parts/footer',['add'=>$scriptFooter]); ?>

<script src="<?php echo base_url().'assets/js/summernote.js'?>"></script>
<script type="text/javascript">

</script>
<script>
  $(document).ready(function() {
    if($('#oldfile').val() == '') {
      hideRow('download');
  } else {
      hideRow('upload');
  }
    <?php foreach ($Data as $namafield){ ?>
      var namafield = "<?php echo $namafield; ?>";

      <?php foreach($isi_field as $key => $value){  ?>
        var indexfield = "<?php echo $key; ?>";
        var datafield = "<?php echo $value ; ?>";
        console.log(indexfield);
        if(namafield == indexfield)
        {
          $( '#textarea' ).val( $('#textarea').val().replace( '[['+namafield+']]', datafield) ); 
        }
      <?php } ?>
    <?php }?>
  var textarea = $('#textarea').val();
  $('#textarea').summernote('reset');
  $('#textarea').summernote({
    height: 300,
    weight: 150
  }).summernote('code', textarea);
  $("#textarea").val(textarea);
  });
  $('#catatan').summernote({
    height: 300,
    weight: 150
  }).summernote();
  $('html').bind('keypress', function(e)
  {
   if(e.keyCode == 13)
   {
      return false;
   }
  });   
  
  function showRow(id) {
    var row = document.getElementById(id);
    row.style.display = '';
  }
  function hideRow(id) {
    var row = document.getElementById(id);
    row.style.display = 'none';
  }
</script>

