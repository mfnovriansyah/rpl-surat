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
		      		<form class="form-horizontal" method="post" action="<?php echo base_url().'keterangan/'.$urlfields?>">

                <table class="table table-bordered table-condensed" style="font-size:11px;">
                  <tr>
                    <th colspan="2">Form</th> 
                  </tr>
                  <?php $count = 0; foreach ($fields as $field):
                  echo '<tr>' ;?>
                  <td><?php echo $field ?></td>

                  <?php
                  if($jenis_text[$count]== 'textarea')
                  {
                    if($this->uri->segment(2)!='add_content_fields'){
                    echo '<td rowspan="2"><textarea id="'.$field.'"name="'.$field.'"class="form-control name_list" required="" value="'.$isi_fields[$count].'" ></textarea>'.$hint[$count];'</td>';
                    }
                    else{
                      echo '<td rowspan="1"><textarea id="'.$field.'"name="'.$field.'"class="form-control name_list" required="" ></textarea>'.$hint[$count];'</td>';
                    }
                  }
                  else
                  {
                    if($this->uri->segment(2)!='add_content_fields'){
                      echo '<td><input type="'.$jenis_text[$count].' "name="'.$field.'" class="form-control name_list" required="" value="'.$isi_fields[$count].'" >'.$hint[$count];'</td>' ;
                    }
                    else{
                    echo '<td><input type="'.$jenis_text[$count].' "name="'.$field.'" class="form-control name_list" required="">'.$hint[$count];'</td>' ;
                    }
                  }
                  $count++;
                  ?>
                <?php endforeach; ?>

              </table>
              <div class="modal-footer">
                <?php if($this->uri->segment(3) != ''){ ?>
                  <a href="<?php echo site_url('keterangan/edit_surat/'.$this->uri->segment(3).'') ?>" class="btn btn-sm btn-success" ><span class="fa fa-close"></span> Back</a>
                <?php } else{ ?>
                  <a href="<?php echo site_url('keterangan/add_surat') ?>" class="btn btn-sm btn-success" ><span class="fa fa-close"></span> Back</a>
                <?php }?>
               
               <input type="hidden" name="id_jenis" value="<?php echo $id_jenis?>">
               <input type="hidden" name="user" value="<?php echo $user?>">

               <button class="btn btn-info" >Next</button>
             </div>  
           </form>
		      	</div>
		      </div>
	      </div>
  		</div>
  	</section>

<?php $this->load->view('parts/footer',['add'=>$scriptFooter]); ?>
<script src="<?php echo base_url().'assets/js/summernote.js'?>"></script>
<script>
   <?php $count = 0; foreach ($fields as $field){ ?>
   $('#<?php echo $field?>').summernote({
              height: 300,
              weight: 150
          });
  <?php } ?>
  <?php $count = 0; foreach ($fields as $field){
    if($this->uri->segment(2)!='add_content_fields'){ ?>
    $('#<?php echo $field?>').summernote({
              height: 300,
              weight: 150
          }).summernote('editor.pasteHTML', '<?php echo $isi_fields[$count] ?>');
  <?php } 
    else { ?>
    
  <?php } 

    $count++;}?>

</script>