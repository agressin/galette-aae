			<form class="form-horizontal" action="lostpasswd.php" method="post" enctype="multipart/form-data">
			  <div class="form-group">
				<label for="login" class="col-sm-2 control-label">{_T string="Username or email:"}</label>
				<div class="col-xs-4">
				  <input class="form-control" name="login" id="login" placeholder="{_T string="Username or email"}">
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <button type="submit" name="lostpasswd"  class="btn btn-primary">{_T string="Recover password"}</button>
				  <input type="hidden" name="valid" value="1"/>
				</div>
			  </div>
			</form>
