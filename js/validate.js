

$('form').submit( function(){

    $(this).find('input[data-validate=yes]').each( function(){
      $(this).removeClass('invalid')
      // Ok, we have the input box... how do I know what to check for?   
      let sDataType =  $(this).attr('data-type')
      let iMin = $(this).attr('data-min')
      let iMax = $(this).attr('data-max')
      switch( sDataType ){
        case "string":
          if(     $(this).val().length < iMin || 
                  $(this).val().length > iMax    ){
                  $(this).addClass('invalid')  
                  // bErrors = true
          }
        break
        case "integer":
          if(     Number($(this).val()) < iMin || 
                  Number($(this).val()) > iMax    ){
                  $(this).addClass('invalid')  
                  // bErrors= true
          }
        break
  
        case "email":
          let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if(  $(this).val().length < iMin || 
              $(this).val().length > iMax ||  
              re.test(String( $(this).val()  ).toLowerCase()) == false){
            $(this).addClass('invalid')
          }        
        break
  
  
        default:
          console.log('sorry, dont know how to validate that')
        break
      }
  
    })
  
    if( $(this).children().hasClass('invalid') ){ return false }

  })