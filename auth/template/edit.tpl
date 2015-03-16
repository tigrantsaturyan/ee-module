<?php
$edit = '
<?php
  if(!isset($_SESSION["user_id"])){
     	return ee()->functions->redirect("http://".$_SERVER["SERVER_NAME"]."/index.php/auth");
    }
?>
{html_head}
    <title>{site_name} | <?=lang("profile")?></title>
{global_stylesheets}
{global_auth_css}
{rss}
{favicon}
{html_head_end}
    <body>
      {exp:auth:getUser}
      			<div class="col-lg-9 edit">
          {exp:auth:editProfile}
			<input type="hidden" name="pass"/>
			<div class="form-group">
			  <label for="firstname" class="col-sm-2 control-label"><?=lang("first_name")?></label>
			  <div class="col-sm-10">
				<input type="text" class="form-control" id="firstname" placeholder="<?=lang("first_name")?>" name="first_name" value="{first_name}">
				<span class="error">{error:first_name}</span>
			  </div>
			</div>

			<div class="form-group">
			  <label for="lastname" class="col-sm-2 control-label"><?=lang("last_name")?></label>
			  <div class="col-sm-10">
				<input type="text" class="form-control" id="lastname" placeholder="<?=lang("last_name")?>" name="last_name" value="{last_name}">
				<span class="error">{error:last_name}</span>
			  </div>
			</div>

			<div class="form-group">
			  <label for="birthday" class="col-sm-2 control-label"><?=lang("birthday")?></label>
			  <div class="col-sm-10">
				<input type="date" class="form-control" id="birthday" name="birthday" value="{birthday}">
				<span class="error">{error:birthday}</span>
			  </div>
			</div>

			<div class="form-group">
			  <label for="login" class="col-sm-2 control-label"><?=lang("email")?></label>
			  <div class="col-sm-10">
				<input type="text" class="form-control" id="login" placeholder="Login"  name="login" value="{login}">
				<span class="error">{error:login}</span>
			  </div>
			</div>

			<div class="form-group">
			  <label for="password" class="col-sm-2 control-label"><?=lang("password")?></label>
			  <div class="col-sm-10">
				<input type="text" class="form-control" id="password" placeholder="<?=lang("password")?>" name="password">
				<span class="error">{error:password}</span>
			  </div>
			</div>

			<div class="form-group">
			  <label for="avatar" class="col-sm-2 control-label"><?=lang("avatar")?></label>
			  <div class="col-sm-10">
				<input type="file" accept="image/jpeg,image/png,image/gif" class="form-control" id="avatar" name="avatar">
				<span class="error">{error:avatar}</span>
			  </div>
			</div>

			<div class="form-group">
			  <div class="col-sm-offset-2 col-sm-10">
			     <input type="submit" class="btn btn-success" name="save_edit" value="<?=lang("save")?>" >
			  </div>
			</div>
          {/exp:auth:editProfile}
	</div>
</div>
{html_close}';
