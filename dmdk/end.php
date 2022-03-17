<?php
$file = 'tiny.php';

$newfile = 'print_message\tinymce_header.php';

if (!copy($file, $newfile)) {
    echo "не удалось скопировать $file...\n";
}
$file = 'add_su.php';

$newfile = 'add_sub.php';

if (!copy($file, $newfile)) {
    echo "не удалось скопировать $file...\n";
}
?>
