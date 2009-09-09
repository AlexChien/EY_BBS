<?php
/**
 * @author sutra
 * @copyright Copyright &copy; 2001-2007, Redv Soft
 * @license http://openid4discuz.redv.com/LICENSE.txt BSD
 */
if (isset($msg)) { showmessage($msg, dreferer()); }
if (isset($error)) { showmessage($error, dreferer()); }
if (isset($success)) { showmessage($success, dreferer()); }
?>