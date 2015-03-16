<?php
$account = '
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
{html_close}';
