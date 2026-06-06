<style>
    .fi-topbar {
        background-color: #c81517 !important;
        min-height: 4.25rem;
    }

    .fi-sidebar-header {
        background-color: #c81517 !important;
        box-shadow: none !important;
    }

    .fi-topbar-start > a:has(.fi-logo),
    .fi-topbar-start > .fi-logo,
    .fi-sidebar-header-logo-ctn .fi-logo {
        display: none !important;
    }

    .pe-admin-topbar-brand {
        display: inline-flex;
        align-items: center;
        margin-inline-end: 0.5rem;
    }

    .pe-admin-topbar-brand img {
        height: 2.75rem;
        width: auto;
        max-width: 220px;
        object-fit: contain;
    }

    .fi-topbar .fi-topbar-collapse-sidebar-btn-ctn .fi-icon-btn,
    .fi-topbar .fi-topbar-open-sidebar-btn,
    .fi-topbar .fi-topbar-close-sidebar-btn {
        color: #ffffff !important;
    }

    .fi-topbar .fi-topbar-collapse-sidebar-btn-ctn .fi-icon-btn:hover,
    .fi-topbar .fi-topbar-open-sidebar-btn:hover,
    .fi-topbar .fi-topbar-close-sidebar-btn:hover {
        color: #fecaca !important;
    }

    .fi-logo {
        color: #ffffff !important;
    }

    .fi-simple-header .fi-logo img {
        width: auto;
        max-width: 280px;
        object-fit: contain;
    }

    .fi-sidebar {
        background-color: #ffffff !important;
        box-shadow: none !important;
    }

    @media (min-width: 64rem) {
        .fi-body:not(.fi-body-has-top-navigation).fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) {
            width: var(--collapsed-sidebar-width) !important;
            min-width: var(--collapsed-sidebar-width) !important;
            max-width: var(--collapsed-sidebar-width) !important;
        }

        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-nav {
            padding-inline: 0.5rem !important;
            padding-block: 1rem !important;
            align-items: center !important;
        }

        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-nav-groups,
        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group,
        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-items {
            margin-inline: 0 !important;
            padding-inline: 0 !important;
            width: 100% !important;
            align-items: center !important;
        }

        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-label,
        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-label,
        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-badge-ctn,
        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-grouped-border {
            display: none !important;
        }

        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item {
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
        }

        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-btn,
        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-dropdown-trigger-btn {
            width: 2.75rem !important;
            height: 2.75rem !important;
            min-width: 2.75rem !important;
            min-height: 2.75rem !important;
            margin-inline: auto !important;
            padding: 0 !important;
            gap: 0 !important;
            justify-content: center !important;
            align-items: center !important;
        }

        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-btn > .fi-icon,
        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-dropdown-trigger-btn > .fi-icon {
            width: 1.375rem !important;
            height: 1.375rem !important;
            margin: 0 !important;
        }

        .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
            background-color: #f3f4f6 !important;
            border-radius: 0.75rem !important;
        }
    }

    .fi-topbar-end .fi-user-menu-trigger,
    .fi-topbar-end .fi-icon-btn {
        color: #ffffff !important;
    }

    .fi-main-ctn{
        background-color: #e8e8e9  !important;
    }

    .fi-sidebar-item-label,
    .fi-sidebar-group-label,
    .fi-sidebar-item-icon,
    .fi-topbar-item-label{
    /*.fi-logo {*/
        color: #c81517 !important;
    }

    .fi-sidebar-item-button:hover {
        background-color: #1e293b !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-button {
        background-color: #2563eb !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-label,
    .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #ffffff !important;
    }

    .fi-main {
        background-color: #f3f4f6 !important;
    }

    /*
     * Galería inmueble: miniaturas cuadradas en cuadrícula (4 columnas).
     * Solo ancho de ítems + ocultar metadatos; la altura la fija imagePreviewHeight.
     */
    .fi-fo-file-upload.inmueble-galeria-upload .filepond--root[data-style-panel-layout=grid] .filepond--item {
        width: calc(25% - 0.5rem);
    }

    @media (max-width: 1024px) {
        .fi-fo-file-upload.inmueble-galeria-upload .filepond--root[data-style-panel-layout=grid] .filepond--item {
            width: calc(33.33% - 0.5rem);
        }
    }

    @media (max-width: 640px) {
        .fi-fo-file-upload.inmueble-galeria-upload .filepond--root[data-style-panel-layout=grid] .filepond--item {
            width: calc(50% - 0.5rem);
        }
    }

    .fi-fo-file-upload.inmueble-galeria-upload .filepond--file-info,
    .fi-fo-file-upload.inmueble-galeria-upload .filepond--file-status {
        display: none !important;
    }

    .fi-fo-file-upload.inmueble-galeria-upload .filepond--item-panel {
        background-color: #4b5563 !important;
    }

    .fi-page-content {
        background: #ffffff !important;
        border-radius: 18px !important;
        padding: 24px !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06) !important;
        border: 1px solid #e5e7eb !important;
    }

    .fi-form {
        background: transparent !important;
    }

    .fi-section {
        background: #ffffff !important;
        border-radius: 14px !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: none !important;
    }

    /*
     * Foto de perfil usuario: avatar circular centrado en la columna izquierda.
     */
    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar {
        display: flex;
        justify-content: center;
        width: 100%;
        margin-inline: auto;
    }

    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .fi-fo-file-upload-input-ctn {
        width: 11rem !important;
        height: 11rem !important;
    }

    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--root {
        width: 11rem !important;
        height: 11rem !important;
        margin: 0 auto;
    }

    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--panel-root,
    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--image-preview-wrapper,
    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--image-preview,
    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--item-panel {
        border-radius: 9999px !important;
    }

    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--image-preview,
    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--image-preview img,
    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--image-preview canvas {
        object-fit: cover !important;
    }

    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--drop-label {
        color: #6b7280 !important;
        font-size: 0.8125rem !important;
    }

    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--file-info,
    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--file-info-main,
    .fi-fo-file-upload.usuario-foto-upload.fi-fo-file-upload-avatar .filepond--file-info-sub {
        display: none !important;
    }

    /* Tabla de inmuebles: scroll vertical/horizontal y acciones visibles */
    .pe-inmuebles-table .fi-ta-content-ctn {
        max-height: calc(100dvh - 17rem);
        overflow: auto;
        overscroll-behavior: contain;
    }

    .pe-inmuebles-table .fi-ta-table thead th,
    .pe-inmuebles-table .fi-ta-header-cell {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f9fafb !important;
    }

    .pe-inmuebles-table .fi-ta-table td:last-child,
    .pe-inmuebles-table .fi-ta-table th:last-child,
    .pe-inmuebles-table .fi-ta-record .fi-ta-actions {
        position: sticky;
        right: 0;
        z-index: 5;
        background-color: #ffffff !important;
        box-shadow: -6px 0 10px -4px rgba(0, 0, 0, 0.08);
    }

    .pe-inmuebles-table .fi-ta-table thead th:last-child {
        z-index: 15;
        background-color: #f9fafb !important;
    }

    .pe-inmuebles-table .fi-ta-table tbody tr:nth-child(even) td:last-child {
        background-color: #f9fafb !important;
    }

    .pe-inmuebles-table .fi-ta-record:nth-child(even) .fi-ta-actions {
        background-color: #f9fafb !important;
    }

    .fi-fo-field:has(.pe-inmueble-detalles-divider) {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    .fi-fo-field:has(.pe-inmueble-detalles-divider) .fi-fo-field-content-col {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .pe-inmueble-detalles-divider {
        display: block;
        width: 100%;
        height: 0;
        margin: 1rem 0 0.75rem;
        border: 0;
        border-top: 1px solid #e5e7eb;
        opacity: 0.45;
    }

    /* Ciudad — repetidores de sedes y dependencias */
    .pe-ciudad-sedes-repeater .fi-fo-repeater-items,
    .pe-ciudad-dependencias-repeater .fi-fo-repeater-items {
        gap: 0.75rem;
    }

    .pe-ciudad-sedes-repeater .fi-fo-repeater-item,
    .pe-ciudad-dependencias-repeater .fi-fo-repeater-item {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }

    .pe-ciudad-sedes-repeater .fi-fo-repeater-items > .fi-fo-repeater-item:nth-child(odd) {
        background-color: #f9fafb;
    }

    .pe-ciudad-sedes-repeater .fi-fo-repeater-items > .fi-fo-repeater-item:nth-child(even) {
        background-color: #f3f4f6;
    }

    .pe-ciudad-dependencias-repeater {
        margin-top: 0.25rem;
        padding: 0.85rem;
        border: 1px dashed #d1d5db;
        border-radius: 8px;
        background-color: #fafafa;
    }

    .pe-ciudad-dependencias-repeater .fi-fo-repeater-items > .fi-fo-repeater-item:nth-child(odd) {
        background-color: #ffffff;
    }

    .pe-ciudad-dependencias-repeater .fi-fo-repeater-items > .fi-fo-repeater-item:nth-child(even) {
        background-color: #f3f4f6;
    }

    .pe-ciudad-sedes-repeater .fi-fo-repeater-item-header,
    .pe-ciudad-dependencias-repeater .fi-fo-repeater-item-header {
        cursor: pointer;
        border-bottom: 1px solid #e5e7eb;
    }

    .pe-ciudad-sedes-repeater .fi-fo-repeater-item.fi-collapsed .fi-fo-repeater-item-header,
    .pe-ciudad-dependencias-repeater .fi-fo-repeater-item.fi-collapsed .fi-fo-repeater-item-header {
        border-bottom: none;
    }

    .pe-ciudad-sedes-repeater .fi-fo-repeater-item-content,
    .pe-ciudad-dependencias-repeater .fi-fo-repeater-item-content {
        padding: 1rem 1.25rem 1.25rem;
    }

    .pe-ciudad-sedes-repeater .fi-fo-repeater-actions,
    .pe-ciudad-dependencias-repeater .fi-fo-repeater-actions {
        margin-bottom: 0.5rem;
    }
</style>
