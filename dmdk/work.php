<?php
$file = 'print_message\work_header.php';

$newfile = 'print_message\tinymce_header.php';

if (!copy($file, $newfile)) {
    echo "не удалось скопировать $file...\n";
}
$file = 'work_add_sub.php';

$newfile = 'add_sub.php';

if (!copy($file, $newfile)) {
    echo "не удалось скопировать $file...\n";
}
?>
