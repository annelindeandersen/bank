<?php

session_start();

ini_set('display_errors', 0);

require_once('top-logged-in.php');

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

<div id="profile" class="page">
    <div class="profileGrid">

        <div id="accountInfo">
            <h2>Account info</h2>
            <h4 class="info">Full name: <?= $jClient->name.' '.$jClient->lastName; ?></h4>
            <h4 class="info">Phone: <?= $sUserId; ?></h4>
            <h4 class="info">Email: <?= $jClient->email; ?></h4>
            <h4 class="info">Balance: <span id="lblBalance"><?= $jClient->balance; ?></span> DKK</h4>
        </div>

        <form id="frmTransfer" action="apis/api-transfer.php" method="POST">
            <h2>Transfer money</h2>
            <h4>Transfer to:</h4>
            <input id="txtTransferToPhone" name="phone" type="number" data-validate="yes" data-type="integer" data-min="8" data-max="8">
            <h4>Amount:</h4>
            <input id="txtTransferAmount" name="amount" type="number" data-validate="yes" data-type="integer" data-min="1" data-max="1000000">
            <h4>Message:</h4>
            <input id="txtTransferMessage" name="message" type="text" data-validate="yes" data-type="string" data-min="0" data-max="50"><br>
            <button>TRANSFER</button>
        </form>

        <form id="frmRequestMoney" action="apis/api-request-money.php" method="POST">
            <h2>Request money</h2>
            <h4>From phone:</h4>
            <input id="phone" name="phone" type="number" data-validate="yes" data-type="integer" data-min="8" data-max="8"><br>
            <h4>Amount:</h4>
            <input id="amount" name="amount" type="number" data-validate="yes" data-type="integer" data-min="1" data-max="1000000"><br>
            <h4>Message:</h4>
            <input id="message" name="message" type="text" data-validate="yes" data-type="string" data-min="0" data-max="25"><br>
            <button>SEND REQUEST</button>
        </form>
        
    </div>

    <div class="profileGrid2">
        <div id="accountOverview">
            <h2>Account overview</h2>
            <table>
                <thead>
                    <tr>
                        <td>NAME</td>
                        <td>TYPE</td>
                        <td>CURRENCY</td>
                        <td>BALANCE</td>
                    </tr>
                </thead>
                <tbody id="lblAccounts">
                <?php 
                // bruges as => når det er en seperat klasse i dataen
                foreach ( $jClient->accounts as $sAccountId => $jAccount ) {
                    echo "
                        <tr>
                            <td>$jAccount->name</td>
                            <td>$jAccount->type</td>
                            <td>$jAccount->currency</td>
                            <td>$jAccount->balance</td>
                        </tr>
                    ";
                }
                ?>
                </tbody>
            </table>
        </div>

        <div id="cardOverview">
            <h2>Cards overview</h2>
            <table>
                <thead>
                    <tr>
                        <td>TYPE</td>
                        <td>REG-NR</td>
                        <td>CARD-NR</td>
                        <td>ACTIVE</td>
                        <td>OPTIONS</td>
                    </tr>
                </thead>
                <tbody id="lblCreditCards">
                <?php 
                // bruges as => når det er en seperat klasse i dataen
                foreach ( $jClient->creditCards as $sCreditCardId => $jCreditCard ) {
                    // if 'activate' else 'deactivate'
                    $sStatus = ($jCreditCard->active == 1) ? 'CANCEL' : 'ACTIVATE';
                    echo "
                        <tr>
                            <td>$jCreditCard->type</td>
                            <td>$jCreditCard->regnr</td>
                            <td>$jCreditCard->cardnr</td>
                            <td>$jCreditCard->active</td>
                            <td><a class='blocked' href='apis/api-cancel-card.php?id=$sCreditCardId'>$sStatus</a></td>
                        </tr>
                    ";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>    

    <div class="profileGrid2">


        <div id="requestOverview">
                <h2>REQUESTS:</h2>
                <h4>You can accept requests from others.</h4>
                    <table>
                        <thead>
                            <tr>
                                <td>FROM PHONE</td>
                                <td>AMOUNT</td>
                                <td>MESSAGE</td>
                                <td>ACCEPT</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                foreach ( $jClient->requests as $sRequestId => $jRequest ) {
                                    $sStatus = ($jRequest->status == 0) ? 'YES' : 'NO';
                                    echo "
                                    <tr>
                                        <td>$jRequest->phone</td>
                                        <td>$jRequest->amount</td>
                                        <td>$jRequest->message</td>
                                        <td><a class='blocked' href='apis/api-accept-requests.php?id=$sRequestId'>$sStatus</a></td>
                                    </tr>
                                    ";
                                }
                        
                            ?>
                        </tbody>
                </table>
            </div>

            <div id="loanOverview">
            <h2>LOANS:</h2>
            <h4>Loans must be activated by admin.</h4>
                <table>
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>ACTIVE</td>
                            <td>AMOUNT</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    
                            foreach ( $jClient->loans as $sLoanId => $jLoan ) {
                            echo "
                            <tr>
                                <td>$sLoanId</td>
                                <td>$jLoan->active</td>
                                <td>$jLoan->amount</td>
                            </tr>
                            ";
                            }
                    
                        ?>
                    </tbody>
            </table>
        </div>

    </div>

        <div id="transactions">
            <h2>Transactions</h2>
                <table>
                    <thead>
                        <tr>
                            <td>DATE</td>
                            <td>AMOUNT</td>
                            <td>NAME</td>
                            <td>LAST NAME</td>
                            <td>PHONE</td>
                            <td>MESSAGE</td>
                        </tr>
                    </thead>
                    <tbody id="lblTransactions">
                    <?php 
                    // bruges as => når det er en seperat klasse i dataen
                    foreach ( $jClient->transactions as $sTransactionId => $jTransaction ) {
                        echo "
                            <tr>
                                <td>$sTransaction->date</td>
                                <td>$sTransaction->name</td>
                                <td>$sTransaction->lastName</td>
                                <td>$sTransaction->fromPhone</td>
                                <td>$sTransaction->amount</td>
                                <td>$sTransaction->message</td>
                            </tr>
                        ";
                    }
                    ?>
                    </tbody>
                </table>
        </div>

</div>

<div id="settings" class="page">

    <div class="profileGrid">

        <form id="frmChangePassword">
            <h2>Change your password</h2>
            <h4>Old password:</h4>
            <input name="oldPassword" type="password" data-validate="yes" data-type="string" data-min="4" data-max="20">
            <h4>New password:</h4>
            <input name="newPassword" type="password" data-validate="yes" data-type="string" data-min="4" data-max="20">
            <h4>Confirm password:</h4>
            <input name="confirmPassword" type="password" data-validate="yes" data-type="string" data-min="4" data-max="20"><br>
            <button>Change password</button>
        </form>

        <form id="frmNewAccount">
            <h2>Create new account</h2>
            <h4>Account name:</h4>
            <input name="accountName" type="text" data-validate="yes" data-type="string" data-min="2" data-max="20">
            <h4>Account type:</h4>
            <select name="accountType" placeholder="Choose type">
                <option value="Savings">Savings</option>
                <option value="Checkings">Checkings</option>
                <option value="Income">Income</option>
            </select>
            <h4>Choose currency:</h4>
            <select name="currency" placeholder="currency">
                <option value="DKK">DKK</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
            <br>
            <button>Create account</button>
        </form>

        <form id="frmTransferToOwnAccount">
            <h2>Transfer to own account</h2>
            <h4>Amount: (no more than balance)</h4>
            <input name="amount" type="number" data-validate="yes" data-type="integer" data-min="1" data-max="">
            <h4>Account:</h4>
            <select name="accountType" placeholder="Choose">
                <?php
                    


                ?>

                <option value="Savings">Savings</option>
                <option value="Checkings">Checkings</option>
                <option value="Income">Income</option>


            </select>
            <h4>Choose currency:</h4>
            <select name="currency" placeholder="currency">
                <option value="DKK">DKK</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
            <br>
            <button>Create account</button>
        </form>

    </div>

    <div class="profileGrid">

        <form id="frmApplyForLoan">
            <h2>Apply for loan</h2>
            <h4>Enter amount:</h4>
            <input name="loanAmount" type="number" data-validate="yes" data-type="integer" data-min="1" data-max="1000000">
            <br>
            <button>Apply</button>
            <br>
        </form>

        <form id="frmNewCreditCard">
            <h2>Create new credit card</h2>
            <h4>Card type:</h4>
            <select id="cardType" name="cardType" placeholder="Choose type">
                <option value="chooseType">- Choose type -</option>
                <option value="MasterCard">MasterCard</option>
                <option value="VisaCard">Visa Card</option>
                <option value="DebitCard">Debit Card</option>
            </select>
            <br>
            <button>Create card</button>
        </form>

        <form id="frmEmailSignup" action="apis/api-get-emails.php" method="POST">
            <h2>What's new?</h2>
            <h4>Would you like to sign up to receive news on your email from the bank?</h4>
            <div id="check">
            <input id="checkbox" type="checkbox" name="checkbox" value="yes"><p>Yes please!</p>
            </div>
            <br>
            <button>Sign me up!</button>
            <br>
        </form>

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

<?php

require_once('bottom.php');

?>

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

