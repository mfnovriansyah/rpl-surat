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
                 
                  <table class="table table-bordered table-condensed" style="font-size:11px;">
                       <tr>
                            <td colspan="2">Nama Template</td>
                            <td colspan="5"><input type='text' name='name' class='form-control' value='<?php echo $name?>' required />
                           </td>
                        </tr>
                        <tr>	
                        	<td colspan="2">Status</td>
	                        <td>
	                           <select  id="status" name="status" class="form-control" required="">
	                                    <option disabled selected value> -- Silahkan pilih -- </option>
	                                    <option value="Active" <?php echo ($status == 'Active') ? "selected": "" ?>>Active</option>
	                                    <option value="Inactive" <?php echo ($status == 'Inactive') ? "selected": "" ?>>Inactive</option>
	                            </select>
	                        </td> 
	                    </tr>
                        <tr>
                            <td colspan="2">Nomor Surat</td>
                            <td colspan="5"><input type='text' name='nomor' class='form-control' value='<?php echo $nomor?>' required />
                           </td>
                        </tr>
                        <tr>	
                        	<td colspan="2">Penandatangan</td>
	                        <td>
	                           <select  id="status" name="penandatangan" class="form-control" required="">
	                                    <option disabled selected value> -- Silahkan pilih -- </option>
                                       <?php 
                                      $jabatan = "";
                                       foreach($user_jabatan as $row):?>
    
                                        $jabatan = $row[i];

                                        <option value="<?php echo $row->jabatan;?>" <?php echo ($penandatangan == $row->jabatan) ? "selected": "" ?>><?php echo $row->jabatan;?></option>

                                    <?php endforeach;?>
	                                   
	                            </select>
	                        </td> 
	                    </tr>
                    </table>
                     <div class="modal-footer">
                       <a href="<?php echo site_url('keterangan/jenis/') ?>" class="btn btn-sm btn-success" ><span class="fa fa-close"></span> Back</a>
                    <button class="btn btn-info">Next</button>
                    
                    </div>  
             
            </form>
		      	</div>
		      </div>
	      </div>
  		</div>
  	</section>
<?php $this->load->view('parts/footer',['add'=>$scriptFooter]); ?>
