<?php /* Smarty version Smarty-3.1.19, created on 2014-12-31 13:43:46
         compiled from "/var/www/aae-ensg/templates/bootstrap/desktop.tpl" */ ?>
<?php /*%%SmartyHeaderCode:194751823054a3ef829f2527-44397304%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '86aeae0290e28b6c74e561b3adfa9b6d878f2c76' => 
    array (
      0 => '/var/www/aae-ensg/templates/bootstrap/desktop.tpl',
      1 => 1414243943,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '194751823054a3ef829f2527-44397304',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'galette_base_path' => 0,
    'login' => 0,
    'show_dashboard' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_54a3ef82b0dc77_07194882',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54a3ef82b0dc77_07194882')) {function content_54a3ef82b0dc77_07194882($_smarty_tpl) {?><?php if (!is_callable('smarty_function__T')) include '/var/www/aae-ensg/includes/../includes/smarty_plugins/function._T.php';
?>        <section id="desktop">
            <header class="ui-state-default ui-state-active">
                <?php echo smarty_function__T(array('string'=>"Activities"),$_smarty_tpl);?>

            </header>
            <div>
                <a id="members" href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
gestion_adherents.php" title="<?php echo smarty_function__T(array('string'=>"View, search into and filter member's list"),$_smarty_tpl);?>
"><?php echo smarty_function__T(array('string'=>"Members"),$_smarty_tpl);?>
</a>
                <a id="groups" href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
gestion_groupes.php" title="<?php echo smarty_function__T(array('string'=>"View and manage groups"),$_smarty_tpl);?>
"><?php echo smarty_function__T(array('string'=>"Groups"),$_smarty_tpl);?>
</a>
<?php if ($_smarty_tpl->tpl_vars['login']->value->isAdmin()||$_smarty_tpl->tpl_vars['login']->value->isStaff()) {?>
                <a id="contribs" href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
gestion_contributions.php?id_adh=all" title="<?php echo smarty_function__T(array('string'=>"View and filter contributions"),$_smarty_tpl);?>
"><?php echo smarty_function__T(array('string'=>"Contributions"),$_smarty_tpl);?>
</a>
                <a id="transactions" href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
gestion_transactions.php" title="<?php echo smarty_function__T(array('string'=>"View and filter transactions"),$_smarty_tpl);?>
"><?php echo smarty_function__T(array('string'=>"Transactions"),$_smarty_tpl);?>
</a>
                <a id="mailings" href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
gestion_mailings.php" title="<?php echo smarty_function__T(array('string'=>"Manage mailings that has been sent"),$_smarty_tpl);?>
"><?php echo smarty_function__T(array('string'=>"Mailings"),$_smarty_tpl);?>
</a>
                <a id="reminder" href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
reminder.php" title="<?php echo smarty_function__T(array('string'=>"Send reminders to late members"),$_smarty_tpl);?>
"><?php echo smarty_function__T(array('string'=>"Reminders"),$_smarty_tpl);?>
</a>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['login']->value->isAdmin()) {?>
                <a id="prefs" href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
preferences.php" title="<?php echo smarty_function__T(array('string'=>"Set applications preferences (adress, website, member's cards configuration, ...)"),$_smarty_tpl);?>
"><?php echo smarty_function__T(array('string'=>"Settings"),$_smarty_tpl);?>
</a>
                <a id="plugins" href="<?php echo $_smarty_tpl->tpl_vars['galette_base_path']->value;?>
plugins.php" title="<?php echo smarty_function__T(array('string'=>"Informations about available plugins"),$_smarty_tpl);?>
"><?php echo smarty_function__T(array('string'=>"Plugins"),$_smarty_tpl);?>
</a>
<?php }?>
            </div>
        </section>
        <p class="center">
            <input type="checkbox" name="show_dashboard" id="show_dashboard" value="1"<?php if ($_smarty_tpl->tpl_vars['show_dashboard']->value) {?> checked="checked"<?php }?>/>
            <label for="show_dashboard"><?php echo smarty_function__T(array('string'=>"Show dashboard on login"),$_smarty_tpl);?>
</label>
        </p>
        <script>
            $(function() {
                $('#show_dashboard').change(function(){
                    var _checked = $(this).is(':checked');
                    $.cookie(
                        'show_galette_dashboard',
                        (_checked ? 1 : 0),
                        { expires: 365 }
                    );
                    if ( !_checked ) {
                        var _url = window.location.href;
                        window.location.replace(
                            _url.replace(
                                /\/desktop\.php.*/,
                                '/gestion_adherents.php'
                            )
                        );
                    }
                });
            });
        </script>
<?php }} ?>
