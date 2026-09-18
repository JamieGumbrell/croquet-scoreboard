import $ from 'jquery';

const app = document.querySelector<HTMLElement>('#scoreboard-app');

if (app) {

    const tabs = document.createElement('div');
    tabs.className = 'scoreboard-nav md-container ';

    const showPanel = (panelId: string): void => {
        $('#scoreboard-details-panel, #scoreboard-scores-panel, #scoreboard-preview-panel')
            .hide();
        $(`#${panelId}`).show();
    };

    const buttons = [
        {
            label: 'Details',
            render: () => showPanel('scoreboard-details-panel'),
        },
        {
            label: 'Scores',
            render: () => showPanel('scoreboard-scores-panel'),
        },
        {
            label: 'Preview',
            render: () => showPanel('scoreboard-preview-panel'),
        },
    ];

    buttons.forEach((tab, index) => {
        const button = document.createElement('div');

        button.textContent = tab.label;
        button.setAttribute('aria-selected', String(index === 0));

        button.addEventListener('click', () => {
            buttons.forEach((_, buttonIndex) => {
                tabs.children[buttonIndex].setAttribute(
                    'aria-selected',
                    String(buttonIndex === index)
                );
            });

            tab.render?.();
        });

        tabs.append(button);
    });

    app.append(tabs);

    buttons[0].render?.();
}