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
                  <td colspan="2">Jenis Surat</td>
                  <td>
                    <select  id="jenis" name="jenis" class="form-control" required="">
                      <option disabled selected value> -- Silahkan pilih -- </option>
                      <?php foreach($jenis_surat as $row):?>
                        <option value="<?php echo $row->id;?>" <?php echo ($jenis == $row->id) ? "selected": "" ?>><?php echo $row->name;?></option>

                      <?php endforeach;?>    
                    </select>
                  </td>
                </tr>
                <?php if($this->session->role=='admin'){ ?>
                  <tr>
                    <td colspan="2">User</td>
                    <td>
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
              </table>


              <div class="modal-footer">
               <a href="<?php echo site_url('keterangan/') ?>" class="btn btn-sm btn-success" ><span class="fa fa-close"></span> Back</a>

               <button class="btn btn-info" >Next</button>
             </div>  
           </form>
		      	</div>
		      </div>
	      </div>
  		</div>
  	</section>
<script src="<?=asset_url('plugins/jQuery/jquery-2.2.3.min.js');?>"></script>
<script type="text/javascript">
    $(document).ready(function(){      
      var i=1;       
      $('#jenis').change(function(){
            var id=$(this).val();
            $.ajax({
                url : "<?php echo base_url();?>Keterangan/get_fields",
                method : "POST",
                data : {id: id},
                async : false,
                dataType : 'json',
                success: function(data){
                    var html = '';
                    var i;                    
                    
                        html += '<option>'+data.fields+'</option>';
                    
                    $('.subkategori').html(html);
                     
                }
            });
         
 });

    });  
</script>

<?php $this->load->view('parts/footer',['add'=>$scriptFooter]); ?>
