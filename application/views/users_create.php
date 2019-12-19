<?php $this->load->view('parts/header', ['title' => 'Users']); ?>
   <!-- Main content -->
    <section class="content">
      <div class="row">
	      <div class="col-md-12">
		      <div class="box">
		      	<div class="box-header">
		      		<h3 class="box-title">Create User</h3>
		      	</div>
		      	<div class="box-body">
		      		<div class="row">
		      			<div class="col-md-6">
		      				<form action="<?=base_url('users/docreate');?>" method="post" id="create">
			      				<input type="hidden" name="dontlogin" value="true">
		      					<div class="form-group">
		      						<label>Name</label>
		      						<input type="text" name="name" class="form-control" placeholder="Name">
		      					</div>
		      					<div class="form-group">
		      						<label>Username</label>
		      						<input type="text" name="username" class="form-control" placeholder="Username">
		      					</div>
		      					<div class="form-group">
		      						<label>Email</label>
		      						<input type="text" name="email" class="form-control" placeholder="Email">
		      					</div>
		      					<div class="form-group">
		      						<label>Password</label>
		      						<input type="password" name="password" class="form-control" placeholder="Password">
		      					</div>
		      					<div class="form-group">
		      						<label>Password Confirm</label>
		      						<input type="password" name="password_confirm" class="form-control" placeholder="Password Confirm">
		      					</div>
		      					<div class="form-group">
		      						<button type="submit" class="btn btn-primary">Save Changes</button>
		      						<a href="<?=base_url('users');?>" class="btn btn-default">Back</a>
		      					</div>
		      				</form>
		      			</div>
		      		</div>
		      	</div>
		      </div>
	      </div>
  		</div>
  	</section>
<?php
$data = ['add' => '
<script>
  $(function () {
    $("#create").submit(function() {
      var $this = $(this);
      $.ajax({
        url: "'.base_url('register/do').'",
        data: $(this).serialize(),
        type: "post",
        dataType: "json",
        beforeSend: function() {
          $this.find(":input").attr("disabled", true);
        },
        complete: function() {
          $this.find(":input").attr("disabled", false);
        },
        error: function(xhr, statusText) {
          console.log(statusText)
        },
        success: function(data) {
          if(data.success == true) {
          	alert("User created successfully");
            document.location = "'.base_url('users').'";
          }else if(data.success == false){
            alert(data.data)
          }
        }
      })
      return false;
    });
  });
</script>'];
$this->load->view('parts/footer', $data); ?>
