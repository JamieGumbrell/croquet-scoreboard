import $ from 'jquery';

type TabId = 'details' | 'scores' | 'preview';

const tabPanels: Record<TabId, string> = {
    details: 'scoreboard-details-panel',
    scores: 'scoreboard-scores-panel',
    preview: 'scoreboard-preview-panel',
};

const getTabFromUrl = (): TabId => {
    const tab = new URLSearchParams(window.location.search).get('tab');

    return tab && tab in tabPanels ? tab as TabId : 'details';
};

const updateTabUrl = (tabId: TabId, replace = false): void => {
    const url = new URL(window.location.href);
    url.searchParams.set('tab', tabId);

    if (replace) {
        window.history.replaceState({}, '', url);
    } else {
        window.history.pushState({}, '', url);
    }
};

export const initializeTabs = (app: HTMLElement): void => {
    const tabs = document.createElement('div');
    tabs.className = 'scoreboard-nav md-container ';

    const showPanel = (panelId: string): void => {
        $('#scoreboard-details-panel, #scoreboard-scores-panel, #scoreboard-preview-panel')
            .hide();
        $(`#${panelId}`).show();
    };

    const buttons: Array<{ id: TabId; label: string }> = [
        { id: 'details', label: 'Details' },
        { id: 'scores', label: 'Scores' },
        { id: 'preview', label: 'Preview' },
    ];

    const setActiveTab = (tabId: TabId, updateUrl = true): void => {
        const activeIndex = buttons.findIndex((tab) => tab.id === tabId);

        if (activeIndex === -1) {
            return;
        }

        buttons.forEach((_, buttonIndex) => {
            tabs.children[buttonIndex].setAttribute(
                'aria-selected',
                String(buttonIndex === activeIndex),
            );
        });

        showPanel(tabPanels[tabId]);

        if (updateUrl) {
            updateTabUrl(tabId);
        }
    };

    buttons.forEach((tab) => {
        const button = document.createElement('div');

        button.textContent = tab.label;
        button.setAttribute('aria-selected', 'false');
        button.addEventListener('click', () => setActiveTab(tab.id));
        tabs.append(button);
    });

    app.append(tabs);

    const initialTab = getTabFromUrl();
    setActiveTab(initialTab, false);
    updateTabUrl(initialTab, true);

    window.addEventListener('popstate', () => {
        setActiveTab(getTabFromUrl(), false);
    });
};
