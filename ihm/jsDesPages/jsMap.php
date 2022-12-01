<script>
    function log(message){
        // —— Creates a new dom element in the list below
        let oUl = document.getElementById("log");
        let oLi = document.createElement("li");
        let oText = document.createTextNode(message);
        oLi.appendChild(oText);
        oUl.appendChild(oLi);
    }

    function CallApiAddItemInSac(idItem){
        fetch('api/addItemInSac.php?idItem='+idItem)
        .then((resp) => resp.json())
        .then(function(data){
            // data est la réponse http de notre API.
            console.log(data);
            if(data[0]!=0 && data[1]==1){
                var li = document.getElementById("item"+idItem)
                var liSac = li;
                //changement de l'evenement onclic
                var Aclick = li.getElementsByTagName("a")[0];
                Aclick.setAttribute('onclick',"useItem("+idItem+")");
                li.setAttribute('id',"itemSac"+idItem);
                if(li!='undefine'){
                    li.remove();
                }
                var ul = document.getElementById("SacItem")
                if(ul!='undefine'){
                    ul.appendChild(liSac);
                }
            }
            else{
                log("Vous n'avez pas réussi à le voler."+data[2]);
            }
        })
        .catch(function(error){
            console.log(error);
        });
    }

    function CallApiAddEquipementInSac(idEquipement){
        fetch(`api/addEquipementInSac.php?idEquipement=${idEquipement}`)
        .then((resp) => resp.json())
        .then((data) => {
            console.log(data);
            if(data[0] != 0 && data[1] == 1){
                // —— Permet de mémoriser equipement fusioné
                idEquipementPop  = idEquipement;
                // —— Si lvlup et fusion
                if(Array.isArray(data[5]) && data[4] > 0){
                    // —— L'ancien li est supprimé
                    // —— 5 est le tableau id à supprimer 4 et l'id à garder
                    for(let i = 0; i < data[5].length; i++){
                        const li = document.getElementById(`equipementSac${data[5][i]}`);
                        li && li.remove();
                    }
                    // —— L'ancien affichage d'item est supprimé pour etre remplacé par sa fusion
                    idEquipementPop = data[4];
                    const li = document.getElementById(`equipementSac${data[4]}`);
                    li && li.remove();
                }
                const li    = document.getElementById(`equipement${idEquipement}`)
                    , liSac = li;
                // —— Changement de l'evenement onclic
                const Aclick = li.getElementsByTagName("a")[0];
                Aclick.setAttribute('onclick', `useEquipement(${idEquipementPop})`);
                Aclick.innerHTML = `${data[3]} lvl ${data[2]}`;
                li.setAttribute('id', `equipementSac${idEquipementPop}`);
                if(li != "undefine")
                    li.remove();
                const ul = document.getElementById("SacEquipement");
                ul && ul.appendChild(liSac);
            }
            else{
                log("Vous n'avez pas réussi à le voler."+data[2]);
            }
        })
        .catch((error) => {
            console.log(error);
        });
    }

    function CallApiRemoveEquipementPerso(idEquipement){
        fetch('api/removeEquipement.php?idEquipement='+idEquipement)
        .then((resp) => resp.json())
        .then(function(data){
            console.log(data);
            if(data[0] != 0){
                var divAtta = document.getElementById("attaqueEntiteValeur"+data[0]);
                divAtta.classList.remove("standard");
                divAtta.classList.remove("distance");
                if(data[1] == 1){// Arme
                    var e3 = document.getElementById("Arme"+data[2]);
                    e3.setAttribute('id',"ArmePerso" + <?= $Personnage->getIdEntite()?>);
                    e3.removeAttribute('onclick');
                    e3.innerHTML='(Poigts)';
                    setEquipementInSac(data[2],data[3],data[9]);
                    UpdateEntite(data[0],data[4],data[5],data[6],data[7]);
                }
                if(data[1] == 2){// Armure
                    var e3 = document.getElementById("Armure"+data[2]);
                    e3.setAttribute('id',"ArmurePerso" + <?= $Personnage->getIdEntite()?>);
                    e3.removeAttribute('onclick');
                    e3.innerHTML='(Tunique)';
                    setEquipementInSac(data[2],data[3],data[9]);
                    UpdateEntite(data[0],data[4],data[5],data[6],data[7]);
                }
            }
            else{
                log("Vous n'avez pas réussi à retirer l\'equipement."+data[2]);
            }
        })
        .catch(function(error){
            console.log(error);
        });
    }

    function UpdateArme(idArme,nameArme,idExArme,nameExArme,imgArme){
        var e3 = document.getElementById("Arme" + idExArme);
        if(e3 === null){
            e3 = document.getElementById("ArmePerso" + <?= $Personnage->getIdEntite() ?>);
        }
        else{
            setEquipementInSac(idExArme,e3.innerText,imgArme);
        }
        e3.innerText = nameArme;
        e3.setAttribute('id','Arme' + idArme);
        e3.setAttribute('onclick','CallApiRemoveEquipementPerso(' + idArme + ')');
        var e4 = document.getElementById("imgArmePerso" + <?= $Personnage->getIdEntite() ?>);
        e4.setAttribute('src',imgArme);
    }

    function UpdateArmure(idArmure,nameArmure,idExArmure,nameExArmure,imgArmure){
        var e3 = document.getElementById("Armure" + idExArmure);
        if(e3 === null){
            e3 = document.getElementById("ArmurePerso" + <?= $Personnage->getIdEntite() ?>);
        }
        else{
            setEquipementInSac(idExArmure,e3.innerText,imgArmure);
        }
        e3.innerText = nameArmure;
        e3.setAttribute('id','Armure' + idArmure);
        e3.setAttribute('onclick','CallApiRemoveEquipementPerso(' + idArmure + ')');
        var e4 = document.getElementById("imgArmurePerso" + <?= $Personnage->getIdEntite() ?>);
        e4.setAttribute('src',imgArmure);
    }

    function AttaquerPerso(idPerso,idTypeEntite, event){
        attaquer(idPerso,idTypeEntite)
    }

    function useEquipement(idEquipement){
        fetch('api/useEquipement.php?idEquipement=' + idEquipement)
        .then((resp) => resp.json())
        .then(function(data){
            console.log(data)
            UpdateEntite(data[0],data[5],data[6],data[7],data[8])
            if(data[0] !=0){
                var li = document.getElementById("equipementSac"+ idEquipement)
                if(li!='undefine'){
                    li.remove()
                }
                var divAtta = document.getElementById("attaqueEntiteValeur" + data[0])
                if(data[1] == 1){
                    UpdateArme(idEquipement,data[2],data[3],data[4],data[10])
                    divAtta.classList.add("standard")
                    divAtta.classList.remove("distance")
                }
                if(data[1] == 2){
                    UpdateArmure(idEquipement,data[2],data[3],data[4],data[10])
                    divAtta.classList.add("standard")
                    divAtta.classList.remove("distance")
                }
            }
        })
        .catch(function(error){
            console.log(error)
        });
    }

    function afficheDivPerso(e){
        var divAvatar = document.getElementById("divAvatar");
        var div = divAvatar.lastElementChild;
        let letY = e.layerY - 40;
        let letX = e.layerX + 20;
        div.style.position = "absolute";
        div.style.left= letX +"px";
        div.style.top  = letY +"px" ;
    }

    function cacheDivPerso(e){
        var divAvatar = document.getElementById("divAvatar");
        var div = divAvatar.lastElementChild;
        div.style.position = "relative";
        div.style.top = '';
        div.style.left = '' ;
    }

    function setEquipementInSac(idEquipement,nameEquipement,imgEquipement){
        var ul = document.getElementById("SacEquipement")
        if(ul != undefined && nameEquipement != "" && imgEquipement != ""){
            var li = document.createElement("li");
            li.setAttribute("id","equipementSac" + idEquipement);
            var a = document.createElement("a");
            a.setAttribute("onclick","useEquipement(" + idEquipement + ")");
            var span = document.createElement("span");
            span.setAttribute("class","spanEquipementSac");
            span.innerText = nameEquipement;
            var img = document.createElement("img");
            img.setAttribute("src",imgEquipement);
            img.setAttribute("class","imgEquipementSac");
            ul.appendChild(li);
            li.appendChild(a);
            a.appendChild(img);
            a.appendChild(span);
        }
    }

    // idTypeEntite : 0 = Créature / 1 = Personnage
    function attaquer(idPerso,idTypeEntite){
        hitAnimation(event);
        // Supprimer temporairement l'attaque pour le cooldown
        if(idTypeEntite == 0){
            var li = document.getElementById("Creature"+idPerso);
            var a = document.getElementById("aCreature"+idPerso);
        }
        else{
            var li = document.getElementById("Perso"+idPerso);
            var a = document.getElementById("aPerso"+idPerso);
        }
        li.classList.add("busy");
        let theclick = a.onclick;
        a.onclick ='';
        // Pour appeler une API on utilise la méthode fetch()
        fetch('api/attaquer.php?idEntite='+idPerso).then(
            (resp) => resp.json()
        )
        .then(function(data){
            // code for handling the data you get from the API
            console.log(data);
            // If Personnage ou Creature Mort
            if(data[2] <= 0 || data[5] <= 0){
                location.reload()// A optimiser pour actualiser la liste de monstre et map
            }
            UpdateHealth(data[0],data[1],data[2],data[3],data[4],data[5],data[6],data[7]);
            a.onclick = theclick;
            li.classList.remove("busy");
            if(data[2] == 0){
                // Si Personnage
                if(data[1] == 1){
                    var e1 = document.getElementById("Perso"+data[0]);
                    e1.remove()
                }
                // Si Creature
                if(data[1] == 0){
                    var e2 = document.getElementById("Creature"+data[0]);
                    e2.classList.add("liCaptured")
                    e2.classList.remove("liAdverse")
                    e2.querySelector('a').setAttribute("onclick", "");
                    //e2.querySelector('a').setAttribute("onclick", "SoinCreature(data[0], 1)");
                }
            }
            log(data[7]);
        })
        .catch(function(error){
            console.log(error);
            a.onclick = theclick;
            li.classList.remove("busy");
        });
    }

    function useItem(idItem){
        //pour appeler une API on utilise la méthode fetch()
        fetch('api/useItem.php?idItem='+idItem).then((resp) => resp.json())
        .then(function(data){
            // code for handling the data you get from the API
            console.log(data);
            UpdateEntite(data[0],data[1],data[2],data[3],data[4]);
            var li = document.getElementById("itemSac"+idItem)
            if(li!='undefine'){
                li.remove();
            }
            log(data[5])
        })
        .catch(function(error){
            console.log(error);
        });
    }

    /** Mise à jours Stats Perso */
    function UpdateEntite(id,attaque,defense,healthNow,healthMax){
        var e1 = document.getElementById("healthEntiteValeur"+id);
        if(e1 != "undefine"){
            e1.innerHTML = '♥️ ' + healthNow + ' / ' + healthMax;
        }
        var e2 = document.getElementById("attaqueEntiteValeur"+id);
        if(e2 != "undefine"){
            e2.innerHTML = attaque;
        }
        var e3 = document.getElementById("defenseEntiteValeur"+id);
        if(e3 != "undefine"){
            e3.innerHTML = defense;
        }
    }

    function UpdateHealth(idCible,idTypeEntite,healthNowCible,healthMaxCible,idPerso,healthPersonnage,healthMaxPersonnage,message){
        var e1 = document.getElementById("healthEntiteValeur"+idCible);
        if(e1!="undefine"){
            e1.innerHTML = '♥️ ' + healthNowCible + ' / ' + healthMaxCible;
        }
        var e2 = document.getElementById("healthEntiteValeur"+idPerso);
        if(e2!="undefine"){
            e2.innerHTML = '♥️ ' + healthPersonnage + ' / ' + healthMaxPersonnage;
        }
    }
</script>