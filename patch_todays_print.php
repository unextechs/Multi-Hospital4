<?php
$file = 'application/modules/appointment/controllers/Appointment.php';
$lines = file($file);

// Line 2954 (index 2953)
if (strpos($lines[2953], '<span class="d-flex gap-2">') !== false) {
    $lines[2953] = "                    '<span class=\"d-flex gap-2\">' . \$option1 . ' ' . \$option_view . ' ' . \$option2 . (\$options7 ?? '') . ' ' . ((!empty(\$appointment->queue_number)) ? '<a type=\"button\" class=\"btn btn-warning btn-sm btn_width\" onclick=\"printToken(' . \$appointment->id . ')\" title=\"' . lang('print') . ' ' . lang('token') . '\"><i class=\"fa fa-print\"></i></a>' : '') . '</span>'\n";
    echo "Updated line 2954\n";
} else {
    echo "Line 2954 not found or already updated\n";
}

// Line 2968 (index 2967)
if (strpos($lines[2967], '<span class=\"d-flex gap-2">') !== false) {
    $lines[2967] = "                    '<span class=\"d-flex gap-2\">' . \$option1 . ' ' . \$option_view . ' ' . \$option2 . (\$options7 ?? '') . ' ' . ((!empty(\$appointment->queue_number)) ? '<a type=\"button\" class=\"btn btn-warning btn-sm btn_width\" onclick=\"printToken(' . \$appointment->id . ')\" title=\"' . lang('print') . ' ' . lang('token') . '\"><i class=\"fa fa-print\"></i></a>' : '') . '</span>'\n";
    echo "Updated line 2968\n";
} else {
    echo "Line 2968 not found or already updated\n";
}

file_put_contents($file, implode('', $lines));
echo "Patch completed successfully\n";
?>