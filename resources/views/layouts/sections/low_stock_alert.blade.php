<!-- Low Stock Alert Popup Component -->
<div class="modal fade" id="lowStockAlertModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header bg-danger text-white py-3">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center">
                    <i class="ri-error-warning-fill fs-3 me-2 text-warning"></i> 
                    Low Stock Reminder Alert (<span id="lowStockCountBadge">0</span>)
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="snoozeLowStockAlert(1)"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="alert alert-warning py-2 mb-3 d-flex align-items-center small rounded-3">
                    <i class="ri-information-line fs-5 me-2"></i>
                    <span>The following products have reached or dropped below their minimum stock alert threshold. Please restock to avoid missing customer orders.</span>
                </div>

                <div class="table-responsive overflow-auto" style="max-height: 280px;">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted">
                                <th>PRODUCT NAME</th>
                                <th>CATEGORY</th>
                                <th class="text-center">CURRENT STOCK</th>
                                <th class="text-center">ALERT LIMIT</th>
                                <th class="text-end">ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="lowStockProductsList">
                            <!-- Dynamic Rows -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light py-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-3" onclick="snoozeLowStockAlert(1)">
                    <i class="ri-time-line me-1"></i> Remind Me In 1 Hour
                </button>
                <a href="{{ route('admin.inventory.index') }}?stock_status=low_stock" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" onclick="closeLowStockModal()">
                    <i class="ri-restart-line me-1"></i> Update Stock Now
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        checkLowStockAlerts();
    });

    function checkLowStockAlerts() {
        const snoozeUntil = localStorage.getItem('low_stock_snooze_until');
        if (snoozeUntil && Date.now() < parseInt(snoozeUntil)) {
            return; // Snoozed for 1 hour
        }

        fetch('{{ route("admin.inventory.low-stock-alerts") }}')
            .then(res => res.json())
            .then(res => {
                if (res.success && res.count > 0) {
                    renderLowStockModal(res.products, res.count);
                }
            })
            .catch(err => console.error('Low stock check failed:', err));
    }

    function renderLowStockModal(products, count) {
        document.getElementById('lowStockCountBadge').innerText = count;
        let listEl = document.getElementById('lowStockProductsList');

        listEl.innerHTML = products.map(p => `
            <tr>
                <td><strong>${p.name}</strong></td>
                <td><span class="badge bg-label-secondary">${p.category}</span></td>
                <td class="text-center">
                    <span class="badge ${p.stock <= 0 ? 'bg-danger' : 'bg-warning text-dark'} fw-bold px-2">
                        ${p.stock} ${p.unit}
                    </span>
                </td>
                <td class="text-center"><small class="text-muted font-monospace">${p.low_stock_threshold} ${p.unit}</small></td>
                <td class="text-end">
                    <button type="button" onclick="redirectToStockUpdate('${p.name.replace(/'/g, "\\'")}', ${p.id})" class="btn btn-sm btn-primary rounded-pill fw-semibold shadow-sm px-3">
                        <i class="ri-edit-box-line me-1"></i> Update Stock
                    </button>
                </td>
            </tr>
        `).join('');

        let modalEl = document.getElementById('lowStockAlertModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            let bsModal = new bootstrap.Modal(modalEl);
            bsModal.show();
        }
    }

    function redirectToStockUpdate(productName, productId) {
        closeLowStockModal();

        const targetUrl = `{{ route('admin.inventory.index') }}?search=${encodeURIComponent(productName)}&open_adjust=${productId}`;

        if (window.location.pathname.includes('/admin/inventory')) {
            let targetModal = document.getElementById(`adjustStockModal-${productId}`);
            if (targetModal && typeof bootstrap !== 'undefined') {
                new bootstrap.Modal(targetModal).show();
            } else {
                let stockInput = document.getElementById(`input-stock-${productId}`);
                if (stockInput) {
                    stockInput.focus();
                    stockInput.select();
                }
            }
        } else {
            window.location.href = targetUrl;
        }
    }

    function closeLowStockModal() {
        let modalEl = document.getElementById('lowStockAlertModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            let bsModal = bootstrap.Modal.getInstance(modalEl);
            if (bsModal) bsModal.hide();
        }
    }

    function snoozeLowStockAlert(hours = 1) {
        const snoozeMs = hours * 3600 * 1000;
        localStorage.setItem('low_stock_snooze_until', (Date.now() + snoozeMs).toString());
        closeLowStockModal();
    }
</script>
