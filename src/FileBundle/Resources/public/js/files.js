$(function() {
$('a[data-delete-link]').on('click', function() {
     var link = $(this).data('delete-link');
     var id = $(this).data('id');
     var thatEl = $(this);
     var fileDisplay = $(this).parent().parent();
     var fileUpload = $(this).parent().parent().parent().find('div.file-upload-field');
     if (link.indexOf('delete/')>-1 && parseInt(id)>0 && fileDisplay && fileUpload) {
         //confirm delete
         if (confirm('Are you sure you want to delete '+$(this).data('file-name'))) {
             sendQeury(link)
            .done(function(result) {
              if('success' in result && 'deleted' in result) {
                if (result['deleted'] == id) {
                  // remove dynami li element if in any of those.
                  var el = thatEl;
                  var prev = null;
                  var delLi = null;
                  var cnt = 0;
                  while(cnt<15) {
                      prev = el;
                      el = el.parent();
                      console.log('del el', el, prev, el.is('ul'),el.attr('class'));
                      if (el.is('ul') && el.attr('class').indexOf('dynamic')>-1) {
                          delLi = prev;
                          break;
                      }
                  cnt++;
                  }
                  // deleted ok. delete file-display div and show upload div
                  fileDisplay.remove();
                  fileDisplay.closest('li').remove();
                  fileUpload.show();
                  //remove deleted element li
                  if (delLi != null) delLi.remove();
                }
              }
            });
        }
     }
 })

})
