<?php


$tmp = 'tmp/kyousipanda.jpg';
if (rename($tmp, 'image/kyousipanda.jpg')) {
    echo "OK";
} else {
    echo "NO";
}
