// ================================================================
// IMPORTS
// ================================================================
import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import gsap from "gsap";
import Swup from "swup";
import { GridStack } from 'gridstack';
import 'gridstack/dist/gridstack.min.css';
import ApexCharts from 'apexcharts';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import Swal from 'sweetalert2'; // <-- Import SweetAlert2

// ================================================================
// REGISTRASI GLOBAL & INISIALISASI DASAR
// ================================================================
window.Alpine = Alpine;
window.Swal = Swal; // Daftarkan Swal ke window agar bisa diakses dari mana saja jika perlu

Alpine.plugin(collapse);
Alpine.start();

// ================================================================
// OBJECT UTAMA UNTUK MENGELOLA SCRIPT HALAMAN
// ================================================================
const AppScripts = {
    // Properti untuk menyimpan instance agar tidak duplikat saat navigasi
    chartInstance: null,
    adminCalendar: null,
    dashboardCalendar: null,

    /**
     * Inisialisasi animasi GSAP
     */
    initGsapAnimations() {
        gsap.from(".gsap-widget", {
            duration: 0.5,
            opacity: 0,
            y: 30,
            ease: "power2.out",
            stagger: 0.1
        });
    },

    /**
     * Inisialisasi chart di dashboard
     */
    initDashboardChart() {
        const chartEl = document.querySelector("#attendanceChart");
        // Hancurkan instance chart lama sebelum membuat yang baru
        if (this.chartInstance) {
            this.chartInstance.destroy();
            this.chartInstance = null;
        }
        if (chartEl && window.dashboardChartData) {
            this.chartInstance = new ApexCharts(chartEl, {
                series: [{ name: 'Jumlah Kehadiran', data: window.dashboardChartData.data }],
                chart: { type: 'bar', height: '100%', toolbar: { show: false } },
                xaxis: { categories: window.dashboardChartData.labels },
                fill: { opacity: 1, colors: ['#14213D'] },
                dataLabels: { enabled: false },
            });
            this.chartInstance.render();
        }
    },

    /**
     * Inisialisasi semua kalender
     */
    initCalendars() {
        // Hancurkan instance kalender lama
        if (this.adminCalendar) this.adminCalendar.destroy();
        if (this.dashboardCalendar) this.dashboardCalendar.destroy();

        const calendarAdminEl = document.getElementById("calendar-admin");
        if (calendarAdminEl) {
            this.adminCalendar = new Calendar(calendarAdminEl, {
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: "dayGridMonth",
                headerToolbar: { left: "prev,next today", center: "title", right: "dayGridMonth" },
                events: "/calendar-events",
                dateClick: this.handleCalendarDateClick.bind(this),
                eventClick: this.handleCalendarEventClick.bind(this),
            });
            this.adminCalendar.render();
        }

        const calendarDashboardEl = document.getElementById("calendar-dashboard");
        if (calendarDashboardEl) {
            this.dashboardCalendar = new Calendar(calendarDashboardEl, {
                plugins: [dayGridPlugin],
                initialView: "dayGridMonth",
                headerToolbar: { left: "prev,next today", center: "title", right: "" },
                events: "/calendar-events",
            });
            this.dashboardCalendar.render();
        }
    },

    /**
     * Handler untuk aksi klik tanggal di kalender admin (Tambah Hari Libur)
     */
    handleCalendarDateClick(info) {
        Swal.fire({
            title: 'Tambah Hari Libur Baru',
            html: `<input type="text" id="swal-description" class="swal2-input" placeholder="Cth: Cuti Bersama Idul Fitri" required>`,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            didOpen: () => document.getElementById('swal-description').focus(),
            preConfirm: () => {
                const description = document.getElementById('swal-description').value;
                if (!description) {
                    Swal.showValidationMessage('Keterangan tidak boleh kosong');
                    return false;
                }
                return fetch('/admin/holidays', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ date: info.dateStr, description: description })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal menyimpan data.');
                    return response.json();
                })
                .catch(error => Swal.showValidationMessage(`Request Gagal: ${error}`));
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Berhasil!', 'Hari libur baru telah ditambahkan.', 'success');
                this.adminCalendar.refetchEvents();
            }
        });
    },

    /**
     * Handler untuk aksi klik event di kalender admin (Hapus Hari Libur)
     */
    handleCalendarEventClick(info) {
        if (info.event.extendedProps.type === "holiday") {
            Swal.fire({
                title: 'Hapus Hari Libur?',
                text: `Anda yakin ingin menghapus "${info.event.title}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
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

    /**
     * Menyiapkan fungsi global untuk approval lembur
     */
    initApprovalActions() {
        window.showApprovalConfirmation = function(log, action) {
            const title = action === 'Approved' ? 'Setujui Klaim Lembur?' : 'Tolak Klaim Lembur?';
            const text = `Anda akan ${action === 'Approved' ? 'menyetujui' : 'menolak'} klaim dari ${log.user.name}.`;
            const confirmButtonText = `Ya, ${action === 'Approved' ? 'Setujui' : 'Tolak'}!`;

            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: action === 'Approved' ? '#3085d6' : '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('approval-form');
                    form.action = `/admin/overtime-approvals/${log.id}`;
                    document.getElementById('approval-status').value = action;
                    form.submit();
                }
            });
        }
    },

    /**
     * Menyiapkan fungsi global untuk approval cuti
     */
    initLeaveApprovalActions() {
        window.showLeaveConfirmation = async function(leave, action) {
            const baseConfig = {
                title: action === 'Approved' ? 'Setujui Pengajuan?' : 'Tolak Pengajuan?',
                text: `Anda akan ${action === 'Approved' ? 'menyetujui' : 'menolak'} pengajuan ${leave.type} dari ${leave.user.name}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: action === 'Approved' ? '#3085d6' : '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: `Ya, ${action === 'Approved' ? 'Setujui' : 'Tolak'}!`,
                cancelButtonText: 'Batal'
            };

            if (action === 'Rejected') {
                baseConfig.input = 'textarea';
                baseConfig.inputPlaceholder = 'Tuliskan alasan penolakan di sini...';
                baseConfig.inputValidator = (value) => {
                    if (!value) {
                        return 'Anda harus mengisi alasan penolakan!'
                    }
                }
            }

            const result = await Swal.fire(baseConfig);

            if (result.isConfirmed) {
                const form = document.getElementById('leave-approval-form');
                form.action = `/admin/leaves/${leave.id}`;
                document.getElementById('leave-approval-status').value = action;
                
                if (action === 'Rejected') {
                    document.getElementById('rejection_reason').value = result.value;
                }
                
                form.submit();
            }
        }
    },

    /**
     * Fungsi utama yang akan dijalankan di setiap halaman
     */
    run() {
        console.log("🚀 Menjalankan script inisialisasi halaman...");
        this.initGsapAnimations();
        this.initDashboardChart();
        this.initCalendars();
        this.initApprovalActions();
        this.initLeaveApprovalActions(); // <-- method baru dipanggil
    }
};

// ================================================================
// PEMICU SCRIPT
// ================================================================
const swup = new Swup();

// Jalankan script saat halaman pertama kali dimuat
document.addEventListener("DOMContentLoaded", () => AppScripts.run());

// Jalankan ulang script setiap kali Swup selesai memuat halaman baru
swup.hooks.on("page:view", () => AppScripts.run());
