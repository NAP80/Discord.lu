<?php
    class Faction {
        private $_bdd;

        private $_idFaction;
        private $_nameFaction;
        private $_descFaction;
        private $_logoFaction;

        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        /** Récupère la Faction by ID */
        public function setFactionById($id){
            // Sélection des personnages de la faction
            $req = "SELECT * FROM `Faction` WHERE idFaction = '".$id."'";
            $Result = $this->_bdd->query($req);
            if($tab=$Result->fetch()){
                $this->_idFaction   = $tab['idFaction'];
                $this->_nameFaction = $tab['nameFaction'];
                $this->_descFaction = $tab['descFaction'];
                $this->_logoFaction = $tab['logoFaction'];
                $this->_rgbaFaction = $tab['rgbaFaction'];
            }
        }

        /** Return ID Faction */
        public function getIdFaction(){
            return $this->_idFaction;
        }

        /** Return Name Faction */
        public function getNameFaction(){
            return $this->_nameFaction;
        }

        /** Return Description Faction */
        public function getDescFaction(){
            return $this->_descFaction;
        }

        /** Return Bannière Faction */
        public function getLogoFaction(){
            return $this->_nameF_logoFactionaction;
        }

        /** Formulaire choix de Faction */
        public function getFormFaction(){
            ?>
                <p>Choisisez une faction :</p>
                <div>
                    <?php
                        $Result = $this->_bdd->query("SELECT * FROM `Faction`");
                        while($tabFaction = $Result->fetch()){
                            ?>
                                <div class="formfaction faction_<?= $tabFaction['idFaction'] ?>">
                                    <p><?= $tabFaction['nameFaction'] ?></p>
                                    <p><?= $tabFaction['descFaction'] ?></p>
                                    <img src="./assets/image/<?= $tabFaction['logoFaction'] ?>">
                                    <?= $tabFaction['idFaction'] ?>
                                    <?= $tabFaction['nameFaction'] ?>
                                    <a id="confirmFaction" class="ui-button ui-widget ui-corner-all"
                                        onclick="idFaction='<?= $tabFaction['idFaction'] ?>', nameFaction='<?= $tabFaction['nameFaction'] ?>', confirmFaction(nameFaction)"
                                    >Rejoindre !</a>
                                </div>
                            <?php
                        }
                        ?>
                        <?php
                    ?>
                </div>
                <!--  -->
                <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
                <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
                <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                <script>
                    function confirmFaction(nameFaction){
                        var form = document.createElement('div');
                        form.innerHTML =    '<div id="dialog-confirm" title="Rejoindre ' + nameFaction + '">'+
                                            '   <div>'+
                                            '       <span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>'+
                                            '       Vous allez rejoindre la faction ' + nameFaction + '.<br>'+
                                            '       Vous ne pourrez pas changer de Faction avant longtemps.'+
                                            '   </div>'+
                                            '</div>';
                        form.setAttribute('id','dialog-confirm', 'title', 'Rejoindre');
                        form.setAttribute('title', 'Rejoindre ' + nameFaction);
                        document.body.appendChild(form);
                        $("#dialog-confirm").dialog({
                            resizable:false,
                            height:"auto",
                            width:400,
                            modal:true,
                            buttons:{
                                "Confirmer":function(){
                                    $(this).dialog("close");
                                    $('div').remove('#dialog-confirm');
                                    $('div').remove('.ui-dialog .ui-corner-all .ui-widget .ui-widget-content .ui-front .ui-dialog-buttons .ui-draggable');
                                    form.submit();
                                },
                                "Annuler":function(){
                                    $(this).dialog("close");
                                    $('div').remove('#dialog-confirm');
                                    $('div').remove('.ui-dialog .ui-corner-all .ui-widget .ui-widget-content .ui-front .ui-dialog-buttons .ui-draggable');
                                }
                            }
                        });
                    };
                </script>
            <?php
        }

        /** Return un tableau des type de personnages en foncton de l'ID Faction */
        public function getAllTypePersonnage(){
            $TypePersos = array();
            $Result = $this->_bdd->query("SELECT * FROM `TypePersonnage` WHERE idFaction = '".$this->_idFaction."'");
            while($tab=$Result->fetch()){
                $TypePerso = new TypePersonnage($this->_bdd);
                $TypePerso->setTypePersonnageById($tab['id']);
                array_push($TypePersos,$TypePerso);
            }
            return $TypePersos;
        }
    }
?>