(function() {
  tinymce.PluginManager.add( 'post-show-notes', function( editor, url ) {
    // Add Button to Visual Editor Toolbar
    editor.addButton('post-show-notes', {
      title: 'Post Show Notes Divider',
      text: 'Additional Fun',
      cmd: 'add-post-show-notes',
      image: url + '/additionalFun.svg',
    });

    editor.addCommand('add-post-show-notes', function() {
      var selected_text = editor.selection.getContent({
        'format': 'html'
      });
      var return_text = '<hr class="post-show-notes" />';
      editor.execCommand('mceReplaceContent', false, return_text);
      return;
    });

  });
})();