var $ = require('jquery');

window.Popper = require('popper.js');
require('bootstrap');
window.Chart = require('chart.js');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
