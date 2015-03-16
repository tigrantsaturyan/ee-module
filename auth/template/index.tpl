<?php
  $index = '
<?php
	if(isset($_SESSION["user_id"])){
      return ee()->functions->redirect("http://".$_SERVER["SERVER_NAME"]."/index.php/auth/account");
    }
?>
{html_head}
    <title>{site_name} | <?=lang("login_button")?></title>
{global_stylesheets}
{global_auth_css}
{rss}
{favicon}
{html_head_end}
    <body>
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title"><?=lang("login_button")?></h1>
            <div class="account-wall">
              {exp:auth:login type="login"}
                {error:all}
                <input type="text" class="form-control" placeholder="<?=lang("email")?>" name="email" autofocus>
                <span class="error">{error:email}</span>
                <input type="password" class="form-control" placeholder="<?=lang("password")?>" name="password">
                <span class="error">{error:password}</span>
                <input type="submit" name="login" class="btn btn-lg btn-primary btn-block" value="<?=lang("login_button")?>">
              {/exp:auth:login}
            </div>
            <a href="auth/register" class="text-center new-account"><?=lang("create_account")?></a>
          </div>
        </div>
      </div>
{html_close}

  ';
?>
