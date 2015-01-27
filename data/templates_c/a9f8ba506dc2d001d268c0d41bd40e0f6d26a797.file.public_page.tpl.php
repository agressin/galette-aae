<?php /* Smarty version Smarty-3.1.19, created on 2015-01-27 19:37:12
         compiled from "/var/www/aae-ensg/templates/bootstrap/public_page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:103852909554a3ef86a71929-85899944%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9f8ba506dc2d001d268c0d41bd40e0f6d26a797' => 
    array (
      0 => '/var/www/aae-ensg/templates/bootstrap/public_page.tpl',
      1 => 1422383826,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '103852909554a3ef86a71929-85899944',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_54a3ef86c9abe3_24555805',
  'variables' => 
  array (
    'galette_lang' => 0,
    'additionnal_html_class' => 0,
    'require_calendar' => 0,
    'jquery_dir' => 0,
    'jquery_ui_version' => 0,
    'require_dialog' => 0,
    'headers' => 0,
    'header' => 0,
    'head_redirect' => 0,
    'galette_base_path' => 0,
    'GALETTE_MODE' => 0,
    'PAGENAME' => 0,
    'login' => 0,
    'preferences' => 0,
    'pref_mail_method' => 0,
    'tpl' => 0,
    'plugins' => 0,
    'languages' => 0,
    'langue' => 0,
    'page_title' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54a3ef86c9abe3_24555805')) {function content_54a3ef86c9abe3_24555805($_smarty_tpl) {?><?php if (!is_callable('smarty_function__T')) include '/var/www/aae-ensg/includes/../includes/smarty_plugins/function._T.php';
?><!DOCTYPE html>
<html lang="<?php echo $_smarty_tpl->tpl_vars['galette_lang']->value;?>
" class="public_page<?php if ($_smarty_tpl->tpl_vars['additionnal_html_class']->value) {?> <?php echo $_smarty_tpl->tpl_vars['additionnal_html_class']->value;?>
<?php }?>">
    <head>
        <?php echo $_smarty_tpl->getSubTemplate ('bootstrap_header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php if ($_smarty_tpl->tpl_vars['require_calendar']->value) {?>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['jquery_dir']->value;?>
jquery-ui-<?php echo $_smarty_tpl->tpl_vars['jquery_ui_version']->value;?>
/jquery.ui.datepicker.min.js"></script>
    <?php if ($_smarty_tpl->tpl_vars['galette_lang']->value!='en') {?>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['jquery_dir']->value;?>
jquery-ui-<?php echo $_smarty_tpl->tpl_vars['jquery_ui_version']->value;?>
/i18n/jquery.ui.datepicker-<?php echo $_smarty_tpl->tpl_vars['galette_lang']->value;?>
.min.js"></script>
    <?php }?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['require_dialog']->value) {?>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['jquery_dir']->value;?>
jquery-ui-<?php echo $_smarty_tpl->tpl_vars['jquery_ui_version']->value;?>
/jquery.ui.mouse.min.js"></script>
        
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['jquery_dir']->value;?>
jquery-ui-<?php echo $_smarty_tpl->tpl_vars['jquery_ui_version']->value;?>
/jquery.ui.draggable.min.js"></script>
        
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['jquery_dir']->value;?>
jquery-ui-<?php echo $_smarty_tpl->tpl_vars['jquery_ui_version']->value;?>
/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['jquery_dir']->value;?>
jquery-ui-<?php echo $_smarty_tpl->tpl_vars['jquery_ui_version']->value;?>
/jquery.ui.dialog.min.js"></script>
<?php }?>

<?php if (count($_smarty_tpl->tpl_vars['headers']->value)!=0) {?>
    <?php  $_smarty_tpl->tpl_vars['header'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['header']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['headers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['header']->key => $_smarty_tpl->tpl_vars['header']->value) {
$_smarty_tpl->tpl_vars['header']->_loop = true;
?>
        <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['header']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    <?php } ?>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['head_redirect']->value) {?>
    <meta http-equiv="refresh" content="<?php echo $_smarty_tpl->tpl_vars['head_redirect']->value['timeout'];?>
;url=<?php echo $_smarty_tpl->tpl_vars['head_redirect']->value['url'];?>
" />
<?php }?>
    </head>
    <body class="page full-width ">
        
        <!--[if lt IE 8]>
        <div id="oldie">
            <p><?php echo smarty_function__T(array('string'=>"Your browser version is way too old and no longer supported in Galette for a while."),$_smarty_tpl);?>
</p>
            <p><?php echo smarty_function__T(array('string'=>"Please update your browser or use an alternative one, like Mozilla Firefox (http://mozilla.org)."),$_smarty_tpl);?>
</p>
        </div>
        <![endif]-->

	<!-- BOOSTRAP  -->
	<div class="col-width">
	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<div id="head-banner-img">
				<a href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
index.php" rel="home">
					<img src="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
/templates/bootstrap/images/cropped-AAE_bandeau.png" width="100%" height=250 alt="">
				</a>
			</div>
			
		</div>
	<?php if ($_smarty_tpl->tpl_vars['GALETTE_MODE']->value=='DEMO') {?>
        <div id="demo" title="<?php echo smarty_function__T(array('string'=>"This application runs under DEMO mode, all features may not be available."),$_smarty_tpl);?>
">
            <?php echo smarty_function__T(array('string'=>"Demonstration"),$_smarty_tpl);?>

        </div>
	<?php }?>
	</header>	

	<nav id="site-navigation" class="main-navigation" role="navigation">
		<h1 class="menu-toggle">Menu</h1>
			<a class="skip-link screen-reader-text" href="#content">Skip to content</a>
			<div class="nav-menu">
				<ul>
					<li class="<?php if ($_smarty_tpl->tpl_vars['PAGENAME']->value=="index.php") {?>current_<?php }?>page_item">
						<a href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
index.php"><?php echo smarty_function__T(array('string'=>"Home"),$_smarty_tpl);?>
</a>
					</li>
    <?php if (!$_smarty_tpl->tpl_vars['login']->value->isLogged()) {?>
        <?php if ($_smarty_tpl->tpl_vars['preferences']->value->pref_bool_selfsubscribe==true) {?>
        			<li class="<?php if ($_smarty_tpl->tpl_vars['PAGENAME']->value=="self_adherent.php") {?>current_<?php }?>page_item">
						<a href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
self_adherent.php"><?php echo smarty_function__T(array('string'=>"Subscribe"),$_smarty_tpl);?>
</a>
					</li>
        <?php }?>
        <!--
        <?php if ($_smarty_tpl->tpl_vars['pref_mail_method']->value!=constant('Galette\Core\GaletteMail::METHOD_DISABLED')) {?>
        			<li class="<?php if ($_smarty_tpl->tpl_vars['PAGENAME']->value=="lostpasswd.php") {?>current_<?php }?>page_item">
						<a href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
lostpasswd.php"><?php echo smarty_function__T(array('string'=>"Lost your password?"),$_smarty_tpl);?>
</a>
					</li>
        <?php }?>
        -->
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['preferences']->value->showPublicPages($_smarty_tpl->tpl_vars['login']->value)==true) {?>
            		<li class="<?php if ($_smarty_tpl->tpl_vars['PAGENAME']->value=="liste_membres.php") {?>current_<?php }?>page_item">
						<a href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
public/liste_membres.php"><?php echo smarty_function__T(array('string'=>"Members list"),$_smarty_tpl);?>
</a>
						<ul class='children'>
							<li class="page_item">
						    	<a href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
public/trombinoscope.php"><?php echo smarty_function__T(array('string'=>"Trombinoscope"),$_smarty_tpl);?>
</a>
						    </li>
						</ul>
					</li>
			<!--
            		<li class="<?php if ($_smarty_tpl->tpl_vars['PAGENAME']->value=="trombinoscope.php") {?>current_<?php }?>page_item">
						<a href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
public/trombinoscope.php"><?php echo smarty_function__T(array('string'=>"Trombinoscope"),$_smarty_tpl);?>
</a>
					</li>
			-->
            
            <?php echo $_smarty_tpl->tpl_vars['plugins']->value->getPublicMenus($_smarty_tpl->tpl_vars['tpl']->value,$_smarty_tpl->tpl_vars['preferences']->value,true);?>

    <?php }?>

    <?php if ($_smarty_tpl->tpl_vars['login']->value->isLogged()) {?>
					<li class="page_item">
					<a href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
voir_adherent.php"><?php echo $_smarty_tpl->tpl_vars['login']->value->login;?>
</a>
						<ul class='children'>
							<li class="page_item">
						    	<a href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
voir_adherent.php" title="<?php echo smarty_function__T(array('string'=>"View my member card"),$_smarty_tpl);?>
"><?php echo smarty_function__T(array('string'=>"My information"),$_smarty_tpl);?>
</a>
						    </li>			
							<li class="page_item">
						    	<a  href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
index.php?logout=1"><?php echo smarty_function__T(array('string'=>"Log off"),$_smarty_tpl);?>
</a>
						    </li>
						</ul>
					</il>
	<?php }?>

			<?php  $_smarty_tpl->tpl_vars['langue'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['langue']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['languages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['langue']->key => $_smarty_tpl->tpl_vars['langue']->value) {
$_smarty_tpl->tpl_vars['langue']->_loop = true;
?>
							<li class="page_item">
								<a href="?pref_lang=<?php echo $_smarty_tpl->tpl_vars['langue']->value->getID();?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['langue']->value->getFlag();?>
" alt="<?php echo $_smarty_tpl->tpl_vars['langue']->value->getName();?>
" lang="<?php echo $_smarty_tpl->tpl_vars['langue']->value->getAbbrev();?>
" class="flag"/>  </a>
							</li>
			<?php } ?>


				</ul>
			</div>
	</nav><!-- #site-navigation -->	

	
		<div id="content" class="site-content">

			<div id="primary" class="content-area">
				<div id="main" class="site-main" role="main">
<!-- <article class="post type-post status-publish format-standard hentry"> -->
<article class="page">
	<header class="entry-header">
		<h1 class="entry-title"><?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
</h1>   
    </header><!-- .entry-header -->
        <?php echo $_smarty_tpl->getSubTemplate ("global_messages.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

</article><!-- #post-## -->

			</div><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->
        <?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</div><!-- .col-width -->
    </body>
</html>
<?php }} ?>
