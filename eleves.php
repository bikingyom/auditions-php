<?php
    session_start();
    include("connexion.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Edition d'un élève - Conservatoire Haut-Jura-Saint-Claude</title>
		<link rel="stylesheet" href="css/styles.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<form action="annulereleve_post.php" method="post" id="formannuler"></form>
		<div class="limiter">
			<div class="conteneur-600">
                <?php
                    // on récupère le nombre de morceaux de l'audition en cours (pour afficher choix dans les élèves participant à l'audition et duplication s'il y a déjà des morceaux)
                    $reponse_nbmorceaux = $bdd->prepare('SELECT COUNT(*) AS nb_morceaux FROM morceaux WHERE audition_id = ?');
                    $reponse_nbmorceaux->execute(array($_SESSION['id_aud']));
                    $donnees_nbmorceaux = $reponse_nbmorceaux->fetch();
                    $nb_morceaux = $donnees_nbmorceaux['nb_morceaux'];
                        
                    if ($nb_morceaux > 0) { // s'il y a déjà des morceaux
                        // on récupère la liste des élèves distincts de l'audition en cours
                        $reponse_touseleves = $bdd->prepare('SELECT DISTINCT b.id, b.nom, b.prenom
                                    FROM (baseeleve b
                                    INNER JOIN eleves e ON b.id = e.eleve_id)
                                    INNER JOIN morceaux m ON e.morceau_id = m.id
                                    WHERE audition_id = ?
                                    ORDER BY nom');
                        $reponse_touseleves->execute(array($_SESSION['id_aud']));
                ?>
	       			<div class="formulaire">
       					<form action="ajoutselectioneleves_post.php" method="post">
       						<header><h1>Sélection parmi les élèves déjà inscrits à l'audition</h1></header>
       						<section>
       							<p>Vous pouvez choisir un ou plusieurs élèves dans la liste ci-dessous :</p>
    	   						<p>
	       							<select name="listeeleves[]" size="5" class="eleves" multiple required>
	       							<?php
                                        while ($eleves = $reponse_touseleves->fetch()) {
                                            if(!in_array($eleves['id'], $_SESSION['eleves_tmp'])) {
                                                echo '<option value="' . htmlspecialchars($eleves['id']) . '">' . htmlspecialchars($eleves['prenom']) . ' ' . htmlspecialchars($eleves['nom']) . '</option>';
                                            }
		       							}
		       							$reponse_touseleves->closeCursor();
                                    ?>
    	    						</select>
    	    					</p>
	       					</section>
        					<footer><input type="submit" name="bouton" value="Valider la sélection"> <input type="submit" name="bouton" value="Annuler" form="formannuler"/></footer>
    	   				</form>
	       			</div>
       				<div class="formulaire">
       					<form action="dupliqeleve_post.php" method="post">
        					<header><h1>Dupliquer les élèves d'un autre morceau</h1></header>
        					<section>
        						<div class="bloc-tableau">
                                    <?php
                                        $reponse_morceaux = $bdd->prepare('SELECT id, titre, compositeur FROM morceaux WHERE audition_id = :idaud AND id != :idmo');
                                        $reponse_morceaux->execute(array(
                                            'idaud' => $_SESSION['id_aud'],
                                            'idmo' => $_SESSION['morceau_tmp']['id']
                                            ));
                                        while ($morceau = $reponse_morceaux->fetch()) {
                                            $mo_id = $morceau['id'];
                                    ?>
    	    							<div class="bloc-morceau">
	    	    							<label class="radiolabel" for="<?php echo htmlspecialchars($mo_id); ?>"></label>
    										<input type="radio" name="morceauchoisi" value="<?php echo htmlspecialchars($mo_id); ?>" id="<?php echo htmlspecialchars($mo_id); ?>" />
    										<div class="contenu-morceau">
    											<div class="elt-60pc"><?php echo htmlspecialchars($morceau['titre']); echo isset($morceau['compositeur']) && $morceau['compositeur'] != '' ? ' - ' : ''; echo htmlspecialchars($morceau['compositeur']); ?></div>
    											<div class="elt-40pc">
    											<?php
                                                    $reponse_eleves = $bdd->prepare('SELECT nom, prenom FROM baseeleve b INNER JOIN eleves e ON b.id = e.eleve_id WHERE morceau_id = ? ORDER BY nom');
                                                    $reponse_eleves->execute(array($morceau['id']));
                                                    while ($eleve = $reponse_eleves->fetch()) {
                                                        echo htmlspecialchars($eleve['prenom']) . ' ' . htmlspecialchars($eleve['nom']) . '<br />';
                                                    }
                                                    $reponse_eleves->closeCursor();
                                                ?>
    											</div>
	    									</div>
   										</div>
   									<?php
                                        }
                                        $reponse_morceaux->closeCursor();
                                    ?>
   								</div>
       						</section>
       						<footer><input type="submit" name="bouton" value="Dupliquer les élèves"> <input type="submit" name="bouton" value="Annuler" form="formannuler"/></footer>
   						</form>
	       			</div>
      			<?php
                    }
      			?>
        		
				<div class="formulaire">
        	        <form action="valideleve_post.php" method="post">
						<header><h1>Saisie d'un nouvel élève</h1></header>
						<section>
							<div class="bloc-tableau">
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="nom">Nom&nbsp;: </label></div>
									<div class="elt-70pc"><input type="text" name="nom" id="nom" required /></div>
								</div>
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="prenom">Prénom&nbsp;: </label></div>
									<div class="elt-70pc"><input type="text" name="prenom" id="prenom" required /></div>
								</div>
								<div class="bloc-morceau contenu-morceau">
	               					<div class="elt-30pc"><label for="instrument">Instrument&nbsp;: </label></div>
               						<div class="elt-70pc">
               							<select name="instrument" id="instrument">
	               							<option value="Flute">Flute</option>
    	           							<option value="Hautbois">Hautbois</option>
        	       							<option value="Clarinette">Clarinette</option>
            	   							<option value="Saxophone">Saxophone</option>
               								<option value="Basson">Basson</option>
            	   							<option value="Piano">Piano</option>
        	       							<option value="Guitare">Guitare</option>
        	       							<option value="Violon">Violon</option>
        	       							<option value="Alto">Alto</option>
        	       							<option value="Violoncelle">Violoncelle</option>
        	       							<option value="Contrebasse">Contrebasse</option>
	    	           						<option value="Trompette">Trompette</option>
		               						<option value="Trombone">Trombone</option>
        	       							<option value="Tuba">Tuba</option>
            	   							<option value="Chant">Chant</option>
               								<option value="Percussions">Percussions</option>
               								<option value="Batterie">Batterie</option>
               								<option value="Accordéon">Accordéon</option>
            	   						</select>
            	   					</div>
								</div>
							</div>
						</section>
						<footer>
							<input type="submit" name="bouton" value="Valider l'élève"> <input type="submit" name="bouton" value="Annuler" form="formannuler"/>
						</footer>
					</form>
				</div>
			</div>
		</div>
	</body>
</html> 
