<?php /* Smarty version Smarty-3.1.19, created on 2015-01-20 19:11:10
         compiled from "/var/www/aae-ensg/templates/bootstrap/js_loader.tpl" */ ?>
<?php /*%%SmartyHeaderCode:33876185354a3ef8e634fb9-26195585%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2c7d0250734deb9bc5da5705fcc6fa1ed1ccbfc3' => 
    array (
      0 => '/var/www/aae-ensg/templates/bootstrap/js_loader.tpl',
      1 => 1421775399,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '33876185354a3ef8e634fb9-26195585',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_54a3ef8e656770_72036700',
  'variables' => 
  array (
    'template_subdir' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54a3ef8e656770_72036700')) {function content_54a3ef8e656770_72036700($_smarty_tpl) {?><?php if (!is_callable('smarty_function__T')) include '/var/www/aae-ensg/includes/../includes/smarty_plugins/function._T.php';
?>        beforeSend: function() {
            var _img = $('<figure id="loading"><p><img src="<?php echo $_smarty_tpl->tpl_vars['template_subdir']->value;?>
images/loading.png" alt="<?php echo smarty_function__T(array('string'=>"Loading..."),$_smarty_tpl);?>
"/><br/><?php echo smarty_function__T(array('string'=>"Currently loading..."),$_smarty_tpl);?>
</p></figure>');
            $('body').append(_img);
        },
        complete: function() {
            $('#loading').remove();
        }

<?php }} ?>
