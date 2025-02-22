<?php
require_once  "functions/gmail.php";

try {
  $gmail = new GmailClient();
  $gmail->getSaveOpay(); // Fetch last 5 emails
} catch (Exception $e) {
  echo 'Error: ' . htmlspecialchars($e->getMessage());
}
//   echo "<h2>OPay Transactions:</h2>";

//   if ($emails) {
//     // die(var_dump($emails));
    
//       foreach ($emails as $index => $details) {
//           echo "<h3>Transaction #" . ($index + 1) . ":</h3>";
//           echo "<ul>";
//           foreach ($details as $key => $value) {
//               echo "<li><strong>" . ucfirst(str_replace('_', ' ', $key)) . ":</strong> " . htmlspecialchars($value) . "</li>";
//           }
//           echo "</ul><hr>";
//       }
//   } else {
//       echo "No OPay transactions found.";
//   }
// } catch (Exception $e) {
//   echo 'Error: ' . htmlspecialchars($e->getMessage());
// }