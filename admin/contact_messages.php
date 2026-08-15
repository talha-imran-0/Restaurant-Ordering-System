<?php

session_start();

require_once "../includes/config.php";
require_once "../php/Auth.php";

/* CHECK ADMIN LOGIN */
require_admin();

/* MARK AS READ */
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];

    mysqli_query(
        $conn,
        "UPDATE contact_messages SET status = 'Read' WHERE id = '$id'"
    );

    header("Location: contact_messages.php");
    exit();
}

/* DELETE MESSAGE */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM contact_messages WHERE id = '$id'"
    );

    header("Location: contact_messages.php");
    exit();
}

/* SEARCH */
$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );
}

/* GET MESSAGES */
$query = "
    SELECT *
    FROM contact_messages
    WHERE
        name LIKE '%$search%'
        OR email LIKE '%$search%'
        OR subject LIKE '%$search%'
    ORDER BY id DESC
";

$get_messages = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">

    <h1>Contact Messages</h1>

    <!-- SEARCH -->
    <div class="search-box">
        <form method="GET">
            <input type="text" name="search" placeholder="Search Messages..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- MESSAGES TABLE -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($get_messages && mysqli_num_rows($get_messages) > 0) {
                    while ($message = mysqli_fetch_assoc($get_messages)) {
                ?>
                    <tr>
                        <!-- ID -->
                        <td><?php echo $message['id']; ?></td>

                        <!-- NAME -->
                        <td><?php echo htmlspecialchars($message['name']); ?></td>

                        <!-- EMAIL -->
                        <td><?php echo htmlspecialchars($message['email']); ?></td>

                        <!-- SUBJECT -->
                        <td><?php echo htmlspecialchars($message['subject']); ?></td>

                        <!-- MESSAGE -->
                        <td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>

                        <!-- STATUS -->
                        <td>
                            <?php
                            $message_status = strtolower(trim($message['status']));

                            if ($message_status == "read") {
                                echo '<span class="message-status read-status">Read</span>';
                            } else {
                                echo '<span class="message-status unread-status">Unread</span>';
                            }
                            ?>
                        </td>

                        <!-- DATE -->
                        <td><?php echo date("d M Y", strtotime($message['created_at'])); ?></td>

                        <!-- ACTIONS -->
                        <td>
                            <?php if ($message_status != "read") { ?>
                                <a href="contact_messages.php?read=<?php echo $message['id']; ?>" class="action read">Mark Read</a>
                            <?php } ?>

                            <a href="contact_messages.php?delete=<?php echo $message['id']; ?>" class="action delete" onclick="return confirm('Delete this message?');">Delete</a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="8">No Contact Messages Found.</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script src="../assets/js/admin.js"></script>
</body>
</html>