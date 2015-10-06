{*
These file contains common html headers to include for Galette Smarty rendering.

Just put a {include file='common_header.tpl'} into the head tag.
*}
<title>{if $pref_slogan ne ""}{$pref_slogan} - {/if}{if $page_title ne ""}{$page_title} - {/if}Galette {$GALETTE_VERSION}</title>
        <!-- <meta charset="UTF-8" />-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


        <!--
        <link rel="stylesheet" type="text/css" href="{$template_subdir}galette.css" />
        {* Let's see if a local CSS exists and include it *}
        {assign var="localstylesheet" value="`$template_subdir`galette_local.css"}
        {if file_exists($localstylesheet)}
            <link rel="stylesheet" type="text/css" href="{$localstylesheet}" />
        {/if}
         -->

		<!-- govpress -->
        <link rel="stylesheet" id="govpress-style-css"  href="{$template_subdir}style.css" type='text/css' media='all' />
 		<link rel='stylesheet' id='fontawesome-css'  href='http://{$template_subdir}fonts/font-awesome/font-awesome.css' type='text/css' media='all' />
 		<link rel='stylesheet' id='govpress-open-sans-css'  href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300' type='text/css' media='screen' />

        <script type="text/javascript" src="{$jquery_dir}jquery-{$jquery_version}.min.js"></script>
        <script type="text/javascript" src="{$jquery_dir}jquery-migrate-{$jquery_migrate_version}.min.js"></script>

         <!-- BOOSTRAP  -->
		<link href="{$template_subdir}bootstrap/css/bootstrap.css" rel="stylesheet">
		<script type="text/javascript" src="{$template_subdir}bootstrap/js/bootstrap.js"></script>
		<!-- BOOSTRAP SELECT -->
		<link href="{$template_subdir}bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet"/>
		<script type="text/javascript" src="{$template_subdir}bootstrap-select/js/bootstrap-select.min.js"></script>
    <!-- BOOSTRAP datepicker -->
		<link href="{$template_subdir}bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
		<script type="text/javascript" src="{$template_subdir}bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

        {* IE8 doe not know html5 tags *}
        <!--[if lte IE 9]>
            <script type="text/javascript" src="{$scripts_dir}html5-ie.js"></script>
            <link rel="stylesheet" type="text/css" href="{$template_subdir}ie.css" />
        <![endif]-->
        <script type="text/javascript" src="{$jquery_dir}jquery.bgFade.js"></script>
        {* UI accordion is used for main menu ; we have to require it and UI core *}
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.accordion.min.js"></script>
        {* Buttons can be used everywhere *}
        <!--
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="{$scripts_dir}common.js"></script>
        -->
        {* Tooltips can be used everywhere *}
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="{$jquery_dir}jquery-ui-{$jquery_ui_version}/jquery.ui.tooltip.min.js"></script>
        <meta name="viewport" content="width=device-width" />
        {* UI accordion is used for main menu ; we need the CSS *}
        <link rel="stylesheet" type="text/css" href="{$template_subdir}jquery-ui/jquery-ui-{$jquery_ui_version}.custom.css" />
        <link rel="stylesheet" type="text/css" href="{$template_subdir}galette_print.css" media="print" />
        {assign var="localprintstylesheet" value="`$template_subdir`galette_print_local.css"}
        {if file_exists($localprintstylesheet)}
            <link rel="stylesheet" type="text/css" href="{$localprintstylesheet}" media="print" />
        {/if}
        <link rel="shortcut icon" href="{$template_subdir}images/favicon.png" />
