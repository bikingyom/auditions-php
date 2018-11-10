<?php
    session_start();
    include("connexion.php");
    $nb_eleves = count($_SESSION['eleves_tmp']);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Edition d'un morceau - Conservatoire Haut-Jura-Saint-Claude</title>
		<link rel="stylesheet" href="css/styles.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript">
			function lancer(elt) {
				if(elt.value == 'Valider le morceau')
					document.forms[0].action='validmorceau_post.php';
				else if(elt.value == 'Supprimer un élève')
					document.forms[0].action='suppreleve_post.php';
				else
					document.forms[0].action='ajouteleve_post.php';
			}
        </script>
	</head>
	<body>
		<div class="limiter">
			<div class="conteneur-600">
				<div class="formulaire">
					<header><h1>Edition d'un morceau</h1></header>
					<section>
                        <?php
                            if(isset($_SESSION['erreurEdition'])) {
                                echo '<p class="warning">' . htmlspecialchars($_SESSION['erreurEdition']) . '</p>';
                            }
	        			?>
						<form action="" onsubmit="" method="post" id="formmorceau">
							<div class="bloc-tableau">
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="titre">Titre de l'oeuvre&nbsp;: </label></div>
									<div class="elt-70pc"><input type="text" name="titre" id="titre" required value="<?php echo htmlspecialchars($_SESSION['morcceau_tmp']['titre']); ?>" autofocus /></div>
								</div>
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="compositeur">Compositeur&nbsp;: </label></div>
									<div class="elt-70pc"><input type="text" name="compositeur" id="compositeur" value="<?php echo htmlspecialchars($_SESSION['morcceau_tmp']['compositeur']); ?>" /></div>
								</div>
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="minutes">Durée&nbsp;: </label></div>
									<div class="elt-70pc">
										<input type="number" name="minutes" id="minutes" value="<?php echo htmlspecialchars($_SESSION['morcceau_tmp']['minutes']); ?>" min="0" max="59" step="1"> min
										<?php $sel = "selected='selected'" ?>
										<select name="secondes" id="secondes">
											<option value="0" <?php echo $_SESSION['morcceau_tmp']['secondes'] == 0 ? $sel : ''; ?>>00</option>
											<option value="15" <?php echo $_SESSION['morcceau_tmp']['secondes'] == 15 ? $sel : ''; ?>>15</option>
											<option value="30" <?php echo $_SESSION['morcceau_tmp']['secondes'] == 30 ? $sel : ''; ?>>30</option>
											<option value="45" <?php echo $_SESSION['morcceau_tmp']['secondes'] == 45 ? $sel : ''; ?>>45</option>
										</select>
									</div>
								</div>
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="chaises">Chaises&nbsp;: </label></div>
									<div class="elt-70pc"><input type="number" name="chaises" id="chaises" min="0" max="100" step="1" value="<?php echo htmlspecialchars($_SESSION['morcceau_tmp']['chaises']); ?>"></div>
								</div>
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="pupitres">Pupitres&nbsp;: </label></div>
									<div class="elt-70pc"><input type="number" name="pupitres" id="pupitres" min="0" max="100" step="1" value="<?php echo htmlspecialchars($_SESSION['morcceau_tmp']['pupitres']); ?>"></div>
								</div>
								<div class="bloc-morceau contenu-morceau">
									<div class="elt-30pc"><label for="titre">Matériel&nbsp;: </label></div>
									<div class="elt-70pc"><input type="text" name="materiel" id="materiel" value="<?php echo htmlspecialchars($_SESSION['morcceau_tmp']['materiel']); ?>" /></div>
								</div>
							</div>
    	           		
                		</form>
					</section>
					<footer>
						<form action="annulermorceau_post.php" method="post" id="formannuler"></form>
						<input type="submit" name="bouton" value="Valider le morceau" <?php echo $nb_eleves == 0 ? 'disabled' : ''; ?> onclick="lancer(this)" form="formmorceau" />
						<input type="submit" name="bouton" value="Annuler" form="formannuler"/>
					</footer>
				</div>
				
				<div class="formulaire">
					<header><h1>Élèves concernés par ce morceau</h1></header>
					<section>
						<?php
                            if ($nb_eleves == 0) {
                                echo '<p>Il n\'y a pas encore d\'élève pour ce morceau, veuillez en ajouter.</p>';
                            } else {
						?>
    		  				<div class="bloc-tableau">
                                <?php
                                    foreach($_SESSION['eleves_tmp'] as $el_id) {
                                        $req = $bdd->prepare('SELECT nom, prenom, instrument FROM baseeleve WHERE id = ? ORDER BY nom');
                                        $req->execute(array($el_id));
                                        $eleve = $req->fetch();
                                ?>
                                        <div class="bloc-morceau">
	        								<label class="radiolabel" for="<?php echo $el_id; ?>"></label>
   	    									<input type="radio" name="elevechoisi" value="<?php echo $el_id; ?>" id="<?php echo $el_id; ?>" form="formmorceau" />
   	    									<div class="contenu-morceau">
                                                <div class="elt-40pc"><?php echo htmlspecialchars($eleve['nom']); ?></div>
                                                <div class="elt-30pc"><?php echo htmlspecialchars($eleve['prenom']); ?></div>
                                                <div class="elt-30pc"><?php echo htmlspecialchars($eleve['instrument']); ?></div>
   	    									</div>
   	    								</div>
                                    <?php
                                        }
                                        $req->closeCursor();
                                    ?>
                            </div>
        				<?php
                            }
                        ?>
        				<!--<c:if test="${ !(empty erreur || erreur == null) }">
	        				<p><c:out value="${ erreur }" /></p>
	        			</c:if>-->
    	    		</section>
        			<footer>
        				<input type="submit" name="bouton" value="Ajouter un élève" onclick="lancer(this)" form="formmorceau" title="Ajouter un ou plusieurs élèves à ce morceau" />
        				<input type="submit" name="bouton" value="Supprimer un élève" onclick="lancer(this)" form="formmorceau" <?php echo $nb_eleves == 0 ? 'class="display-none"' : ''; ?> title="Supprimer l'élève sélectionné" />
					</footer>
				</div>
			</div>
		</div> 
	</body>
</html> 
<?php 
unset($_SESSION['erreurEdition']);
?>
