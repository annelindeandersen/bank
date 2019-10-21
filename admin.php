<?php

session_start();

// check if session is set
if ( !isset($_SESSION['sUserId']) ) {
    header('Location: index.php#login');
}

$sUserId = $_SESSION['sUserId'];

//get data
$sData = file_get_contents('data/clients.json');
$jData = json_decode($sData);
if ( $jData == null ) { echo 'JSON cannot be read'; }

$jInnerData = $jData->data;
$jClient = $jInnerData->$sUserId;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ADMIN</title>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans|Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="css/style-logged-in.css">
    <link rel="icon" 
      type="image/png" 
      href="imgs/favicon.png">
</head>
<body>

    <nav>
        <!-- <div>ADMIN</div> -->
        <div class="navLink active" data-showPage="accounts">ACCOUNTS</div>
        <div class="navLink loans" data-showPage="loans">LOANS</div>
        <a href="index.php#login">LOGOUT</a>
        <div id="chatIcon" class="navLink" data-showPage="chat"></div>
    </nav>

    <div id="accounts" class="page">
        <h1>ACCOUNTS OVERVIEW</h1>
        <div class="profileGrid">

            <?php 

            foreach ( $jInnerData as $jClientId => $jClient ) {
                // TERNARY: if true '?' unblock and if not block
                // if 'unblock' else 'block'
                $sWord = ($jClient->active == 0) ? 'UNBLOCK' : 'BLOCK';

                echo "<div class='client'>
                <div>ID: $jClientId</div>
                <div>Full name: $jClient->name $jClient->lastName</div>
                <div>Email: $jClient->email</div>
                <div>Balance: $jClient->balance</div>
                <div>Status: $jClient->active</div>
                <br>
                <a class='blocked' href='apis/api-block-accounts.php?id=$jClientId'>$sWord</a>
                </div>";

            }

            ?>
        </div>
        <br>

        <div class="profileGrid">

            <form id="frmAdminTransfer" action="apis/api-admin-transfer.php" method="POST">
                <h2>Transfer money to an account</h2>
                <input name="phone" type="number" placeholder="ID number">
                <br>
                <input name="amount" type="number" placeholder="Amount">
                <br>
                <button>Transfer money</button>
            </form>

            <form id="frmAdminSendMail" action="apis/api-admin-send-mails.php" method="POST">
                <h2>Send email to all clients</h2>
                <input name="subject" type="text" placeholder="Subject">
                <br>
                <input name="message" type="text" placeholder="Message">
                <br>
                <button>Send emails</button>
            </form>

        </div>
        

    </div>

    <div id="loans" class="page">
        <h1>LOANS OVERVIEW</h1>
        <div class="profileGrid">

            <?php 

            foreach ( $jInnerData as $jClientId => $jClient ) {
                // TERNARY: if true '?' unblock and if not block
                
                foreach ( $jClient->loans as $jLoanId => $jLoan ) {
                    
                    // if 'activate' else 'deactivate'
                    $sStatus = ($jLoan->active == 0) ? 'ACTIVATE' : 'DEACTIVATE';

                    echo "<div class='client'>
                    <div>Client ID: $jClientId</div>
                    <div>Client name: $jClient->name $jClient->lastName</div>
                    <div>ID: $jLoanId</div>
                    <div>Amount: $jLoan->amount</div>
                    <div>Active: $jLoan->active</div>
                    <br>
                    <a class='blocked' href='apis/api-approve-loans.php?id=$jLoanId'>$sStatus</a>
                    </div>";
                }

            }

            ?>
            

        </div>
    </div>

    <div id="chat" class="page">

        <div class="profileGrid">
            <br>
            <div id="chatBox">
                <h1>LIVE CHAT</h1>
                <div id="chatSpace">
                </div>
                <form id="frmChat">
                    <input name="txt-user-id" type="number" value="<?= $sUserId; ?>">
                    <input id="message-input" name="txt-message" type="text" placeholder="TYPE MESSAGE HERE..." data-emojiable="true">
                    <button>SEND</button>
                </form>
            </div>
        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="js/admin.js"></script>

    <script>

    let sUserId = '<?= $sUserId; ?>'

    $('#frmChat').submit( function(){
    $.ajax({
        method: "POST",
        url: "apis/api-chat-set-messages.php",
        data: $('#frmChat').serialize(),
        cache: false
    }).
    done(function( sMessages ){
        $('#chatSpace').prepend('<div>'+sUserId+' is saying: '+sMessages+'</div>')
        $('#message-input').val('')
        console.log(sMessages);
    }).
    fail(function(){
        console.log('Chat set failed!!')
    })
    return false;
    })

    // ***********************************

    setInterval( function(){

    $.ajax({
        method: "GET",
        url: "apis/api-chat-get-messages.php",
        cache: false
    }).
    done(function( sMessages ){
        $('#chatSpace').prepend('<div>'+sMessages+'</div>')
        console.log('done')
    }).
    fail(function(){
        console.log('Chat failed!!')
    })
    } , 1000 )

</script>

</body>
</html>