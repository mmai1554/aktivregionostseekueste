(function ($) {
    'use strict';

    $(function () {
        const mapid = 'mimap';
        const mymap = L.map(mapid).setView([54.3455741,10.3584314], 11);
        // const tileURL = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        const tileURL = 'https://{s}.tile.openstreetmap.de/{z}/{x}/{y}.png';
        L.tileLayer(tileURL, {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 16,
        }).addTo(mymap);
        let markers = $('#markers').find('.marker');
        // add markers
        markers.each(function () {
            let divmarker = $(this);
            let lat = divmarker.attr('data-lat'), lng = divmarker.attr('data-lng');
            let mapmarker = L.marker([lat, lng]).addTo(mymap);
            let html = divmarker.html();
            if (html) {
                mapmarker.bindPopup(html);
            }
        });

    });
})(jQuery);
