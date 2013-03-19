<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error occured</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="page-header"><h1>Internal Server Error</h1></div>
        <div class="alert alert-block alert-error">
            <strong>Critical error</strong>
            <p><?= nl2br(safe_html($error_details)) ?></p>
        </div>
    </div>
</body>
</html>