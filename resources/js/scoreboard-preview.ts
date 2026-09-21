import { ScoreboardContext } from './scoreboard-common';

export const initializePreview = (context: ScoreboardContext): void => {
    const panel = document.querySelector<HTMLElement>('#scoreboard-preview-panel');
    const previewUrl = context.app.dataset.previewUrl;

    if (!panel || !previewUrl) {
        return;
    }

    const refreshPreview = async (): Promise<void> => {
        try {
            const response = await fetch(previewUrl, {
                headers: {
                    Accept: 'text/html',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                cache: 'no-store',
            });

            if (!response.ok) {
                throw new Error(`Preview refresh failed with status ${response.status}`);
            }

            panel.innerHTML = await response.text();
        } catch (error) {
            console.error(error);
        }
    };

    context.app.addEventListener('scoreboard:updated', () => {
        void refreshPreview();
    });
};