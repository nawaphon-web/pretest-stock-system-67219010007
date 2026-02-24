<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดสเปคคอมพิวเตอร์ - TechStock Builder</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .builder-layout {
            display: grid;
            grid-template-columns: 280px 1fr 380px;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            background: var(--bg-color);
        }

        .builder-nav {
            background: rgba(15, 23, 42, 0.8);
            border-right: 1px solid var(--glass-border);
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            overflow-y: auto;
        }

        .nav-item {
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            cursor: pointer;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 600;
            color: var(--text-muted);
            border: 1px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .nav-item.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.1);
        }

        .main-content {
            padding: 3rem;
            overflow-y: auto;
            position: relative;
        }

        .build-summary-sidebar {
            background: rgba(15, 23, 42, 0.9);
            border-left: 1px solid var(--glass-border);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            overflow-y: auto;
        }

        .product-card-advanced {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 1.5rem;
            padding: 1.5rem;
            transition: 0.4s;
            position: relative;
            overflow: hidden;
        }

        .product-card-advanced:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            background: rgba(30, 41, 59, 0.9);
        }

        .summary-item-lite {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 1rem;
            font-size: 0.85rem;
            border: 1px solid transparent;
        }

        .summary-item-lite.filled {
            border-color: rgba(16, 185, 129, 0.2);
            background: rgba(16, 185, 129, 0.05);
        }

        .total-panel {
            margin-top: auto;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 2rem;
            border-radius: 1.5rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>

<body>
    <div class="builder-layout">
        <!-- Left Nav -->
        <aside class="builder-nav">
            <div style="padding: 0 1rem 2rem 1rem;">
                <h2 class="shimmer-text" onclick="location.href='user_dashboard.php'" style="cursor:pointer;">TECHSTOCK
                </h2>
                <div
                    style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px;">
                    ระบบจัดสเปคคอมพิวเตอร์</div>
            </div>
            <div class="nav-item active" onclick="loadCategory('cpu')"><i class="fa-solid fa-microchip"></i> CPU</div>
            <div class="nav-item" onclick="loadCategory('mainboard')"><i class="fa-solid fa-circuit-board"></i>
                Mainboard</div>
            <div class="nav-item" onclick="loadCategory('ram')"><i class="fa-solid fa-memory"></i> RAM</div>
            <div class="nav-item" onclick="loadCategory('gpu')"><i class="fa-solid fa-video"></i> GPU</div>
            <div class="nav-item" onclick="loadCategory('psu')"><i class="fa-solid fa-plug"></i> PSU</div>
            <div class="nav-item" onclick="loadCategory('case')"><i class="fa-solid fa-box-open"></i> Case</div>
            <div class="nav-item" onclick="loadCategory('monitor')"><i class="fa-solid fa-desktop"></i> Monitor</div>
            <div class="nav-item" onclick="loadCategory('ssd')"><i class="fa-solid fa-hard-drive"></i> SSD</div>
            <div class="nav-item" onclick="loadCategory('cooler')"><i class="fa-solid fa-fan"></i> Cooler</div>
            <div class="nav-item" onclick="loadCategory('keyboard')"><i class="fa-solid fa-keyboard"></i> Keyboard</div>
            <div class="nav-item" onclick="loadCategory('mouse')"><i class="fa-solid fa-mouse"></i> Mouse</div>

            <button class="cyber-btn" onclick="openBudgetModal()"
                style="margin-top: 2rem; border-color: var(--secondary-color); color: var(--secondary-color);">
                <i class="fa-solid fa-coins"></i> จัดสเปคตามงบประมาณ
            </button>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="productGridContainer">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 id="categoryTitle" style="font-size: 2.5rem; text-transform: uppercase;">เลือกอุปกรณ์</h2>
                <div style="position: relative;">
                    <i class="fa-solid fa-magnifying-glass"
                        style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" placeholder="ค้นหาชื่ออุปกรณ์..."
                        style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 2rem; padding: 0.75rem 1rem 0.75rem 3rem; color: white;">
                </div>
            </div>
            <div id="productGrid"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                <!-- Products via JS -->
            </div>
        </main>

        <!-- Right Summary -->
        <aside class="build-summary-sidebar">
            <h3
                style="text-transform: uppercase; letter-spacing: 2px; font-size: 1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
                รายการสเปคของคุณ</h3>
            <div id="buildSummary" style="display: flex; flex-direction: column; gap: 0.75rem; flex: 1;">
                <!-- Summary via JS -->
            </div>

            <div class="total-panel">
                <div style="font-size: 0.8rem; opacity: 0.8; text-transform: uppercase;">ราคารวมทั้งหมด</div>
                <div style="font-size: 2.5rem; font-weight: 800;" id="totalPrice">฿0</div>
                <button onclick="proceedToCheckout()" class="cyber-btn"
                    style="width: 100%; margin-top: 1.5rem; background: white; color: var(--bg-color); border: none;">
                    ยืนยันสเปคและสั่งซื้อ
                </button>
            </div>
        </aside>
    </div>
    <label class="option-card">
        <input type="radio" name="assembly" value="build" onchange="updateAssembly(500)">
        <div class="option-content">
            <span class="option-title">บริการประกอบมืออาชีพ</span>
            <span class="option-desc">ประกอบเครื่องพร้อมจัดสายไฟให้สวยงาม</span>
            <span class="option-price">฿500</span>
        </div>
    </label>
    </div>
    </div>

    <div id="product-list" class="product-grid">
        <!-- Products loaded via AJAX -->
        <div class="loading">กำลังโหลดข้อมูล...</div>
    </div>

    <div class="action-footer">
        <button class="btn-share" onclick="shareSpecs()">
            <i class="fa-solid fa-share-nodes"></i> แชร์สเปค
        </button>
    </div>
    </div>
    </div>

    <!-- Comparison Tray -->
    <div id="compare-tray" class="compare-tray">
        <div class="compare-items" id="compare-items">
            <!-- Items added here -->
        </div>
        <button class="btn-compare-action" onclick="showComparison()">เปรียบเทียบเลย</button>
    </div>

    <!-- Comparison Modal -->
    <div id="compare-overlay" class="compare-overlay" onclick="closeComparison()"></div>
    <div id="compare-modal" class="compare-modal">
        <button class="btn-close-modal" onclick="closeComparison()"><i class="fa-solid fa-xmark"></i></button>
        <h2 style="margin-bottom: 2rem;">เปรียบเทียบสินค้า</h2>
        <div id="compare-grid" class="compare-grid">
            <!-- Comparison data here -->
        </div>
    </div>

    <!-- Budget Modal -->
    <div id="budget-overlay" class="compare-overlay" onclick="closeBudgetModal()"></div>
    <div id="budget-modal" class="compare-modal" style="max-width: 400px; text-align: center;">
        <i class="fa-solid fa-coins" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1.5rem;"></i>
        <h2 style="margin-bottom: 1rem;">ระบุงบประมาณ</h2>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">ระบุงบประมาณที่ต้องการ (ขั้นต่ำ 15,000 บาท)</p>
        <input type="number" id="budget-input" placeholder="ตัวอย่าง: 30000"
            style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); color: white; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 1.25rem;">
        <button onclick="generateBudgetBuild()" class="cyber-btn"
            style="width: 100%; background: var(--secondary-color); color: white; border: none;">จัดสเปคเลย</button>
    </div>

    <script>
        // State
        const currentBuild = {};
        let currentCategory = 'cpu';

        let assemblyPrice = 0;

        // Load initial category or auto-load build
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const loadBuild = urlParams.get('load_build');
            const loadBundleId = urlParams.get('load_bundle');
            const shouldCheckout = urlParams.get('checkout');

            if (loadBuild) {
                try {
                    const build = JSON.parse(decodeURIComponent(loadBuild));
                    Object.assign(currentBuild, build);
                    updateSummary();
                    if (shouldCheckout) {
                        proceedToCheckout();
                    }
                } catch (e) {
                    console.error("Failed to load build:", e);
                }
            } else if (loadBundleId) {
                fetch(`api/get_bundle.php?id=${loadBundleId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.items) {
                            Object.assign(currentBuild, data.items);
                            updateSummary();
                            // Optional: show a notification that bundle was loaded
                        }
                    })
                    .catch(err => console.error("Error loading bundle:", err));
            }
            loadCategory('cpu');
        });

        function updateAssembly(price) {
            assemblyPrice = price;
            updateSummary();
        }

        async function loadCategory(category) {
            currentCategory = category;

            // Update UI tabs
            document.querySelectorAll('.nav-item').forEach(btn => {
                btn.classList.remove('active');
                const catText = btn.textContent.trim().toLowerCase();
                if (catText.includes(category.toLowerCase())) btn.classList.add('active');
            });

            const grid = document.getElementById('productGrid');
            const title = document.getElementById('categoryTitle');
            title.textContent = `เลือกส่วนประกอบ: ${category.toUpperCase()}`;
            grid.innerHTML = '<div class="loading" style="grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--text-muted);"><i class="fa-solid fa-sync fa-spin"></i> กำลังโหลดข้อมูลอุปกรณ์...</div>';

            try {
                const params = new URLSearchParams();
                params.append('category', category);
                params.append('current_build', JSON.stringify(currentBuild));

                const response = await fetch(`api/get_parts.php?${params.toString()}`);
                const products = await response.json();

                renderProducts(products);
            } catch (err) {
                grid.innerHTML = '<div class="error" style="grid-column: 1/-1; text-align: center; color: var(--error-color);">การเชื่อมต่อขัดข้อง: ไม่สามารถโหลดข้อมูลได้</div>';
                console.error(err);
            }
        }

        function renderProducts(products) {
            const grid = document.getElementById('productGrid');
            grid.innerHTML = '';

            if (products.length === 0) {
                grid.innerHTML = '<div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--text-muted);">ไม่พบอุปกรณ์ที่รองรับสำหรับการจัดสเปคพจจุบัน</div>';
                return;
            }

            products.forEach(p => {
                const card = document.createElement('div');
                card.className = `product-card-advanced ${p.is_compatible ? '' : 'incompatible'}`;
                if (!p.is_compatible) card.style.opacity = '0.5';

                let specsHtml = '<div style="margin: 1rem 0; font-size: 0.75rem; color: var(--text-muted); display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">';
                if (p.specs) {
                    let count = 0;
                    for (const [key, value] of Object.entries(p.specs)) {
                        if (count++ > 3) break;
                        const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        specsHtml += `<div><span style="color: var(--primary-color)">//</span> ${label}: <span style="color: white">${value}</span></div>`;
                    }
                }
                specsHtml += '</div>';

                const actionBtn = p.is_compatible
                    ? `<button class="cyber-btn" style="width: 100%; padding: 0.5rem; font-size: 0.7rem;" onclick="selectPart('${currentCategory}', ${p.id}, '${p.name}', ${p.price}, ${p.specs?.tdp || 0})">เลือก</button>`
                    : `<button class="cyber-btn" disabled style="width: 100%; padding: 0.5rem; font-size: 0.7rem; border-color: #334155; color: #334155;">ไม่ค่อยรับ</button>`;

                const stockStatus = p.stock > 0
                    ? `<span style="font-size: 0.6rem; color: var(--success-color);"><i class="fa-solid fa-circle" style="font-size: 0.4rem;"></i> พร้อมส่ง (${p.stock})</span>`
                    : `<span style="font-size: 0.6rem; color: var(--error-color);"><i class="fa-solid fa-circle" style="font-size: 0.4rem;"></i> สินค้าหมด</span>`;

                card.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <i class="fa-solid ${p.icon || 'fa-box'}" style="font-size: 2rem; color: var(--primary-color);"></i>
                        ${stockStatus}
                    </div>
                    <h3 style="font-size: 1rem; margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${p.name}</h3>
                    <div style="font-size: 1.25rem; font-weight: 700; color: var(--neon-blue);">฿${parseFloat(p.price).toLocaleString()}</div>
                    ${specsHtml}
                    ${actionBtn}
                `;
                grid.appendChild(card);
            });
        }

        function selectPart(category, id, name, price, tdp) {
            currentBuild[category] = { id, name, price, tdp };
            updateSummary();

            const categories = ['cpu', 'cooler', 'mainboard', 'ram', 'gpu', 'ssd', 'psu', 'case', 'keyboard', 'mouse', 'monitor'];
            const nextIdx = categories.indexOf(category) + 1;
            if (nextIdx < categories.length) {
                loadCategory(categories[nextIdx]);
            }
        }

        function updateSummary() {
            const list = document.getElementById('buildSummary');
            list.innerHTML = '';

            let total = assemblyPrice;
            let totalTdp = 50;

            if (Object.keys(currentBuild).length === 0) {
                list.innerHTML = '<div style="color: var(--text-muted); text-align: center; padding: 2rem; border: 1px dashed var(--glass-border); border-radius: 1rem;">ยังไม่มีการเลือกอุปกรณ์...</div>';
            } else {
                for (const [cat, part] of Object.entries(currentBuild)) {
                    total += part.price;
                    totalTdp += part.tdp;

                    const item = document.createElement('div');
                    item.className = 'summary-item-lite filled';
                    item.innerHTML = `
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(59, 130, 246, 0.1); display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                            <i class="fa-solid ${getCategoryIcon(cat)}"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase;">${cat}</div>
                            <div style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">${part.name}</div>
                        </div>
                        <div style="font-weight: 700;">฿${part.price.toLocaleString()}</div>
                        <i class="fa-solid fa-xmark" style="cursor: pointer; color: var(--error-color); font-size: 0.8rem; margin-left: 0.5rem;" onclick="removePart('${cat}')"></i>
                    `;
                    list.appendChild(item);
                }
            }

            document.getElementById('totalPrice').textContent = `฿${total.toLocaleString()}`;
        }

        function getCategoryIcon(cat) {
            const icons = {
                cpu: 'fa-microchip', cooler: 'fa-fan', mainboard: 'fa-circuit-board',
                ram: 'fa-memory', gpu: 'fa-video', ssd: 'fa-hard-drive',
                psu: 'fa-plug', case: 'fa-box-open', monitor: 'fa-desktop',
                keyboard: 'fa-keyboard', mouse: 'fa-mouse'
            };
            return icons[cat] || 'fa-box';
        }

        function removePart(category) {
            delete currentBuild[category];
            updateSummary();
            loadCategory(currentCategory);
        }

        function proceedToCheckout() {
            if (Object.keys(currentBuild).length === 0) {
                alert("กรุณาเลือกอุปกรณ์ให้ครบถ้วนก่อนยืนยันสเปค");
                return;
            }
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'checkout.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'build_data';
            input.value = JSON.stringify(currentBuild);
            form.appendChild(input);

            const assembly = document.createElement('input');
            assembly.type = 'hidden';
            assembly.name = 'assembly';
            assembly.value = 'box'; // Default for now
            form.appendChild(assembly);

            document.body.appendChild(form);
            form.submit();
        }
    </script>

    function openBudgetModal() {
    document.getElementById('budget-overlay').style.display = 'block';
    document.getElementById('budget-modal').style.display = 'block';
    }

    function closeBudgetModal() {
    document.getElementById('budget-overlay').style.display = 'none';
    document.getElementById('budget-modal').style.display = 'none';
    }

    function generateBudgetBuild() {
    const budget = document.getElementById('budget-input').value;
    if (!budget || budget < 15000) { alert("กรุณาระบุงบประมาณอย่างน้อย 15,000 บาท"); return; }
        window.location.href=`budget_result.php?budget=${budget}`; } function shareSpecs() { if
        (Object.keys(currentBuild).length===0) { alert("กรุณาเลือกอุปกรณ์ก่อน!"); return; } const
        summary=Object.entries(currentBuild) .map(([cat, part])=> `${cat.toUpperCase()}: ${part.name}`)
        .join('\n');
        const fullText = `คอมพิวเตอร์ที่ฉันจัดสเปค:\n${summary}\nราคารวม:
        ${document.getElementById('totalPrice').textContent}`;

        navigator.clipboard.writeText(fullText).then(() => {
        alert("คัดลอกสเปคไปยังคลิปบอร์ดแล้ว!");
        }).catch(err => {
        console.error('Could not copy text: ', err);
        });
        }

        // Compare Logic
        let compareList = [];

        function toggleCompare(product) {
        const index = compareList.findIndex(p => p.id === product.id);
        if (index > -1) {
        compareList.splice(index, 1);
        } else {
        if (compareList.length >= 3) {
        alert("เปรียบเทียบได้สูงสุด 3 ชิ้นเท่านั้น");
        return;
        }
        compareList.push(product);
        }
        renderCompareTray();
        }

        function renderCompareTray() {
        const tray = document.getElementById('compare-tray');
        const items = document.getElementById('compare-items');
        items.innerHTML = '';

        if (compareList.length > 0) {
        tray.style.display = 'flex';
        compareList.forEach(p => {
        const thumb = document.createElement('div');
        thumb.className = 'compare-item-thumb';
        thumb.innerHTML = `
        <i class="fa-solid ${p.icon || 'fa-box'}"></i>
        <span>${p.name}</span>
        <i class="fa-solid fa-xmark" style="cursor:pointer; color:var(--error-color)"
            onclick="toggleCompare({id: ${p.id}})"></i>
        `;
        items.appendChild(thumb);
        });
        } else {
        tray.style.display = 'none';
        }
        }

        function showComparison() {
        if (compareList.length < 2) { alert("กรุณาเลือกอย่างน้อย 2 ชิ้นเพื่อเปรียบเทียบ"); return; }
            document.getElementById('compare-overlay').style.display='block' ; const
            modal=document.getElementById('compare-modal'); modal.style.display='block' ; const
            grid=document.getElementById('compare-grid'); grid.innerHTML='' ; compareList.forEach(p=> {
            const col = document.createElement('div');
            col.className = 'compare-col';

            let specsHtml = '';
            if (p.specs) {
            for (const [key, value] of Object.entries(p.specs)) {
            const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            specsHtml += `<div><strong>${label}:</strong> ${value}</div>`;
            }
            }

            col.innerHTML = `
            <i class="fa-solid ${p.icon || 'fa-box'}" style="font-size: 3rem; color: var(--primary-color);"></i>
            <h3>${p.name}</h3>
            <div class="price" style="margin-bottom: 2rem;">฿${parseFloat(p.price).toLocaleString()}</div>
            <div class="compare-specs">${specsHtml}</div>
            `;
            grid.appendChild(col);
            });
            }

            function closeComparison() {
            document.getElementById('compare-overlay').style.display = 'none';
            document.getElementById('compare-modal').style.display = 'none';
            }
            </script>
</body>

</html>