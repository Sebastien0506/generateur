





document.addEventListener('cookie-consent-form-submit-successful', function() {
   var analyticsConsent = document.getElementById('cookie_consent_analytics_0').checked; // Permet de verifier si true à été coché
 
   
    if (analyticsConsent) {
        // Si l'utilisateur accepte le cookie, ce code permet l'exécution du script de google analytics
        loadAnalyticsScript();
        console.log("Cookie accepté");
    } else {
        // Si l'utilisateur refuse le cookie, affiche un message de refus dans la console du navigateur
        console.log('Cookie refusé');
    }
}, false);

function loadAnalyticsScript() {
    var script = document.createElement('script'); // Créer un élément de type script
    // console.log(script);
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-MX3PJLVG'; // Adresse https de google analytics et id du conteneur
    script.onload = function() {
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
    };
    document.head.appendChild(script);
};

function loadPlanning(){
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4){
            if(xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                displayPlanning(data);
            }else {
                console.error('Erreur lors de la requete:', xhr.statusText);
            }
        }
    };
    xhr.open('GET', '/planning', true);
    xhr.send();

}