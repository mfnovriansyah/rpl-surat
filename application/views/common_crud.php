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
		      		<?php if($this->uri->segment(1) =='keterangan' && $this->uri->segment(2) =='jenis' && $this->uri->segment(3) ==''){ ?>
		      		<button type="button" class="btn btn-outline-dark" style="margin-bottom:-100px; " > <a href="<?php echo site_url('Keterangan/add_template') ?>" ><span class="fa fa-plus"></span> Add Template</a></button>
		      		<?php } if($this->uri->segment(1) =='keterangan' && $this->uri->segment(2) =='' ){ ?>
		      		<button type="button" class="btn btn-outline-dark" style="margin-bottom:-100px;" > <a href="<?php echo site_url('Keterangan/add_surat') ?>" ><span class="fa fa-plus"></span> Add Surat</a></button>
		      		<?php } ?>
					<?php echo $output->output; ?>
		      	</div>
		      </div>
	      </div>
  		</div>
  	</section>
<?php $this->load->view('parts/footer',['add'=>$scriptFooter]); ?>
