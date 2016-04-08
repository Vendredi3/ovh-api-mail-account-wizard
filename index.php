<?php
require_once( __DIR__ .'/config.php');
  $applicationKeyError = $applicationKey == "your_app_key" ? true : false;
	$applicationSecretError = $applicationSecret == "your_app_secret" ? true : false;
	$consumer_keyError = $consumer_key == "your_consumer_key" ? true : false;
  $domainError = $domain == 'yourdomain.com' ? true : false;
  $error = ($applicationKeyError || $applicationSecretError || $consumer_keyError || $domainError) ? true : false;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <title>Outil Mail OVH</title>


</head>

<body>
  <div class="container">
    <div class="jumbotron">
      <h1>
        Comptes Emails <?php echo $domain;?> OVH
      </h1>
      <?php if($error){ ?>
        <div class='alert alert-danger' role='alert'>ATTENTION : 
          Vous devez modifier le contenu du fichier config.php avec vos informations
        </div>
      <?php } ?>
      <p>
        Gestion de comptes Email et redirections <?php echo $domain;?> en masse (API OVH)
      </p>
      <p>
        <a href="src/mail_creator.php" class="btn btn-primary btn-lg" <?php if($error){ echo "disabled"; }?>>
          <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Création de boites
        </a>
        <a href="src/redirection_creator.php" class="btn btn-primary btn-lg" <?php if($error){ echo "disabled"; }?>>
          <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> Création de redirections
        </a>
        <a href="src/imapCopy_creator.php" class="btn btn-primary btn-lg" <?php if($error){ echo "disabled"; }?>>
          <span class="glyphicon glyphicon-copy" aria-hidden="true"></span> Copie de boites IMAP
        </a>
      </p>
    </div>
  </div>
</body>

</html>