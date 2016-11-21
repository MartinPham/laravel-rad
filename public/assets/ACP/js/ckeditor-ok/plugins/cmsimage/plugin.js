CKEDITOR.plugins.add( 'cmsimage', {
    hidpi: true,
    init: function( editor ) {
        //Plugin logic goes here.

        editor.addCommand( 'insertCMSImage', {
            exec: function( editor ) {
                var now = new Date();
                editor.insertHtml( 'The current date and time is: <em>' + now.toString() + '</em>' );
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