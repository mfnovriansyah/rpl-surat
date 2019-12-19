<?php $this->load->view('parts/header', ['title' => 'Users']); ?>
   <!-- Main content -->
    <section class="content">
      <div class="row">
	      <div class="col-md-12">
		      <div class="box">
		      	<div class="box-header">
		      		<h3 class="box-title">Edit User</h3>
		      	</div>
		      	<div class="box-body">
		      		<div class="row">
		      			<div class="col-md-6">
		      				<form action="<?=base_url('users/doedit/' . $user->id);?>" method="post" id="edit">
		      					<div class="form-group">
		      						<label>Name</label>
		      						<input type="text" name="name" class="form-control" placeholder="Name" value="<?=$user->name;?>">
		      					</div>
		      					<div class="form-group">
		      						<label>Username</label>
		      						<input type="text" name="username" class="form-control" placeholder="Username" value="<?=$user->username;?>" disabled>
		      						<div class="help-block">Username can't be change</div>
		      					</div>
		      					<div class="form-group">
		      						<label>Email</label>
		      						<input type="text" name="email" class="form-control" placeholder="Email" value="<?=$user->email;?>">
		      					</div>
		      					<div class="form-group">
		      						<label>Password</label>
		      						<input type="password" name="password" class="form-control" placeholder="Password" id="password_field">
		      						<div class="help-block">* Leave blank if not change</div>
		      					</div>
		      					<div class="form-group" style="display:none" id="password_confirm">
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
    $("#edit").submit(function() {
      var $this = $(this);
      $.ajax({
        url: $this.attr("action"),
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
            alert("User updated successfully");
            $this[0].reset();
            document.location = "'.base_url('users').'";
          }else if(data.success == false){
          	alert(data.data);
          }
        }
      })
      return false;
    });

    $("#password_field").on("keyup paste", function() {
    	if($(this).val().trim().length > 0) {
    		$("#password_confirm").fadeIn();
    	}else{
    		$("#password_confirm").fadeOut();
    	}
    });
  });
</script>'];
$this->load->view('parts/footer', $data); ?>
