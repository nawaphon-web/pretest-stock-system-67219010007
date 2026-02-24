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
    <title>ระบบจัดสเปคคอมพิวเตอร์อัจฉริยะ - TechStock</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="builder-container">
        <!-- Sidebar / Summary -->
        <div class="build-summary">
            <h2>สเปคของคุณ</h2>
            <div id="build-list">
                <!-- Selected parts will appear here -->
                <div class="empty-state">ยังไม่ได้เลือกอุปกรณ์</div>
            </div>

            <div class="stats">
                <div class="stat-item">
                    <span>กำลังไฟที่ใช้ (โดยประมาณ)</span>
                    <strong id="total-tdp">0 W</strong>
                </div>
                <div class="stat-item">
                    <span>ราคารวม</span>
                    <strong id="total-price" style="color: var(--primary-color);">฿0.00</strong>
                </div>
            </div>

            <form id="checkout-form" action="checkout.php" method="POST" style="display: none;">
                <input type="hidden" name="build_data" id="form-build-data">
                <input type="hidden" name="assembly" id="form-assembly">
            </form>
            <button class="btn-checkout" onclick="proceedToCheckout()">ไปที่หน้าชำระเงิน</button>
            <a href="user_dashboard.php" class="btn-back">กลับไปยังหน้าหลัก</a>
        </div>

        <!-- Main Selection Area -->
        <div class="selection-area">
            <div class="budget-builder"
                style="background: rgba(59, 130, 246, 0.1); padding: 1.5rem; border-radius: 1rem; margin-bottom: 2rem; border: 1px solid rgba(59, 130, 246, 0.3);">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;"><i
                        class="fa-solid fa-wand-magic-sparkles"></i>
                    จัดสเปกตามงบประมาณ (Smart Budget Build)</h3>
                <div style="display: flex; gap: 1rem; align-items: flex-end;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <label>งบประมาณ (บาท)</label>
                        <input type="number" id="budget-input" placeholder="เช่น 30000" min="15000">
                    </div>
                    <button class="btn-checkout" style="width: auto; margin-top: 0; padding: 0.75rem 2rem;"
                        onclick="generateBudgetBuild()">แนะนำสเปก</button>
                </div>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">*
                    ระบบจะคำนวณและเลือกอุปกรณ์ที่คุ้มค่าที่สุดภายใต้งบระบุ</p>
            </div>

            <div class="steps-nav">
                <button class="step-btn active" data-category="cpu" onclick="loadCategory('cpu')">ซีพียู (CPU)</button>
                <button class="step-btn" data-category="cooler" onclick="loadCategory('cooler')">ซิงค์พัดลม
                    (Cooler)</button>
                <button class="step-btn" data-category="mainboard" onclick="loadCategory('mainboard')">เมนบอร์ด
                    (Mainboard)</button>
                <button class="step-btn" data-category="ram" onclick="loadCategory('ram')">แรม (RAM)</button>
                <button class="step-btn" data-category="gpu" onclick="loadCategory('gpu')">การ์ดจอ (GPU)</button>
                <button class="step-btn" data-category="ssd" onclick="loadCategory('ssd')">ฮาร์ดดิสก์/SSD
                    (Storage)</button>
                <button class="step-btn" data-category="psu" onclick="loadCategory('psu')">พาวเวอร์ซัพพลาย
                    (PSU)</button>
                <button class="step-btn" data-category="case" onclick="loadCategory('case')">เคส (Case)</button>
                <button class="step-btn" data-category="keyboard" onclick="loadCategory('keyboard')">คีย์บอร์ด
                    (Keyboard)</button>
                <button class="step-btn" data-category="mouse" onclick="loadCategory('mouse')">เมาส์ (Mouse)</button>
            </div>

            <div class="assembly-options">
                <h3><i class="fa-solid fa-screwdriver-wrench"></i> บริการประกอบคอมพิวเตอร์</h3>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="assembly" value="box" checked onchange="updateAssembly(0)">
                        <div class="option-content">
                            <span class="option-title">แยกชิ้นส่วนลงกล่อง</span>
                            <span class="option-desc">จัดส่งอุปกรณ์แบบแยกกล่องตามปกติ</span>
                            <span class="option-price">ฟรี</span>
                        </div>
                    </label>
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

    <script>
        // State
        const currentBuild = {};
        let currentCategory = 'cpu';

        let assemblyPrice = 0;

        // Load initial category or auto-load build
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const loadBuild = urlParams.get('load_build');
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
            document.querySelectorAll('.step-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.category === category) btn.classList.add('active');
            });

            const grid = document.getElementById('product-list');
            grid.innerHTML = '<div class="loading">กำลังค้นหาอุปกรณ์ที่รองรับ...</div>';

            try {
                // Prepare query params
                const params = new URLSearchParams();
                params.append('category', category);
                params.append('current_build', JSON.stringify(currentBuild));

                const response = await fetch(`api/get_parts.php?${params.toString()}`);
                const products = await response.json();

                renderProducts(products);
            } catch (err) {
                grid.innerHTML = '<div class="error">ไม่สามารถโหลดข้อมูลอุปกรณ์ได้</div>';
                console.error(err);
            }
        }

        function renderProducts(products) {
            const grid = document.getElementById('product-list');
            grid.innerHTML = '';

            if (products.length === 0) {
                grid.innerHTML = '<div class="empty-state">ไม่พบอุปกรณ์ที่รองรับ ลองเปลี่ยนการเลือกอุปกรณ์อื่น</div>';
                return;
            }

            products.forEach(p => {
                const card = document.createElement('div');
                card.className = `product-card ${p.is_compatible ? '' : 'incompatible'}`;

                let specsHtml = '<ul class="specs-list">';
                if (p.specs) {
                    for (const [key, value] of Object.entries(p.specs)) {
                        // Cleanup key name for display (e.g., base_clock -> Base Clock)
                        const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        specsHtml += `<li><strong>${label}:</strong> ${value}</li>`;
                    }
                }
                specsHtml += '</ul>';

                const actionBtn = p.is_compatible
                    ? `<button class="btn-select" onclick="selectPart('${currentCategory}', ${p.id}, '${p.name}', ${p.price}, ${p.specs?.tdp || 0})">เลือก</button>`
                    : `<button class="btn-disabled" disabled>ไม่รองรับ</button>`;

                const compareBtn = `<button class="btn-back" style="margin-top: 0.5rem; padding: 0.4rem;" onclick="toggleCompare(${JSON.stringify(p).replace(/"/g, '&quot;')})">
                    <i class="fa-solid fa-scale-balanced"></i> เปรียบเทียบ
                </button>`;

                const warning = p.is_compatible ? '' : `<div class="warning-msg"><i class="fa-solid fa-triangle-exclamation"></i> ${p.incompatibility_reason}</div>`;

                const stockStatus = p.stock > 0
                    ? `<span class="stock-badge in-stock">มีของ (${p.stock})</span>`
                    : `<span class="stock-badge out-of-stock">สินค้าหมด</span>`;

                card.innerHTML = `
                    <div class="product-icon-container">
                        <i class="fa-solid ${p.icon || 'fa-box'} main-icon"></i>
                        <div class="icon-overlay"></div>
                    </div>
                    <div class="product-info">
                        ${stockStatus}
                        <h3>${p.name}</h3>
                        <div class="price">฿${parseFloat(p.price).toLocaleString()}</div>
                        ${specsHtml}
                        ${warning}
                        ${actionBtn}
                        ${compareBtn}
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        function selectPart(category, id, name, price, tdp) {
            currentBuild[category] = { id, name, price, tdp };
            updateSummary();

            // Auto-advance to next category logic
            const categories = ['cpu', 'cooler', 'mainboard', 'ram', 'gpu', 'ssd', 'psu', 'case', 'keyboard', 'mouse'];
            const nextIdx = categories.indexOf(category) + 1;
            if (nextIdx < categories.length) {
                loadCategory(categories[nextIdx]);
            }
        }

        function updateSummary() {
            const list = document.getElementById('build-list');
            list.innerHTML = '';

            let totalPrice = assemblyPrice;
            let totalTdp = 0;

            if (Object.keys(currentBuild).length === 0 && assemblyPrice === 0) {
                list.innerHTML = '<div class="empty-state">ยังไม่ได้เลือกอุปกรณ์</div>';
            } else {
                for (const [cat, part] of Object.entries(currentBuild)) {
                    totalPrice += part.price;
                    totalTdp += part.tdp;

                    const item = document.createElement('div');
                    item.className = 'summary-item';
                    item.innerHTML = `
                        <div class="info">
                            <span class="cat-label">${cat.toUpperCase()}</span>
                            <span class="part-name">${part.name}</span>
                        </div>
                        <div class="item-price">฿${part.price.toLocaleString()}</div>
                        <button class="btn-remove" onclick="removePart('${cat}')"><i class="fa-solid fa-xmark"></i></button>
                    `;
                    list.appendChild(item);
                }

                if (assemblyPrice > 0) {
                    const item = document.createElement('div');
                    item.className = 'summary-item assembly-item';
                    item.innerHTML = `
                        <div class="info">
                            <span class="cat-label">SERVICE</span>
                            <span class="part-name">ประกอบมืออาชีพ</span>
                        </div>
                        <div class="item-price">฿${assemblyPrice.toLocaleString()}</div>
                    `;
                    list.appendChild(item);
                }
            }

            // Base TDP overhead
            totalTdp += 50;

            document.getElementById('total-price').textContent = `฿${totalPrice.toLocaleString()}`;
            document.getElementById('total-tdp').textContent = `${totalTdp} W`;
        }

        function removePart(category) {
            delete currentBuild[category];
            updateSummary();
            // Reload current category to refresh compatibility if needed
            loadCategory(currentCategory);
        }

        function proceedToCheckout() {
            if (Object.keys(currentBuild).length === 0) {
                alert("กรุณาเลือกอุปกรณ์ก่อน!");
                return;
            }
            document.getElementById('form-build-data').value = JSON.stringify(currentBuild);
            document.getElementById('form-assembly').value = assemblyPrice > 0 ? 'build' : 'box';
            document.getElementById('checkout-form').submit();
        }

        async function generateBudgetBuild() {
            const budget = document.getElementById('budget-input').value;
            if (!budget || budget < 15000) {
                alert("กรุณาระบุงบประมาณอย่างน้อย 15,000 บาท");
                return;
            }
            window.location.href = `budget_result.php?budget=${budget}`;
        }

        function shareSpecs() {
            if (Object.keys(currentBuild).length === 0) {
                alert("กรุณาเลือกอุปกรณ์ก่อน!");
                return;
            }
            const summary = Object.entries(currentBuild)
                .map(([cat, part]) => `${cat.toUpperCase()}: ${part.name}`)
                .join('\n');
            const fullText = `คอมพิวเตอร์ที่ฉันจัดสเปค:\n${summary}\nราคารวม: ${document.getElementById('total-price').textContent}`;

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
                        <i class="fa-solid fa-xmark" style="cursor:pointer; color:var(--error-color)" onclick="toggleCompare({id: ${p.id}})"></i>
                    `;
                    items.appendChild(thumb);
                });
            } else {
                tray.style.display = 'none';
            }
        }

        function showComparison() {
            if (compareList.length < 2) {
                alert("กรุณาเลือกอย่างน้อย 2 ชิ้นเพื่อเปรียบเทียบ");
                return;
            }
            document.getElementById('compare-overlay').style.display = 'block';
            const modal = document.getElementById('compare-modal');
            modal.style.display = 'block';

            const grid = document.getElementById('compare-grid');
            grid.innerHTML = '';

            compareList.forEach(p => {
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