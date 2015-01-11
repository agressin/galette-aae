<!DOCTYPE html>
<html lang="{$galette_lang}" class="public_page{if $additionnal_html_class} {$additionnal_html_class}{/if}">
    <head>
        {include file='bootstrap_header.tpl'}
{if $require_calendar}
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.datepicker.min.js"></script>
    {if $galette_lang ne 'en'}
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/i18n/jquery.ui.datepicker-{$galette_lang}.min.js"></script>
    {/if}
{/if}
{if $require_dialog}
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.mouse.min.js"></script>
        {* Drag component, only used for Dialog for the moment *}
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.draggable.min.js"></script>
        {* So the dialog could be aligned in the middle of the screen *}
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.dialog.min.js"></script>
{/if}
{* If some additionnals headers should be added from plugins, we load the relevant template file
We have to use a template file, so Smarty will do its work (like replacing variables). *}
{if $headers|@count != 0}
    {foreach from=$headers item=header}
        {include file=$header}
    {/foreach}
{/if}
{if $head_redirect}
    <meta http-equiv="refresh" content="{$head_redirect.timeout};url={$head_redirect.url}" />
{/if}
    </head>
    <body class="page full-width ">
        {* IE7 and above are no longer supported *}
        <!--[if lt IE 8]>
        <div id="oldie">
            <p>{_T string="Your browser version is way too old and no longer supported in Galette for a while."}</p>
            <p>{_T string="Please update your browser or use an alternative one, like Mozilla Firefox (http://mozilla.org)."}</p>
        </div>
        <![endif]-->

	<!-- BOOSTRAP  -->
	<div class="col-width">
	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<div id="head-banner-img">
				<a href="{$galette_base_path}index.php" rel="home">
					<img src="{$galette_base_path}/templates/bootstrap/images/cropped-AAE_bandeau.png" width="100%" height=250 alt="">
				</a>
			</div>
			{*
			<h1 class="site-title"><a href="{$galette_base_path}index.php" rel="home">{$preferences->pref_nom}</a></h1>
			{if $preferences->pref_slogan}
				<h2 class="site-description">{$preferences->pref_slogan}</h2>
			{/if}
			*}
		</div>
	{if $GALETTE_MODE eq 'DEMO'}
        <div id="demo" title="{_T string="This application runs under DEMO mode, all features may not be available."}">
            {_T string="Demonstration"}
        </div>
	{/if}
	</header>	

	<nav id="site-navigation" class="main-navigation" role="navigation">
		<h1 class="menu-toggle">Menu</h1>
			<a class="skip-link screen-reader-text" href="#content">Skip to content</a>
			<div class="nav-menu">
				<ul>
					<li class="{if $PAGENAME eq "index.php"}current_{/if}page_item">
						<a href="{$galette_base_path}index.php">{_T string="Home"}</a>
					</li>
    {if !$login->isLogged()}
        {if $preferences->pref_bool_selfsubscribe eq true}
        			<li class="{if $PAGENAME eq "self_adherent.php"}current_{/if}page_item">
						<a href="{$galette_base_path}self_adherent.php">{_T string="Subscribe"}</a>
					</li>
        {/if}
        <!--
        {if $pref_mail_method neq constant('Galette\Core\GaletteMail::METHOD_DISABLED')}
        			<li class="{if $PAGENAME eq "lostpasswd.php"}current_{/if}page_item">
						<a href="{$galette_base_path}lostpasswd.php">{_T string="Lost your password?"}</a>
					</li>
        {/if}
        -->
    {/if}
    {if $preferences->showPublicPages($login) eq true}
            		<li class="{if $PAGENAME eq "liste_membres.php"}current_{/if}page_item">
						<a href="{$galette_base_path}public/liste_membres.php">{_T string="Members list"}</a>
					</li>
			<!--
            		<li class="{if $PAGENAME eq "trombinoscope.php"}current_{/if}page_item">
						<a href="{$galette_base_path}public/trombinoscope.php">{_T string="Trombinoscope"}</a>
					</li>
			-->
            {* Include plugins menu entries *}
            {$plugins->getPublicMenus($tpl, $preferences, true)}
    {/if}

    {if $login->isLogged()}
					<li class="page_item page_item_has_children">
					{$login->loggedInAs(true)}
						<ul class='children'>
							<li class="page_item">
								<a href="{$galette_base_path}voir_adherent.php">{_T string="View your member card"}</a>
							</li>
							<li class="page_item">
						    	<a  href="{$galette_base_path}index.php?logout=1">{_T string="Log off"}</a>
						    </li>
						</ul>
					</il>
	{/if}

			{foreach item=langue from=$languages}
							<li class="page_item">
								<a href="?pref_lang={$langue->getID()}"><img src="{$langue->getFlag()}" alt="{$langue->getName()}" lang="{$langue->getAbbrev()}" class="flag"/>  </a>
							</li>
			{/foreach}


				</ul>
			</div>
	</nav><!-- #site-navigation -->	

	
		<div id="content" class="site-content">

			<div id="primary" class="content-area">
				<div id="main" class="site-main" role="main">
<!-- <article class="post type-post status-publish format-standard hentry"> -->
<article class="page">
	<header class="entry-header">
		<h1 class="entry-title">{$page_title}</h1>   
    </header><!-- .entry-header -->
        {include file="global_messages.tpl"}
        {$content}
</article><!-- #post-## -->

			</div><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->
        {include file="footer.tpl"}
</div><!-- .col-width -->
    </body>
</html>
