
     <footer class="site-footer no-widgets" role="contentinfo">

			<a id="copyright" href="http://galette.eu/">Galette {$GALETTE_VERSION}</a>
			Theme inspired by <a href="http://govpress.co/" rel="designer">GovPress</a>.
			{if $login->isLogged() &&  ($login->isAdmin() or $login->isStaff())}
            <br/><a id="sysinfos" href="{$galette_base_path}sysinfos.php">{_T string="System informations"}</a>
{/if}

	</footer><!-- .site-footer -->
<script type="text/javascript" src="{$template_subdir}js/combined-min.js"></script>
