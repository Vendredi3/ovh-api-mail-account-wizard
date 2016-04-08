<?php
require __DIR__ . '/vendor/autoload.php';
use \Ovh\Api;
require_once( __DIR__ .'/config.php');
?>
  <!DOCTYPE html>
  <html lang="fr">

  <head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
    <title>Outil Mail OVH</title>


  </head>

  <body>
    <div class="container">
      <?php


if(isset($_GET['id'])&&isset($_GET['secretKey'])){
  
  echo "<h4>Copie du compte : ".$_GET['from_account']." du serveur ".$_GET['from_server']." vers le compte ".$_GET['to_account']." du serveur ".$_GET['to_server']."</h4>";

  $conn = new Api(    $applicationKey,
                      $applicationSecret,
                      $endpoint,
                      $consumer_key);
         
         try {

            // On vérifie l'état de la tâche
            $verifCopie = $conn->get('/email/imapCopy/task', array(
              'id' 	=> $_GET['id'],
              'secretKey' =>  $_GET['secretKey']
            ));
           
            $lastUpdate = $verifCopie["lastUpdate"];
            $status = $verifCopie["status"];
            $todoDate = $verifCopie["todoDate"];
            $finishDate = $verifCopie["finishDate"];
            $id = $verifCopie["id"];
            $return = $verifCopie["return"];
            switch($status){
                case "todo":
                    $alert = "alert-warning";
                    break;
                case "error":
                    $alert = "alert-danger";
                    break;
                case "done":
                    $alert = "alert-success";
                    break;
            }
            

           echo "<div class='alert $alert' role='alert'>";
           echo "<p><strong>lastUpdate :</strong> $lastUpdate</p>";
           echo "<p><strong>status :</strong> $status</p>";
           echo "<p><strong>todoDate :</strong> $todoDate</p>";
           echo "<p><strong>finishDate :</strong> $finishDate</p>";
           echo "<p><strong>id :</strong> $id</p>";
           echo "<p><strong>return :</strong> $return</p>";
           echo "</div>";

          } catch ( Exception $ex ) {
            echo "<div class='alert alert-warning' role='alert'>Copie de compte : problème... <pre>";
            print_r( $ex->getMessage() );
            echo "</pre></div>";
          }
  
     
}
else
{ 
 ?>

        <div class='alert alert-danger' role='alert'>Erreur : ce fichiers attend au moins deux paramètres...</div>
        <a href="index.php" class="btn btn-primary btn-lg">Retour</button>
  <?php
}  
?>
</body>

</html>