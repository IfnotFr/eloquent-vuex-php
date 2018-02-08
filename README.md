# Eloquent Vuex - WIP

**Realtime model synchronization between Vuex (VueJs) and Laravel Database (Eloquent)**

This package allows you to send eloquent events (create, update and delete) through laravel echo as vuex mutations in order to keep all your clients data in sync with your laravel database.

This package is designed for an easy integration without deep changes of your laravel backend and vuex frontend.

## Installation

    composer require ifnot/eloquent-vuex

As it is a WIP, you may want lower your stability options in your `composer.json` :

    "minimum-stability": "dev",
    "prefer-stable": true

## Quick Start

> Important : before using this package you should have a working Echo installation (client + server). [Please follow the official installation steps from the documentation](https://laravel.com/docs/5.5/broadcasting). You have to be able to send a ping from laravel and read it with Echo.

To be rewritten here ...