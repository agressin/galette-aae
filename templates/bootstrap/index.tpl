{if $loginfault}
                <div id="errorbox">{_T string="Login failed."}</div>
{/if}   
			<form class="form-horizontal" action="index.php" method="post">
			  <div class="form-group">
				<label for="login" class="col-sm-2 control-label">{_T string="Username:"}</label>
				<div class="col-xs-4">
				  <input class="form-control" name="login" id="login" placeholder="{_T string="Username"}">
				</div>
			  </div>
			  <div class="form-group">
				<label for="password" class="col-sm-2 control-label">{_T string="Password:"}</label>
				<div class="col-xs-4">
				  <input type="password" class="form-control" name="password" id="password" placeholder="{_T string="Password"}">
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <button type="submit" class="btn btn-primary">{_T string="Login"}</button>
				  <input type="hidden" name="ident" value="1" />
				</div>
			  </div>
			</form>
