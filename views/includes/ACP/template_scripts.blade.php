<?php
/**
 * template_scripts.php
 *
 * Author: fornace All vital JS scripts are included here
 *
 */
?>

<!-- Bootstrap.js, Jquery plugins and Custom JS code -->
<script src="/assets/ACP/js/vendor/bootstrap.min.js"></script>
<script src="/assets/ACP/js/bootstrap-dialog.min.js"></script>
<script src="/assets/ACP/js/fileinput.min.js"></script>
<script src="/assets/ACP/js/plugins.js"></script>
<script src="/assets/ACP/js/RowSorter.js"></script>

<script src="/assets/ACP/js/ckeditor/ckeditor.js"></script>
<script src="/assets/ACP/js/ckeditor/adapters/jquery.js"></script>


<!-- Bootstrap-Iconpicker Iconset for Glyphicon -->
<script type="text/javascript" src="/assets/ACP/js/bootstrap-iconpicker/bootstrap-iconpicker/js/iconset/iconset-elusiveicon-2.0.0.js"></script>
<!-- Bootstrap-Iconpicker -->
<script type="text/javascript" src="/assets/ACP/js/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="/assets/ACP/js/app.js"></script>

<div id="cmsIPopup" class="modal fade" tabindex="-1" role="dialog" style="z-index:999999999">
    <div class="modal-dialog" style="width:95%;height:90%">
        <div class="modal-content" style="height:100%">
            <div class="modal-header">
                <h2 class="modal-title">Title</h2>
            </div>
            <div class="modal-body" style="height:100%">
                <iframe src="" style="zoom:0.60" width="99.6%" height="85%" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<script>

    function confirmDelete(url){
        BootstrapDialog.confirm({
            title: 'Delete',
            message: 'Are you sure?',
            type: BootstrapDialog.TYPE_DANGER, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
            closable: true, // <-- Default value is false
            draggable: true, // <-- Default value is false
            btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
            btnOKLabel: 'OK', // <-- Default value is 'OK',
            btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will be used,
            callback: function(result) {
                // result will be true if button was click, while it will be false if users close the dialog directly.
                if(result) {
                    location = url;
                }else {

                }
            }
        });
    }




</script>