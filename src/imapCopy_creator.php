<?php
require __DIR__ . '/../vendor/autoload.php';
use \Ovh\Api;
require_once( __DIR__ .'/../config.php');
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js" ></script>
  <title>Outil Mail OVH</title>
  <script>
  $(document).ready(function(){
      $('.verifBtn').click(function(){
          var id = $(this).attr('id');
          var secretkey = $(this).attr('secretkey');
          var from_account  = $(this).attr('from_account');
          var from_server = $(this).attr('from_server');
          var to_account = $(this).attr('to_account');
          var to_server = $(this).attr('to_server');
          var frameSrc = "imapCopy_verif.php?id="+id+"&secretKey="+secretkey+"&from_account="+from_account+"&from_server="+from_server+"&to_account="+to_account+"&to_server="+to_server;
          $('#verif').on('show.bs.modal', function () {

              $('#iframe-verif').attr("src",frameSrc);

          });
          $('#verif').modal({show:true});
      });
    });
  </script>

</head>

<body>
  <div class="container">
  <h1>
  Copie de boites
  </h1>
<?php


if(isset($_REQUEST['envoi'])){
//    print_r($_FILES);
//   die();

  $conn = new Api(    $applicationKey,
                      $applicationSecret,
                      $endpoint,
                      $consumer_key);

 $fichier = $_FILES['fichier']['tmp_name'];
 $fichier_real_name = $_FILES['fichier']['name'];

     echo "<div class='alert alert-info' role='alert'>Fichier : $fichier_real_name </div>";

     $ligne = 1; // compteur de ligne
     $fic = fopen($fichier, "r");



     while ($tab = fgetcsv($fic, 1024, ';')) {
         echo "<hr/>";
         
         $champs = count($tab); //nombre de champ dans la ligne en question
         if ($champs !=6) {
             echo "<div class='alert alert-danger' role='alert'>Erreur : $champs champs</div>";
         }
         else 
         {
           $from_account = $tab[0];
           $from_mdp = $tab[1];
           $from_server = $tab[2];
           $to_account = $tab[3];
           $to_mdp = $tab[4];
           $to_server = $tab[5];
           
           try {

              // On créé la redirection
              $newCopy = $conn->post('/email/imapCopy', array(
                'from' 	=> array(
                  'SSL'=>true,
                  'account'=> $from_account,
                  'password'=> $from_mdp,
                  'serverIMAP'=> $from_server
                ),
                'to'		=> array(
                  'SSL'=>true,
                  'account'=> $to_account,
                  'password'=> $to_mdp,
                  'serverIMAP'=> $to_server
                )
              ));
             
             $id=$newCopy['id'];
             $secretKey=urlencode($newCopy['secretKey']) ;
             echo "<div class='alert alert-success' role='alert'>Copie du compte : $from_account du serveur $from_server vers le compte $to_account du serveur $to_server en cours<br/><pre>";
             print_r( $newCopy );
             echo "</pre><button type='button' id='$id' secretKey='$secretKey' from_account='$from_account' from_server='$from_server' to_account='$to_account' to_server='$to_server' class='btn btn-primary btn-xs verifBtn'>Vérification</button></div>";

            } catch ( Exception $ex ) {
              echo "<div class='alert alert-warning' role='alert'>Copie du compte : $from_account du serveur $from_server vers le compte $to_account du serveur $to_server problème...<br/><pre>";
              print_r( $ex->getMessage() );
              echo "</pre></div>";
            }
         }
     }
  
      echo '<a href="index.php" class="btn btn-primary btn-lg">Retour</a>';
}
else
{ 
 ?>
  <form method="POST" action="imapCopy_creator.php" enctype="multipart/form-data">
    <input type="hidden" name="envoi" value="1" />
    <div class="form-group">
      <label for="fichier">Choisissez le fichier contenant les redirections</label>
<!--       <input type="file" id="fichier"> -->
      <div class="fileinput fileinput-new" data-provides="fileinput">
        <span class="btn btn-default btn-file"><span class="fileinput-new">Choisissez le fichier</span><span class="fileinput-exists">Changer de fichier</span><input type="file" name="fichier"></span>
        <span class="fileinput-filename"></span>
        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
      </div>
      <p class="help-block">(csv 6 colonnes : from_account, from_mdp, from_server, to_account, to_mdp et to_server)</p>
    </div>
    <button type="submit" class="btn btn-success">Envoyer</button>
    <a href="../index.php" class="btn btn-default">Retour</a>
  </form>
  </div>
  


  <?php
}
?>
  <div class="modal fade" id="verif" tabindex="-1" role="dialog" aria-labelledby="verification">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Vérification tâche</h4>
        </div>
        <div class="modal-body" id="verif-content">
            <iframe id="iframe-verif" src="" style="zoom:0.60" width="99.6%" height="600" frameborder="0"></iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
  </div>
  </body>

</html>
