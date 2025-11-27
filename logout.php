<?php
session_start();
session_destroy();
header("Location: login.php"); // přesměruje zpět na login
exit; // důležité, aby PHP dál nic nevykreslovalo
