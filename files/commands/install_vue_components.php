<?php 

// publish passport front end components
php artisan vendor:publish --tag=passport-components


// delete the examplle.VUE
// run npm install
// npm run dev
//npm run watch - to watch every modification in the files

// register the componetns routes and views

// run to create a new personal client
 php artisan passport:client --personal

  What should we name the personal access client? [Laravel Personal Access Client]:
 > 

Personal access client created successfully.
Client ID: 5
Client Secret: tRqL4QKCqbohx3LwU2lnJ2TxXZjdzh1K9yUa9cZD

// recompile
npm run dev


