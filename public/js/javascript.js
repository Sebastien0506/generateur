

document.addEventListener('DOMContentLoaded', function() {
    var changeCookie = document.getElementById('modify_cookie');
    var cookieForm = document.getElementById('cookie_form');

    changeCookie.addEventListener('click', function(e) {
        e.preventDefault(); // empêche l'action par defaut
        
        cookieForm.style.display = 'block'; //Affiche le formulaire
    });
    
})

function loadAnalyticsScript() {
    var script = document.createElement('script');
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-MX3PJLVG'; // Assurez-vous que l'ID est correct
    script.onload = function() {
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
    };
    document.head.appendChild(script);
}

document.addEventListener('cookie-consent-form-submit-successful', function() {
   var analyticsConsent = document.getElementById('cookie_consent_analytics_0').checked;
 
   
    if (analyticsConsent) {
        loadAnalyticsScript();
    } else {
        console.log('Cookie refusé');
    }
}, false);

