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
			function lancer(elt) {
				if(elt.value == "Ajouter un morceau")
					document.getElementById('formmorceau').action='ajoutmorceau_post.php';
                else if(elt.value == "Editer un morceau")
                	document.getElementById('formmorceau').action='editmorceau_post.php';
				else if(elt.value == 'Restaurer des morceaux')
					document.getElementById('formmorceau').action='restaurermorceaux';
				else if(elt.value == "haut" || elt.value == "bas" || elt.value == "Changer l'ordre")
					document.getElementById('formmorceau').action='auditiongestion#ancre';
				else if(elt.value == "Editer")
					document.getElementById('formmorceau').action='dateheurelieu';
				else if(elt.value == "Accueil")
					document.getElementById('formmorceau').action='retouraccueil_post.php';
				else if(elt.value == "Supprimer un morceau")
					document.forms[0].action='supprmorceau_post.php';
			}

			function openEditor() {
				document.getElementById('formmorceau').action='editmorceau_post.php';
				document.getElementById('formmorceau').submit();
			}
  		</script>
	</head>
	<body>
        <div class="limiter height-100">
			<div class="conteneur-1200 height-100">
        		<div class="formulaire height-100 formuflex">
        			<!-- <c:if test="${ displaySaveOk }">
        				<p id="tempo">Vos modifications ont bien été enregistrées.</p>
        			</c:if> -->
        			<header>
                        <?php
                            $reponse_audition = $bdd->prepare('SELECT DATE_FORMAT(date_audition, \'%d/%m/%Y à %Hh%i\') AS date, lieu FROM auditions WHERE id = ?');
                            $reponse_audition->execute(array($_SESSION['id_aud']));
                            $audition = $reponse_audition->fetch();
                            echo '<div><h1>Audition du ' . htmlspecialchars($audition['date']) . '<br />' . htmlspecialchars($audition['lieu']) . '</h1></div>';
                        ?>
        				<div>
       						<!--<input type="submit" name="bouton" value="Editer" onclick="lancer(this)" form="formmorceau" title="Modifier la date, l'heure et le lieu de l'audition" />-->
       						<input type="submit" name="bouton" value="Accueil" onclick="lancer(this)" form="formmorceau" title="Revenir à la page d'accueil, pour pouvoir charger une autre audition ou en créer une nouvelle" />
        				</div>
        			</header>
        			<section id="tableau-morceaux">
        				<?php
                            $reponse_nbmorceaux = $bdd->prepare('SELECT COUNT(*) AS nb_morceaux FROM morceaux WHERE audition_id = ?');
                            $reponse_nbmorceaux->execute(array($_SESSION['id_aud']));
                            $donnees_nbmorceaux = $reponse_nbmorceaux->fetch();
                            $nb_morceaux = $donnees_nbmorceaux['nb_morceaux'];
                            if ($nb_morceaux == 0) {
                                echo '<p>Il n\'y a pas encore de morceau pour cette audition, veuillez en ajouter.</p>';
                            }
                            else
                            {
                        ?>

								<!--<c:if test="${ !(empty erreuredition || erreuredition == null) }">
	        						<p class="warning"><c:out value="${ erreuredition }" /></p>
	        					</c:if>
								<c:if test="${ ordre }">
									<div id="boutons-ordre">
										<input type="submit" name="bouton" value="haut" form="formmorceau" id="bouton-haut" onclick="lancer(this)" />
										<input type="submit" name="bouton" value="bas" form="formmorceau" id="bouton-bas" onclick="lancer(this)" />
									</div>
								</c:if>-->
    	    					<div class="bloc-tableau">
    	    					
    	    						<div class="contenu-morceau grand gras">
    	    							<div id="elt2">Titre de l'oeuvre</div>
    	    							<div id="elt3">Compositeur</div>
    	    							<div id="elt4">Durée</div>
    		    						<div id="elt5">Chaises</div>
    		    						<div id="elt6">Pupitres</div>
	    	    						<div id="elt7">Matériel</div>
    	    							<div id="elt8">Élèves</div>
    	    						</div>

      								<!--<c:set var="i" value="0" scope="page" />-->
      								
      								<?php
                                        $reponse = $bdd->prepare('SELECT id, titre, compositeur, MINUTE(duree) AS minutes, SECOND(duree) AS secondes, chaises, pupitres, materiel FROM morceaux WHERE audition_id = ?');
                                        $reponse->execute(array($_SESSION['id_aud']));
                                        while ($morceau = $reponse->fetch()) {
                                    ?>
       									<!--<c:set var="i" value="${ i+1 }" scope="page" />-->
        								<div class="bloc-morceau" ondblclick="openEditor()">
	        								<label class="radiolabel" for="<?php echo htmlspecialchars($morceau['id']); ?>"></label>
        									<!--<c:if test="${ i == isauve }"><a id="ancre"></a></c:if>-->
   	    									<input type="radio" name="morceauchoisi" value="<?php echo htmlspecialchars($morceau['id']); ?>" id="<?php echo htmlspecialchars($morceau['id']); ?>" form="formmorceau" ${ (ordre == true && hashLocal == hashChoisi) ? 'checked="checked"' : '' } />
                                            <div class="contenu-morceau grand">
												<div id="elt2"><?php echo htmlspecialchars($morceau['titre']); ?></div>
												<div id="elt3"><?php echo htmlspecialchars($morceau['compositeur']); ?></div><br />
												<div id="elt4"><?php echo htmlspecialchars($morceau['minutes']); ?>&#39;<?php echo $morceau['secondes'] == 0 ? '00' : htmlspecialchars($morceau['secondes']); ?></div>
												<div id="elt5"><?php echo htmlspecialchars($morceau['chaises']); ?></div>
												<div id="elt6"><?php echo htmlspecialchars($morceau['pupitres']); ?></div><br />
												<div id="elt7"><?php echo htmlspecialchars($morceau['materiel']); ?></div>
												<?php echo $morceau['materiel'] == null ? '' : '<br />' ?>
												<div id="elt8">
												<?php
                                                    $req = $bdd->prepare('SELECT b.nom, b.prenom, b.instrument
                                                    FROM baseeleve b
                                                    INNER JOIN eleves e
                                                    ON b.id = e.eleve_id
                                                    WHERE morceau_id = ?
                                                    ORDER BY nom');
                                                    $req->execute(array($morceau['id']));
                                                    while ($eleves = $req->fetch()) {
                                                        echo htmlspecialchars($eleves['prenom']) . ' ' . htmlspecialchars($eleves['nom']) . ' (' . htmlspecialchars($eleves['instrument']) . ')<br />';
                                                    }
                                                    $req->closeCursor();
   	    										?>
   	    										</div>
   	    									</div>
   	    									<div class="contenu-morceau petit">
	   	    									<div class="morceau-inline">
													<div id="elt2"><?php echo htmlspecialchars($morceau['titre']); ?></div>
													<div class="morceau-inline"><?php echo $morceau['compositeur'] == null ? '' : '- '; ?></div>
													<div id="elt3"><?php echo htmlspecialchars($morceau['compositeur']); ?></div><br />
													<div class="morceau-inline">Durée&nbsp;: </div>
       												<div id="elt4"><?php echo htmlspecialchars($morceau['minutes']); ?>&#39;<?php echo $morceau['secondes'] == 0 ? '00' : htmlspecialchars($morceau['secondes']); ?></div>
       												<div class="morceau-inline">- Chaises&nbsp;: </div>
       												<div id="elt5"><?php echo htmlspecialchars($morceau['chaises']); ?></div>
       												<div class="morceau-inline">- Pupitres&nbsp;: </div>
       												<div id="elt6"><?php echo htmlspecialchars($morceau['pupitres']); ?></div><br />
        											<?php echo $morceau['materiel'] == null ? '' : '<div class="morceau-inline">Matériel&nbsp;: </div><div id="elt7">' . htmlspecialchars($morceau['materiel']) . '</div><br />'; ?>
		   	    									<div class="morceau-inline">Élèves : </div>
   			    									<div id="elt8">
	    		   										<?php
                                                        $req = $bdd->prepare('SELECT b.nom, b.prenom, b.instrument
                                                        FROM baseeleve b
                                                        INNER JOIN eleves e
                                                        ON b.id = e.eleve_id
                                                        WHERE morceau_id = ?
                                                        ORDER BY nom');
                                                        $req->execute(array($morceau['id']));
                                                        while ($eleves = $req->fetch()) {
                                                            echo htmlspecialchars($eleves['prenom']) . ' ' . htmlspecialchars($eleves['nom']) . ' (' . htmlspecialchars($eleves['instrument']) . ')<br />';
                                                        }
                                                        $req->closeCursor();
                                                    ?>
   	    											</div>
   	    										</div>
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
        				<form action="" onsubmit="" method="post" id="formmorceau">
        					<!--<c:choose>
        						<c:when test="${ ordre }">
        							<input type="submit" name="bouton" value="Valider l'ordre" onclick="lancer(this)" title="Valider et enregistrer le nouvel ordre des morceaux" />
        						</c:when>
        						<c:otherwise>-->
									<input type="submit" name="bouton" value="Ajouter un morceau" onclick="lancer(this)" title="Ajouter un nouveau morceau à l'audition" />
									<input type="submit" name="bouton" value="Editer un morceau" onclick="lancer(this)" <?php echo $nb_morceaux == 0 ? 'class="display-none"' : ''; ?> title="Modifier le morceau sélectionné" />
									<!--<input type="submit" name="bouton" value="Changer l'ordre" onclick="lancer(this)" <?php echo $nb_morceaux == 0 ? 'class="display-none"' : '' ?> title="Modifier l'ordre des morceaux en faisant monter ou descenddre le morceau sélectionné" />-->
									<input type="submit" name="bouton" value="Supprimer un morceau" onclick="lancer(this)" <?php echo $nb_morceaux == 0 ? 'class="display-none"' : '' ?> title="Supprimer le morceau sélectionné" />
									<!--<input type="submit" name="bouton" value="Restaurer des morceaux" onclick="lancer(this)" ${ empty sessionScope.audition.morceauxSuppr ? 'class="display-none"' : '' } title="Afficher la corbeille et restaurer un ou plusieurs morceaux"/>
								</c:otherwise>
							</c:choose>-->
						</form>
					</footer>
        		</div>
			</div>
		</div>   
	</body>
</html> 
