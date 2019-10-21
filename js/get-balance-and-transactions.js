console.log('Get balance and transactions not read')

// Talk to the server and get the balance of logged user
// self unvoking functions

function fnvGetBalance() {

    let money = new Audio('money.mp3')
    
    setInterval( function() {
      
      $.ajax({
        method: "GET",
        url: 'apis/api-get-balance',
        cache: false
      }).
      done(function(sBalance){
        //console.log(sBalance)
        if ( sBalance != $('#lblBalance').text() && sBalance > $('#lblBalance').text() ) {
          $('#lblBalance').text(sBalance)
          swal({ title: "Money transfer!", icon: "success", text: "You received money" });
          money.play();
        }
        if( sBalance != $('#lblBalance').text() ){
          $('#lblBalance').text(sBalance)
        } 
      }).
      fail(function(){ console.log('FAIL') })
      
      $.ajax({
        method: "GET",
        url: 'apis/api-get-transactions-not-read.php',
        cache: false,
        dataType : "JSON"
      }).
      done(function( jTransactions ){
        for( let jTransactionKey in jTransactions ){
          // console.log(jTransactionKey)
          // console.log('test something')
          let jTransaction = jTransactions[jTransactionKey] // get object from key
          let date = jTransaction.date
          let amount = jTransaction.amount
          let name = jTransaction.name
          let lastName = jTransaction.lastName
          let fromPhone = jTransaction.fromPhone
          let message = jTransaction.message
          
          // string literals
          let sTransactionTr = `
          <tr>
              <td>${date}</td>
              <td>${amount}</td>
              <td>${name}</td>
              <td>${lastName}</td>
              <td>${fromPhone}</td>
              <td>${message}</td>
            </tr>
          `
  
          $('#lblTransactions').prepend(sTransactionTr);
        }
  
      }).
      fail(function(){ console.log('FAIL 2') })
  
    }, 5000 )
  }
  
  fnvGetBalance()