console.log('Request money')

$('#frmRequestMoney').submit( function(){

    $.ajax({
      method : "POST",
      url : 'apis/api-request-money.php',
      data :  {
                "phone": $('#phone').val(),
                "amount":$('#amount').val(),   
                "message":$('#message').val()   
              },
      cache: false,
      dataType:"JSON"    
    }).
    done( function(jData){
      if(jData.status == -1){
        console.log('*************')
        console.log(jData)
        swal({ title: "Error!", icon: "error", text: jData.message });
      }
  
      if(jData.status == 0){
        console.log('*************')
        swal({ title: "Warning!", icon: "warning", text: jData.message });
      } // end of 0 case
  
      if(jData.status == 1){
        console.log('*************')
        console.log(jData)
        swal({ title: "Success!", icon: "success", text: jData.message });
      }   
    }).
    fail( function(){
      console.log('FATAL ERROR')
      swal({ title: "Error!", icon: "error", text: jData.message });
    })
  
    return false
  })
