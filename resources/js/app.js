// ================================================================
// Import dasar (TETAP SAMA)
// ================================================================
import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import gsap from "gsap";
import Swup from "swup";

import { GridStack } from 'gridstack';
import 'gridstack/dist/gridstack.min.css';
import ApexCharts from 'apexcharts';

// ================================================================
// Import FullCalendar (TETAP SAMA)
// ================================================================
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

// ================================================================
// Registrasi Library ke Global Scope (TETAP SAMA)
// ================================================================
window.GridStack = GridStack;
window.ApexCharts = ApexCharts;
window.gsap = gsap;
window.Swup = Swup;
window.Alpine = Alpine;

window.FullCalendar = { Calendar };
window.dayGridPlugin = dayGridPlugin;
window.interactionPlugin = interactionPlugin;

Alpine.plugin(collapse);
Alpine.start();

// ================================================================
// "MANAJER SCRIPT" UTAMA
// ================================================================
function initPageScripts() {
    console.log("🚀 Menjalankan script inisialisasi halaman...");

    // ... (kode GSAP & ApexCharts tetap sama) ...
    gsap.from(".gsap-widget", { duration: 0.5, opacity: 0, y: 30, ease: "power2.out", stagger: 0.1 });

    const chartEl = document.querySelector("#attendanceChart");
    if (chartEl && window.dashboardChartData) {
        const chart = new ApexCharts(chartEl, {
            series: [{ name: 'Jumlah Kehadiran', data: window.dashboardChartData.data }],
            chart: { type: 'bar', height: '100%', toolbar: { show: false }},
            xaxis: { categories: window.dashboardChartData.labels },
            fill: { opacity: 1, colors: ['#14213D'] },
        });
        chart.render();
    }

    // ============================================================
    // Inisialisasi Kalender (Admin & Dashboard)
    // ============================================================
    function initializeCalendars() {
        console.log("📅 FullCalendar siap, memulai inisialisasi...");

        // ---- Kalender Admin ----
        const calendarAdminEl = document.getElementById("calendar-admin");
        if (calendarAdminEl) {
            const calendarAdmin = new Calendar(calendarAdminEl, {
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: "dayGridMonth",
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth",
                },
                events: "/calendar-events",

                // ▼▼▼ BAGIAN INI DIUBAH DENGAN SWEETALERT2 ▼▼▼
                dateClick(info) {
                    Swal.fire({
                        title: 'Tambah Hari Libur Baru',
                        html: `
                            <form id="swalHolidayForm" class="text-left mt-4">
                                <label for="swal-description" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <input type="text" id="swal-description" class="mt-1 w-full rounded-md border-gray-300" placeholder="Cth: Cuti Bersama" required>
                            </form>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        cancelButtonText: 'Batal',
                        didOpen: () => {
                            document.getElementById('swal-description').focus();
                        },
                        preConfirm: () => {
                            const description = document.getElementById('swal-description').value;
                            if (!description) {
                                Swal.showValidationMessage('Keterangan tidak boleh kosong');
                                return false;
                            }
                            
                            // Kirim data ke server
                            return fetch('/admin/holidays', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    date: info.dateStr,
                                    description: description
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(err => { throw new Error(err.message || 'Gagal menyimpan data.') });
                                }
                                return response.json();
                            })
                            .catch(error => {
                                Swal.showValidationMessage(`Request Gagal: ${error}`);
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Berhasil!', 'Hari libur baru telah ditambahkan.', 'success');
                            calendarAdmin.refetchEvents(); // Refresh kalender
                        }
                    });
                },
                
                eventClick(info) {
                    if (info.event.extendedProps.type === "holiday") {
                        Swal.fire({
                            title: 'Hapus Hari Libur?',
                            text: `Anda yakin ingin menghapus "${info.event.title}"? Aksi ini tidak bisa dibatalkan.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const holidayId = (info.event.id || "").replace("h-", "");
                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

                                const form = document.createElement("form");
                                form.method = "POST";
                                form.action = `/admin/holidays/${holidayId}`;
                                form.innerHTML = `<input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="${csrfToken}">`;
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    }
                },
                // ▲▲▲ ----------------------------------------- ▲▲▲
            });
            calendarAdmin.render();
            console.log("✅ Kalender Admin berhasil di-render.");
        }

        // ---- Kalender Dashboard (TETAP SAMA) ----
        const calendarDashboardEl = document.getElementById("calendar-dashboard");
        if (calendarDashboardEl) {
            const calendarDashboard = new Calendar(calendarDashboardEl, {
                plugins: [dayGridPlugin],
                initialView: "dayGridMonth",
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "",
                },
                events: "/calendar-events",
            });
            calendarDashboard.render();
            console.log("✅ Kalender Dashboard berhasil di-render.");
        }
    }

    // Tunggu FullCalendar siap (TETAP SAMA)
    if (typeof Calendar !== "undefined") {
        initializeCalendars();
    } else {
        console.warn("⚠️ FullCalendar belum terload, skip init.");
    }
}

// ================================================================
// Pemicu "Manajer Script" (TETAP SAMA)
// ================================================================
document.addEventListener("DOMContentLoaded", initPageScripts);

const swup = new Swup();
swup.hooks.on("page:view", initPageScripts);