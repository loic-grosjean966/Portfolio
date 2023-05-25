//Initialisation des tooltips de la page
let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
let tooltipList = tooltipTriggerList.map(function(tooltipTriggerE1) { return new bootstrap.Tooltip(tooltipTriggerE1)});