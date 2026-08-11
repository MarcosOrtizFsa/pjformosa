(() => {
    'use strict';

    const feed = document.querySelector('#news-feed');
    const status = document.querySelector('#news-status');
    const loadMore = document.querySelector('#load-more');
    const step = 3;
    let limit = 6;
    let loading = false;

    const createText = (tag, className, value) => {
        const element = document.createElement(tag);
        element.className = className;
        element.textContent = value;
        return element;
    };

    const createArticle = (item, index) => {
        const article = document.createElement('article');
        article.className = `news-card${item.image ? '' : ' news-card--no-image'}`;

        if (item.image) {
            const media = document.createElement('div');
            media.className = 'news-media';
            const image = document.createElement('img');
            image.src = item.image;
            image.alt = item.title ? `Imagen de la noticia: ${item.title}` : 'Imagen de la noticia';
            image.loading = index < 2 ? 'eager' : 'lazy';
            image.decoding = 'async';
            media.append(image);
            article.append(media);
        }

        const body = document.createElement('div');
        body.className = 'news-body';
        body.append(createText('span', 'news-category', item.category || 'PJ Formosa'));
        body.append(createText('h3', 'news-title', item.title || 'Novedad'));

        if (item.summary) {
            body.append(createText('p', 'news-summary', item.summary));
        }

        const copy = document.createElement('div');
        copy.className = 'news-copy';
        const paragraphs = (item.text || '').split(/\n+/).map((text) => text.trim()).filter(Boolean);
        paragraphs.forEach((paragraph) => copy.append(createText('p', '', paragraph)));
        body.append(copy);
        article.append(body);
        return article;
    };

    const render = (items) => {
        const fragment = document.createDocumentFragment();
        items.forEach((item, index) => fragment.append(createArticle(item, index)));
        feed.replaceChildren(fragment);
    };

    const fetchNews = async () => {
        if (loading) return;
        loading = true;
        loadMore.disabled = true;
        status.hidden = false;
        status.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Cargando noticias…';

        try {
            const response = await fetch(`php/noticias.php?limit=${limit}`, {
                headers: { Accept: 'application/json' },
                cache: 'no-store',
            });
            if (!response.ok) throw new Error('API unavailable');

            const payload = await response.json();
            const items = Array.isArray(payload.data) ? payload.data : [];
            render(items);
            status.hidden = true;
            loadMore.hidden = items.length < limit;
        } catch (error) {
            if (!feed.children.length) {
                status.textContent = 'No pudimos cargar las noticias en este momento. Por favor, intentá nuevamente.';
                status.classList.add('news-status--error');
            }
            loadMore.textContent = 'Reintentar';
            loadMore.hidden = false;
        } finally {
            loading = false;
            loadMore.disabled = false;
        }
    };

    loadMore.addEventListener('click', () => {
        status.classList.remove('news-status--error');
        loadMore.textContent = 'Cargar más noticias';
        limit += step;
        fetchNews();
    });

    fetchNews();
})();
