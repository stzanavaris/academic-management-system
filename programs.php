<?php
$page  = 'programs';
$title = 'Προγράμματα Σπουδών';
include 'header.php';

$sections = [
    [
        'title' => 'Προπτυχιακά Προγράμματα',
        'text'  => 'Τετραετή προγράμματα που συνδυάζουν θεωρία και πρακτική εξάσκηση.'
    ],
    [
        'title' => 'Μεταπτυχιακά Προγράμματα',
        'text'  => 'Εξειδίκευση, έρευνα και συνεργασία με επαγγελματικούς φορείς.'
    ],
    [
        'title' => 'Σεμινάρια & Εργαστήρια',
        'text'  => 'Short courses σε τεχνολογία, media, επιχειρηματικότητα και άλλα.'
    ]
];

function isActive($p, $curr) {
    return $p === $curr ? 'class="active"' : '';
}
?>
<!doctype html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
<main class="wrap">
    <div class="programs-grid">

        <?php foreach ($sections as $s): ?>
            <section class="section-block">
                <h2><?= $s['title'] ?></h2>
                <p><?= $s['text'] ?></p>
            </section>
        <?php endforeach; ?>

    </div>
</main>

<footer>
    <p>2025 Global Academic Institute</p>
</footer>

</body>
</html>
