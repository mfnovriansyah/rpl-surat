<?php 
if(!isset($scriptBeforeJQuery)){$scriptBeforeJQuery = '';}
if(!isset($scriptAfterJQuery)){$scriptAfterJQuery = '';}
if(!isset($scriptFooter)){$scriptFooter = '';}
$this->load->view('parts/header',['add' => $scriptBeforeJQuery, 'add2' => $scriptAfterJQuery]); ?>

   <!-- Main content -->
    <section class="content">
      <div class="row">
	      <div class="col-md-12">
		      <div class="box">
		      	<div class="box-body">
		      		<form class="form-horizontal" method="post" action="<?php echo base_url().'keterangan/'.$urlfields?>">
                 
                  <table class="table table-bordered table-condensed" style="font-size:11px;" id="dynamic_field">
                  <?php $i=0;
                  foreach ($fields as $row): ?>
                       <tr > 
                       
                          <td><input type="text" name="fields[]" placeholder="masukan fields" class="form-control name_list" required="" value='<?php echo $row;?>'/>
                          </td>
                          <td>
                             <select  id="jenis_text[]" name="jenis_text[]" class="form-control" required="">
                                      <option selected value="text"<?php echo ($jenis_text[$i] == 'text') ? "selected": "" ?>>Text</option>
                                      <option value="textarea"<?php echo ($jenis_text[$i] == 'textarea') ? "selected": "" ?>>Text Area</option>
                              </select>
                          </td> 
                          <td><input type="text" name="hint[]" placeholder="hint" class="form-control name_list" value='<?php echo $hint[$i];?>'/>
                          </td> 
                          <?php if($i == 0){ ?>
                              <td><button type="button" name="add" id="add" class="btn btn-success">Tambah lagi</button></td>
                          <?php } else{ ?>
                                <td><button type="button" name="remove" onclick="functionDeleteRow(this)" class="btn btn-danger btn_remove">X</button></td>
                          <?php } $i++; endforeach; ?> 
                       
                      </tr> 
                    
                      </table>
                </div>   
                <div class="modal-footer">
                    <?php if($this->uri->segment(3) != ''){ ?>
                      <a href="<?php echo site_url('keterangan/edit_template/'.$this->uri->segment(3).'') ?>" class="btn btn-sm btn-success" ><span class="fa fa-close"></span> Back</a>
                    <?php } else{ ?>
                      <a href="<?php echo site_url('keterangan/add_template') ?>" class="btn btn-sm btn-success" ><span class="fa fa-close"></span> Back</a>
                    <?php }?>
                    <button class="btn btn-info">Next</button>
                    <input type="hidden" name="name" value="<?php echo $name?>">
                    <input type="hidden" name="status" value="<?php echo $status?>">
                    <input type="hidden" name="nomor" value="<?php echo $nomor?>">
                    <input type="hidden" name="penandatangan" value="<?php echo $penandatangan?>">

                </div>  
		      </div>
	      </div>
  		</div>
  	</section>
  

<?php $this->load->view('parts/footer',['add'=>$scriptFooter]); ?>
<script type="text/javascript">
  $(document).ready(function(){ 
    var i=1;   
    $('#add').click(function(){  

     i++;  
     $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="text" name="fields[]" placeholder="masukan fields" class="form-control name_list" required /></td><td><select  id="jenis_text[]" name="jenis_text[]" class="form-control"><option selected value="text">Text</option><option value="textarea">Text Area</option></select></td>  </td><td><input type="text" name="hint[]" placeholder="hint" class="form-control name_list"/></td>  <td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
   });
  });  
  function functionDeleteRow(o) {
     //no clue what to put here?
     var p=o.parentNode.parentNode;
         p.parentNode.removeChild(p);
    }
</script>
