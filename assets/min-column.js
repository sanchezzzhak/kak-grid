(function() {
  
  $('.grid-btn-min-column').each(function() {
    
    $(this).on('click', function(event, force) {
      let $el = $(this);
      let stage = !!force;
      if (force === undefined) {
        stage = !!$el.data('hide');
      }
      let min = $el.data('min-width');
      let max = $el.data('max-width');
      
      let $table = $el.closest('table');
      let index = $el.closest('th').index();
      let size = stage
        ? (min + 'px')
        : (isFinite(max) && parseInt(max) > 0 ? max + 'px' : 'unset');
      
      $el.data('hide', !stage);
      
      $(this).closest('th').css('width', size);
      $table.find('td:nth-child(' + parseInt(index + 1) + ')').each(function() {
        $(this).find('p').css('width', size);
      });
      
    }).trigger('click', $(this).data('hide'));
    
  });
  
})();