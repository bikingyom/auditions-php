<?php
session_start();
include("connexion.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Gestion des auditions - Conservatoire Haut-Jura-Saint-Claude</title>
		<link rel="stylesheet" href="css/styles.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript">
			function openEditor() {
				document.getElementById('formcharger').submit();
			}
		</script>
	</head>
	<body>
		<div class="limiter">
			<div class="conteneur-600">
	        	<div class="formulaire">
                    <?php
                        if(isset($_SESSION['erreuredition'])) {
                            echo '<p id="tempo">' . htmlspecialchars($_SESSION['erreuredition']) . '</p>';
                        }
                    ?>
    	    		<header><h1>Charger une audition</h1></header>
       				<section id="tableau-morceaux">
                        <?php
                            $reponse_nbauditions = $bdd->query('SELECT COUNT(*) AS nb_auditions FROM auditions');
                            $donnees_nbauditions= $reponse_nbauditions->fetch();
                            $nb_auditions = $donnees_nbauditions['nb_auditions'];
                            if ($nb_auditions == 0) {
                                echo '<p>Il n\'y a pas encore d\'audition prévue, veuillez en ajouter.</p>';
                            }
                            else
                            {
                        ?>
    	    					<div class="bloc-tableau">
    	    					
    	    						<div class="contenu-morceau gras">
    	    							<div class="elt-60pc">Date / Heure</div>
    	    							<div class="elt-40pc">Lieu</div>
    	    						</div>
    	    						<?php
                                        $reponse = $bdd->query('SELECT id, DATE_FORMAT(date_audition, \'%d/%m/%Y à %Hh%i\') AS date, lieu FROM auditions');
                                        while ($audition = $reponse->fetch()) {
                                            $audid = htmlspecialchars($audition['id']);
                                    ?>
        								<div class="bloc-morceau" ondblclick="openEditor()">
	        								<label class="radiolabel" for="<?php echo $audid; ?>"></label>
   	    									<input type="radio" name="auditionchoisie" value="<?php echo $audid; ?>" id="<?php echo $audid; ?>" form="formcharger" />
   	    									<div class="contenu-morceau">
												<div class="elt-60pc"><?php echo htmlspecialchars($audition['date']); ?></div>
    	   										<div class="elt-40pc"><?php echo htmlspecialchars($audition['lieu']); ?></div>
   	    									</div>
   	    								</div>
       								<?php
                                        }
                                        $reponse->closeCursor();
                                    ?>
    	    					</div>
                        <?php
                            }
                        ?>
                    </section>
	        		<footer>
    	    			<form action="chargeraudition_post.php" method="post" id="formcharger">
							<input type="submit" name="bouton" value="Charger une audition" />
						</form>
					</footer>
	        	</div>
				<div class="formulaire">
					<form action="nouvelleaudition_post.php" method="post">
        				<header><h1>Nouvelle audition</h1></header>
        				<section>
        					<div class="bloc-tableau">
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="nom">Date&nbsp;: </label></div>
									<div class="elt-70pc"><input type="date" name="date" id="date" required /></div>
								</div>
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="heure">Heure&nbsp;: </label></div>
									<div class="elt-70pc"><input type="time" name="heure" id="heure" required /></div>
								</div>
								<div class="bloc-morceau contenu-morceau">
	               					<div class="elt-30pc"><label for="lieu">Lieu&nbsp;: </label></div>
               						<div class="elt-70pc"><input type="text" name="lieu" id="lieu" required /></div>
								</div>
							</div>
        				</section>
        				<footer>
							<input type="submit" name="bouton" value="Créer une audition" />
						</footer>
					</form>
    	    	</div>
    	    </div>
		</div>   
	</body>
</html>
