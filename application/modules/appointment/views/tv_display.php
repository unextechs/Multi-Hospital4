<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($settings) ? $settings->system_vendor : 'Hospital'; ?> - Queue Display</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg,
                    <?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?>
                    0%,
                    <?php echo !empty($settings->tv_secondary_color) ? $settings->tv_secondary_color : '#764ba2'; ?>
                    50%,
                    <?php echo !empty($settings->tv_accent_color) ? $settings->tv_accent_color : '#f093fb'; ?>
                    100%);
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Animated background decorations */
        .bg-decoration {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite ease-in-out;
        }

        .decoration-1 {
            width: 300px;
            height: 300px;
            background: white;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }

        .decoration-2 {
            width: 200px;
            height: 200px;
            background: #ffd700;
            top: 60%;
            right: 10%;
            animation-delay: 5s;
        }

        .decoration-3 {
            width: 150px;
            height: 150px;
            background: #00ff88;
            bottom: 10%;
            left: 15%;
            animation-delay: 10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-30px) rotate(180deg);
            }
        }

        .header {
            background: rgba(255, 255, 255, 0.98);
            padding: 20px 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 100;
            border-bottom: 5px solid transparent;
            border-image: linear-gradient(90deg,
                    <?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?>
                    ,
                    <?php echo !empty($settings->tv_secondary_color) ? $settings->tv_secondary_color : '#764ba2'; ?>
                    ,
                    <?php echo !empty($settings->tv_accent_color) ? $settings->tv_accent_color : '#f093fb'; ?>
                ) 1;
        }

        .hospital-branding {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .hospital-logo {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg,
                    <?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?>
                    0%,
                    <?php echo !empty($settings->tv_secondary_color) ? $settings->tv_secondary_color : '#764ba2'; ?>
                    100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.8em;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            animation: pulse 3s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            50% {
                box-shadow: 0 5px 30px rgba(0, 0, 0, 0.4);
            }
        }

        .hospital-name {
            font-size: 2.8em;
            font-weight: bold;
            background: linear-gradient(135deg,
                    <?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?>
                    0%,
                    <?php echo !empty($settings->tv_secondary_color) ? $settings->tv_secondary_color : '#764ba2'; ?>
                    100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-stats {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .stat-box {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .stat-number {
            font-size: 3em;
            font-weight: bold;
            line-height: 1;
        }

        .stat-label {
            font-size: 1em;
            margin-top: 5px;
            opacity: 0.9;
        }

        .datetime-display {
            text-align: right;
        }

        .current-time {
            font-size: 2.8em;
            font-weight: bold;
            color: #333;
        }

        .current-date {
            font-size: 1.2em;
            color: #666;
            margin-top: 5px;
        }

        .container {
            display: flex;
            gap: 30px;
            padding: 30px;
            height: calc(100vh - 180px);
            position: relative;
            z-index: 10;
        }

        .section {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            padding: 30px;
            overflow-y: auto;
            position: relative;
        }

        .serving-section {
            flex: 2;
            border: 6px solid transparent;
            background-image: linear-gradient(white, white), linear-gradient(135deg, #4CAF50, #45a049);
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        .waiting-section {
            flex: 1;
            border: 6px solid transparent;
            background-image: linear-gradient(white, white), linear-gradient(135deg, #2196F3, #1976D2);
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        .section-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #eee;
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
        }

        .section-title {
            font-size: 2.8em;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .serving-section .section-title {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .waiting-section .section-title {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-icon {
            font-size: 1.8em;
            margin-right: 15px;
        }

        .queue-card {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-left: 12px solid #4CAF50;
            padding: 35px;
            margin-bottom: 25px;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.2);
            transition: all 0.3s ease;
            animation: fadeIn 0.5s ease;
            position: relative;
            overflow: hidden;
        }

        .queue-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.3) 100%);
            pointer-events: none;
        }

        .queue-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(76, 175, 80, 0.3);
        }

        .queue-card.waiting {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left-color: #2196F3;
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.2);
        }

        .queue-card.waiting::before {
            background: linear-gradient(135deg, transparent 0%, rgba(33, 150, 243, 0.1) 100%);
        }

        .queue-card.serving {
            animation: pulseGlow 2s ease-in-out infinite, fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
            }

            50% {
                box-shadow: 0 8px 40px rgba(76, 175, 80, 0.6);
            }
        }

        .queue-number {
            font-size: 5em;
            font-weight: bold;
            color: #333;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .queue-details {
            text-align: right;
            flex: 1;
            margin-left: 30px;
        }

        .patient-name {
            font-size: 2.2em;
            color: #333;
            margin-bottom: 12px;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .patient-name i {
            color:
                <?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?>
            ;
        }

        .doctor-name {
            font-size: 1.8em;
            color: #555;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .doctor-name i {
            color: #4CAF50;
        }

        .room-info {
            font-size: 1.5em;
            color: #777;
            font-style: italic;
        }

        .room-info i {
            color: #FF9800;
        }

        .empty-state {
            text-align: center;
            padding: 100px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 6em;
            margin-bottom: 30px;
            opacity: 0.4;
            background: linear-gradient(135deg,
                    <?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?>
                    ,
                    <?php echo !empty($settings->tv_secondary_color) ? $settings->tv_secondary_color : '#764ba2'; ?>
                );
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .empty-state h3 {
            font-size: 2.5em;
            margin-bottom: 15px;
            color: #666;
        }

        .empty-state p {
            font-size: 1.5em;
            color: #999;
        }

        /* Scrollbar styling */
        .section::-webkit-scrollbar {
            width: 12px;
        }

        .section::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .section::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg,
                    <?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?>
                    0%,
                    <?php echo !empty($settings->tv_secondary_color) ? $settings->tv_secondary_color : '#764ba2'; ?>
                    100%);
            border-radius: 10px;
        }

        .section::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg,
                    <?php echo !empty($settings->tv_secondary_color) ? $settings->tv_secondary_color : '#764ba2'; ?>
                    0%,
                    <?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?>
                    100%);
        }

        /* Responsive design */
        @media (max-width: 1200px) {
            .container {
                flex-direction: column;
            }

            .section {
                flex: none !important;
                height: auto;
                max-height: 45vh;
            }

            .queue-number {
                font-size: 3.5em;
            }

            .patient-name {
                font-size: 1.8em;
            }
        }


        /* Skeleton Loading */
        .skeleton-loading {
            background: rgba(255, 255, 255, 0.8) !important;
            border-left: 12px solid #ccc !important;
        }

        .skeleton-number,
        .skeleton-text {
            background: #eee;
            background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
            border-radius: 5px;
            background-size: 200% 100%;
            animation: 1.5s shine linear infinite;
        }

        .skeleton-number {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        @keyframes shine {
            to {
                background-position-x: -200%;
            }
        }
    </style>
</head>

<body>
    <!-- Background Decorations -->
    <div class="bg-decoration decoration-1"></div>
    <div class="bg-decoration decoration-2"></div>
    <div class="bg-decoration decoration-3"></div>

    <div class="header">
        <div class="hospital-branding">
            <div class="hospital-logo">
                <i class="fas fa-hospital"></i>
            </div>
            <div class="hospital-name">
                <?php echo isset($settings) ? $settings->system_vendor : 'Hospital Management System'; ?>
            </div>
        </div>
        <div class="header-stats">
            <div class="stat-box">
                <div class="stat-number" id="totalQueue">0</div>
                <div class="stat-label">Today's Queue</div>
            </div>
        </div>
        <div class="datetime-display">
            <div class="current-time" id="current-time">--:--:--</div>
            <div class="current-date" id="current-date">Loading...</div>
        </div>
    </div>

    <div class="container">
        <div class="section serving-section">
            <div class="section-header">
                <h1 class="section-title">
                    <i class="fas fa-user-md section-icon"></i>
                    NOW SERVING
                </h1>
            </div>
            <div id="serving-list">
                <!-- Serving Items will be loaded here -->
            </div>
        </div>

        <div class="section waiting-section">
            <div class="section-header">
                <h1 class="section-title">
                    <i class="fas fa-users section-icon"></i>
                    NEXT PATIENTS
                </h1>
            </div>
            <div id="waiting-list">
                <!-- Waiting Items will be loaded here -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let lastServingIds = [];

        // Update clock
        function updateClock() {
            const now = new Date();
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', timeOptions);
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', dateOptions);
        }

        function fetchUpdates() {
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            const hospitalId = urlParams.get('hospital_id');

            $.ajax({
                url: '<?php echo base_url('appointment/tv/get_updates'); ?>',
                data: {
                    token: token,
                    hospital_id: hospitalId
                },
                dataType: 'json',
                success: function (data) {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }
                    updateServing(data.serving);
                    updateWaiting(data.waiting);
                    updateQueueStats(data.serving, data.waiting);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching updates:', error);
                }
            });
        }

        function updateQueueStats(serving, waiting) {
            const total = (serving ? serving.length : 0) + (waiting ? waiting.length : 0);
            $('#totalQueue').text(total);
        }

        // Skeleton loader HTML
        const skeletonCard = `
            <div class="queue-card skeleton-loading">
                <div class="skeleton-number"></div>
                <div class="queue-details" style="width: 100%;">
                    <div class="skeleton-text" style="width: 60%; height: 30px; margin-left: auto; margin-bottom: 15px;"></div>
                    <div class="skeleton-text" style="width: 40%; height: 25px; margin-left: auto;"></div>
                </div>
            </div>
        `;

        function showLoading() {
            $('#serving-list').html(skeletonCard);
            $('#waiting-list').html(skeletonCard + skeletonCard + skeletonCard);
        }

        function updateServing(list) {
            let html = '';
            let currentIds = [];

            if (!list || !Array.isArray(list) || list.length === 0) {
                html = `
                <div class="empty-state">
                    <i class="fas fa-user-clock"></i>
                    <h3>No patients currently being served</h3>
                    <p>The queue is empty</p>
                </div>
                `;
                $('#serving-list').html(html);
                lastServingIds = [];
                return;
            }

            list.forEach(item => {
                currentIds.push(item.id);
                html += `
                <div class="queue-card serving">
                    <div class="queue-number">#${item.queue_number}</div>
                    <div class="queue-details">
                        <div class="patient-name"><i class="fas fa-user"></i> ${item.patientname}</div>
                        <div class="doctor-name"><i class="fas fa-user-md"></i> Dr. ${item.doctorname}</div>
                        <div class="room-info"><i class="fas fa-door-open"></i> Room ${item.room_id || '101'}</div>
                    </div>
                </div>
            `;

                // Announce new patient
                if (!lastServingIds.includes(item.id)) {
                    announce(item.queue_number, item.patientname, item.doctorname, item.room_id);
                }
            });

            $('#serving-list').html(html);
            lastServingIds = currentIds;
        }

        function updateWaiting(list) {
            let html = '';

            if (!list || !Array.isArray(list) || list.length === 0) {
                html = `
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>No patients waiting</h3>
                    <p>Queue is empty</p>
                </div>
                `;
                $('#waiting-list').html(html);
                return;
            }

            list.forEach(item => {
                html += `
                <div class="queue-card waiting">
                    <div class="queue-number">#${item.queue_number}</div>
                    <div class="queue-details">
                         <div class="patient-name">${item.patientname}</div>
                         <div class="doctor-name">Dr. ${item.doctorname}</div>
                    </div>
                </div>
            `;
            });
            $('#waiting-list').html(html);
        }

        function announce(queueNumber, patientName, doctorName, roomId) {
            if ('speechSynthesis' in window) {
                // Cancel any potentially stuck speech
                window.speechSynthesis.cancel();

                let text = `Patient ${patientName}, Queue Number ${queueNumber}, please proceed to Doctor ${doctorName}`;
                if (roomId) {
                    text += `, Room ${roomId}`;
                }
                let utterance = new SpeechSynthesisUtterance(text);
                utterance.rate = 0.9;
                utterance.pitch = 1.0;
                utterance.volume = 1.0;
                window.speechSynthesis.speak(utterance);
            }
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock();

        // Initial loading state
        showLoading();

        // Fetch queue updates every 5 seconds
        setInterval(fetchUpdates, 5000);
        fetchUpdates(); // Initial call
    </script>

</body>

</html>