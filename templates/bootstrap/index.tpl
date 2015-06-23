{if $loginfault}
            <div id="errorbox">{_T string="Login failed."}</div>
{/if}
			<form action="index.php" method="post">
			<div class="form-horizontal col-md-10">
				  <div class="form-group col-md-10">
						<label for="login" class="col-sm-4 control-label">{_T string="Username"}</label>
						<div class="col-sm-6">
						  <input class="form-control" name="login" id="login" placeholder="{_T string="Username"}">
						</div>
				  </div>
				  <div class="form-group col-md-10">
						<label for="password" class="control-label col-sm-4">{_T string="Password"}</label>
						<div class="col-sm-6">
							<input type="password" class="form-control" name="password" id="password" placeholder="{_T string="Password"}">
						</div
						<div class="col-sm-2">
							<a href="{$galette_base_path}lostpasswd.php">{_T string="Lost your password?"}</a>
						</div>
				  </div>
			  </div>
				<div class="col-sm-offset-3 col-sm-10">
				  	<div class="form-group">
						  <button type="submit" class="btn btn-primary">{_T string="Login"}</button>
						  <input type="hidden" name="ident" value="1" />
			  			<a class="btn btn-warning" href="{$galette_base_path}lostpasswd.php">{_T string="Logon"}</a>
				  	</div>
				</div>
			</form>
