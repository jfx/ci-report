var $ = require('jquery');

import Popper from 'popper.js';
window.Popper = Popper;
require('bootstrap');
window.Chart = require('chart.js');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
