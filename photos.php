<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oui les photos</title>
    <style>
        *{
            box-sizing:content-box;
        }
        body{
            position: relative;
            background-color: lightblue;
        }
        table{
            border: solid 1px black;
            background-color: white;
        }
        table tr:first-child td{
            background-color: grey;
            color: white;
        }
        td{
            border: solid 1px black;
        }
        #zonePhotos img{
            cursor: pointer;
            border-bottom: 1px solid black
        }
        #zonePhotos img:hover{
            opacity:0.5;
        }
        #z1{
            display: flex;
            justify-content:center;
        }
        #z2{
            display: flex;
            justify-content:center;
            align-items:center;
            flex-direction: column;
        }
        #z2 form{
            display:flex;
            justify-content: space-between;
            width:500px;
        }
        #boutons1{
            display:flex;
            justify-content:center;
        }
        #boutons1 input{
            height:30px;
            width: 200px;
        }
        #selCat{
            margin-top:25px;
        }
        #zonePhotos{
            margin-top:20px;
            display:flex;
            flex-flow: row wrap;
            justify-content: center;
        }
        #zonePhotos form{
            width:100%;
            text-align:center;
        }
        #choixTable{
            margin-bottom:25px;
        }
        .currentImg{
            width: 95%;
            height: 100vh;
            position: fixed;
            margin-left: auto;
            margin-right: auto;
            top: 1vh;
            left: 2.5vw;
            border: white 3px solid;
            cursor: pointer;
            overflow: scroll;
        }
        .imgCurrent{
            width: 100%;
        }
        .cadreImg{
            display:flex;
            flex-flow: column wrap;
            width: 23%;
            justify-content: space-between;
            text-align: center;
            border: 1px solid black;
            background-color: rgba(240, 248, 255, 0.475);
            margin: 5px;
        }


        @import url(//fonts.googleapis.com/css?family=Vibur);

.logo {
  text-align: center;
  width: 65%;
  height: 250px;
  margin: auto;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
   user-select: none;
}

.logo b{
  font: 400 19vh "Vibur";
  color: #fee;
  text-shadow: 0 -40px 100px, 0 0 2px, 0 0 1em #0e7afe, 0 0 0.5em #4470ff, 0 0 0.1em #4479ff, 0 10px 3px #000;
}
.logo b span{
  animation: blink linear infinite 2s;
}
.logo b span:nth-of-type(2){
  animation: blink linear infinite 3s;
}
@keyframes blink {
  78% {
    color: inherit;
    text-shadow: inherit;
  }
  79%{
     color: #333;
  }
  80% {
    
    text-shadow: none;
  }
  81% {
    color: inherit;
    text-shadow: inherit;
  }
  82% {
    color: #333;
    text-shadow: none;
  }
  83% {
    color: inherit;
    text-shadow: inherit;
  }
  92% {
    color: #333;
    text-shadow: none;
  }
  92.5% {
    color: inherit;
    text-shadow: inherit;
  }
}


/* follow me @nodws */
#btn-twtr{
  clear:both;
  color:#fff;
  border:2px solid;
  border-radius:3px;
  text-align:center;
  text-decoration:none;
  display:block;
  font-family:sans-serif;
  font-size:14px;
  width:13em;
  padding:5px 10px;
  font-weight:600;
  position:absolute;
  bottom:20px;
  left:0;
  right:0;
  margin:0 auto;
  background:rgba(0,0,0,0.2);
  box-shadow:0 0 0px 3px rgba(0,0,0,0.2);
  opacity:0.4
}#btn-twtr:hover{color:#fff;opacity:1}
    </style>
</head>
<body>
    <header>
        <div class="logo"><b>G<span>al</span>er<span>i</span>e</b></div>

    </header>
    <div id="boutons1">
        <form action='' method='get' id="choixTable">
            <input type='submit' value='mes_photos' name='boutons'>
            <input type='submit' value='mes_categories' name='boutons'>
        </form>
    </div>
    <section id="z1">
    <?php
        //Connection BDD
        require('inc/identifiants.php');
        require('inc/connect.php');
        $conn = connection(SERVERNAME,DATABASE,USERNAME,PASSWORD);

        session_start();
        $_SESSION['password'];
        if($_POST['pass']!=NULL){
            $_SESSION['password'][] = $_POST['pass'];
        }
        if($_GET['reset']== 'oui'){
            $_SESSION['password']=[];
        }

        //Recup bouton cliqué
        $choix = $_GET['boutons'];

        //Requette affichage
        if(isset($choix) && in_array($choix, ['mes_categories', 'mes_photos'])){
            $reqA = 'SELECT * FROM '.$choix;
            $resultRq = $conn->query($reqA);
        }
        $categorie = $_GET['categL'];

        if($choix == 'mes_categories'){
            echo '<table><tr><td>Catégorie</td><td>Chemin</td><td>Password</td></tr>';
            foreach($resultRq as $ligne){
                echo '<tr><td>'.$ligne['categorie'].'</td><td>'.$ligne['chemin'].'</td><td>Oui</td></tr>';
            }
            echo '</table>';
        }
        if($choix == 'mes_photos'){
            echo '<table><tr><td>Nom photo</td><td>Categorie</td><td>Titre</td><td>Hauteur</td><td>Largeur</td><td>Date</td><td>Mots clés</td></tr>';
            foreach($resultRq as $ligne){
                echo '<tr><td>'.$ligne['nom_photo'].'</td><td>'.$ligne['categorie'].'</td><td>'.$ligne['titre'].'</td><td>'.$ligne['hauteur'].'</td><td>'.$ligne['largeur'].'</td><td>'.$ligne['date'].'</td><td>'.$ligne['liste_mots'].'</td></tr>';
            }
            echo '</table>';
        }
    ?>
    </section>
    <br>
    <hr>
    <section id="z2">
        <form action="" method="get" id="selCat">
            <select name="categL" id="categL">
                <option value="defaut">*Categorie*</option>
            <?php
                $rqListeCat = 'SELECT * FROM mes_categories';
                $listeCat = $conn->query($rqListeCat);
                foreach($listeCat as $categ){
                    echo '<option value="'.$categ['categorie'].'">'.ucfirst($categ['categorie']).'</option>';
                }
            ?>
             </select>
            <input type="text" name="motsCle" id="motsCle" placeholder="Mots Clefs">
            <input type="date" name="inDate" id="inDate">
            <?php echo '<input type="hidden" name="boutons" value='.$choix.'>';?> 
            <input type="submit" value="Rechercher">
        </form>
    </section>
    <br>
    <hr>
    <?php

        function affiche_photo($resultat){
            $password='oui';
            echo '<div id="zonePhotos">';
            foreach($resultat as $result){
                if($result['passwd']!=null AND !in_array($result['passwd'],$_SESSION['password'])){
                    if($password=='oui'){
                        echo '<form method="POST"><label>Mot de passe nécessaire pour accéder à la catégorie '.$result['categorie'].'</label><input type="text" placeholder="mdp" name="pass"><input type="submit"></form>';
                        $password='non';
                    }
                }
                if(in_array($result['passwd'],$_SESSION['password'])){
                    echo '<div class="cadreImg"><img src="'.$result['chemin'].'/'.$result['nom_photo'].'" alt="La photo" width="100%" height="auto"><p>'.$result['nom_photo'].' - '.$result['date'].'</p></div>';
                    $password='oui';
                }
            }
            echo '</div>';
        }
        

    //Gestion multiple
        $jointure = 0;
        $rechercheMots = htmlspecialchars($_GET['motsCle']);
        $rechercheDate = $_GET['inDate'];
        $requete = 'SELECT * FROM mes_photos INNER JOIN mes_categories ON mes_photos.categorie = mes_categories.categorie WHERE ';

        if(($categorie!=null OR $categorie!='') AND $categorie!='defaut'){
            $requete .= 'mes_photos.categorie = :cat ';
            $jointure = 1;
        }

        if($rechercheMots!=null OR $rechercheMots!=''){
            $rechercheMots = explode(" ", $rechercheMots);
            if($jointure == 1){
                $requete .= 'AND ';
            }
            $requete .= "liste_mots LIKE '";
            for($i=0;$i<count($rechercheMots);$i++){
                $requete .="%".$rechercheMots[$i]."%' ";
                if(isset($rechercheMots[$i+1])){
                    $requete .= " AND liste_mots LIKE '";
                }
            }
            $jointure = 1;
        }
    
        if($rechercheDate!=null){
            if($jointure == 1){
                $requete .= 'AND ';
            }
            $requete .= " date = '".$rechercheDate."'";
            $jointure = 1;
        }
        if($jointure===1){
            $jointure=0;
            $resultatRq = $conn->prepare($requete);
            $resultatRq->bindParam(':cat',$categorie,PDO::PARAM_STR);
            $resultatRq->execute();
            affiche_photo($resultatRq);
        }
        
    ?>
    <form action="">
        <input type="submit" name="reset" value="oui">
    </form>
    <script>
        var zoomImg = 0;
        document.addEventListener('click', function(e){
            if(e.target.nodeName =='IMG'){
                if(zoomImg==0){
                    var curImg = document.createElement('img');
                    var contImg = document.createElement('div');
                    contImg.setAttribute('class','currentImg');
                    curImg.setAttribute('class','imgCurrent');
                    var curImgSrc = e.target.getAttribute('src');
                    curImg.setAttribute('src',curImgSrc);
                    contImg.appendChild(curImg)
                    document.body.appendChild(contImg);
                    zoomImg=1;
                } else {
                    e.target.parentNode.remove();
                    zoomImg = 0;
                }
            }
        })

    </script>
</body>
</html>










<!-- // //Gestion recherche par catégories
    //     if($categorie!=null OR $categorie!='' OR $categorie!='defaut'){
    //         $rqCat = 'SELECT * FROM mes_photos INNER JOIN mes_categories ON mes_photos.categorie = mes_categories.categorie WHERE mes_photos.categorie = :cat';
            
    //         $resultRqCat = $conn->prepare($rqCat);
    //         $resultRqCat->bindParam(':cat',$categorie,PDO::PARAM_STR);
    //         $resultRqCat->execute(); 
    //         echo '<div id="zonePhotos">';
    //         foreach($resultRqCat as $result){
    //             echo '<img src="'.$result['chemin'].'/'.$result['nom_photo'].'" alt="La photo" width="23%" height="auto">';
    //         }
    //         echo '</div>';
    // }

    // //Gestion recherche par mots clefs
    //     $rechercheMots = htmlspecialchars($_GET['motsCle']);
    //     if($rechercheMots!=null OR $rechercheMots!=''){
    //         $rechercheMots = explode(" ", $rechercheMots);
    //         $rqRecherche = "SELECT * FROM mes_photos INNER JOIN mes_categories ON mes_photos.categorie = mes_categories.categorie WHERE liste_mots LIKE '";
    //         for($i=0;$i<count($rechercheMots);$i++){
    //             $rqRecherche .="%".$rechercheMots[$i]."%'";
    //             if(isset($rechercheMots[$i+1])){
    //                 $rqRecherche .= " OR liste_mots LIKE '";
    //             }
    //         }
    //         $resultRqRecherche = $conn->query($rqRecherche);
    //         echo '<div id="zonePhotos">';
    //         foreach($resultRqRecherche as $result){
    //             echo '<img src="'.$result['chemin'].'/'.$result['nom_photo'].'" alt="La photo" width="23%" height="auto">';
    //         }
    //         echo '</div>';
    //     }
    // //Gestion recherche par date
    //     $rechercheDate = $_GET['inDate'];
    //     if($rechercheDate!=null){
    //         $rqRechercheDate = "SELECT * FROM mes_photos INNER JOIN mes_categories ON mes_photos.categorie = mes_categories.categorie WHERE date = '".$rechercheDate."'";
    //         $resultRqRechercheDate = $conn->query($rqRechercheDate);
    //         echo '<div id="zonePhotos">';
    //         foreach($resultRqRechercheDate as $result){
    //             echo '<img src="'.$result['chemin'].'/'.$result['nom_photo'].'" alt="La photo" width="23%" height="auto">';
    //         }
    //         echo '</div>';
    //     } -->