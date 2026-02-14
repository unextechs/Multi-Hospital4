-- ============================================================
-- LAB TEST TEMPLATES FOR MULTI-HOSPITAL SYSTEM
-- Import these templates into your lab_template table
-- ============================================================

-- Note: Adjust table and column names according to your database schema
-- This assumes you have a table structure like:
-- lab_category (id, name, description)
-- lab_template (id, name, template, category_id, user, hospital_id)

-- ============================================================
-- STEP 1: Insert Lab Categories
-- ============================================================

INSERT INTO `lab_category` (`name`, `description`) VALUES
('Hematology', 'Blood tests including CBC, ESR, Blood Group, Coagulation studies'),
('Biochemistry', 'Clinical chemistry tests including LFT, RFT, Lipid Profile, Glucose tests'),
('Serology & Immunology', 'Viral markers, infectious disease markers, inflammatory markers'),
('Endocrinology', 'Hormone tests, diabetes screening, thyroid function'),
('Urine Analysis', 'Routine urine examination, 24-hour urine tests'),
('Microbiology', 'Culture & sensitivity, stool examination'),
('Pregnancy & Fertility', 'Pregnancy tests, prenatal screening, fertility tests'),
('Tumor Markers', 'Cancer screening and monitoring tests'),
('Vitamins & Minerals', 'Vitamin and mineral deficiency tests'),
('Special Tests', 'ABG, autoimmune panel, celiac disease panel');

-- ============================================================
-- STEP 2: Insert Lab Test Templates
-- ============================================================

-- Template 1: Complete Blood Count (CBC)
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Complete Blood Count (CBC)', 
'<h3>Complete Blood Count (CBC)</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>Hemoglobin (Hb)</td><td>_____</td><td>g/dL</td><td>Male: 13-17, Female: 12-15</td></tr>
<tr><td>RBC Count</td><td>_____</td><td>million/μL</td><td>Male: 4.5-5.5, Female: 4.0-5.0</td></tr>
<tr><td>WBC Count</td><td>_____</td><td>/μL</td><td>4,000-11,000</td></tr>
<tr><td>Platelet Count</td><td>_____</td><td>/μL</td><td>150,000-450,000</td></tr>
<tr><td>Hematocrit (HCT)</td><td>_____</td><td>%</td><td>Male: 40-50, Female: 36-44</td></tr>
<tr><td>MCV</td><td>_____</td><td>fL</td><td>80-100</td></tr>
<tr><td>MCH</td><td>_____</td><td>pg</td><td>27-33</td></tr>
<tr><td>MCHC</td><td>_____</td><td>g/dL</td><td>32-36</td></tr>
<tr><td>RDW</td><td>_____</td><td>%</td><td>11.5-14.5</td></tr>
</table>
<h4>Differential Count</h4>
<table class="table table-bordered">
<tr><th>Cell Type</th><th>Result (%)</th><th>Normal Range</th></tr>
<tr><td>Neutrophils</td><td>_____</td><td>40-70%</td></tr>
<tr><td>Lymphocytes</td><td>_____</td><td>20-45%</td></tr>
<tr><td>Monocytes</td><td>_____</td><td>2-10%</td></tr>
<tr><td>Eosinophils</td><td>_____</td><td>1-6%</td></tr>
<tr><td>Basophils</td><td>_____</td><td>0-2%</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Hematology' LIMIT 1));

-- Template 2: Liver Function Test (LFT)
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Liver Function Test (LFT)', 
'<h3>Liver Function Test (LFT)</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>Total Bilirubin</td><td>_____</td><td>mg/dL</td><td>0.3-1.2</td></tr>
<tr><td>Direct Bilirubin</td><td>_____</td><td>mg/dL</td><td>0.1-0.4</td></tr>
<tr><td>Indirect Bilirubin</td><td>_____</td><td>mg/dL</td><td>0.2-0.8</td></tr>
<tr><td>SGOT/AST</td><td>_____</td><td>U/L</td><td>5-40</td></tr>
<tr><td>SGPT/ALT</td><td>_____</td><td>U/L</td><td>7-56</td></tr>
<tr><td>Alkaline Phosphatase (ALP)</td><td>_____</td><td>U/L</td><td>44-147</td></tr>
<tr><td>GGT</td><td>_____</td><td>U/L</td><td>8-61</td></tr>
<tr><td>Total Protein</td><td>_____</td><td>g/dL</td><td>6.0-8.3</td></tr>
<tr><td>Albumin</td><td>_____</td><td>g/dL</td><td>3.5-5.5</td></tr>
<tr><td>Globulin</td><td>_____</td><td>g/dL</td><td>2.0-3.5</td></tr>
<tr><td>A/G Ratio</td><td>_____</td><td></td><td>1.0-2.5</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Biochemistry' LIMIT 1));

-- Template 3: Renal Function Test (RFT/KFT)
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Renal Function Test (RFT/KFT)', 
'<h3>Renal Function Test (RFT/KFT)</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>Blood Urea Nitrogen (BUN)</td><td>_____</td><td>mg/dL</td><td>7-20</td></tr>
<tr><td>Serum Creatinine</td><td>_____</td><td>mg/dL</td><td>Male: 0.7-1.3, Female: 0.6-1.1</td></tr>
<tr><td>Uric Acid</td><td>_____</td><td>mg/dL</td><td>Male: 3.5-7.2, Female: 2.6-6.0</td></tr>
<tr><td>Sodium (Na+)</td><td>_____</td><td>mEq/L</td><td>136-145</td></tr>
<tr><td>Potassium (K+)</td><td>_____</td><td>mEq/L</td><td>3.5-5.1</td></tr>
<tr><td>Chloride (Cl-)</td><td>_____</td><td>mEq/L</td><td>98-107</td></tr>
<tr><td>Bicarbonate (HCO3-)</td><td>_____</td><td>mEq/L</td><td>22-29</td></tr>
<tr><td>eGFR</td><td>_____</td><td>mL/min/1.73m²</td><td>>60</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Biochemistry' LIMIT 1));

-- Template 4: Lipid Profile
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Lipid Profile', 
'<h3>Lipid Profile</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>Total Cholesterol</td><td>_____</td><td>mg/dL</td><td>Desirable: <200, Borderline: 200-239, High: >240</td></tr>
<tr><td>Triglycerides</td><td>_____</td><td>mg/dL</td><td>Normal: <150, Borderline: 150-199, High: >200</td></tr>
<tr><td>HDL Cholesterol</td><td>_____</td><td>mg/dL</td><td>Male: >40, Female: >50</td></tr>
<tr><td>LDL Cholesterol</td><td>_____</td><td>mg/dL</td><td>Optimal: <100, Borderline: 130-159, High: >160</td></tr>
<tr><td>VLDL Cholesterol</td><td>_____</td><td>mg/dL</td><td>5-40</td></tr>
<tr><td>Total Cholesterol/HDL Ratio</td><td>_____</td><td></td><td><5</td></tr>
<tr><td>LDL/HDL Ratio</td><td>_____</td><td></td><td><3</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Biochemistry' LIMIT 1));

-- Template 5: Thyroid Function Test (TFT)
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Thyroid Function Test (TFT)', 
'<h3>Thyroid Function Test (TFT)</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>T3 (Triiodothyronine)</td><td>_____</td><td>ng/dL</td><td>80-200</td></tr>
<tr><td>T4 (Thyroxine)</td><td>_____</td><td>μg/dL</td><td>5.0-12.0</td></tr>
<tr><td>Free T3 (FT3)</td><td>_____</td><td>pg/mL</td><td>2.0-4.4</td></tr>
<tr><td>Free T4 (FT4)</td><td>_____</td><td>ng/dL</td><td>0.8-1.8</td></tr>
<tr><td>TSH</td><td>_____</td><td>μIU/mL</td><td>0.4-4.0</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Endocrinology' LIMIT 1));

-- Template 6: Blood Glucose Tests
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Blood Glucose Tests', 
'<h3>Blood Glucose Tests</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Interpretation</th></tr>
<tr><td>Fasting Blood Sugar (FBS)</td><td>_____</td><td>mg/dL</td><td>Normal: 70-100, Prediabetes: 100-125, Diabetes: >126</td></tr>
<tr><td>Random Blood Sugar (RBS)</td><td>_____</td><td>mg/dL</td><td>Normal: <140, Prediabetes: 140-199, Diabetes: >200</td></tr>
<tr><td>Post-Prandial (PPBS)</td><td>_____</td><td>mg/dL</td><td>Normal: <140, Prediabetes: 140-199, Diabetes: >200</td></tr>
<tr><td>HbA1c</td><td>_____</td><td>%</td><td>Normal: <5.7, Prediabetes: 5.7-6.4, Diabetes: >6.5</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Biochemistry' LIMIT 1));

-- Template 7: ESR
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('ESR (Erythrocyte Sedimentation Rate)', 
'<h3>ESR Test</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>ESR</td><td>_____</td><td>mm/hr</td><td>Male: 0-15, Female: 0-20</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Hematology' LIMIT 1));

-- Template 8: Blood Group & Rh
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Blood Group & Rh Type', 
'<h3>Blood Group & Rh Type</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th></tr>
<tr><td>Blood Group</td><td>_____ (A/B/AB/O)</td></tr>
<tr><td>Rh Factor</td><td>_____ (Positive/Negative)</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Hematology' LIMIT 1));

-- Template 9: Coagulation Profile
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Coagulation Profile', 
'<h3>Coagulation Profile</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>Prothrombin Time (PT)</td><td>_____</td><td>seconds</td><td>11-13.5</td></tr>
<tr><td>INR</td><td>_____</td><td></td><td>0.8-1.2</td></tr>
<tr><td>aPTT</td><td>_____</td><td>seconds</td><td>25-35</td></tr>
<tr><td>Bleeding Time</td><td>_____</td><td>minutes</td><td>2-7</td></tr>
<tr><td>Clotting Time</td><td>_____</td><td>minutes</td><td>5-10</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Hematology' LIMIT 1));

-- Template 10: Cardiac Enzymes
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Cardiac Enzymes', 
'<h3>Cardiac Enzymes</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>Troponin I</td><td>_____</td><td>ng/mL</td><td><0.04</td></tr>
<tr><td>Troponin T</td><td>_____</td><td>ng/mL</td><td><0.01</td></tr>
<tr><td>CK-MB</td><td>_____</td><td>ng/mL</td><td><5</td></tr>
<tr><td>Creatine Kinase (CK)</td><td>_____</td><td>U/L</td><td>Male: 52-336, Female: 38-176</td></tr>
<tr><td>LDH</td><td>_____</td><td>U/L</td><td>140-280</td></tr>
<tr><td>Myoglobin</td><td>_____</td><td>ng/mL</td><td>Male: 28-72, Female: 25-58</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Biochemistry' LIMIT 1));

-- Template 11: Hepatitis B Profile
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Hepatitis B Profile', 
'<h3>Hepatitis B Markers</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th></tr>
<tr><td>HBsAg</td><td>_____ (Positive/Negative)</td></tr>
<tr><td>Anti-HBs</td><td>_____ (Positive/Negative)</td></tr>
<tr><td>Anti-HBc</td><td>_____ (Positive/Negative)</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Serology & Immunology' LIMIT 1));

-- Template 12: Hepatitis C Test
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Hepatitis C Test', 
'<h3>Hepatitis C Markers</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th></tr>
<tr><td>Anti-HCV</td><td>_____ (Positive/Negative)</td></tr>
<tr><td>HCV RNA PCR</td><td>_____ (Positive/Negative)</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Serology & Immunology' LIMIT 1));

-- Template 13: HIV Screening
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('HIV Screening', 
'<h3>HIV Test</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Normal Range</th></tr>
<tr><td>HIV 1 & 2 Antibody</td><td>_____ (Reactive/Non-Reactive)</td><td></td></tr>
<tr><td>HIV p24 Antigen</td><td>_____ (Positive/Negative)</td><td></td></tr>
<tr><td>CD4 Count</td><td>_____</td><td>cells/μL (Normal: 500-1500)</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Serology & Immunology' LIMIT 1));

-- Template 14: Routine Urine Examination
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Routine Urine Examination', 
'<h3>Routine Urine Examination</h3>
<h4>Physical Examination</h4>
<table class="table table-bordered">
<tr><th>Parameter</th><th>Result</th><th>Normal</th></tr>
<tr><td>Color</td><td>_____</td><td>Pale yellow to amber</td></tr>
<tr><td>Appearance</td><td>_____</td><td>Clear</td></tr>
<tr><td>Specific Gravity</td><td>_____</td><td>1.005-1.030</td></tr>
<tr><td>pH</td><td>_____</td><td>5.0-8.0</td></tr>
</table>
<h4>Chemical Examination</h4>
<table class="table table-bordered">
<tr><th>Parameter</th><th>Result</th></tr>
<tr><td>Protein</td><td>_____ (Negative/Trace/+/++/+++)</td></tr>
<tr><td>Glucose</td><td>_____ (Negative/Trace/+/++/+++)</td></tr>
<tr><td>Ketones</td><td>_____ (Negative/Positive)</td></tr>
<tr><td>Blood</td><td>_____ (Negative/Trace/+/++/+++)</td></tr>
<tr><td>Bilirubin</td><td>_____ (Negative/Positive)</td></tr>
<tr><td>Urobilinogen</td><td>_____</td></tr>
<tr><td>Nitrite</td><td>_____ (Negative/Positive)</td></tr>
<tr><td>Leukocyte Esterase</td><td>_____ (Negative/Positive)</td></tr>
</table>
<h4>Microscopic Examination</h4>
<table class="table table-bordered">
<tr><th>Parameter</th><th>Result</th><th>Normal</th></tr>
<tr><td>Pus Cells</td><td>_____ /HPF</td><td>0-5</td></tr>
<tr><td>RBCs</td><td>_____ /HPF</td><td>0-2</td></tr>
<tr><td>Epithelial Cells</td><td>_____ /HPF</td><td>Few</td></tr>
<tr><td>Casts</td><td>_____ /LPF</td><td>None</td></tr>
<tr><td>Crystals</td><td>_____</td><td>None</td></tr>
<tr><td>Bacteria</td><td>_____</td><td>None</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Urine Analysis' LIMIT 1));

-- Template 15: Urine Culture & Sensitivity
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Urine Culture & Sensitivity', 
'<h3>Urine Culture & Sensitivity</h3>
<table class="table table-bordered">
<tr><th>Parameter</th><th>Result</th></tr>
<tr><td>Culture Report</td><td>_____ (Growth/No Growth)</td></tr>
<tr><td>Organism Isolated</td><td>_____________________</td></tr>
<tr><td>Colony Count</td><td>_____ CFU/mL</td></tr>
</table>
<h4>Sensitivity Pattern</h4>
<table class="table table-bordered">
<tr><th>Antibiotic</th><th>Result</th></tr>
<tr><td>Ampicillin</td><td>_____ (S/R/I)</td></tr>
<tr><td>Ciprofloxacin</td><td>_____ (S/R/I)</td></tr>
<tr><td>Gentamicin</td><td>_____ (S/R/I)</td></tr>
<tr><td>Ceftriaxone</td><td>_____ (S/R/I)</td></tr>
<tr><td>Nitrofurantoin</td><td>_____ (S/R/I)</td></tr>
<tr><td>Levofloxacin</td><td>_____ (S/R/I)</td></tr>
</table>
<p><strong>S = Sensitive, R = Resistant, I = Intermediate</strong></p>', 
(SELECT id FROM lab_category WHERE name = 'Microbiology' LIMIT 1));

-- Template 16: Stool Routine Examination
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Stool Routine Examination', 
'<h3>Stool Routine Examination</h3>
<h4>Physical Examination</h4>
<table class="table table-bordered">
<tr><th>Parameter</th><th>Result</th><th>Normal</th></tr>
<tr><td>Color</td><td>_____</td><td>Brown</td></tr>
<tr><td>Consistency</td><td>_____</td><td>Formed</td></tr>
<tr><td>Blood</td><td>_____</td><td>Negative</td></tr>
<tr><td>Mucus</td><td>_____</td><td>Negative</td></tr>
</table>
<h4>Microscopic Examination</h4>
<table class="table table-bordered">
<tr><th>Parameter</th><th>Result</th></tr>
<tr><td>Pus Cells</td><td>_____ /HPF</td></tr>
<tr><td>RBCs</td><td>_____ /HPF</td></tr>
<tr><td>Ova</td><td>_____ (Present/Absent)</td></tr>
<tr><td>Cysts</td><td>_____ (Present/Absent)</td></tr>
<tr><td>Parasites</td><td>_____________________</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Microbiology' LIMIT 1));

-- Template 17: Pregnancy Test
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Pregnancy Test', 
'<h3>Pregnancy Test</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th></tr>
<tr><td>Urine Pregnancy Test</td><td>_____ (Positive/Negative)</td></tr>
<tr><td>Beta-hCG (Quantitative)</td><td>_____ mIU/mL</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Pregnancy & Fertility' LIMIT 1));

-- Template 18: Semen Analysis
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Semen Analysis', 
'<h3>Semen Analysis</h3>
<table class="table table-bordered">
<tr><th>Parameter</th><th>Result</th><th>Normal Range</th></tr>
<tr><td>Volume</td><td>_____ mL</td><td>1.5-6</td></tr>
<tr><td>pH</td><td>_____</td><td>7.2-8.0</td></tr>
<tr><td>Sperm Concentration</td><td>_____ million/mL</td><td>>15</td></tr>
<tr><td>Total Sperm Count</td><td>_____ million</td><td>>39</td></tr>
<tr><td>Motility</td><td>_____ %</td><td>>40</td></tr>
<tr><td>Progressive Motility</td><td>_____ %</td><td>>32</td></tr>
<tr><td>Normal Morphology</td><td>_____ %</td><td>>4</td></tr>
<tr><td>Liquefaction Time</td><td>_____ min</td><td>15-30</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Pregnancy & Fertility' LIMIT 1));

-- Template 19: Tumor Markers
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Common Tumor Markers', 
'<h3>Tumor Markers</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>CEA</td><td>_____</td><td>ng/mL</td><td><5</td></tr>
<tr><td>CA 19-9</td><td>_____</td><td>U/mL</td><td><37</td></tr>
<tr><td>CA 125</td><td>_____</td><td>U/mL</td><td><35</td></tr>
<tr><td>CA 15-3</td><td>_____</td><td>U/mL</td><td><30</td></tr>
<tr><td>AFP</td><td>_____</td><td>ng/mL</td><td><10</td></tr>
<tr><td>PSA (Total)</td><td>_____</td><td>ng/mL</td><td><4</td></tr>
<tr><td>Free PSA</td><td>_____</td><td>ng/mL</td><td></td></tr>
<tr><td>Free/Total PSA Ratio</td><td>_____ %</td><td>>25</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Tumor Markers' LIMIT 1));

-- Template 20: Vitamin D
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Vitamin D Test', 
'<h3>Vitamin D (25-OH)</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Interpretation</th></tr>
<tr><td>Vitamin D (25-OH)</td><td>_____</td><td>ng/mL</td><td>Deficient: <20, Insufficient: 20-30, Sufficient: >30</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Vitamins & Minerals' LIMIT 1));

-- Template 21: Vitamin B12 & Folate
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Vitamin B12 & Folate', 
'<h3>Vitamin B12 & Folate</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>Vitamin B12</td><td>_____</td><td>pg/mL</td><td>200-900</td></tr>
<tr><td>Folate</td><td>_____</td><td>ng/mL</td><td>2.7-17</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Vitamins & Minerals' LIMIT 1));

-- Template 22: Iron Studies
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Iron Studies', 
'<h3>Iron Studies</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>Serum Iron</td><td>_____</td><td>μg/dL</td><td>Male: 65-175, Female: 50-170</td></tr>
<tr><td>TIBC</td><td>_____</td><td>μg/dL</td><td>250-450</td></tr>
<tr><td>Ferritin</td><td>_____</td><td>ng/mL</td><td>Male: 24-336, Female: 11-307</td></tr>
<tr><td>Transferrin Saturation</td><td>_____ %</td><td></td><td>20-50</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Vitamins & Minerals' LIMIT 1));

-- Template 23: Arterial Blood Gas (ABG)
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Arterial Blood Gas (ABG)', 
'<h3>Arterial Blood Gas (ABG)</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th><th>Unit</th><th>Normal Range</th></tr>
<tr><td>pH</td><td>_____</td><td></td><td>7.35-7.45</td></tr>
<tr><td>PaO2</td><td>_____</td><td>mmHg</td><td>80-100</td></tr>
<tr><td>PaCO2</td><td>_____</td><td>mmHg</td><td>35-45</td></tr>
<tr><td>HCO3-</td><td>_____</td><td>mEq/L</td><td>22-26</td></tr>
<tr><td>Base Excess</td><td>_____</td><td>mEq/L</td><td>-2 to +2</td></tr>
<tr><td>O2 Saturation</td><td>_____ %</td><td></td><td>>95</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Special Tests' LIMIT 1));

-- Template 24: Widal Test (Typhoid)
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Widal Test (Typhoid)', 
'<h3>Widal Test</h3>
<table class="table table-bordered">
<tr><th>Antigen</th><th>Titre</th></tr>
<tr><td>Salmonella Typhi O</td><td>_____</td></tr>
<tr><td>Salmonella Typhi H</td><td>_____</td></tr>
<tr><td>Salmonella Paratyphi AH</td><td>_____</td></tr>
<tr><td>Salmonella Paratyphi BH</td><td>_____</td></tr>
</table>
<p><em>Significant titre: ≥1:80</em></p>', 
(SELECT id FROM lab_category WHERE name = 'Serology & Immunology' LIMIT 1));

-- Template 25: Dengue Test
INSERT INTO `lab_template` (`name`, `template`, `category_id`) VALUES
('Dengue Test', 
'<h3>Dengue Test</h3>
<table class="table table-bordered">
<tr><th>Test Parameter</th><th>Result</th></tr>
<tr><td>Dengue NS1 Antigen</td><td>_____ (Positive/Negative)</td></tr>
<tr><td>Dengue IgM</td><td>_____ (Positive/Negative)</td></tr>
<tr><td>Dengue IgG</td><td>_____ (Positive/Negative)</td></tr>
</table>', 
(SELECT id FROM lab_category WHERE name = 'Serology & Immunology' LIMIT 1));

-- ============================================================
-- NOTES FOR USAGE:
-- 1. These templates use HTML tables for better formatting
-- 2. The underscores (___) indicate fields to be filled by lab technician
-- 3. Adjust category_id mappings based on your actual category IDs
-- 4. You can add hospital_id and user columns as needed
-- 5. For multi-hospital setup, you may want to copy these for each hospital
-- ============================================================
