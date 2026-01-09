/**
 * SAVANA - Main JavaScript
 * Dark mode, sidebar controls, DataTables, SweetAlert integrations
 */

document.addEventListener("DOMContentLoaded", function () {
    initTheme();
    initSidebar();
    initDataTables();
    initSweetAlertForms();
    initTooltips();
});

/* ==================== Theme (Dark Mode) ==================== */

function initTheme() {
    const themeToggle = document.querySelector(".theme-toggle");
    const savedTheme = localStorage.getItem("savana-theme") || "light";

    document.documentElement.setAttribute("data-theme", savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener("click", toggleTheme);
    }
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute("data-theme");
    const newTheme = currentTheme === "dark" ? "light" : "dark";

    document.documentElement.setAttribute("data-theme", newTheme);
    localStorage.setItem("savana-theme", newTheme);
    updateThemeIcon(newTheme);
}

function updateThemeIcon(theme) {
    const themeToggle = document.querySelector(".theme-toggle i");
    if (themeToggle) {
        themeToggle.className = theme === "dark" ? "fas fa-sun" : "fas fa-moon";
    }
}

/* ==================== Sidebar ==================== */

function initSidebar() {
    const sidebar = document.querySelector(".sidebar");
    const sidebarToggle = document.querySelector(".sidebar-toggle");
    const sidebarOverlay = document.querySelector(".sidebar-overlay");
    const savedState = localStorage.getItem("savana-sidebar");

    // Restore collapsed state
    if (savedState === "collapsed" && window.innerWidth > 1024) {
        sidebar?.classList.add("collapsed");
    }

    // Toggle button
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function () {
            if (window.innerWidth <= 1024) {
                sidebar?.classList.toggle("show");
                sidebarOverlay?.classList.toggle("show");
            } else {
                sidebar?.classList.toggle("collapsed");
                const isCollapsed = sidebar?.classList.contains("collapsed");
                localStorage.setItem(
                    "savana-sidebar",
                    isCollapsed ? "collapsed" : "expanded"
                );
            }
        });
    }

    // Overlay click to close
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", function () {
            sidebar?.classList.remove("show");
            sidebarOverlay.classList.remove("show");
        });
    }

    // Handle resize
    window.addEventListener("resize", function () {
        if (window.innerWidth > 1024) {
            sidebar?.classList.remove("show");
            sidebarOverlay?.classList.remove("show");
        }
    });
}

/* ==================== DataTables ==================== */

function initDataTables() {
    const tables = document.querySelectorAll(".datatable");

    tables.forEach((table) => {
        if ($.fn.DataTable.isDataTable(table)) return;

        $(table).DataTable({
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(filter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya",
                },
            },
            dom: '<"d-flex justify-between align-center mb-3"lf>rt<"d-flex justify-between align-center mt-3"ip>',
            pageLength: 10,
            order: [[0, "asc"]],
            columnDefs: [{ orderable: false, targets: "no-sort" }],
        });
    });
}

/* ==================== SweetAlert Forms ==================== */

function initSweetAlertForms() {
    // Delete confirmation
    document.querySelectorAll("[data-confirm-delete]").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const form = this.closest("form");
            const name = this.dataset.confirmDelete || "item ini";

            Swal.fire({
                title: "Konfirmasi Hapus",
                html: `Apakah Anda yakin ingin menghapus <strong>${name}</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#EF4444",
                cancelButtonColor: "#6B7280",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        });
    });

    // Generic confirmation
    document.querySelectorAll("[data-confirm]").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const href = this.getAttribute("href");
            const message = this.dataset.confirm || "Lanjutkan tindakan ini?";
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#7C3AED';

            Swal.fire({
                title: "Konfirmasi",
                text: message,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: primaryColor,
                cancelButtonColor: "#6B7280",
                confirmButtonText: "Ya, Lanjutkan",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed && href) {
                    window.location.href = href;
                }
            });
        });
    });
}

/* ==================== Tooltips ==================== */

function initTooltips() {
    const tooltips = document.querySelectorAll("[data-tooltip]");

    tooltips.forEach((el) => {
        el.addEventListener("mouseenter", function () {
            const text = this.dataset.tooltip;
            const tooltip = document.createElement("div");
            tooltip.className = "tooltip-popup";
            tooltip.textContent = text;
            tooltip.style.cssText = `
                position: absolute;
                background: var(--gray-800);
                color: white;
                padding: 0.375rem 0.75rem;
                border-radius: 6px;
                font-size: 0.75rem;
                z-index: 9999;
                white-space: nowrap;
            `;
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.left =
                rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + "px";
            tooltip.style.top =
                rect.top - tooltip.offsetHeight - 8 + window.scrollY + "px";

            this._tooltip = tooltip;
        });

        el.addEventListener("mouseleave", function () {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });
}

/* ==================== Helper Functions ==================== */

// Get primary color from CSS variable
function getPrimaryColor() {
    return getComputedStyle(document.documentElement).getPropertyValue('--primary').trim() || '#7C3AED';
}

// Show toast notification
function showToast(message, type = "success") {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });

    Toast.fire({
        icon: type,
        title: message,
    });
}

// Show success alert
function showSuccess(title, text) {
    return Swal.fire({
        icon: "success",
        title: title,
        text: text,
        confirmButtonColor: getPrimaryColor(),
    });
}

// Show error alert
function showError(title, text) {
    return Swal.fire({
        icon: "error",
        title: title,
        text: text,
        confirmButtonColor: getPrimaryColor(),
    });
}

// Format number with thousand separator
function formatNumber(num) {
    return new Intl.NumberFormat("id-ID").format(num);
}

// Format date to Indonesian format
function formatDate(date) {
    return new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    }).format(new Date(date));
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Animate progress bar
function animateProgress(element, targetValue) {
    const bar = element.querySelector(".progress-bar");
    if (!bar) return;

    let current = 0;
    const increment = targetValue / 50;

    const timer = setInterval(() => {
        current += increment;
        if (current >= targetValue) {
            current = targetValue;
            clearInterval(timer);
        }
        bar.style.width = current + "%";
    }, 20);
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard
        .writeText(text)
        .then(() => {
            showToast("Berhasil disalin!", "success");
        })
        .catch(() => {
            showToast("Gagal menyalin", "error");
        });
}
