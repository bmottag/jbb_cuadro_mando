$(function() {

    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            y: '2019',
            a: 75,
            b: 38,
            c: 100,
            d: 90,
            e: 70,
            f: 90,
            g: 60
        }, {
            y: '2020',
            a: 45,
            b: 60,
            c: 100,
            d: 76,
            e: 100,
            f: 90,
            g: 53
        }, {
            y: '2021',
            a: 75,
            b: 38,
            c: 100,
            d: 90,
            e: 100,
            f: 90,
            g: 100
        }, {
            y: '2022',
            a: 75,
            b: 38,
            c: 57,
            d: 26,
            e: 86,
            f: 57,
            g: 100
        }],
        xkey: 'y',
        ykeys: ['a', 'b', 'c', 'd','e','f','g'],
        labels: ['ACREDITAR', 'MEJORAMIENTO', 'MODERNIZACIÓN', 'POSICIONAMIENTO ENTE CONSULTOR', 'POSICIONAMIENTO INTERÉS TURÍSTICO', 'PROMOCIÓN','RECONOCER'],
        hideHover: 'auto',
        resize: true
    });
    
});
