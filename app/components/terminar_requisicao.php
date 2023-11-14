<?php

session_write_close();
header('Connection: close');
header('Content-Length: '.ob_get_length());
ob_end_flush();
flush();
fastcgi_finish_request();

