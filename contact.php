<?php
header('Content-Type: text/html; charset=utf-8');
$page = 'contact';
$title = 'Επικοινωνία';
include 'header.php';

$contact = [
    'email' => 'globaluni@outlook.com',
    'phone' => '2102476310',
    'mobile' => '6945582933',
    'address' => 'Τζαναβάρης 10'
];
$map_query = urlencode($contact['address']);
function isActive($p, $current) {
    return $p === $current ? 'class="active"' : '';
}
?>
<!doctype html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="wrap">
    <h2>Στοιχεία Επικοινωνίας</h2>

    <section class="contact-box">
        <h3>Επικοινωνία</h3>
        <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?></a></p>
        <p><strong>Τηλέφωνο:</strong> <a href="tel:+30<?= htmlspecialchars($contact['phone'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact['phone'], ENT_QUOTES, 'UTF-8') ?></a></p>
        <p><strong>Κινητό:</strong> <a href="tel:+30<?= htmlspecialchars($contact['mobile'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact['mobile'], ENT_QUOTES, 'UTF-8') ?></a></p>
    </section>

    <section class="contact-box">
        <h3>Διεύθυνση Πανεπιστημίου</h3>
        <p><?= htmlspecialchars($contact['address'], ENT_QUOTES, 'UTF-8') ?></p>

        <div class="map-container">
            <iframe
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps?q=<?= $map_query ?>&output=embed"
                width="600" height="450" style="border:0">
            </iframe>
        </div>
    </section>
</main>

<footer>
    <p>2025 Global Academic Institute</p>
</footer>

</body>
</html>
