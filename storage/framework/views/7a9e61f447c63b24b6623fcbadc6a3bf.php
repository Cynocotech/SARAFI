
<style>
    /* Sidebar root (the <aside>) - force dark background. High specificity to override Tailwind. */
    body .fi-main-sidebar,
    #admin .fi-main-sidebar,
    .fi-main-sidebar.fi-sidebar {
        background-color: #1e293b !important;
        --tw-ring-color: rgb(255 255 255 / 0.1);
    }
    body .fi-main-sidebar.lg\:bg-transparent,
    body .fi-main-sidebar.dark\:lg\:bg-transparent {
        background-color: #1e293b !important;
    }
    /* Header bar */
    body .fi-main-sidebar .fi-sidebar-header,
    .fi-main-sidebar .fi-sidebar-header {
        background-color: #1e293b !important;
        color: rgb(248 250 252) !important;
        border-color: rgb(255 255 255 / 0.1) !important;
    }
    /* Nav area */
    .fi-main-sidebar .fi-sidebar-nav {
        background-color: transparent !important;
    }
    /* Group labels (دایرکتوری, مالی, مدیریت) */
    .fi-main-sidebar .fi-sidebar-group-label {
        color: rgb(203 213 225) !important;
    }
    .fi-main-sidebar .fi-sidebar-group-icon {
        color: rgb(203 213 225) !important;
    }
    /* Nav item labels - white text */
    .fi-main-sidebar .fi-sidebar-item-label {
        color: rgb(241 245 249) !important;
    }
    .fi-main-sidebar .fi-sidebar-item.fi-active .fi-sidebar-item-label {
        color: rgb(253 224 71) !important;
    }
    /* Nav item icons */
    .fi-main-sidebar .fi-sidebar-item-icon {
        color: rgb(203 213 225) !important;
    }
    .fi-main-sidebar .fi-sidebar-item.fi-active .fi-sidebar-item-icon {
        color: rgb(253 224 71) !important;
    }
    /* Item hover/active background */
    .fi-main-sidebar .fi-sidebar-item-button:hover,
    .fi-main-sidebar .fi-sidebar-item-button:focus-visible {
        background-color: rgb(255 255 255 / 0.08) !important;
    }
    .fi-main-sidebar .fi-sidebar-item.fi-active .fi-sidebar-item-button {
        background-color: rgb(255 255 255 / 0.12) !important;
    }
    /* Group collapse button */
    .fi-main-sidebar .fi-sidebar-group-collapse-button {
        color: rgb(203 213 225) !important;
    }
    /* Logo / brand in sidebar */
    .fi-main-sidebar .fi-sidebar-header a,
    .fi-main-sidebar .fi-sidebar-header svg,
    .fi-main-sidebar .fi-sidebar-header span {
        color: rgb(248 250 252) !important;
    }
    .fi-main-sidebar .fi-sidebar-header svg {
        fill: currentColor;
    }
    /* Expand/collapse sidebar icon buttons */
    .fi-main-sidebar button[class*="icon-button"],
    .fi-main-sidebar [class*="icon-button"] {
        color: rgb(203 213 225) !important;
    }
    .fi-main-sidebar button[class*="icon-button"]:hover,
    .fi-main-sidebar [class*="icon-button"]:hover {
        color: rgb(241 245 249) !important;
        background-color: rgb(255 255 255 / 0.08) !important;
    }
    /* Sub-group dots */
    .fi-main-sidebar .fi-sidebar-item-grouped-border .rounded-full.bg-gray-400,
    .fi-main-sidebar .fi-sidebar-item-grouped-border .rounded-full[class*="gray-500"] {
        background-color: rgb(148 163 184) !important;
    }
    .fi-main-sidebar .fi-sidebar-item-grouped-border .rounded-full.bg-primary-600,
    .fi-main-sidebar .fi-sidebar-item-grouped-border .rounded-full[class*="primary-400"] {
        background-color: rgb(253 224 71) !important;
    }
    .fi-main-sidebar .fi-sidebar .bg-gray-300,
    .fi-main-sidebar .fi-sidebar .w-px.bg-gray-300,
    .fi-main-sidebar .fi-sidebar .w-px[class*="gray-600"] {
        background-color: rgb(255 255 255 / 0.2) !important;
    }
    /* Tenant menu */
    .fi-main-sidebar .fi-sidebar-nav-tenant-menu-ctn {
        color: rgb(241 245 249) !important;
    }
</style>
<?php /**PATH /Volumes/G-DRIVE  SSD  1TB/Exchange Landing/exchange-backend/resources/views/filament/components/admin-sidebar-dark.blade.php ENDPATH**/ ?>