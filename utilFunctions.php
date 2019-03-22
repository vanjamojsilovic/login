<?php

function sanatize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));;
}
