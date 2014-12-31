<?php /* Smarty version Smarty-3.1.19, created on 2014-12-31 13:43:35
         compiled from "/var/www/aae-ensg/templates/bootstrap/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:209028175854a3ef777df616-88623617%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7340ee1ab3000a6615e2f93c3a9dcd232a904120' => 
    array (
      0 => '/var/www/aae-ensg/templates/bootstrap/footer.tpl',
      1 => 1414250174,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '209028175854a3ef777df616-88623617',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'GALETTE_VERSION' => 0,
    'login' => 0,
    'galette_base_path' => 0,
    'template_subdir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_54a3ef77822fd9_54747953',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54a3ef77822fd9_54747953')) {function content_54a3ef77822fd9_54747953($_smarty_tpl) {?><?php if (!is_callable('smarty_function__T')) include '/var/www/aae-ensg/includes/../includes/smarty_plugins/function._T.php';
?>     <footer class="site-footer no-widgets" role="contentinfo">
		<div class="col-width">
			<a id="copyright" href="http://galette.eu/">Galette <?php echo $_smarty_tpl->tpl_vars['GALETTE_VERSION']->value;?>
</a>
			Theme inspired by <a href="http://govpress.co/" rel="designer">GovPress</a>. 
			<?php if ($_smarty_tpl->tpl_vars['login']->value->isLogged()&&($_smarty_tpl->tpl_vars['login']->value->isAdmin()||$_smarty_tpl->tpl_vars['login']->value->isStaff())) {?>
            <br/><a id="sysinfos" href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
sysinfos.php"><?php echo smarty_function__T(array('string'=>"System informations"),$_smarty_tpl);?>
</a>
<?php }?>
     
		</div><!-- .col-width -->
	</footer><!-- .site-footer -->
        
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['template_subdir']->value;?>
js/combined-min.js"></script>

<?php }} ?>
