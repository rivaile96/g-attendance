// resources/js/app.js

import './bootstrap';

// Import libraries
import 'gridstack/dist/gridstack.min.css';
import { GridStack } from 'gridstack';
import ApexCharts from 'apexcharts';
import gsap from "gsap"; // <-- GSAP
import Swup from "swup"; // <-- Swup

// "Perkenalkan" library ke window browser
window.GridStack = GridStack;
window.ApexCharts = ApexCharts;
window.gsap = gsap;
window.Swup = Swup;

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// ======================
// Swup + GSAP Transition
// ======================

document.addEventListener("DOMContentLoaded", () => {
    const swup = new Swup();

    // Animasi masuk saat halaman baru dimuat
    swup.hooks.on("page:view", () => {
        gsap.from("main", {
            opacity: 0,
            y: 30,
            duration: 0.5,
            ease: "power2.out"
        });
    });

    // Animasi keluar sebelum halaman diganti
    swup.hooks.before("content:replace", () => {
        return gsap.to("main", {
            opacity: 0,
            y: -30,
            duration: 0.4,
            ease: "power2.in"
        });
    });
});
