console.log('Transfer money')

$('#frmTransfer').submit( function(){
    console.log('Transfering')

    $.ajax({
      method : "GET",
      url : 'apis/api-transfer',
      data :  {
                "phone": $('#txtTransferToPhone').val(),
                "amount":$('#txtTransferAmount').val(),   
                "message":$('#txtTransferMessage').val()   
              },
      cache: false,
      dataType:"JSON"    
    }).
    done( function(jData){
      if(jData.status == -1){
        console.log('*************')
        console.log(jData)
        swal({ title: "Error!", icon: "error", text: "Something went wrong. Please try again later" });
      }
  
      if(jData.status == 0){
        console.log('*************')
        swal({ title: "Warning!", icon: "warning", text: "You do not have enough money in your account" });
      } // end of 0 case
  
      if(jData.status == 1){
        console.log('*************')
        console.log(jData)
        // TODO: Continue with a local transfer
        swal({ title: "Success!", icon: "success", text: "You transfered money" });
      }   
    }).
    fail( function(){
      console.log('FATAL ERROR')
    })
  
  
    return false
  })
