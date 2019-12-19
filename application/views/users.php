<?php $this->load->view('parts/header', ['title' => 'Users']); ?>
   <!-- Main content -->
    <section class="content">
      <div class="row">
	      <div class="col-md-12">
		      <div class="box">
		      	<div class="box-header">
		      		<h3 class="box-title">Users</h3>
		      		<div class="box-tools">
		      			<a href="<?=base_url('users/create');?>" class="btn btn-sm btn-primary">Add New</a>
		      		</div>
		      	</div>
		      	<div class="box-body">
			      	<?php if($this->session->flashdata('msg')) { ?>
			      	<div class="alert alert-success">
				      	<?=$this->session->flashdata('msg');?>
			      	</div>
			      	<?php } ?>
				      <div class="table-responsive">
				      	<table class="table table-bordered table-stripped table-hover">
					      	<thead>
					      		<tr>
					      			<td>ID</td>
					      			<td>Name</td>
					      			<td>Username</td>
					      			<td>Email</td>
					      			<td>Action</td>
					      		</tr>					      		
					      	</thead>
					      	<tbody>
						      	<?php foreach($users as $user) { ?>
					      		<tr>
					      			<td><?=$user->id;?></td>
					      			<td><?=$user->name;?></td>
					      			<td><?=$user->username;?></td>
					      			<td><?=$user->email;?></td>
					      			<td>
					      				<a href="<?=base_url('users/edit/' . $user->id);?>" class="btn btn-sm btn-warning">Edit</a>
					      				<?php if($user->id > 1 && $user->username !== $this->session->username) { ?>
					      				<a href="<?=base_url('users/delete/' . $user->id);?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
					      				<?php }else{ ?>
					      				<a href="#" class="btn btn-sm btn-danger disabled">Delete</a>
					      				<?php } ?>					      				
					      			</td>
					      		</tr>
					      		<?php } ?>
					      	</tbody>
				      	</table>
				      </div>
		      	</div>
		      </div>
	      </div>
  		</div>
  	</section>
<?php $this->load->view('parts/footer'); ?>
