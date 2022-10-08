<?php
require "config.php";
$pdo = new \PDO(DSN, USER, PASS);

$firstName = $lastName = "";
$errors = [];

function sanitizePost(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = sanitizePost($_POST['firstname']);
    $lastName = sanitizePost($_POST['lastname']);
}
if (isset($_POST['submit'])) {
    $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
    $statement = $pdo->prepare($query);

    $statement->bindValue(':firstname', $firstName, \PDO::PARAM_STR);
    $statement->bindValue(':lastname', $lastName, \PDO::PARAM_STR);

    $statement->execute();

    $friend = $statement->fetchAll();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css">
    <title>PDO Quest</title>
</head>

<body>
    <div class="container">
        <h1>Friends name</h1>
        <table>
            <thead>
                <tr>
                    <th>Firstname</th>
                    <th>Lastname</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT * FROM friend";
                $result = $pdo->query($sql);

                while ($row = $result->fetch()) {
                    echo " <tr>
                                <td>" . $row['firstname'] . "</td>
                                <td>" . $row['lastname'] . "</td>
                            </tr>";
                }
                ?>
            </tbody>
        </table>

        <form action="" method="POST">
            <label for="firstname">Firstname</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="lastname">Lastname</label>
            <input type="text" id="lastname" name="lastname" required>

            <button type="submit" name="submit">Submit</button>
        </form>
</body>

</html>