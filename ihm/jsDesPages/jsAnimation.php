<script>
    /** Animation Click Attaque Monster */
    function hitAnimation(event){
        const weaponType = document.querySelector(".Arme").innerText
            , eventFrame = document.querySelector(".effect")
            , soundPath  = "assets/sounds";
        const rand = (x) => ~~(Math.random() * x) + 1;
        const {clientX, clientY} = event;
        if(["Glaive", "Épée"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/sword/swordSound${rand(3)}.mp3`).play();
        }
        if(["Fouet"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/fouet/soundfouet.mp3`).play();
        }
        if(["Pistolet"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/pistolet/soundpistol${rand(4)}.mp3`).play();
        }
        if(["L'amour"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/amour/oni-chan.mp3`).play();
        }
        if(["Sabre Laser"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            const sound = new Audio(`${soundPath}/sabre_Laser/enuma_Elish.mp3`);
            sound.volume = 0.1;
            sound.play();
        }
        if(["Parapluie"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/parapluie/parapluie.mp3`).play();
        }
        if(["Baton"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/baton/coup_baton${rand(2)}.mp3`).play();
        }
        eventFrame.classList.add("play");
        eventFrame.onanimationend = () => eventFrame.classList.remove("play");
    }
</script>