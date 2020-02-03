var map = new Microsoft.Maps.Map(document.getElementById('myMap'), {
    /* No need to set credentials if already passed in URL */
    center: new Microsoft.Maps.Location(47.624527, -122.355255),
    zoom: 8
});
Microsoft.Maps.loadModule('Microsoft.Maps.Search', function () {
    var searchManager = new Microsoft.Maps.Search.SearchManager(map);
    var requestOptions = {
        bounds: map.getBounds(),
        where: 'Seattle',
        callback: function (answer, userData) {
            map.setView({ bounds: answer.results[0].bestView });
            map.entities.push(new Microsoft.Maps.Pushpin(answer.results[0].location));
        }
    };
    searchManager.geocode(requestOptions);
});
