<?php

	class client {

		//Attribut

		private $_idClient;
		private $_nom;
		private $_prenom;
		private $_adresse;
		private $_collectionFact=array();

		//Constructeur

		public function __construct(){

		}

		//Destructeur

		public function __destruct(){
			echo 'Destroying : '.$this->_idClient.'</br>';
			echo 'Destroying : '.$this->_nom.'</br>';
			echo 'Destroying : '.$this->_prenom.'</br>';
			echo 'Destroying : '.$this->_adresse.'</br>';
		}

		//Mutateurs

		public function getIdClient(){

			return $this->_idClient;
		}

		public function getName(){

			return $this->_nom;
		}

		public function getPrenom(){

			return $this->_prenom;
		}

		public function getAdresse(){

			return $this->_adresse;
		}

		public function setIdClient($IdClient){

			$this->_idClient = $IdClient;
		}

		public function setNom($Nom){

			$this->_nom = $Nom;
		}

		public function setPrenom($Prenom){

			$this->_prenom= $Prenom;
		}

		public function setAdresse($Adresse){

			$this->_adresse = $Adresse;
		}

		//Methode

		public function addFactCollection(facture $fact){

			if(!in_array($fact, $this->_collectionFact)){

				$fact->setClient($this);
				$this->_collectionFact[]=$fact;
			}
		}

		public function getCollectionFact(){

			return $this->_collectionFact;
		}

		public function afficheCli(){

			echo $this->_idClient.'<br/>';
			echo $this->_nom.'<br/>';
			echo $this->_prenom.'<br/>';
			echo $this->_adresse.'<br/>';

			foreach(self::getCollectionFact() AS $valeur){

				echo $valeur->afficheFact();
			}
		}

		public function hydrateCli(array $donnees){

			foreach($donnees as $key => $value){

				$method = 'set'.ucfirst($key);
				if(method_exists($this, $method)){

					$this->$method($value);
				}
			}	
		}
	}

	class ClientManager{

		private $_db; // Instance de PDO

	  	public function __construct($db){

	    $this->setDb($db);
		}

		public function add(Cli $perso){

			$q = $this->_db->prepare('INSERT INTO clients(nom, forcePerso, degats, niveau, experience) VALUES(:nom, :forcePerso, :degats, :niveau, :experience)');

		    $q->bindValue(':nom', $perso->nom());

		    $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);

		    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);

		    $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);

		    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);

		    $q->execute();
		}


		public function delete(Cli $perso){

		    $this->_db->exec('DELETE FROM clients WHERE id = '.$perso->id());
		}


		public function get($id){

		    $id = (int) $id;


		    $q = $this->_db->query('SELECT id, nom, forcePerso, degats, niveau, experience FROM clients WHERE id = '.$id);

		    $donnees = $q->fetch(PDO::FETCH_ASSOC);

		    return new Cli($donnees);
		}


		public function getList(){

			$persos = [];

		    $q = $this->_db->query('SELECT id, nom, forcePerso, degats, niveau, experience FROM clients ORDER BY nom');


		    while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){

		    $persos[] = new Cli($donnees);
		    }

		    return $persos;
		}


		public function update(Cli $perso){

			$q = $this->_db->prepare('UPDATE clients SET forcePerso = :forcePerso, degats = :degats, niveau = :niveau, experience = :experience WHERE id = :id');

		    $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);

		    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);

		    $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);

		    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);

		    $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);

		    $q->execute();
		}

		public function setDb(PDO $db){

		    $this->_db = $db;
		}
	}
	
	class facture {

		//Attribut

		private $_idFact;
		private $_modePaiement;
		private $_dateFact;
		private $_collectionProd=array();

		//Constructeur

		public function __construct(){
			
		}

		//Mutateurs

		public function getIdFacture(){

			return $this->_idFact;
		}

		public function getModePaiement(){

			return $this->_modePaiement;
		}

		public function getDateFact(){

			return $this->_dateFact;
		}

		public function setIdFacture($mIdFact){

			$this->_idFact = $mIdFact;
		}

		public function setModePaiment($mModePaiment){

			$this->_modePaiement = $mModePaiment;
		}

		public function setDateFact($mDateFact){

			$this->_dateFact = $mDateFact;
		}

		//Methode

		public function setClient(client $client){

			$this->_client = $client;
		}

		public function getCollectionProd(){

			return $this->_collectionProd;
		}

		public function afficheFact(){

			echo $this->_idFact.'<br/>';
			echo $this->_modePaiement.'<br/>';
			echo $this->_dateFact.'<br/>';
			foreach(self::getCollectionProd() AS $value){

				echo $value->afficheProd().'</br>';
			}
		}

		public function addProdCollection(produit $prod){

			if(!in_array($prod, $this->_collectionProd)){

				$this->_collectionProd[]=$prod;
			}
		}
	}

	class produit{

		//Attribut

		private $_designation;
		private $_description;
		private $_prix;

		//Constructeur

		public function __construct(){

		}

		//Destructeur

		public function __destruct(){

			echo 'destruction de : '.$this->_designation.'<br/>';
			echo 'destruction de : '.$this->_description.'<br/>';
			echo 'destruction de : '.$this->_prix.'<br/>';
		}

		//Mutateurs

		public function getDesignation(){

			return $this->_designation;
		}

		public function getDescription(){

			return $this->_description;
		}

		public function getPrix(){

			return $this->_prix;
		}

		public function setDesignation($mDesignation){

			$this->_designation = $mDesignation;
		}

		public function setDescription($mDescription){

			$this->_description = $mDescription;
		}

		public function setPrix($mPrix){

			$this->_prix = $mPrix;
		}

		//Methode

		public function afficheProd(){

			echo $this->_designation.'</br>';
			echo $this->_description.'</br>';
			echo $this->_prix.'</br>';
		}
	}

	class dFact{

		//Attribut

		private $_quantite;
		private $_id;

		//Constructeur

		public function __construct($mID, $mQuantite){

			$this->_id = $mID;
			$this->_quantite = $mQuantite;
		}

		//Destructeur

		public function __destruct(){

			echo 'destruction de : '.$this->_id.'<br/>';
			echo 'detruction de : '.$this->_quantite.'<br/>';
		}

		//Mutateurs

		public function getID(){

			return $this->_id;
		}

		public function getPrix(){

			return $this->_quantite;
		}			

		public function setID($mID){

			$this->_id = $mID;
		}

		public function setQuantite($mQuantite){

			$this->_quantite = $mQuantite;
		}
	}

	//Main

	$donneesCli = [
			
		'idClient' => 2,
		'nom' => 'KORMANN',
		'prenom' => 'Quentin',
		'adresse' => '11 rue de la gare'
	];

	$donneesFact = [

		'idFacture' => 2,
		'modePaiment' => 'CB',
		'dateFact' => '2017-02-15'
	];

	$donneesProd = [

		'prix' => 1.50,
		'designation' => 'CA01',
		'description' => 'Clou'
	];

	$perso = new Cli([

	'nom' => 'Victor',

	'forcePerso' => 5,

  	'degats' => 0,

  	'niveau' => 1,

  	'experience' => 0

	]);

	$db = new PDO('mysql:host=localhost;dbname=tests', 'root', '');

	$manager = new CliManager($db);

	$manager->add($perso);

	$c1 = new client();
	$f1 = new facture();
	$p1 = new produit();

	$c1->hydrateCli($donneesCli);
	$p1->hydrateProd($donneesProd);
	$f1->hydrateFact($donneesFact);
	$f1->addProdCollection($p1);
	$c1->addFactCollection($f1);
	echo '</br>';
	$c1->afficheCli();
	echo '</br></br>';
?>