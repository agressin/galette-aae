			<form class="form-horizontal" action="lostpasswd.php" method="post" enctype="multipart/form-data">
			  <div class="row">
			  	<div class="col-xs-12">
				  {_T string="If you know your login or email address, thank you to use the form below to retrieve your password (you will be emailed). Otherwise, thank you to contact the administrator by mail at the following address:"} <a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>. </br></br>
				</div>
			  </div>
			  <div class="form-group row">
				<label for="login" class="col-sm-2 control-label">{_T string="Username or email:"}</label>
				<div class="col-sm-4">
				  <input class="form-control" name="login" id="login" placeholder="{_T string="Username or email"}">
				</div>
			  </div>
			  <div class="form-group row">
				<div class="col-sm-offset-2 col-sm-10">
				  <button type="submit" name="lostpasswd"  class="btn btn-primary">{_T string="Recover password"}</button>
				  <input type="hidden" name="valid" value="1"/>
				</div>
			  </div>
			</form>
