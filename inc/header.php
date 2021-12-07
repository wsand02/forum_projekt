<!DOCTYPE html>
<html lang="sv">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo ($pageTitle); ?> - Forum</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="static/css/main.css">
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Forum Projekt</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="index.php">Kategorier</a>
          </li>
        </ul>
        <?php if ($logged_in) { ?>
          <ul class="navbar-nav mb-2 mb-md-0">
            <li class="nav-item">
              <a class="nav-link" href="profile_settings.php">Inställningar</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logga ut</a>
            </li>
          </ul>
        <?php } else { ?>
          <ul class="navbar-nav mb-2 mb-md-0">
            <li class="nav-item">
              <a class="nav-link" href="login.php">Logga in</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Registrera dig</a>
            </li>
          </ul>
        <?php } ?>
      </div>
    </div>
  </nav>
  <div class="container mt-5">