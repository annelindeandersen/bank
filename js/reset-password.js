


  $('#forgotMyPassword').submit( function(){
    $.ajax({
      method:"POST",
      url:"apis/api-reset-password.php",
      data:$('#forgotMyPassword').serialize(),
      dataType:"JSON"
    }).
    done(function(jData){
      if(jData.status == 0){
       console.log(jData)
        return
    }
      if(jData.status == -1){
        console.log(jData)
        return
    }
    if(jData.status == 1){
      location.href = '../index.php#login'
  }
    
    
    }).
    fail(function(jData){
      console.log('error')
    })
    return false
  })
