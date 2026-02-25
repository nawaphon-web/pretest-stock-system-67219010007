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
    <title>PC Builder - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="builder-layout">
    <!-- Sidebar -->
    <aside class="builder-sidebar">
        <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
            <h2 style="font-weight: 800; color: var(--primary-color);">TECHSTOCK</h2>
            <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">WORKSTATION BUILDER</p>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-link active" onclick="loadCategory('cpu')"><i class="fa-solid fa-microchip"></i> CPU</a>
            <a href="#" class="nav-link" onclick="loadCategory('mainboard')"><i class="fa-solid fa-circuit-board"></i> Mainboard</a>
            <a href="#" class="nav-link" onclick="loadCategory('ram')"><i class="fa-solid fa-memory"></i> RAM</a>
            <a href="#" class="nav-link" onclick="loadCategory('gpu')"><i class="fa-solid fa-video"></i> GPU</a>
            <a href="#" class="nav-link" onclick="loadCategory('psu')"><i class="fa-solid fa-plug"></i> PSU</a>
            <a href="#" class="nav-link" onclick="loadCategory('case')"><i class="fa-solid fa-box"></i> Case</a>
            <a href="#" class="nav-link" onclick="loadCategory('ssd')"><i class="fa-solid fa-hard-drive"></i> SSD</a>
            <a href="#" class="nav-link" onclick="loadCategory('cooler')"><i class="fa-solid fa-fan"></i> Cooler</a>
            <a href="#" class="nav-link" onclick="loadCategory('keyboard')"><i class="fa-solid fa-keyboard"></i> Keyboard</a>
            <a href="#" class="nav-link" onclick="loadCategory('mouse')"><i class="fa-solid fa-mouse"></i> Mouse</a>
            <a href="#" class="nav-link" onclick="loadCategory('monitor')"><i class="fa-solid fa-desktop"></i> Monitor</a>
        </nav>

        <div style="margin-top: auto; padding-top: 2rem;">
            <button class="btn btn-primary" onclick="openAIModal()" style="width: 100%; background: #8b5cf6;">
                <i class="fa-solid fa-wand-magic-sparkles"></i> ช่วยจัดสเปคอัจฉริยะ (AI)
            </button>
        </div>
    </aside>

    <!-- Main Grid -->
    <main class="builder-main">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 id="category-title" style="font-size: 2rem; font-weight: 800;">เลือกส่วนประกอบ</h1>
            <div style="position: relative; width: 300px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                <input type="text" placeholder="ค้นหารุ่นสินค้า..." id="search-parts" class="form-group" style="padding-left: 3rem; margin-bottom: 0; background: white; width: 100%;">
            </div>
        </div>

        <div id="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <!-- Loaded via JS -->
            <div style="grid-column: 1/-1; text-align: center; padding: 5rem;">
                <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 3rem; color: var(--primary-color);"></i>
            </div>
        </div>
    </main>

    <!-- Summary -->
    <aside class="builder-summary">
        <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fa-solid fa-list-check" style="color: var(--primary-color);"></i> รายการที่คุณเลือก
        </h3>

        <div id="build-summary-list" style="flex: 1;">
            <!-- Loaded via JS -->
        </div>

        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                <div>
                    <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700;">ราคารวมทั้งหมด</span>
                    <div style="font-size: 2.25rem; font-weight: 800; color: var(--primary-color);" id="total-price-display">฿0</div>
                </div>
            </div>
            
            <div style="display: grid; gap: 0.75rem;">
                <button onclick="proceedToCheckout()" class="btn btn-primary" style="height: 3.5rem;">ยืนยันสเปคและสั่งซื้อ</button>
                <div style="display: flex; gap: 0.5rem;">
                    <button onclick="shareSpecs()" class="btn btn-outline" style="flex: 1;"><i class="fa-solid fa-share-nodes"></i> แชร์</button>
                    <button onclick="location.href='user_dashboard.php'" class="btn btn-outline" style="flex: 1;">ยกเลิก</button>
                </div>
            </div>
        </div>
    </aside>

    <!-- AI Modal -->
    <div id="ai-overlay" class="modal-overlay" onclick="closeAIModal()"></div>
    <div id="ai-modal" class="modal" style="text-align: center;">
        <i class="fa-solid fa-wand-magic-sparkles" style="font-size: 4rem; color: #8b5cf6; margin-bottom: 1.5rem;"></i>
        <h2 style="font-weight: 800; margin-bottom: 1rem;">ช่วยจัดสเปคอัจฉริยะ</h2>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">ระบุงบประมาณของคุณ เพื่อให้ระบบเลือกชิ้นส่วนที่คุ้มค่าและรองรับกันได้ดีที่สุด</p>
        
        <div class="form-group">
            <label>งบประมาณ (บาท)</label>
            <input type="number" id="budget-input" placeholder="ตัวอย่าง: 30000" style="font-size: 1.5rem; height: 4rem; text-align: center; width: 100%;">
        </div>
        
        <button onclick="generateAIBuild()" class="btn btn-primary" style="width: 100%; background: #8b5cf6; height: 3.5rem;">วิเคราะห์และจัดสเปค</button>
    </div>

    <script>
        const currentBuild = {};
        let activeCategory = 'cpu';

        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const loadBuild = params.get('load_build');
            const loadBundleId = params.get('load_bundle');

            if (loadBuild) {
                try {
                    Object.assign(currentBuild, JSON.parse(decodeURIComponent(loadBuild)));
                    updateSummaryUI();
                } catch(e) { console.error(e); }
            } else if (loadBundleId) {
                fetch(`api/get_bundle.php?id=${loadBundleId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.items) {
                            Object.assign(currentBuild, data.items);
                            updateSummaryUI();
                        }
                    });
            }
            loadCategory('cpu');
        });

        async function loadCategory(cat) {
            activeCategory = cat;
            document.querySelectorAll('.nav-link').forEach(l => {
                l.classList.remove('active');
                if (l.innerText.toLowerCase().includes(cat)) l.classList.add('active');
            });

            document.getElementById('category-title').innerText = `เลือก: ${cat.toUpperCase()}`;
            const grid = document.getElementById('product-grid');
            grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 5rem;"><i class="fa-solid fa-circle-notch fa-spin" style="font-size: 3rem; color: #e2e8f0;"></i></div>';

            try {
                const res = await fetch(`api/get_parts.php?category=${cat}&current_build=${JSON.stringify(currentBuild)}`);
                const products = await res.json();
                renderProducts(products);
            } catch(e) { grid.innerHTML = 'Error loading products.'; }
        }

        function renderProducts(products) {
            const grid = document.getElementById('product-grid');
            grid.innerHTML = '';

            products.forEach(p => {
                const card = document.createElement('div');
                card.className = "product-card animate-fade-in";
                if (!p.is_compatible) card.style.opacity = '0.5';
                
                let specsHtml = '<ul class="specs-list" style="margin-top: 1rem; list-style: none; padding: 0; font-size: 0.8rem; color: #64748b;">';
                if (p.specs) {
                    Object.entries(p.specs).slice(0, 3).forEach(([k, v]) => {
                        specsHtml += `<li><strong>${k.replace(/_/g, ' ')}:</strong> ${v}</li>`;
                    });
                }
                specsHtml += '</ul>';

                card.innerHTML = `
                    <div class="product-image-container">
                        <i class="fa-solid ${p.icon || 'fa-box'}"></i>
                    </div>
                    <div class="product-details">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <h3 class="product-title">${p.name}</h3>
                            <span class="badge ${p.stock > 0 ? 'badge-success' : 'badge-danger'}">${p.stock > 0 ? 'In Stock' : 'Out of Stock'}</span>
                        </div>
                        <div class="product-price">฿${parseFloat(p.price).toLocaleString()}</div>
                        ${!p.is_compatible ? `<div style="color: #ef4444; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.5rem;"><i class="fa-solid fa-triangle-exclamation"></i> ${p.incompatibility_reason}</div>` : ''}
                        ${specsHtml}
                        <button onclick="selectPart('${activeCategory}', ${p.id}, '${p.name}', ${p.price})" 
                            class="btn btn-primary" 
                            style="margin-top: auto; width: 100%; ${!p.is_compatible ? 'opacity: 0.5; pointer-events: none; background: #94a3b8;' : ''}">
                            ${p.is_compatible ? 'เลือกอุปกรณ์นี้' : 'ไม่รองรับ'}
                        </button>
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        function selectPart(cat, id, name, price) {
            currentBuild[cat] = { id, name, price };
            updateSummaryUI();
            
            const cats = ['cpu', 'mainboard', 'ram', 'gpu', 'psu', 'case', 'ssd', 'cooler', 'keyboard', 'mouse', 'monitor'];
            const nextIdx = cats.indexOf(cat) + 1;
            if (nextIdx < cats.length) loadCategory(cats[nextIdx]);
        }

        function updateSummaryUI() {
            const list = document.getElementById('build-summary-list');
            list.innerHTML = '';
            let total = 0;

            if (Object.keys(currentBuild).length === 0) {
                list.innerHTML = '<p style="color: #94a3b8; text-align: center; margin-top: 3rem;">ยังไม่ได้เลือกอุปกรณ์...</p>';
            } else {
                for (const [cat, p] of Object.entries(currentBuild)) {
                    total += p.price;
                    const item = document.createElement('div');
                    item.className = 'summary-item animate-fade-in';
                    item.innerHTML = `
                        <div class="summary-icon"><i class="fa-solid ${getIcon(cat)}"></i></div>
                        <div class="summary-info">
                            <span class="summary-label">${cat}</span>
                            <span class="summary-name">${p.name}</span>
                        </div>
                        <div style="font-weight: 700; color: #1e293b;">฿${p.price.toLocaleString()}</div>
                        <i class="fa-solid fa-circle-xmark" style="color: #cbd5e1; cursor: pointer; margin-left: 0.5rem;" onclick="removePart('${cat}')"></i>
                    `;
                    list.appendChild(item);
                }
            }
            document.getElementById('total-price-display').innerText = `฿${total.toLocaleString()}`;
        }

        function removePart(cat) {
            delete currentBuild[cat];
            updateSummaryUI();
            loadCategory(activeCategory);
        }

        function getIcon(cat) {
            const icons = { cpu: 'fa-microchip', mainboard: 'fa-circuit-board', ram: 'fa-memory', gpu: 'fa-video', psu: 'fa-plug', case: 'fa-box', ssd: 'fa-hard-drive', cooler: 'fa-fan', monitor: 'fa-desktop', keyboard: 'fa-keyboard', mouse: 'fa-mouse' };
            return icons[cat] || 'fa-box';
        }

        function openAIModal() {
            document.getElementById('ai-overlay').style.display = 'block';
            document.getElementById('ai-modal').style.display = 'block';
        }

        function closeAIModal() {
            document.getElementById('ai-overlay').style.display = 'none';
            document.getElementById('ai-modal').style.display = 'none';
        }

        function generateAIBuild() {
            const budget = document.getElementById('budget-input').value;
            if (!budget || budget < 15000) return alert("กรุณาใส่แผนงบ 15,000 บาทขึ้นไป");
            window.location.href = `budget_result.php?budget=${budget}`;
        }

        function proceedToCheckout() {
            if (Object.keys(currentBuild).length === 0) return alert("กรุณาเลือกอุปกรณ์ขั้นต่ำก่อน!");
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'checkout.php';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'build_data';
            input.value = JSON.stringify(currentBuild);
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        function shareSpecs() {
            const text = Object.entries(currentBuild).map(([c,p]) => `${c.toUpperCase()}: ${p.name}`).join('\n');
            const fullText = `TechStock Build:\n${text}\nTotal: ฿${document.getElementById('total-price-display').innerText}`;
            navigator.clipboard.writeText(fullText).then(() => alert("คัดลอกลงคลิปบอร์ดแล้ว!"));
        }
    </script>
</body>
</html>