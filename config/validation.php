<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Forbidden Words
    |--------------------------------------------------------------------------
    |
    | Parole non ammesse nei campi "title" e "content" degli articoli.
    | Usate dalla regola di validazione App\Rules\WithoutForbiddenWords.
    |
    */

    'forbidden_words' => ['grazia', 'bad', 'ciao'],

];
