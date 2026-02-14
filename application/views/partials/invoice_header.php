<?php
/**
 * Invoice Header Partial
 * 
 * Displays a professional header with huge logo and date/time
 * Include in invoice views with: $this->load->view('partials/invoice_header', $data);
 * 
 * Required Variables:
 * - $settings: Object containing logo, title, address, phone, email
 * - $invoice_date: Unix timestamp or formatted date string
 * - $invoice_time: (optional) Time string, will extract from $invoice_date if not provided
 */

// Determine the date and time
if (isset($invoice_date)) {
    if (is_numeric($invoice_date)) {
        $formatted_date = date('d-m-Y', $invoice_date);
        $formatted_time = date('h:i A', $invoice_date);
    } else {
        $formatted_date = $invoice_date;
        $formatted_time = isset($invoice_time) ? $invoice_time : date('h:i A');
    }
} else {
    $formatted_date = date('d-m-Y');
    $formatted_time = date('h:i A');
}

// Override time if explicitly provided
if (isset($invoice_time)) {
    $formatted_time = $invoice_time;
}
?>

<div class="invoice-header">
    <div class="invoice-header-left">
        <?php if (!empty($settings->logo)): ?>
            <img src="<?php echo $settings->logo; ?>" alt="<?php echo $settings->title; ?>"
                class="invoice-logo invoice-logo-large">
        <?php else: ?>
            <h1 class="invoice-clinic-name">
                <i class="fas fa-hospital-alt mr-2"></i>
                <?php echo $settings->title; ?>
            </h1>
        <?php endif; ?>

        <?php // Clinic details removed as per request ?>
    </div>

    <div class="invoice-header-right">
        <div class="invoice-datetime">
            <div class="invoice-datetime-row">
                <span class="invoice-datetime-label">
                    <?php echo lang('date'); ?>:
                </span>
                <span class="invoice-datetime-value">
                    <?php echo $formatted_date; ?>
                </span>
            </div>
            <div class="invoice-datetime-row">
                <span class="invoice-datetime-label">
                    <?php echo lang('time'); ?>:
                </span>
                <span class="invoice-datetime-value">
                    <?php echo $formatted_time; ?>
                </span>
            </div>
        </div>
    </div>
</div>