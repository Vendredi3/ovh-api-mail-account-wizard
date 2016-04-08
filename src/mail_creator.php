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


</head>

<body>
  <div class="container">
  <h1>
  Création de Boites <?php echo "@$domain"; ?>
  </h1>
<?php
if(isset($_REQUEST['envoi'])){    
    
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
         if ($champs != 2) {
             echo "<div class='alert alert-danger' role='alert'>Erreur : $champs champs</div>";
         }
         else
         {
           $accountName = $tab[0];
           $passWord = $tab[1];
           
           //echo "$accountName@naturhouse.fr : $passWord<br/>";
           
           try {

              // On créé le compte
              $newAccount = $conn->post('/email/domain/' . $domain . '/account', array(
                'accountName' 	=> $accountName,
                'password'		=> $passWord
              ));

               echo "<div class='alert alert-success' role='alert'>Compte : $accountName@naturhouse.fr créé</div>";

            } catch ( Exception $ex ) {
              echo "<div class='alert alert-warning' role='alert'>Compte : $accountName@naturhouse.fr problème...  <pre>";
              print_r( $ex->getMessage() );
              echo "</pre></div>";
            }
         }
     }
  
      echo '<a href="../index.php" class="btn btn-primary btn-lg">Retour</a>';
}
else
{ 
 ?>

  <form method="POST" action="mail_creator.php" enctype="multipart/form-data">
    <input type="hidden" name="envoi" value="1" />
    <div class="form-group">
      <label for="fichier">Choisissez le fichier contenant les comptes à créer</label>
<!--       <input type="file" id="fichier"> -->
      <div class="fileinput fileinput-new" data-provides="fileinput">
        <span class="btn btn-default btn-file"><span class="fileinput-new">Choisissez le fichier</span><span class="fileinput-exists">Changer de fichier</span><input type="file" name="fichier"></span>
        <span class="fileinput-filename"></span>
        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
      </div>
      <p class="help-block">csv deux colonnes : nom du compte <mark>(sans <?php echo $domain; ?>)</mark> et mot de passe</p>
    </div>
    <button type="submit" class="btn btn-success">Envoyer</button>
    <a href="../index.php" class="btn btn-default">Retour</a>
  </form>
  </div>
  

</body>

</html>
  <?php
}

