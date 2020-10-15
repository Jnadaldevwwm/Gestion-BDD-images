<?php
    function connection($server,$base,$id,$mdp){
        try {
            $conn = new PDO("mysql:host=".$server.";dbname=".$base, $id, $mdp);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            echo "Erreur : ".$e->getMessage();
        }
    }

?>