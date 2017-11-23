<?php

define('REGEX_FOR_NAME', '/^[a-zA-Z\sáéíóúàèìòùÁÉÍÓÚÀÈÌÒÙãñÃÑ]{1,256}$/');
define('REGEX_FOR_LASTNAME', '/^[a-zA-Z\sáéíóúàèìòùÁÉÍÓÚÀÈÌÒÙãñÃÑ]{1,256}$/');
define('REGEX_FOR_EMAIL', '');
define('REGEX_FOR_PASSWORD', '/^[a-zA-Z0-9]{8,256}$/');
define('REGEX_FOR_TOKEN', '/^[-A-Za-z0-9+=]{1,512}$/');
define('REGEX_FOR_DIRECTORY', '/^((([\/]{1}[^\?\*\\\.\:\/\<\>\|\"]{1,}([\.]{1,}[^\?\*\\\.\:\/\<\>\|\"]{1,})*)|([\/]{1}[^\?\*\\\.\:\/\<\>\|\"]{1,}([\.]{1,}[^\?\*\\\.\:\/\<\>\|\"]{1,})*))*|[\/]{1})$/');
define('REGEX_FOR_USERNAME', '/^[a-z0-9]{1,128}/');

?>