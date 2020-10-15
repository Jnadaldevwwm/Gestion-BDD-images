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
        td{
            border: solid 1px black;
        }
        #zonePhotos img{
            cursor: pointer;
            margin: 5px;
            border: 1px solid black
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
        #choixTable{
            margin-bottom:25px;
        }
        .currentImg{
            max-width: 100%;
            position: absolute;
            margin-left: auto;
            margin-right: auto;
            top: 20px;
        }
        
    </style>
</head>
<body>
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
            array_push($_SESSION['password'],$_POST['pass']);
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
                    echo '<img src="'.$result['chemin'].'/'.$result['nom_photo'].'" alt="La photo" width="23%" height="auto">';
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
                    $requete .= " OR liste_mots LIKE '";
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
        document.addEventListener('click', function(e){
            if(e.target.nodeName =='IMG'){
                var curImg = document.createElement('img');
                var curImgSrc = e.target.getAttribute('src');
                curImg.setAttribute('src',curImgSrc);
                curImg.setAttribute('class','currentImg');
                document.body.appendChild(curImg);
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