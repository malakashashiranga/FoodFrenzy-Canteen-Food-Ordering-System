<?php
session_set_cookie_params(0, '/', '', true, true);
session_name('active_check');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    $response['success'] = true;
    $userID = $_SESSION['user_id'];

    ob_start();
    include '/xampp/htdocs/FoodFrenzy-Canteen-Food-Ordering-System/configuration/php/connect to database/con_server.php';
    $includedContent = ob_get_clean();

    $conn_status_data = json_decode($includedContent, true);
    $conn_status = $conn_status_data['connection_status'];

    if ($conn_status === "successfully") {

        $retrieveWalletHistoryDetails = "SELECT wh.date, wh.past_balance, wh.payment_method, wh.payment, wh.balance 
                            FROM wallet_records wh
                            INNER JOIN user_wallets w ON wh.wallet_id = w.wallet_id
                            WHERE w.user_id = ?
                            ORDER BY wh.date DESC, wh.time DESC";

        if ($stmt = $conn->prepare($retrieveWalletHistoryDetails)) {
            $stmt->bind_param("s", $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $pastWalletDetails = array();
                while ($row = $result->fetch_assoc()) {
                    $paymentMethod = '';
                    if ($row['payment_method'] === 'deposits') {
                        $paymentMethod = 'Deposit';
                    } elseif ($row['payment_method'] === 'expired_order_returns') {
                        $paymentMethod = 'Order return';
                    } elseif ($row['payment_method'] === 'withdraw') {
                        $paymentMethod = 'Withdraw';
                    } else {
                        $paymentMethod = '-';
                    }

                    $transactionDate = is_null($row['date']) ? '-' : $row['date'];

                    $pastWalletDetails[] = array(
                        'date' => $transactionDate,
                        'pastBalance' => 'Rs ' .$row['past_balance'],
                        'transactionType' => $paymentMethod,
                        'transactionBalance' => 'Rs ' .$row['payment'],
                        'newBalance' => 'Rs ' . $row['balance']
                    );
                }
                $response['pastWalletDetails'] = $pastWalletDetails;
            } else {
				$response['noWalletHistory'] = true;
				$response['alert'] = 'No wallet history!';
            }
            $stmt->close();
        } else {
            $response = array(
                'success' => false,
                'alert' => 'Error in retrieveWalletHistoryDetails query.!',
                'error_page' => true
            );
        }
    } else {
        $response = array(
            'success' => false,
            'alert' => 'Database connection error.',
            'error_console' => true
        );
    }

} else {
    $response = array(
        'success' => false,
        'alert' => 'Invalid request method.',
        'error_page' => true
    );
}


header('Content-Type: application/json');
echo json_encode($response);
?>
