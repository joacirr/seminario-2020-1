/*
* Welcome to your app's main JavaScript file!
*
* We recommend including the built version of this JavaScript file
* (and its CSS file) in your base layout (base.html.twig).
*/

const $ = require('jquery');
var Inputmask = require('inputmask');

require('bootstrap');

// any CSS you import will output into a single css file (app.css in this case)
import '../css/global.scss';

$(document).ready(function(){
  Inputmask().mask(document.querySelectorAll("input"));
});

$('.remove').on('click', function(e) {
  var $link = $(this);
  e.preventDefault();

  $('.confirmRemove').modal('show').modal({
    backdrop: 'static',
    keyboard: false
  }).on('click', '.btn-confirm-remove', function(e) {
      window.location = $link.attr('href');
    });

  return false;
});