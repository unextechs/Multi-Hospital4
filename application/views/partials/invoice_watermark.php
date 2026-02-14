<?php
/**
 * Invoice Watermark Partial
 * 
 * Displays a transparent centered logo watermark
 * Include in invoice views with: $this->load->view('partials/invoice_watermark', $data);
 * 
 * Required Variables:
 * - $settings: Object containing logo
 */
?>

<?php if (!empty($settings->logo)): ?>
    <div class="invoice-watermark">
        <img src="<?php echo $settings->logo; ?>" alt="Watermark">
    </div>
<?php endif; ?>