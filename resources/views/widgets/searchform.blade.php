<?php
/**
 * Template for displaying search forms in Custom Theme
 *
 * @package WordPress
 * @subpackage Jansen Theme
 * @since 1.0
 * @version 1.0
 */

?>

<form role="search" method="get" class="search-form" action="{!! esc_url( home_url( '/' ) ) !!}">
    <input type="search" class="search-field" placeholder="{!! esc_attr( 'SUCHEN &hellip;' ) !!}"
        value="{!! get_search_query() !!}" name="s" />
    <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
</form>
<style>
    form.search-form {
        position: relative;
    }

    form.search-form input {
        width: 100% !important;
        border: 1px solid #9d9d9d;
        padding: 10px 15px;
        padding-right: 55px;
        font-size: 14px;
    }

    form.search-form button {
        position: absolute;
        height: 100%;
        right: 0;
        top: 0;
        border: none;
        width: 50px;
        padding-top: 5px;
        color: #b7b7b7;
        background-color: transparent;
        cursor: pointer;
    }

    form.search-form button:hover {
        color: #808080;
        background-color: transparent;
    }
</style>