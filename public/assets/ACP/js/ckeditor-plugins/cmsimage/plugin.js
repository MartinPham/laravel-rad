CKEDITOR.plugins.add( 'cmsimage', {
    hidpi: true,
    init: function( editor ) {
        //Plugin logic goes here.

        editor.addCommand( 'insertCMSImage', {
            exec: function( editor ) {
                //var now = new Date();
                //editor.insertHtml( 'The current date and time is: <em>' + now.toString() + '</em>' );
                console.log(editor);

                cms_ipopup('Immagine','/laravel-filemanager?type=Images&CKEditor=' + editor.name + '&CKEditorFuncNum=cms_useFile&langCode=en&ipopup=2');
            }
        });

        editor.ui.addButton( 'CMS Image', {
            label: 'Immagine',
            command: 'insertCMSImage',
            toolbar: 'insert',
            icon: this.path + 'images/cmsimage.png'
        });
    }
});