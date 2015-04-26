<?php echo "<h1>Its Confidential</h1>";

$email = "zlog@challydoff.com";

$subject = "Zidoff.com Audit Trail of 30-Mar-15";

$message = '<table><thead><th>S/N</th><th>User ID</th><th>User</th><th>User Action</th><th>Table</th><th>Comment</th><th>Date</th><th>IP Address</th></thead><tbody><tr><td>1</td><td></td><td></td><td>read</td><td>users</td><td>displayed new record form from the table</td><td>23-Mar-15 16:08</td><td>::1</td></tr><tr><td>2</td><td>35991362173</td><td>pat2echo@gmail.com</td><td>read</td><td>visit_schedule</td><td>displayed new record form from the table</td><td>23-Mar-15 16:12</td><td>::1</td></tr></tbody></table>';

$headers = "MIME-Version: 1.0
Content-type: text/html; charset=iso-8859-1
From: Zidoff.com
Bcc: pat3echo@gmail.com
";

$timestamp = "1427728574";

echo "<br /><br /><br /><br /><br /><h6>Written by Patrick Ogbuitepu</h6>";

?>