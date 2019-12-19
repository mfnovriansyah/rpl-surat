<?php 
$data = ['simple' => true, 'add_body_class' => 'login-page', 'title' => 'Login'];
$this->load->view('parts/header', $data); ?>
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>D'</b>Office</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <div class="social-auth-links text-center">
      <a href="<?php echo base_url('login/sso?ref=').urlencode($ref);?>" class="btn btn-block btn-social btn-openid btn-flat" ><i class="fa fa-user"></i> Sign in using SSO UI</a>
      <br><p>or using local credentials<br>(some users only)</p>
    </div>

    <form action="<?=base_url('login/do');?>" method="post" id="login">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="username" name="username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <!--label>
              <input type="checkbox" name="remember_me"> Remember Me
            </label-->
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!-- /.social-auth-links -->

    <a data-toggle="collapse" href="#resetPass" role="button" aria-expanded="false" aria-controls="resetPass">I forgot my password</a>
		<div class="collapse" id="resetPass">
			<form action="<?=base_url('login/resetpassword');?>" method="post" id="reset">
				<div class="form-group has-feedback">
					<input type="text" class="form-control" placeholder="email" name="email">
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-5">
						<button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
					</div>
				</div>
			</form>
		</div>

		
    <!--a href="<?=base_url('register');?>" class="text-center">Register a new membership</a-->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<?php 
$data = ['add' => '<!-- iCheck -->
<script src="'.asset_url('plugins/iCheck/icheck.min.js').'"></script>
<script>
  $(function () {
    $(\'input\').iCheck({
      checkboxClass: \'icheckbox_square-blue\',
      radioClass: \'iradio_square-blue\',
      increaseArea: \'20%\' // optional
    });

    $("#login").submit(function() {
      var $this = $(this);
      $.ajax({
        url: "'.base_url('login/do').'",
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
            document.location = "'.$ref.'";
          }else{
            alert("Username or password incorrect.")
          }
        }
      })
      return false;
    });
  });
</script>'];
$this->load->view('parts/footer', $data); ?>