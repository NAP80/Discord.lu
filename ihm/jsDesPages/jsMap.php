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
        fetch('api/addItemInSac.php?idItem='+idItem).then((resp) => resp.json()).then(function(data){
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
        }).catch(function(error){
        // This is where you run code if the server returns any errors
        console.log(error);});
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
            }).catch((error) => {
                // This is where you run code if the server returns any errors
        console.log(error);});
    }

    function CallApiRemoveEquipementEntite(idEquipement){
        fetch('api/removeEquipement.php?idEquipement='+idEquipement).then((resp) => resp.json()) .then(function(data){
            // data est la réponse http de notre API.
            console.log(data);
            //data 7 == 1 c'est une arme pour les autre types d'item il faudra faire un switch case
            //data 6 == 1 c'est l'ancien id de equipement
            if(data[0]!=0){
                var divAtta = document.getElementById("attaqueEntiteValeur"+data[0]);
                divAtta.classList.remove("standard");
                divAtta.classList.remove("magic");
                if(data[7]==1){ //cas de l'arme
                    var e3 = document.getElementById("Arme"+data[6]);
                    e3.setAttribute('id',"ArmePerso"+<?php echo $Personnage->getId()?>);
                    e3.innerHTML='';
                    //data 5 c'est l'ancien nom de equipement
                    setEquipementInSac(data[6],data[5]);
                    lvlUp(data[0],data[1],data[2],data[3],data[8]);
                }
                if(data[7]==2){
                    //cas de l'armure
                    var e3 = document.getElementById("Armure"+data[6]);
                    e3.setAttribute('id',"ArmurePerso"+<?php echo $Personnage->getId()?>);
                    e3.innerHTML='';
                    //data 5 c'est l'ancien nom de equipement
                    setEquipementInSac(data[6],data[5]);
                    lvlUp(data[0],data[1],data[2],data[3],data[8]);
                }
                if(data[7]==4){
                    //cas de bouclier
                    var e3 = document.getElementById("Armure"+data[6]);
                    e3.setAttribute('id',"ArmurePerso"+<?php echo $Personnage->getId()?>);
                    e3.innerHTML='';
                    //data 5 c'est l'ancien nom de equipement
                    setEquipementInSac(data[6],data[5]);
                    lvlUp(data[0],data[1],data[2],data[3],data[8]);
                }
                if(data[7]==3){ //cas de pouvoir
                    var e3 = document.getElementById("Arme"+data[6]);
                    e3.setAttribute('id',"ArmePerso"+<?php echo $Personnage->getId()?>);
                    e3.innerHTML='';
                    //data 5 c'est l'ancien nom de equipement
                    setEquipementInSac(data[6],data[5]);
                    lvlUp(data[0],data[1],data[2],data[3],data[8]);
                }
            }
            else{
                log("Vous n'avez pas réussi à retirer l equipement."+data[2]);
            }
        }).catch(function(error){
        // This is where you run code if the server returns any errors
        console.log(error);});
    }

    function UpdateArme(nomArme,idAncienneArme,idNouvelArme,Laclass){
        var e3 = document.getElementById("Arme"+idAncienneArme);
        if(e3 === null){
            e3 = document.getElementById("ArmePerso"+<?php echo $Personnage->getId()?>);
        }
        //on remet l'ancien equipement dans le sac
        setEquipementInSac(idAncienneArme,e3.innerHTML);
        e3.innerHTML = nomArme;
        e3.setAttribute('id',"Arme"+idNouvelArme);
        e3.className = '';
        e3.classList.add("Arme");
        e3.classList.add(Laclass);
        e3.setAttribute('onclick',"CallApiRemoveEquipementEntite("+idNouvelArme+")");
    }

    function UpdateArmure(nomArmure,idAncienneArmure,idNouvelArmure,Laclass){
        var e3 = document.getElementById("Armure"+idAncienneArmure);
        if(e3 === null){
            e3 = document.getElementById("ArmurePerso"+<?php echo $Personnage->getId()?>);
        }
        //on remet l'ancien equipement dans le sac
        setEquipementInSac(idAncienneArmure,e3.innerHTML);
        e3.className = '';
        e3.classList.add("Armure");
        e3.classList.add(Laclass);
        e3.innerHTML = nomArmure;
        e3.setAttribute('id',"Armure"+idNouvelArmure);
        e3.setAttribute('onclick',"CallApiRemoveEquipementEntite("+idNouvelArmure+")");
    }

    function AttaquerPerso(idPerso,type, event){
        attaquer(idPerso,type)
    }

    function useEquipement(idEquipement){
        //pour appeler une API on utilise la méthode fetch()
        fetch('api/useEquipement.php?idEquipement='+idEquipement).then((resp) => resp.json())
        .then(function(data){
            // code for handling the data you get from the API
            console.log(data);
            lvlUp(data[0],data[1],data[2],data[3],data[8]);
            if(data[0]!=0){
                var li = document.getElementById("equipementSac"+idEquipement)
                if(li!='undefine'){
                    li.remove();
                }
                //5 c'est le nom de la nouvelle et
                //6 c'est id de l'ancienne
                //idEquipement c'est le nouvel id de arme
                var divAtta = document.getElementById("attaqueEntiteValeur"+data[0]);
                divAtta.classList.remove("standard");
                divAtta.classList.remove("magic");
                if(data[7]==1){
                    UpdateArme(data[5],data[6],idEquipement,"standard");
                    divAtta.classList.add("standard");
                }
                //si c'est une armure
                if(data[7]==2){
                    UpdateArmure(data[5],data[6],idEquipement,"standard");
                }//si c'est un pouvoir
                if(data[7]==3){
                    divAtta.classList.add("magic");
                    UpdateArme(data[5],data[6],idEquipement,"magic");
                }
                //si c'est un bouclier
                if(data[7]==4){
                    UpdateArmure(data[5],data[6],idEquipement,"bouclier");
                }
            }
        })
        .catch(function(error){
            // This is where you run code if the server returns any errors
            console.log(error);
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

    function setEquipementInSac(id,inner){
        var ul = document.getElementById("SacEquipement")
        if(ul!='undefine' && inner != ''){
            var liArme = document.createElement("li");
            liArme.setAttribute('id',"equipementSac"+id);
            var a = document.createElement("a");
            a.setAttribute('onclick',"useEquipement("+id+")");
            a.innerHTML =inner;
            liArme.appendChild(a);
            ul.appendChild(liArme);
        }
    }

    //le type est 0 = person 1 = mob
    function attaquer(idPerso,type){
        hitAnimation(event);
        //supprimer temporairement l'attaque pour le cooldown
        if(type==0){
            var li = document.getElementById("PersoEnCours"+idPerso);
            var a = document.getElementById("aPerso"+idPerso);
        }
        else{
            var li = document.getElementById("Mob"+idPerso);
            var a = document.getElementById("aMob"+idPerso);
        }
        li.classList.add("busy");
        let theclick =a.onclick;
        a.onclick ='';
        //pour appeler une API on utilise la méthode fetch()
        fetch('api/attaquer.php?id='+idPerso+'&type='+type).then(
            (resp) => resp.json()
        )
        .then(function(data){
            // code for handling the data you get from the API
            console.log(data);
                UpdateVie("vieEntiteValeur"+data[0],data[1],data[2],data[3],data[4],"vieEntiteValeur"+data[5],data[6]);
                //data[7]c'est xp
                if(data[1] <= 0){
                    //si mob mort on doit recharger le server
                    //Todo toruver une alternative à un refrech de page
                    location.reload();
                }
                a.onclick =theclick;
                li.classList.remove("busy");
        })
        .catch(function(error){
            // This is where you run code if the server returns any errors
            console.log(error);
            a.onclick =theclick;
            li.classList.remove("busy");
        });
    }

    function useItem(idItem){
        //pour appeler une API on utilise la méthode fetch()
        fetch('api/useItem.php?idItem='+idItem).then((resp) => resp.json())
        .then(function(data){
            // code for handling the data you get from the API
            console.log(data);
            lvlUp(data[0],data[1],data[2],data[3],data[8]);
            if(data[0] != 0){
                var li = document.getElementById("itemSac"+idItem)
                if(li!='undefine'){
                    li.remove();
                }
            }
        })
        .catch(function(error){
            // This is where you run code if the server returns any errors
            console.log(error);
        });
    }

    function lvlUp(id,attaque,vie,vieMax,Armure){
        if(id==0){
            log("La magie à fait chou blanc");
        }
        else{
            var e1 = document.getElementById("vieEntiteValeur"+id);
            if(e1!="undefine"){
                let pourcentage = vie/vieMax*100;
                e1.style.width = pourcentage+"%";
                e1.innerHTML = '♥️'+vie+'/'+vieMax;
            }
            var e2 = document.getElementById("attaqueEntiteValeur"+id);
            if(e2!="undefine"){
                e2.innerHTML = attaque;
            }
            var e3 = document.getElementById("defenseEntiteValeur"+id);
            if(e3!=null){
                e3.innerHTML = Armure;
                e3.setAttribute('style',"width:"+Armure+"%");
            }
        }
    }

    function UpdateVie(id,vie,vieMax,vieEntite2,viMaxEntite2,id2,message){
        var e1 = document.getElementById(id);
        if(e1!="undefine"){
            let pourcentage = vie/vieMax*100;
            e1.style.width = pourcentage+"%";
            e1.innerHTML = '♥️'+vie+'/'+vieMax;
        }
        var e2 = document.getElementById(id2);
        if(e2!="undefine"){
            let pourcentage = vieEntite2/viMaxEntite2*100;
            e2.style.width = pourcentage+"%";
            e2.innerHTML = '♥️'+vieEntite2+'/'+viMaxEntite2;
        }
        if(vieEntite2 == 0 || message != ''){
            log(message);
            if(vie <= 0){
                const clearID = id.match(/(\d+)/);
                if(!clearID)
                    return;
                var mob = document.getElementById(`Mob${clearID[0]}`);
                mob.classList.add("Captured")
                mob.classList.remove("adverse")
                mob.querySelector('a').setAttribute("onclick", `SoinMob(${clearID[0]}, 1)`);
            }
        }
    }
</script>