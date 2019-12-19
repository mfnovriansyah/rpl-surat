<?php 
if(!isset($scriptBeforeJQuery)){$scriptBeforeJQuery = '';}
if(!isset($scriptAfterJQuery)){$scriptAfterJQuery = '';}
if(!isset($scriptFooter)){$scriptFooter = '';}

$this->load->view('parts/header',['add' => $scriptBeforeJQuery, 'add2' => $scriptAfterJQuery]); ?>
<link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/')?>summernote.css" rel="stylesheet">
   <!-- Main content -->
    <section class="content">
      <div class="row">
	      <div class="col-md-12">
		      <div class="box">
		      	<div class="box-body">
		      		<form class="form-horizontal" method="post" action="<?php echo base_url().'keterangan/'.$urlfields ?>"enctype="multipart/form-data">
                  <table class="table table-bordered table-condensed" style="font-size:11px;">
                    <tr> 
                      <th>Isi Surat</th>
                      <td> 
                        <textarea id="textarea" name="textarea" ><?php foreach ($fields as $row): echo "[[".$row."]]\n"?>
                        <?php endforeach; ?></textarea> 
                      </td>
                    </tr>
                    <tr>  
                      <th>Prosedur</th>
                      <td>
                        <textarea id="prosedur" name="prosedur"><?php echo $prosedur ?></textarea>
                      </td>    
                    </tr>
                    </table>
                  <div class="modal-footer">
                    <?php if($this->uri->segment(3) != ''){ ?>
                      <a href="<?php echo site_url('keterangan/edit_template/'.$this->uri->segment(3).'') ?>" class="btn btn-sm btn-success" ><span class="fa fa-close"></span> Back</a>
                    <?php } else{ ?>
                      <a href="<?php echo site_url('keterangan/add_template') ?>" class="btn btn-sm btn-success" ><span class="fa fa-close"></span> Back</a>
                    <?php }?>
                    <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" />  
                    <input type="hidden" name="name" value="<?php echo $name?>">
                    <input type="hidden" name="status" value="<?php echo $status?>">
                    <input type="hidden" name="nomor" value="<?php echo $nomor?>">
                    <input type="hidden" name="penandatangan" value="<?php echo $penandatangan?>">
                    <input type="hidden" name="fields[]" value="<?php echo $field?>">
                    <input type="hidden" name="jenis_text[]" value="<?php echo $jenis?>">
                    <input type="hidden" name="hint[]" value="<?php echo $hint?>">
                  </div>
            </form>
		      	</div>
		      </div>
	      </div>
  		</div>
  	</section>
<?php $this->load->view('parts/footer',['add'=>$scriptFooter]); ?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script src="<?php echo base_url().'assets/js/summernote.js'?>"></script>
<script>
 $(document).ready(function() {
  
   $('#textarea').summernote({
                height: 300,
                weight: 150
            });
    $('#prosedur').summernote({
                height: 300,
                weight: 150
            });
  var prosedur = $('#prosedur').val();
  $('#prosedur').summernote('reset');
  $('#prosedur').summernote({
    height: 300,
    weight: 150
  }).summernote('code', prosedur);
  $("#prosedur").val(prosedur);
  });


</script>
