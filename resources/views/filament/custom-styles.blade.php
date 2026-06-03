<style>
    .fi-topbar {
        background-color: #c81517 !important;
    }

    .fi-sidebar-header {
        background-color: #c81517 !important;
        box-shadow: none !important;
    }
    .fi-logo {
        /*background-color: #c81517 !important;*/
        color: #ffffff !important;
    }

    .fi-sidebar {
        background-color: #ffffff !important;
        box-shadow: none !important;
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
     * Fotos inmueble (Repeater 4 columnas): la miniatura ocupa todo el ancho
     * de la celda, sin cuadro negro pequeño al centro.
     */
    .fi-fo-file-upload.inmueble-foto-upload {
        width: 100%;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--root {
        width: 100% !important;
        margin-bottom: 0;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--list {
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--item {
        width: 100% !important;
        margin: 0 !important;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--file-wrapper,
    .fi-fo-file-upload.inmueble-foto-upload .filepond--file {
        width: 100% !important;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--image-preview-wrapper,
    .fi-fo-file-upload.inmueble-foto-upload .filepond--image-preview,
    .fi-fo-file-upload.inmueble-foto-upload .filepond--item-panel {
        background-color: #f3f4f6 !important;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--image-preview {
        height: 140px !important;
        min-height: 140px !important;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--image-preview img,
    .fi-fo-file-upload.inmueble-foto-upload .filepond--image-preview canvas {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--panel-root,
    .fi-fo-file-upload.inmueble-foto-upload .filepond--panel-center {
        background-color: transparent !important;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--file-info {
        background: rgba(255, 255, 255, 0.92) !important;
    }

    .fi-fo-file-upload.inmueble-foto-upload .filepond--file-info-main,
    .fi-fo-file-upload.inmueble-foto-upload .filepond--file-info-sub {
        color: #374151 !important;
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
</style>
