<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$budget = isset($_GET['budget']) ? floatval($_GET['budget']) : 0;
if ($budget < 15000) {
    header("Location: builder.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Build Recommendation - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    <style>
        .result-container {
            max-width: 1000px;
            width: 100%;
            padding: 2rem;
            animation: fadeIn 0.8s ease-out;
            overflow-y: auto;
            height: 100vh;
        }

        .result-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .result-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .part-card {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .part-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            width: 60px;
            text-align: center;
        }

        .part-info h4 {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .part-info h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .part-price {
            font-weight: 700;
            color: var(--primary-color);
        }

        .summary-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            padding: 1.5rem;
            border-radius: 1rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .suitability-card {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            text-align: left;
        }

        .suitability-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            background: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .suitability-info h2 {
            font-size: 1.25rem;
            color: white;
            margin-bottom: 0.25rem;
        }

        .suitability-info p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-action {
            padding: 1rem 2rem;
            border-radius: 2rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: white;
            color: var(--primary-color);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid white;
            color: white;
        }

        .btn-primary:hover {
            transform: scale(1.05);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body style="display: flex; justify-content: center; align-items: flex-start;">
    <?php
    $suitability = "";
    $suitIcon = "";
    $suitTitle = "";

    if ($budget <= 20000) {
        $suitTitle = "Entry-level / Home Office";
        $suitability = "เหมาะสำหรับงานเอกสาร เรียนออนไลน์ ดูหนัง ฟังเพลง และการใช้งานทั่วไปในชีวิตประจำวัน";
        $suitIcon = "fa-house-laptop";
    } elseif ($budget <= 35000) {
        $suitTitle = "Mainstream Gaming / Content Creation";
        $suitability = "เหมาะสำหรับการเล่นเกมยอดนิยมในปัจจุบัน (Esports) และงานตัดต่อวิดีโอ/กราฟิกพื้นฐาน";
        $suitIcon = "fa-gamepad";
    } elseif ($budget <= 55000) {
        $suitTitle = "High-Performance Gaming";
        $suitability = "รองรับการเล่นเกมระดับ AAA ที่ความละเอียด 1080p หรือ 1440p ได้อย่างไหลลื่น และงานสร้างสรรค์เนื้อหาที่เข้มข้นขึ้น";
        $suitIcon = "fa-bolt";
    } elseif ($budget <= 80000) {
        $suitTitle = "Professional Workstation / 4K Gaming";
        $suitability = "เหมาะสำหรับมืออาชีพ งานเรนเดอร์ 3D, ตัดต่อวิดีโอ 4K และการเล่นเกมที่ความละเอียดสูงสุด";
        $suitIcon = "fa-briefcase";
    } else {
        $suitTitle = "Enthusiast / Extreme Performance";
        $suitability = "ที่สุดของความแรงสำหรับผู้ใช้ที่ต้องการประสิทธิภาพสูงสุดในทุกด้าน รองรับเทคโนโลยีล้ำสมัยที่สุด";
        $suitIcon = "fa-crown";
    }
    ?>

    <div class="result-container">
        <div class="result-header">
            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">เสร็จเรียบร้อย!</h1>
            <p class="subtitle">สเปคที่คุ้มค่าที่สุดในงบ ฿
                <?php echo number_format($budget); ?>
            </p>
        </div>

        <div id="loading-state" style="text-align: center; margin-top: 5rem;">
            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 3rem; color: var(--primary-color);"></i>
            <p style="margin-top: 1rem;">กำลังจัดเตรียมอุปกรณ์ที่เหมาะสม...</p>
        </div>

        <div id="result-content" style="display: none;">
            <div class="suitability-card">
                <div class="suitability-icon">
                    <i class="fa-solid <?php echo $suitIcon; ?>"></i>
                </div>
                <div class="suitability-info">
                    <h4 style="color: var(--primary-color); font-size: 0.75rem; text-transform: uppercase;">ความเหมาะสมของงบประมาณนี้</h4>
                    <h2><?php echo $suitTitle; ?></h2>
                    <p><?php echo $suitability; ?></p>
                </div>
            </div>

            <div class="summary-card">
                <div style="font-size: 0.9rem; opacity: 0.9;">ราคารวมประมาณ</div>
                <div id="total-price" style="font-size: 3rem; font-weight: 800; margin: 0.5rem 0;">฿0</div>
                <div id="remaining-info" style="font-size: 0.9rem; opacity: 0.8;">เหลืองบประมาณ ฿0</div>
            </div>

            <div class="result-grid" id="parts-grid">
                <!-- Parts will be loaded here -->
            </div>

            <div class="actions">
                <a href="#" id="edit-link" class="btn-action btn-outline">
                    <i class="fa-solid fa-pen-to-square"></i> ปรับแต่งเพิ่มเติม
                </a>
                <a href="#" id="checkout-link" class="btn-action btn-primary">
                    <i class="fa-solid fa-cart-shopping"></i> ยืนยันสเปคนี้
                </a>
                <a href="builder.php" class="btn-action btn-outline"
                    style="border-color: rgba(255,255,255,0.3); color: var(--text-muted);">
                    <i class="fa-solid fa-rotate-left"></i> จัดใหม่
                </a>
            </div>
        </div>
    </div>

    <script>
        const budget = <?php echo $budget; ?>;
        let recommendedBuild = null;

        async function loadBuild() {
            try {
                const response = await fetch(`api/auto_build.php?budget=${budget}`);
                const data = await response.json();

                if (data.error) {
                    alert(data.error);
                    window.location.href = 'builder.php';
                    return;
                }

                recommendedBuild = data.build;
                renderBuild(data);
            } catch (err) {
                console.error(err);
                alert("เกิดข้อผิดพลาดในการโหลดข้อมูล");
            }
        }

        function renderBuild(data) {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('result-content').style.display = 'block';

            document.getElementById('total-price').textContent = `฿${data.total_price.toLocaleString()}`;
            document.getElementById('remaining-info').textContent = `เหลืองบประมาณ ฿${data.remaining.toLocaleString()}`;

            const grid = document.getElementById('parts-grid');
            grid.innerHTML = '';

            const catIcons = {
                cpu: 'fa-microchip',
                cooler: 'fa-fan',
                mainboard: 'fa-microchip',
                ram: 'fa-memory',
                gpu: 'fa-bolt',
                ssd: 'fa-hard-drive',
                psu: 'fa-plug',
                case: 'fa-box',
                keyboard: 'fa-keyboard',
                mouse: 'fa-mouse'
            };

            for (const [cat, part] of Object.entries(data.build)) {
                const card = document.createElement('div');
                card.className = 'part-card';
                card.innerHTML = `
                    <div class="part-icon"><i class="fa-solid ${catIcons[cat] || 'fa-box'}"></i></div>
                    <div class="part-info">
                        <h4>${cat.toUpperCase()}</h4>
                        <h3>${part.name}</h3>
                        <div class="part-price">฿${part.price.toLocaleString()}</div>
                    </div>
                `;
                grid.appendChild(card);
            }

            // Setup links
            const buildJson = encodeURIComponent(JSON.stringify(recommendedBuild));
            document.getElementById('edit-link').href = `builder.php?load_build=${buildJson}`;

            // For checkout, we'll need a way to pass data. For now, let's use a form or similar.
            // Simplified: Redirect to builder then auto-checkout
            document.getElementById('checkout-link').onclick = (e) => {
                e.preventDefault();
                window.location.href = `builder.php?load_build=${buildJson}&checkout=1`;
            };
        }

        loadBuild();
    </script>
</body>

</html>