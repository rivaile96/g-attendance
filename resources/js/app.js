// ================================================================
// Import dasar
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
// Import FullCalendar
// ================================================================
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

// ================================================================
// Registrasi Library ke Global Scope
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

    // ============================================================
    // Animasi GSAP untuk Widget
    // ============================================================
    gsap.from(".gsap-widget", {
        duration: 0.5,
        opacity: 0,
        y: 30,
        ease: "power2.out",
        stagger: 0.1
    });

    // ============================================================
    // Grafik ApexCharts (Dashboard)
    // ============================================================
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

                dateClick(info) {
                    const alpineComponent = document.querySelector("[x-data]");
                    if (alpineComponent) {
                        alpineComponent.dispatchEvent(
                            new CustomEvent("open-modal", {
                                detail: {
                                    action: "/admin/holidays",
                                    date: info.dateStr,
                                    description: "",
                                },
                            })
                        );
                    }
                },

                eventClick(info) {
                    if (info.event.extendedProps.type === "holiday") {
                        if (confirm(`Yakin ingin menghapus: '${info.event.title}'?`)) {
                            const holidayId = (info.event.id || "").replace("h-", "");
                            if (!holidayId) {
                                alert("❌ ID holiday tidak ditemukan.");
                                return;
                            }

                            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                            const csrfToken = csrfMeta?.getAttribute("content");
                            if (!csrfToken) {
                                alert("❌ CSRF token tidak ditemukan.");
                                return;
                            }

                            const form = document.createElement("form");
                            form.method = "POST";
                            form.action = `/admin/holidays/${holidayId}`;
                            form.innerHTML = `
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="${csrfToken}">
                            `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                },
            });

            calendarAdmin.render();
            console.log("✅ Kalender Admin berhasil di-render.");
        }

        // ---- Kalender Dashboard ----
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

    // Tunggu FullCalendar siap
    if (typeof Calendar !== "undefined") {
        initializeCalendars();
    } else {
        console.warn("⚠️ FullCalendar belum terload, skip init.");
    }
}

// ================================================================
// Pemicu "Manajer Script"
// ================================================================
document.addEventListener("DOMContentLoaded", initPageScripts);

const swup = new Swup();
swup.hooks.on("page:view", initPageScripts);
