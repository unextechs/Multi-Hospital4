# Medicine Bulk Import Guide

## Files Created

1. **medicine_bulk_import.xlsx** - Excel file ready for bulk import
2. **medicine_bulk_import.csv** - CSV version (backup)

## Import Instructions

### Step 1: Access Import Module
1. Log in to your Multi-Hospital system
2. Navigate to **Import** section
3. Select **Medicine Import**

### Step 2: Upload File
1. Click on "Choose File" or "Browse"
2. Select: `medicine_bulk_import.xlsx`
3. Click "Import" or "Submit"

### Step 3: Verify Import
- The system will import all 90+ medicines
- Initial quantities are set to **0** (as per system design)
- You'll need to add batches with expiry dates separately

## Excel File Structure

The Excel file contains the following columns:

| Column | Description | Example |
|--------|-------------|---------|
| **name** | Medicine name with dosage | FIXEF (CEFAXIM 400MG) TAB |
| **price** | Purchase/Cost price | 2.65 |
| **s_price** | Selling price | 5 |
| **box** | Items per box/pack | 10 |
| **quantity** | Stock quantity (set to 0) | 0 |
| **generic** | Generic/Active ingredient | Cefixime |
| **company** | Manufacturer/Company | TURKEY |
| **effects** | Medical category/use | Antibiotic |

## Medicine Categories Included

✅ **Antibiotics** (35+ items)
- Amoxicillin, Azithromycin, Ceftriaxone, Ciprofloxacin, etc.

✅ **Pain/Fever Management** (15+ items)  
- Paracetamol, Ibuprofen, Diclofenac, etc.

✅ **Gastrointestinal** (10+ items)
- Omeprazole, Laxatives, Probiotics, Anti-nausea, etc.

✅ **Respiratory** (5+ items)
- Asthma medications, Antihistamines

✅ **Neurological** (5+ items)
- Anticonvulsants, Sedatives

✅ **IV Fluids & Electrolytes** (5+ items)
- Saline, Ringer's Lactate, etc.

✅ **Vitamins & Minerals** (8+ items)
- Vitamin D, Zinc, Multivitamins, etc.

✅ **Antimalarials** (4+ items)
- Artemether combinations

✅ **Corticosteroids** (4+ items)
- Dexamethasone, Prednisolone, etc.

✅ **Topical/External** (6+ items)
- Ear/Eye drops, Creams, Shampoos

✅ **Other** 
- Diuretics, Antiparasitics, etc.

## Important Notes

⚠️ **Quantities Start at Zero**
- All medicines will be imported with quantity = 0
- This is intentional (system design)
- Add stock batches with expiry dates through Medicine Management

⚠️ **Duplicate Check**
- System will skip medicines that already exist
- Check import result for any warnings

⚠️ **Pricing**
- Review and adjust prices as needed for your hospital
- Prices are in the currency configured in your system

## After Import

1. **Add Stock Batches**
   - Go to Medicine → Each Medicine
   - Add batches with:
     - Quantity
     - Expiry date
     - Batch number

2. **Review Categories**
   - Verify medicines are in correct categories
   - Add any missing information

3. **Set Reorder Levels**
   - Configure low stock alerts
   - Set minimum quantities

## Troubleshooting

**Problem:** Wrong file format error  
**Solution:** Ensure you're uploading the .xlsx file, not the PDF

**Problem:** Some medicines not imported  
**Solution:** They may already exist in your database - check the import warnings

**Problem:** Need to update medicine info  
**Solution:** Go to Medicine Management and edit individually, or re-import after Excel modifications

---

**Total Medicines:** 90+  
**File Created:** February 4, 2026  
**Source:** medicine_xl_format.pdf
