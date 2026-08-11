

    function CargarChat(){     
        var oldscrollHeight = $("#botonchat").attr("scrollHeight") - 20;
        $.ajax({
            url: "php/botonchat.php",
            cache: false,
            success: function(html){        
                $("#botonchat").html(html); //Insert chat log into the #chatbox div  
				
								             
                var newscrollHeight = $("#botonchat").attr("scrollHeight") - 20;
                if(newscrollHeight > oldscrollHeight){
                    $("#botonchat").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                }               
            },
        });	
    }
    
	//velocidad de refrescada de la funcio CargarChat para tomar datos de la base de data
	setInterval (CargarChat, 2000); 
	// velocidad de refrescada de la caja de texto
	setInterval(function(){document.getElementById('chatbox').scrollTop=document.getElementById('chatbox').scrollHeight},500);
