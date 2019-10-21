console.log('get newsletter')

$('#frmEmailSignup').submit(function() {
console.log('trykket på sign up for mails')

$.ajax({
    method: "POST",
    url: 'apis/api-get-emails.php',
    data: $('#frmEmailSignup').serialize(),
    dataType : "JSON"
  }).
  done(function( jData ){
    console.log('success')
    console.log( jData )

    if ( $('#checkbox').val() == 'yes' ) {
        swal({ title: "Success!", icon: "success", text: "Sign up successfull. Check your email." });
        } 

    if ( $('#checkbox').val() != 'yes' ) {
        swal({ title: "Something went wrong!", icon: "warning", text: "You must check the checkbox to sign up." });
        }
  }).
  fail(function(){ 
      console.log('FAIL') 
      swal({ title: "Something went wrong!", icon: "warning", text: "You must check the checkbox to sign up." });
    })
return false
})