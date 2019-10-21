console.log('create account')

$('#frmNewAccount').submit(function() {
console.log('trykket på create')

$.ajax({
    method: "POST",
    url: 'apis/api-create-account.php',
    data: $('#frmNewAccount').serialize(),
    dataType : "JSON"
  }).
  done(function( jAccounts ){
    console.log('success')
    if (jAccounts.status == -1) {
      console.log(jAccounts)
      swal({ title: "Error!", icon: "error", text: "Account name must be at least 2 characters long." });
    } 

    if (jAccounts.status == 1) {
      console.log(jAccounts)
      swal({ title: "Success!", icon: "success", text: "Account created. See it on your profile" });
    }

  }).
  fail(function(){ 
      console.log('FAIL') 
    })
return false
})