<?php

if (! function_exists('inputClass')) {
    /**
     * retor uma class tailwindcss que estiliza as inputs do form
     *
     * @return void
     */
    function inputClass() {
        return 'w-full text-black border border-stone-200 transition-all ease-in disabled:opacity-50 disabled:pointer-events-none select-none text-sm py-2 px-2.5 ring shadow-sm bg-white rounded-lg duration-100 hover:border-stone-300 hover:ring-none focus:border-stone-400 focus:ring-none peer';
    }
}
