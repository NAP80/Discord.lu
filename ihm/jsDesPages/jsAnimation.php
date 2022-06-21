<script>
    /** Animation Click Attaque Mob */
    function hitAnimation(event){
        const weaponType = document.querySelector(".Arme").innerText
            , eventFrame = document.querySelector(".effect")
            , soundPath  = "Assets/Sounds";
        const rand = (x) => ~~(Math.random() * x) + 1;
        const {clientX, clientY} = event;
        if(["Glaive", "Épée"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/Sword/swordSound${rand(3)}.mp3`).play();
        }
        if(["Fouet"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/Fouet/SoundFouet.mp3`).play();
        }
        if(["Pistolet"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/Pistolet/SoundPistol${rand(4)}.mp3`).play();
        }
        if(["L'amour"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/Amour/oni-chan.mp3`).play();
        }
        if(["Sabre Laser"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            const sound = new Audio(`${soundPath}/Sabre_Laser/Enuma_Elish.mp3`);
            sound.volume = 0.1;
            sound.play();
        }
        if(["Parapluie"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/Parapluie/Parapluie.mp3`).play();
        }
        if(["Baton"].some(w => weaponType.includes(w))){
            eventFrame.style.top  = `${clientY + window.scrollY}px`;
            eventFrame.style.left = `${clientX}px`;
            eventFrame.classList.add("sword-slash");
            new Audio(`${soundPath}/Baton/coup_baton${rand(2)}.mp3`).play();
        }
        eventFrame.classList.add("play");
        eventFrame.onanimationend = () => eventFrame.classList.remove("play");
    }
</script>