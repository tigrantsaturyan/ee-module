<?php
  $register = '
<?php
  if(isset($_SESSION["user_id"])){
      return ee()->functions->redirect("http://".$_SERVER["SERVER_NAME"]."/index.php/auth/account");
    }
?>
{html_head}
    <title>{site_name} | <?=lang("create_account")?></title>
{global_stylesheets}
{global_auth_css}
{rss}
{favicon}
{html_head_end}
    <body>
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-md-4 col-md-offset-4 no_margin_top">
            <h1 class="text-center login-title"><?=lang("create_account")?></h1>
            <div class="account-wall">
              {exp:auth:register}
                <input type="text" class="form-control" placeholder="<?=lang("first_name")?>" name="first_name" autofocus  >
                <span class="error">{error:first_name}</span>
                <input type="text" class="form-control" placeholder="<?=lang("last_name")?>" name="last_name"  >
                <span class="error">{error:last_name}</span>
                <input type="date" class="form-control" placeholder="<?=lang("birthday")?>" name="birthday" >
                <span class="error">{error:birthday}</span>
                <input type="text" class="form-control" placeholder="<?=lang("email")?>" name="email" >
                <span class="error">{error:email}</span>
                <input type="password" class="form-control" placeholder="<?=lang("password")?>" name="password" >
                <span class="error">{error:password}</span>
                <input type="password" class="form-control" placeholder="<?=lang("confirm_password")?>" name="confirm_password" >
                <span class="error">{error:confirm_password}</span>
                <input type="file" class="form-control" name="avatar" accept="image/jpeg,image/png,image/gif" >
                <span class="error">{error:avatar}</span>
                <input type="submit" class="btn btn-lg btn-primary btn-block" name="add_member" value="<?=lang("save")?>">
              {/exp:auth:register}
            </div>
            <a href="../auth" class="text-center new-account"><?=lang("login_button")?></a>
          </div>
        </div>
      </div>
{html_close}';
