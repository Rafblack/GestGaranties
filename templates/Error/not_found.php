
<!DOCTYPE html>
<html>
<head>
    <title>Entity Not Found</title>
    <link rel="stylesheet" href="/css/error.css"> <!-- Link to your custom CSS for error pages -->
</head>
<body>
    <div class="error-container">
        <h1>Entity Not Found</h1>
        <p><?= h($message) ?></p>
        <a href="<?= $this->Url->build('/') ?>">Return to Home</a>
    </div>
</body>
</html>
