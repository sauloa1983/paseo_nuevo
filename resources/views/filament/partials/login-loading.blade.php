<div id="loading-screen" class="pe-login-loading" aria-live="polite" aria-busy="false" hidden>
    <div class="pe-login-loading__panel" role="status">
        <div class="pe-login-loading__spinner spinner-border text-danger" aria-hidden="true"></div>
        <p class="pe-login-loading__text">Iniciando sesión, por favor espere...</p>
    </div>
</div>

<style>
    .pe-login-loading {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(2px);
        transition: opacity 0.2s ease;
    }

    .pe-login-loading.is-visible {
        display: flex;
    }

    .pe-login-loading__panel {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        text-align: center;
    }

    /* Equivalente a Bootstrap 5: spinner-border text-danger */
    .pe-login-loading__spinner.spinner-border {
        display: inline-block;
        width: 3rem;
        height: 3rem;
        vertical-align: -0.125em;
        border: 0.25em solid #c81517;
        border-right-color: transparent;
        border-radius: 50%;
        animation: pe-login-spinner 0.75s linear infinite;
    }

    .pe-login-loading__text {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        color: #374151;
        letter-spacing: 0.01em;
    }

    .pe-login-form-shell {
        transition: opacity 0.2s ease;
    }

    .pe-login-form-shell.is-hidden {
        opacity: 0;
        pointer-events: none;
        visibility: hidden;
        height: 0;
        overflow: hidden;
    }

    @keyframes pe-login-spinner {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    (function () {
        function getLoginForms() {
            return Array.from(document.querySelectorAll('#form, #multiFactorChallengeForm'));
        }

        function getSubmitButtons() {
            const forms = getLoginForms();
            const buttons = [];

            forms.forEach((form) => {
                form.querySelectorAll('button[type="submit"], button.fi-btn').forEach((button) => {
                    buttons.push(button);
                });
            });

            return buttons;
        }

        function formHasRequiredValues(form) {
            const email = form.querySelector('input[type="email"], input[id*="email"]');
            const password = form.querySelector('input[type="password"], input[id*="password"]');

            if (email && password) {
                return email.value.trim() !== '' && password.value.trim() !== '';
            }

            return true;
        }

        function showLoadingScreen() {
            const loading = document.getElementById('loading-screen');
            const shell = document.getElementById('login-form-shell');

            if (loading) {
                loading.hidden = false;
                loading.classList.add('is-visible');
                loading.setAttribute('aria-busy', 'true');
            }

            if (shell) {
                shell.classList.add('is-hidden');
            }

            getSubmitButtons().forEach((button) => {
                button.dataset.peLoginWasDisabled = button.disabled ? '1' : '0';
                button.disabled = true;
                button.setAttribute('aria-disabled', 'true');
            });
        }

        function hideLoadingScreen() {
            const loading = document.getElementById('loading-screen');
            const shell = document.getElementById('login-form-shell');

            if (loading) {
                loading.classList.remove('is-visible');
                loading.hidden = true;
                loading.setAttribute('aria-busy', 'false');
            }

            if (shell) {
                shell.classList.remove('is-hidden');
            }

            getSubmitButtons().forEach((button) => {
                if (button.dataset.peLoginWasDisabled !== '1') {
                    button.disabled = false;
                }

                button.removeAttribute('aria-disabled');
                delete button.dataset.peLoginWasDisabled;
            });
        }

        function bindLoginForm(form) {
            if (!form || form.dataset.peLoginBound === '1') {
                return;
            }

            form.dataset.peLoginBound = '1';

            form.addEventListener('submit', function () {
                if (!formHasRequiredValues(form)) {
                    return;
                }

                showLoadingScreen();
            });
        }

        function wrapLoginContent() {
            if (document.getElementById('login-form-shell')) {
                return;
            }

            const forms = getLoginForms();

            if (!forms.length) {
                return;
            }

            const shell = document.createElement('div');
            shell.id = 'login-form-shell';
            shell.className = 'pe-login-form-shell';

            const anchor = forms[0];
            anchor.parentNode.insertBefore(shell, anchor);
            forms.forEach((form) => shell.appendChild(form));
        }

        function initLoginLoading() {
            wrapLoginContent();
            getLoginForms().forEach(bindLoginForm);
        }

        document.addEventListener('DOMContentLoaded', initLoginLoading);
        document.addEventListener('livewire:init', function () {
            initLoginLoading();

            Livewire.hook('commit', ({ commit, fail }) => {
                const isAuthenticate = commit.calls.some((call) => call.method === 'authenticate');

                if (!isAuthenticate) {
                    return;
                }

                showLoadingScreen();

                fail(() => {
                    hideLoadingScreen();
                });
            });

            Livewire.hook('morph.updated', () => {
                initLoginLoading();
            });
        });
    })();
</script>
