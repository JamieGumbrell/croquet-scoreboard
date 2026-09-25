import { ScoreboardContext } from './scoreboard-common';

export const initializePreview = (context: ScoreboardContext): void => {
    const previewUrl = context.app.dataset.previewUrl;
    const previews = (): NodeListOf<HTMLElement> => document.querySelectorAll<HTMLElement>(
        '[data-scoreboard-preview]',
    );

    if (!previewUrl || previews().length === 0) {
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

            const previewDocument = new DOMParser().parseFromString(
                await response.text(),
                'text/html',
            );
            const updatedPreview = previewDocument.querySelector<HTMLElement>(
                '[data-scoreboard-preview]',
            );

            if (!updatedPreview) {
                return;
            }

            previews().forEach((preview) => {
                preview.innerHTML = updatedPreview.innerHTML;
            });
        } catch (error) {
            console.error(error);
        }
    };

    context.app.addEventListener('scoreboard:updated', () => {
        void refreshPreview();
    });
};
