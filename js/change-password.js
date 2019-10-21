console.log('Change password')

$('#frmChangePassword').submit( function(){

    $.ajax({
      method : "POST",
      url : 'apis/api-change-password',
      data: $('#frmChangePassword').serialize(),
      cache: false,
      dataType:"JSON"    
    }).
    done( function(jData){
        if(jData.status == -2){
            console.log('*************')
            swal({ title: "Warning!", icon: "warning", text: "New password must be between 4 and 50 characters." });
        } 

        if(jData.status == -1){
            console.log('*************')
            console.log(jData)
            swal({ title: "Error!", icon: "error", text: "Your password is incorrect." });
        }
    
        if(jData.status == 0){
            console.log('*************')
            swal({ title: "Warning!", icon: "warning", text: "New password must match the confirmed password." });
        } 
    
        if(jData.status == 1){
            console.log('*************')
            console.log(jData)
            swal({ title: "Warning!", icon: "warning", text: "New password cannot be the same as the old!" });
        }   
        
        if(jData.status == 2){
            console.log('*************')
            console.log(jData)
            swal({ title: "Success!", icon: "success", text: "Your password has been changed!" });
        }   
    }).
    fail( function(){
      console.log('FATAL ERROR')
    })
  
    return false
  })
