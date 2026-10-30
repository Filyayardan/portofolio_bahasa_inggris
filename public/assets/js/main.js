(() => {
    const root = document.documentElement;
    const body = document.body;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const finishIntro = () => {
        if (!body.classList.contains('is-loaded')) {
            body.classList.add('is-loaded');
        }
    };

    window.addEventListener('load', () => window.setTimeout(finishIntro, 300), { once: true });
    window.setTimeout(finishIntro, 2200);

    /* ----------------------------------------------------------------
       Custom cursor
       ---------------------------------------------------------------- */

    const finePointer = window.matchMedia('(pointer: fine)').matches;

    if (finePointer && !reduceMotion) {
        root.classList.add('has-custom-cursor');

        const dot = document.getElementById('cursorDot');
        const ring = document.getElementById('cursorRing');
        let mouseX = -100;
        let mouseY = -100;
        let ringX = -100;
        let ringY = -100;

        const renderCursor = () => {
            if (!dot || !ring) return;

            ringX += (mouseX - ringX) * 0.16;
            ringY += (mouseY - ringY) * 0.16;

            dot.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0) translate(-50%, -50%)`;
            ring.style.transform = `translate3d(${ringX}px, ${ringY}px, 0) translate(-50%, -50%)`;

            window.requestAnimationFrame(renderCursor);
        };

        window.addEventListener('mousemove', (event) => {
            mouseX = event.clientX;
            mouseY = event.clientY;
        }, { passive: true });

        document.addEventListener('mouseover', (event) => {
            if (event.target.closest('a, button, input, textarea, .task-card, .filter')) {
                ring?.classList.add('is-hovering');
            }
        });

        document.addEventListener('mouseout', (event) => {
            if (event.target.closest('a, button, input, textarea, .task-card, .filter')) {
                ring?.classList.remove('is-hovering');
            }
        });

        document.addEventListener('mouseleave', () => {
            if (dot) dot.style.opacity = '0';
            if (ring) ring.style.opacity = '0';
        });

        document.addEventListener('mouseenter', () => {
            if (dot) dot.style.opacity = '1';
            if (ring) ring.style.opacity = '1';
        });

        window.requestAnimationFrame(renderCursor);
    }

    /* ----------------------------------------------------------------
       Header, progress bar, reveal, counters
       ---------------------------------------------------------------- */

    const header = document.getElementById('siteHeader');
    const progress = document.getElementById('scrollProgress');

    const updateScroll = () => {
        const scrollTop = window.scrollY || root.scrollTop;
        const maxScroll = Math.max(1, root.scrollHeight - window.innerHeight);
        const percentage = Math.min(100, (scrollTop / maxScroll) * 100);

        header?.classList.toggle('is-scrolled', scrollTop > 36);

        if (progress) {
            progress.style.width = `${percentage}%`;
        }
    };

    window.addEventListener('scroll', updateScroll, { passive: true });
    updateScroll();

    const revealElements = document.querySelectorAll('.reveal');

    if (revealElements.length > 0) {
        if (reduceMotion || !('IntersectionObserver' in window)) {
            revealElements.forEach((element) => element.classList.add('is-visible'));
        } else {
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -6% 0px',
            });

            revealElements.forEach((element) => revealObserver.observe(element));
        }
    }

    const counters = document.querySelectorAll('[data-counter]');

    const animateCounter = (element) => {
        const target = Number.parseInt(element.dataset.counter || '0', 10);
        const duration = 1300;
        const start = performance.now();

        const tick = (now) => {
            const progressValue = Math.min(1, (now - start) / duration);
            const eased = 1 - Math.pow(1 - progressValue, 3);
            element.textContent = Math.round(target * eased).toString();

            if (progressValue < 1) {
                window.requestAnimationFrame(tick);
            }
        };

        window.requestAnimationFrame(tick);
    };

    if (counters.length > 0) {
        if (reduceMotion || !('IntersectionObserver' in window)) {
            counters.forEach((counter) => {
                counter.textContent = counter.dataset.counter || '0';
            });
        } else {
            const counterObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.6 });

            counters.forEach((counter) => counterObserver.observe(counter));
        }
    }

    /* ----------------------------------------------------------------
       Mobile menu
       ---------------------------------------------------------------- */

    const menu = document.getElementById('mobileMenu');
    const menuToggle = document.getElementById('menuToggle');
    const menuClose = document.getElementById('menuClose');

    const setMenu = (open) => {
        if (!menu || !menuToggle) return;

        menu.classList.toggle('is-open', open);
        menu.setAttribute('aria-hidden', open ? 'false' : 'true');
        menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        body.classList.toggle('menu-open', open);
    };

    menuToggle?.addEventListener('click', () => setMenu(true));
    menuClose?.addEventListener('click', () => setMenu(false));

    menu?.querySelectorAll('a, button').forEach((element) => {
        element.addEventListener('click', () => setMenu(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setMenu(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1020) {
            setMenu(false);
        }
    });

    /* ----------------------------------------------------------------
       Task filters
       ---------------------------------------------------------------- */

    const filterButtons = document.querySelectorAll('[data-filter]');
    const taskCards = Array.from(document.querySelectorAll('.task-card[data-type]'));
    const taskCount = document.querySelector('[data-task-count]');
    const emptyState = document.getElementById('tasksEmpty');

    const applyFilter = (filter) => {
        let visible = 0;

        taskCards.forEach((card) => {
            const matches = filter === 'all' || card.dataset.type === filter;
            card.hidden = !matches;

            if (matches) {
                visible += 1;
                card.classList.add('is-visible');
            }
        });

        filterButtons.forEach((button) => {
            const active = button.dataset.filter === filter;
            button.classList.toggle('is-active', active);
            button.setAttribute('aria-pressed', active ? 'true' : 'false');
        });

        if (taskCount) {
            taskCount.textContent = visible.toString();
        }

        if (emptyState) {
            emptyState.hidden = visible !== 0;
        }
    };

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => applyFilter(button.dataset.filter || 'all'));
    });

    /* ----------------------------------------------------------------
       Task modal
       ---------------------------------------------------------------- */

    const modal = document.getElementById('taskModal');
    const modalMedia = document.getElementById('modalMedia');
    const modalMeta = document.getElementById('modalMeta');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalExtra = document.getElementById('modalExtra');

    const icons = {
        close: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>',
        file: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h5"/></svg>',
        pen: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>',
        film: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="1"/><path d="M7 4v16M17 4v16M3 9h4M17 9h4M3 15h4M17 15h4"/></svg>',
        download: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M7 10l5 5 5-5M4 21h16"/></svg>',
        external: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>',
        spark: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"><path d="M12 3l2.1 6.4L20 12l-5.9 2.6L12 21l-2.1-6.4L4 12l5.9-2.6z"/></svg>',
    };

    const safeUrl = (value, fallback = '#') => {
        if (typeof value !== 'string' || value.trim() === '') return fallback;

        const trimmed = value.trim();

        if (/^(https?:\/\/|\/|\.\/|uploads\/|assets\/|#|mailto:|tel:)/i.test(trimmed)) {
            return trimmed;
        }

        return fallback;
    };

    const makeElement = (tag, className, text) => {
        const element = document.createElement(tag);

        if (className) element.className = className;
        if (typeof text === 'string') element.textContent = text;

        return element;
    };

    const buildPoster = (payload, iconName) => {
        const poster = makeElement('div', 'modal-poster');
        const thumbnail = safeUrl(payload.thumbnail, '');

        if (thumbnail) {
            const image = document.createElement('img');
            image.src = thumbnail;
            image.alt = '';
            poster.appendChild(image);
        }

        const iconBox = makeElement('div', 'modal-poster__icon');
        iconBox.innerHTML = icons[iconName] || icons.spark;
        poster.appendChild(iconBox);

        return poster;
    };

    const buildFilePanel = (payload) => {
        if (!payload.file) {
            return null;
        }

        const fileUrl = safeUrl(payload.file.url);

        if (fileUrl === '#') {
            return null;
        }

        const panel = makeElement('div', 'modal-file');
        const info = makeElement('div', 'modal-file__info');
        const iconBox = makeElement('div', 'modal-file__icon');
        iconBox.innerHTML = icons.file;

        const text = makeElement('div');
        text.appendChild(makeElement('strong', '', payload.file.name || 'File'));
        text.appendChild(makeElement(
            'small',
            '',
            [payload.file.extension, payload.file.size].filter(Boolean).join(' · ') || payload.labels.file_info
        ));

        info.append(iconBox, text);

        const actions = makeElement('div', 'modal-file__actions');
        const openLink = makeElement('a', 'button button--ghost', payload.labels.open_file);
        openLink.href = fileUrl;
        openLink.target = '_blank';
        openLink.rel = 'noopener noreferrer';
        openLink.insertAdjacentHTML('beforeend', icons.external);

        const downloadLink = makeElement('a', 'button button--gold', payload.labels.download);
        downloadLink.href = fileUrl;
        downloadLink.setAttribute('download', payload.file.name || '');
        downloadLink.insertAdjacentHTML('beforeend', icons.download);

        actions.append(openLink, downloadLink);
        panel.append(info, actions);

        return panel;
    };

    const resetModal = () => {
        if (modalMedia) {
            modalMedia.innerHTML = '';
            modalMedia.style.minHeight = '';
        }
        if (modalExtra) modalExtra.innerHTML = '';
    };

    const closeModal = () => {
        if (!modal?.open) return;
        modal.close();
    };

    const openModal = (payload) => {
        if (!modal || !modalMedia || !modalExtra || !modalTitle) return;

        resetModal();
        modal.dataset.type = payload.type;
        modalTitle.textContent = payload.title;
        modalDescription.textContent = payload.description || '';

        if (modalMeta) {
            modalMeta.innerHTML = '';

            [payload.labels.type, payload.category, payload.date]
                .filter(Boolean)
                .forEach((item) => {
                    const span = makeElement('span', '', item);
                    modalMeta.appendChild(span);
                });
        }

        let hasMedia = true;

        if (payload.type === 'video') {
            if (payload.video?.kind === 'embed') {
                const iframe = document.createElement('iframe');
                iframe.src = safeUrl(payload.video.url, '');
                iframe.title = payload.title;
                iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
                iframe.allowFullscreen = true;
                modalMedia.appendChild(iframe);
            } else if (payload.video?.kind === 'file') {
                const video = document.createElement('video');
                video.controls = true;
                video.autoplay = true;
                video.playsInline = true;
                video.src = safeUrl(payload.video.url, '');
                if (payload.thumbnail) video.poster = safeUrl(payload.thumbnail, '');
                modalMedia.appendChild(video);
            } else if (payload.video?.kind === 'link') {
                const link = makeElement('a', 'button button--gold', payload.labels.open_file);
                link.href = safeUrl(payload.video.url);
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                link.insertAdjacentHTML('beforeend', icons.external);
                const noMedia = makeElement('div', 'modal-no-media');
                noMedia.append(buildPoster(payload, 'film'), link);
                modalMedia.appendChild(noMedia);
            } else {
                hasMedia = false;
            }
        } else if (payload.type === 'document') {
            if (payload.file) {
                const extension = (payload.file.extension || '').toUpperCase();

                if (extension === 'PDF') {
                    const frame = document.createElement('iframe');
                    frame.src = `${safeUrl(payload.file.url)}#toolbar=0&navpanes=0&view=FitH`;
                    frame.title = payload.file.name || payload.title;
                    frame.loading = 'lazy';
                    frame.style.height = 'clamp(300px, 52vh, 620px)';
                    frame.style.minHeight = '0';
                    modalMedia.appendChild(frame);
                } else {
                    modalMedia.appendChild(buildPoster(payload, 'file'));
                }
            } else {
                hasMedia = false;
            }

            const filePanel = buildFilePanel(payload);

            if (filePanel) {
                modalExtra.appendChild(filePanel);
            } else {
                const empty = makeElement('div', 'modal-no-media');
                empty.innerHTML = icons.file;
                empty.appendChild(makeElement('p', '', payload.labels.no_file));
                modalExtra.appendChild(empty);
            }
        } else {
            modalMedia.appendChild(buildPoster(payload, 'pen'));
            modalMedia.style.minHeight = '240px';

            const content = (payload.content || '').trim();

            if (content) {
                const article = makeElement('div', 'modal-text');

                content.split(/\n+/).filter((paragraph) => paragraph.trim() !== '').forEach((paragraph) => {
                    article.appendChild(makeElement('p', '', paragraph.trim()));
                });

                modalExtra.appendChild(article);
            }
        }

        if (!hasMedia) {
            const empty = makeElement('div', 'modal-no-media');
            empty.innerHTML = icons.film;
            empty.appendChild(makeElement('p', '', payload.labels.no_media));
            modalMedia.appendChild(empty);
        }

        body.classList.add('modal-open');
        modal.showModal();
        modal.querySelector('.task-modal__panel')?.scrollTo({ top: 0, behavior: 'auto' });
    };

    document.querySelectorAll('[data-task]').forEach((button) => {
        button.addEventListener('click', () => {
            try {
                const payload = JSON.parse(button.dataset.task || '{}');
                openModal(payload);
            } catch (error) {
                console.error('Unable to open task', error);
            }
        });
    });

    modal?.addEventListener('click', (event) => {
        if (event.target === modal || event.target.closest('[data-close-modal]')) {
            closeModal();
        }
    });

    modal?.addEventListener('close', () => {
        body.classList.remove('modal-open');
        resetModal();
    });

    modal?.addEventListener('cancel', () => {
        body.classList.remove('modal-open');
    });

    /* ----------------------------------------------------------------
       Toasts + local clock
       ---------------------------------------------------------------- */

    document.querySelectorAll('.toast').forEach((toast) => {
        const dismiss = () => {
            toast.classList.add('is-leaving');
            window.setTimeout(() => toast.remove(), 380);
        };

        toast.querySelector('[data-dismiss-toast]')?.addEventListener('click', dismiss);
        window.setTimeout(dismiss, 6500);
    });

    const clock = document.querySelector('[data-clock]');

    if (clock) {
        const formatter = new Intl.DateTimeFormat(root.lang === 'en' ? 'en-GB' : 'id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        });

        const updateClock = () => {
            clock.textContent = formatter.format(new Date());
        };

        updateClock();
        window.setInterval(updateClock, 1000);
    }
})();
