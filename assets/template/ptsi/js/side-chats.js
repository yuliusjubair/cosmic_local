 /*Side Chat*/
  $('.toggle-menu').jPushMenu();
  /*Chat*/
    
    
    $('.side-chat .content .contacts li a').click(function(e){
		alert('ok');
      var user = $('<span>' + $(this).html() + '</span>');
      user.find('i').remove();
	  var hiddenval=user.find('data').html();
	  publicuserchat=hiddenval;
	  user.find('data').remove();
	  getArrayChatData();
      
      $('#chat-box').fadeIn();
      $('#chat-box .header span').html(user.html());
      $("#chat-box .nano").nanoScroller();
      $("#chat-box .nano").nanoScroller({ scroll: 'top' });
	  
      e.preventDefault();
    });
    
    $('#chat-box .header .close').click(function(r){
      var h = $(this).parents(".header");
      var p = h.parent();
      
      p.fadeOut();
      r.preventDefault();
    });
    
    $('.chat-input .input-group button').click(function(){
      addText( $(this).parents('.input-group').find('input'));
    });
    
    $('.chat-input .input-group input').keypress(function(e){
      if(e.which == 13) {
         addText($(this));
      }
    });
    
    $(document).click(function(){
      $('#chat-box').fadeOut();
    
    });

