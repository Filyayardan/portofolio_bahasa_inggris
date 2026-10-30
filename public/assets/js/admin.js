(() => {
    const body = document.body;
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminOverlay');
    const menuToggle = document.getElementById('adminMenuToggle');

    const setMenu = (open) => {
        if (!sidebar || !overlay || !menuToggle) {
            return;
        }

        body.classList.toggle('admin-menu-open', open);
        overlay.hidden = !open;
        menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        menuToggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    };

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            setMenu(!body.classList.contains('admin-menu-open'));
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => setMenu(false));
    }

    if (sidebar) {
        sidebar.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 960) {
                    setMenu(false);
                }
            });
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setMenu(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 960) {
            setMenu(false);
        }
    });

    document.querySelectorAll('[data-dismiss-alert]').forEach((button) => {
        button.addEventListener('click', () => {
            const alert = button.closest('.admin-alert');
            if (alert) {
                alert.remove();
            }
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.getAttribute('data-confirm') || 'Lanjutkan tindakan ini?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    const taskForm = document.querySelector('[data-task-form]');

    if (taskForm) {
        const typeInputs = Array.from(taskForm.querySelectorAll('input[name="type"]'));
        const typePanels = Array.from(taskForm.querySelectorAll('[data-type-panel]'));

        const syncTypePanels = () => {
            const selected = typeInputs.find((input) => input.checked);
            const currentType = selected ? selected.value : 'video';

            typePanels.forEach((panel) => {
                panel.hidden = panel.getAttribute('data-type-panel') !== currentType;
            });
        };

        typeInputs.forEach((input) => {
            input.addEventListener('change', syncTypePanels);
        });

        syncTypePanels();
    }

    const rangeInput = document.querySelector('[data-range-for]');

    if (rangeInput) {
        const numberInput = document.getElementById(rangeInput.getAttribute('data-range-for'));
        const previewValue = document.querySelector('[data-skill-preview-value]');
        const previewBar = document.querySelector('[data-skill-preview-bar]');
        const previewName = document.querySelector('[data-skill-preview-name]');
        const previewCategory = document.querySelector('[data-skill-preview-category]');
        const nameInput = document.getElementById('name');
        const categoryInput = document.getElementById('category_id');

        const updatePreview = (value) => {
            const safeValue = Math.max(0, Math.min(100, Number(value) || 0));

            if (previewValue) {
                previewValue.textContent = `${safeValue}%`;
            }

            if (previewBar) {
                previewBar.style.width = `${safeValue}%`;
            }
        };

        rangeInput.addEventListener('input', () => {
            if (numberInput) {
                numberInput.value = rangeInput.value;
            }
            updatePreview(rangeInput.value);
        });

        if (numberInput) {
            numberInput.addEventListener('input', () => {
                const value = Math.max(0, Math.min(100, Number(numberInput.value) || 0));
                rangeInput.value = String(value);
                updatePreview(value);
            });
        }

        if (nameInput && previewName) {
            nameInput.addEventListener('input', () => {
                previewName.textContent = nameInput.value.trim() || 'Nama Keahlian';
            });
        }

        if (categoryInput && previewCategory) {
            categoryInput.addEventListener('input', () => {
                previewCategory.textContent = categoryInput.value.trim() || 'Kategori';
            });
        }
    }

    const formatSize = (bytes) => {
        if (!Number.isFinite(bytes) || bytes <= 0) {
            return '';
        }

        const units = ['B', 'KB', 'MB', 'GB'];
        const index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
        const value = bytes / Math.pow(1024, index);

        return `${value.toFixed(value >= 10 || index === 0 ? 0 : 1)} ${units[index]}`;
    };

    document.querySelectorAll('[data-file-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const field = input.closest('.admin-field');
            const panel = input.closest('[data-type-panel]');
            const nameTarget = field ? field.querySelector('[data-file-name]') : null;
            const panelTarget = panel ? panel.querySelector('[data-file-name]') : null;
            const target = nameTarget || panelTarget;

            if (!target) {
                return;
            }

            const file = input.files && input.files[0];

            if (!file) {
                target.textContent = '';
                return;
            }

            const size = formatSize(file.size);
            target.textContent = size ? `${file.name} · ${size}` : file.name;
        });
    });
})();
