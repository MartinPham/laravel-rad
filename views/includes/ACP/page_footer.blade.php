<?php
/**
 * page_footer.php
 *
 * Author: fornace*
 * The footer of each page
 *
 */
?>
        <!-- Footer -->
        <footer class="clearfix">
            <div class="pull-right">
                <?php echo $template['name'] . ' ' . $template['version']; ?>
            </div>
            <div class="pull-left">
                @ <span id="year-copy"></span> Fornace Srl
            </div>
        </footer>
        <!-- END Footer -->
    </div>
    <!-- END Main Container -->
</div>
<!-- END Page Container -->

<!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
<a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

<!-- User Settings, modal which opens from Settings link (found in top right user menu) and the Cog link (found in sidebar user info) -->
<div id="modal-user-settings" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-pencil"></i> Modifica utente</h2>
            </div>
            <!-- END Modal Header -->

            <!-- Modal Body -->
            <div class="modal-body">
                <form method="post" action="/acp" enctype="multipart/form-data" class="form-horizontal form-bordered">
                    <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Nome</label>
                            <div class="col-md-8">
                                <input type="text" id="user-settings-name" name="name" class="form-control" value="<?php echo \Auth::user()->name?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-settings-email">Email</label>
                            <div class="col-md-8">
                                <p class="form-control-static"><?php echo \Auth::user()->email?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-settings-password">Nuova Password</label>
                            <div class="col-md-8">
                                <input type="password" id="user-settings-password" name="password" class="form-control" placeholder="Please choose a complex one..">
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Chiudi</button>
                            <button type="submit" class="btn btn-sm btn-primary" name="settings">Salva modifiche</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END Modal Body -->
        </div>
    </div>
</div>
<!-- END User Settings -->
