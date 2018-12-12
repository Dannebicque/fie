/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/global.scss');

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

const $ = require('jquery')
require('bootstrap')
require('./jquery.collection')
$('.selector-representant').collection({
  init_with_n_elements: 1,
  add_at_the_end: true,
  min: 1,
  add: '<a href="#" class="btn btn-primary">[ + Ajouter un representant de l\'entreprise]</a>',
  remove: '<a href="#" class="btn btn-warning">[ - Retire un representant de l\'entreprise]</a>',
});

$('.selector-offre').collection({
  init_with_n_elements: 1,
  add_at_the_end: true,
  position_field_selector: '.my-position',
  min: 0,
  add: '<a href="#" class="btn btn-primary">[ + Ajouter une offre de stage]</a>',
  remove: '<a href="#" class="btn btn-warning">[ - Retirer une offre de stage]</a>',
});

var $radios = $('input[name="jobdating"]');
if($("#diplomes")) {
  $("#diplomes").parent().hide();
}
$radios.change(function() {
  if ($(this).val() == true)
  {
    $("#diplomes").parent().show();
  } else {
    $("#diplomes").parent().hide();
  }
});

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');
