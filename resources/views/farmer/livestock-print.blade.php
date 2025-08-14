<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual Animal Record</title>
    <style>
        @media print {
            body { 
                margin: 0; 
                padding: 0;
            }
            .no-print { display: none !important; }
            .page { 
                page-break-after: always; 
                margin-bottom: 0;
                height: 190mm !important;
            }
            .page:last-child {
                page-break-after: avoid;
            }
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            background: white;
            color: #000;
        }
        
        .page {
            padding: 10mm;
            width: 297mm;
            height: 190mm;
            margin: 0 auto;
            background: white;
            position: relative;
            overflow: hidden;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        
        .print-header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .print-header h2 {
            margin: 3px 0 0 0;
            font-size: 12px;
            font-weight: normal;
        }
        
        .main-container {
            display: flex;
            gap: 25px;
        }
        
        .left-section {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .right-section {
            width: 350px;
            flex-shrink: 0;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            background: #f8f9fa;
            padding: 5px;
            margin-bottom: 8px;
            border: 1px solid #000;
            text-transform: uppercase;
        }
        
        .form-row {
            display: flex;
            margin-bottom: 6px;
            align-items: center;
        }
        
        .form-group {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }
        
        .form-group.full-width {
            margin-right: 0;
            flex: 1;
        }
        
        .form-group label {
            font-weight: normal;
            margin-right: 5px;
            font-size: 9px;
            white-space: nowrap;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            border: none;
            border-bottom: 1px solid #000;
            background: transparent;
            font-family: inherit;
            font-size: 9px;
            padding: 1px 0;
            min-width: 80px;
        }
        
        .form-group.wide input {
            min-width: 120px;
        }
        
        .form-group textarea {
            resize: none;
            height: 40px;
            width: 100%;
        }
        
        .drawing-section {
            text-align: center;
            margin: 10px 0;
            border: 1px solid #000;
            padding: 8px;
        }
        
        .drawing-container {
            display: flex;
            justify-content: space-around;
            margin: 5px 0;
        }
        
        .drawing {
            text-align: center;
        }
        
        .drawing canvas {
            border: 1px solid #000;
            width: 70px;
            height: 50px;
            background: #f9f9f9;
        }
        
        .drawing label {
            font-size: 8px;
            margin-top: 3px;
            display: block;
            font-weight: normal;
        }
        
        .table-container {
            margin-top: 5px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
        }
        
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
            height: 12px;
        }
        
        .data-table th {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 7px;
        }
        
        .data-table input {
            border: none;
            background: transparent;
            text-align: center;
            width: 100%;
            font-family: inherit;
            font-size: 6px;
            height: 10px;
            padding: 0;
        }
        
        .signature-section {
            text-align: center;
            margin-top: 15px;
            border-top: 1px solid #000;
            padding-top: 5px;
            width: 120px;
            font-size: 9px;
            font-weight: bold;
            margin-left: auto;
            margin-right: auto;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        @media print {
            .print-button { display: none; }
        }
        
        /* Page 2 specific styles */
        .page-2 {
            padding-top: 15px;
        }
        
        .page-2-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }
        
        .top-section {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex: 1;
        }
        
        .growth-section {
            flex: 1;
        }
        
        .breeding-section {
            flex: 1;
        }
        
        .calving-section {
            margin-top: 10px;
        }
        
        .legend {
            margin-top: 10px;
            font-size: 8px;
            text-align: right;
        }
        
        .legend-content {
            display: inline-block;
            text-align: left;
        }
        
        .legend h4 {
            margin: 0 0 3px 0;
            font-size: 8px;
            font-weight: bold;
        }
        
        .legend ul {
            margin: 0 0 8px 0;
            padding-left: 12px;
        }
        
        .legend li {
            margin-bottom: 1px;
        }

        .breeding-table th {
            font-size: 6px;
            padding: 1px;
        }

        .breeding-table {
            font-size: 6px;
        }
        
        .breeding-table input {
            font-size: 5px;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Print Record
    </button>

    <!-- PAGE 1: Individual Animal Record (Second Image) -->
    <div class="page">
        <div class="print-header">
            <h1>INDIVIDUAL ANIMAL RECORD</h1>
            <h2>LUBAN DAIRY RAISER'S ASSOCIATION</h2>
        </div>

        <div class="main-container">
            <!-- Left Section - Animal Information -->
            <div class="left-section">
                <div class="form-row">
                    <div class="form-group wide">
                        <label>Owned by:</label>
                        <input type="text" value="Sample Owner Name" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group wide">
                        <label>Dispersal From:</label>
                        <input type="text" value="Sample Farm Name" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Registry ID Number:</label>
                        <input type="text" value="REG001" readonly>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth:</label>
                        <input type="text" value="2022-01-15" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Tag ID Number:</label>
                        <input type="text" value="TAG001" readonly>
                    </div>
                    <div class="form-group">
                        <label>Sex:</label>
                        <input type="text" value="Female" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" value="Bella" readonly>
                    </div>
                    <div class="form-group">
                        <label>Breed:</label>
                        <input type="text" value="Holstein" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Sire - Registry ID Number:</label>
                        <input type="text" value="SIRE001" readonly>
                    </div>
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" value="Thunder" readonly>
                    </div>
                    <div class="form-group">
                        <label>Breed:</label>
                        <input type="text" value="Holstein" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Dam - Registry ID Number:</label>
                        <input type="text" value="DAM001" readonly>
                    </div>
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" value="Rosie" readonly>
                    </div>
                    <div class="form-group">
                        <label>Breed:</label>
                        <input type="text" value="Holstein" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Distinct Characteristics:</label>
                        <input type="text" value="White markings on face" readonly style="flex: 1;">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Natural marks:</label>
                        <input type="text" value="Small scar on left flank" readonly style="flex: 1;">
                    </div>
                </div>
                
                <!-- Drawing Section -->
                <div class="drawing-section">
                    <div class="drawing-container">
                        <div class="drawing">
                            <canvas id="rightSide" width="70" height="50"></canvas>
                            <label>Right Side<br>Mark "X" for cowlicks</label>
                        </div>
                        <div class="drawing">
                            <canvas id="frontView" width="70" height="50"></canvas>
                            <label>Front View</label>
                        </div>
                        <div class="drawing">
                            <canvas id="leftSide" width="70" height="50"></canvas>
                            <label>Left Side</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Property No.:</label>
                        <input type="text" value="PROP001" readonly>
                    </div>
                    <div class="form-group">
                        <label>Name of Cooperator:</label>
                        <input type="text" value="John Farmer" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Date acquired:</label>
                        <input type="text" value="2024-01-15" readonly>
                    </div>
                    <div class="form-group">
                        <label>Date released:</label>
                        <input type="text" value="" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Acquisition Cost:</label>
                        <input type="text" value="₱50,000" readonly>
                    </div>
                    <div class="form-group">
                        <label>Cooperative:</label>
                        <input type="text" value="LDRA" readonly>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Source / Origin:</label>
                        <input type="text" value="Local farm" readonly style="flex: 1;">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group full-width">
                        <label>Address:</label>
                        <input type="text" value="123 Farm Road, Luban" readonly style="flex: 1;">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Remarks:</label>
                        <textarea readonly>Healthy and active animal. Good milk producer.</textarea>
                    </div>
                    <div class="form-group">
                        <label>Contact No.:</label>
                        <input type="text" value="0912-345-6789" readonly>
                    </div>
                </div>
                
                <div class="signature-section">
                    In-Charge
                </div>
            </div>
            
            <!-- Right Section - Health Record -->
            <div class="right-section">
                <div class="section-title">HEALTH RECORD</div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 12%;">Date</th>
                                <th style="width: 18%;">Observations</th>
                                <th style="width: 18%;">Test Performed</th>
                                <th style="width: 18%;">Diagnosis/ Remarks</th>
                                <th style="width: 20%;">Drugs/ Biologicals Given</th>
                                <th style="width: 14%;">Signature</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" value="2024-01-15" readonly></td>
                                <td><input type="text" value="Normal behavior" readonly></td>
                                <td><input type="text" value="Physical exam" readonly></td>
                                <td><input type="text" value="Healthy" readonly></td>
                                <td><input type="text" value="Vitamins" readonly></td>
                                <td><input type="text" value="Dr. Cruz" readonly></td>
                            </tr>
                            <tr>
                                <td><input type="text" value="2024-02-15" readonly></td>
                                <td><input type="text" value="Good appetite" readonly></td>
                                <td><input type="text" value="Weight check" readonly></td>
                                <td><input type="text" value="Normal weight" readonly></td>
                                <td><input type="text" value="None" readonly></td>
                                <td><input type="text" value="Dr. Cruz" readonly></td>
                            </tr>
                            <tr>
                                <td><input type="text" value="2024-03-15" readonly></td>
                                <td><input type="text" value="Active" readonly></td>
                                <td><input type="text" value="Vaccination" readonly></td>
                                <td><input type="text" value="Vaccinated" readonly></td>
                                <td><input type="text" value="FMD vaccine" readonly></td>
                                <td><input type="text" value="Dr. Cruz" readonly></td>
                            </tr>
                            <!-- Empty rows -->
                            <script>
                                for (let i = 0; i < 18; i++) {
                                    document.write(`
                                        <tr>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                        </tr>
                                    `);
                                }
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- PAGE 2: Growth, Breeding, and Calving Records (First Image) -->
    <div class="page page-2">
        <div class="page-2-content">
            <!-- Top Section: Growth and Breeding side by side -->
            <div class="top-section">
                <!-- Growth Record Section -->
                <div class="growth-section">
                    <div class="section-title">GROWTH RECORD</div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 18%;">Date</th>
                                <th style="width: 15%;">Weight<br>kg.</th>
                                <th style="width: 15%;">Height,<br>cm</th>
                                <th style="width: 16%;">Heart<br>girth, cm</th>
                                <th style="width: 16%;">Body<br>length,<br>cm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <script>
                                for (let i = 0; i < 12; i++) {
                                    document.write(`
                                        <tr>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                        </tr>
                                    `);
                                }
                            </script>
                        </tbody>
                    </table>
                </div>
                
                <!-- Breeding/AI Activity Record Section -->
                <div class="breeding-section">
                    <div class="section-title">BREEDING/AI ACTIVITY RECORD</div>
                    <table class="data-table breeding-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 12%;">Date of<br>Service</th>
                                <th rowspan="2" style="width: 8%;">BCS</th>
                                <th colspan="3" style="width: 18%;">Signs of Estrus</th>
                                <th colspan="2" style="width: 20%;">Bull Used</th>
                                <th colspan="2" style="width: 16%;">PD</th>
                                <th rowspan="2" style="width: 14%;">AI Tech's<br>signature</th>
                            </tr>
                            <tr>
                                <th>VO</th>
                                <th>UT</th>
                                <th>MD</th>
                                <th>ID No.</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <script>
                                for (let i = 0; i < 12; i++) {
                                    document.write(`
                                        <tr>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                            <td><input type="text" value="" readonly></td>
                                        </tr>
                                    `);
                                }
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Bottom Section: Calving Record -->
            <div class="calving-section">
                <div class="section-title">CALVING AND MILK PRODUCTION RECORD</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 16%;">DATE OF CALVING</th>
                            <th style="width: 14%;">CALF ID NO.</th>
                            <th style="width: 10%;">SEX</th>
                            <th style="width: 12%;">BREED</th>
                            <th style="width: 14%;">SIRE ID NO.</th>
                            <th style="width: 14%;">MILK<br>PROD'N</th>
                            <th style="width: 20%;">DAYS IN<br>MILK (DIM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <script>
                            for (let i = 0; i < 8; i++) {
                                document.write(`
                                    <tr>
                                        <td><input type="text" value="" readonly></td>
                                        <td><input type="text" value="" readonly></td>
                                        <td><input type="text" value="" readonly></td>
                                        <td><input type="text" value="" readonly></td>
                                        <td><input type="text" value="" readonly></td>
                                        <td><input type="text" value="" readonly></td>
                                        <td><input type="text" value="" readonly></td>
                                    </tr>
                                `);
                            }
                        </script>
                    </tbody>
                </table>
            </div>
            
            <!-- Legends -->
            <div class="legend">
                <div class="legend-content">
                    <h4>Signs of Estrus:</h4>
                    <ul>
                        <li>MD - Mucus discharge, 1, 2, 3</li>
                        <li>UT - Uterine tone, 1, 2, 3</li>
                        <li>VO - Vaginal opening, 1, 2, 3</li>
                    </ul>
                    
                    <h4>Type of Service:</h4>
                    <ul>
                        <li>AI/ES - AI, synchronized</li>
                        <li>AI/NH - AI, natural heat</li>
                        <li>NM - Natural heat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple drawing function for the animal outlines
        function drawAnimalOutline(canvasId) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 1;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            if (canvasId === 'rightSide') {
                // Right side view - simple cattle outline
                ctx.beginPath();
                ctx.moveTo(15, 35); // Head
                ctx.lineTo(25, 30); // Neck
                ctx.lineTo(40, 25); // Back
                ctx.lineTo(55, 20); // Rump
                ctx.lineTo(60, 30); // Tail
                ctx.lineTo(55, 40); // Hind leg
                ctx.lineTo(40, 42); // Body
                ctx.lineTo(25, 42); // Front leg
                ctx.lineTo(15, 35); // Back to head
                ctx.stroke();
                
                // Horn
                ctx.beginPath();
                ctx.moveTo(12, 30);
                ctx.lineTo(8, 25);
                ctx.stroke();
                
                // Udder
                ctx.beginPath();
                ctx.arc(45, 40, 3, 0, 2 * Math.PI);
                ctx.stroke();
            } else if (canvasId === 'leftSide') {
                // Left side view - mirror of right
                ctx.beginPath();
                ctx.moveTo(55, 35); // Head
                ctx.lineTo(45, 30); // Neck
                ctx.lineTo(30, 25); // Back
                ctx.lineTo(15, 20); // Rump
                ctx.lineTo(10, 30); // Tail
                ctx.lineTo(15, 40); // Hind leg
                ctx.lineTo(30, 42); // Body
                ctx.lineTo(45, 42); // Front leg
                ctx.lineTo(55, 35); // Back to head
                ctx.stroke();
                
                // Horn
                ctx.beginPath();
                ctx.moveTo(58, 30);
                ctx.lineTo(62, 25);
                ctx.stroke();
                
                // Udder
                ctx.beginPath();
                ctx.arc(25, 40, 3, 0, 2 * Math.PI);
                ctx.stroke();
            } else if (canvasId === 'frontView') {
                // Front view - head and shoulders
                ctx.beginPath();
                ctx.moveTo(35, 15); // Top of head
                ctx.lineTo(30, 25); // Left ear
                ctx.lineTo(25, 35); // Left side of face
                ctx.lineTo(20, 40); // Left shoulder
                ctx.lineTo(50, 40); // Right shoulder
                ctx.lineTo(45, 35); // Right side of face
                ctx.lineTo(40, 25); // Right ear
                ctx.lineTo(35, 15); // Back to top
                ctx.stroke();
                
                // Eyes
                ctx.beginPath();
                ctx.arc(30, 30, 1.5, 0, 2 * Math.PI);
                ctx.arc(40, 30, 1.5, 0, 2 * Math.PI);
                ctx.fill();
                
                // Nose
                ctx.beginPath();
                ctx.moveTo(33, 35);
                ctx.lineTo(37, 35);
                ctx.moveTo(35, 35);
                ctx.lineTo(35, 38);
                ctx.stroke();
            }
        }
        
        // Draw all animal outlines when page loads
        window.onload = function() {
            drawAnimalOutline('rightSide');
            drawAnimalOutline('leftSide');
            drawAnimalOutline('frontView');
        };
    </script>
</body>
</html>