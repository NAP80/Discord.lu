//DEV BY CARUELLE
// —— Allows drag movement on mobile devices

let xDown = null
  , yDown = null;

// —— Listening when the user puts his fingers on the screen
document.addEventListener( "touchstart", ( e ) => {

    const firstTouch = e.touches[0];

    xDown = firstTouch.clientX;
    yDown = firstTouch.clientY;

}, true );

// —— Listening when the user move his fingers on the screen
document.addEventListener("touchmove", ( e ) => {

    // —— If there was no movement
    if ( !xDown || !yDown )
        return;

    // —— New coordinates
    let xUp = e.touches[0].clientX
      , yUp = e.touches[0].clientY
    // —— Calculate the difference
      , xDiff = xDown - xUp
      , yDiff = yDown - yUp;

    if ( Math.abs( xDiff ) > Math.abs( yDiff ) ) {
        if ( xDiff > 0 ) {
            console.log( "Gauche" );
        } else {
            console.log( "Droite" );
        }
    } else {
        if ( yDiff > 0 ) {
            console.log( "Haut" );
        } else {
            console.log( "Bas" );
        }
    }

    // —— Reset
    xDown = null;
    yDown = null;

}, false);