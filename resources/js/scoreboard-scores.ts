import { ScoreboardContext, submitScoreboardForm } from './scoreboard-common';

export const initializeScoresTab = (context: ScoreboardContext): void => {
    const playersForm = document.querySelector<HTMLFormElement>('#scoreboard-players-form');
    const scoresForm = document.querySelector<HTMLFormElement>('#scoreboard-scores-form');

    if (!playersForm && !scoresForm) {
        return;
    }

    playersForm?.addEventListener('change', (event) => {
        const target = event.target;

        if (target instanceof HTMLSelectElement && target.dataset.countryTarget) {
            const countryField = playersForm.elements.namedItem(target.dataset.countryTarget);
            const selectedCountry = target.selectedOptions[0]?.dataset.country ?? '';

            if (countryField instanceof HTMLInputElement || countryField instanceof HTMLSelectElement) {
                countryField.value = selectedCountry;
            }
        }

        void submitScoreboardForm(context, playersForm);
    });

    document.querySelector<HTMLButtonElement>('#swap-players')?.addEventListener('click', () => {
        if (!playersForm) {
            return;
        }

        const name1 = playersForm.elements.namedItem('name1');
        const name2 = playersForm.elements.namedItem('name2');
        const country1 = playersForm.elements.namedItem('country1');
        const country2 = playersForm.elements.namedItem('country2');

        if (
            (!(name1 instanceof HTMLInputElement) && !(name1 instanceof HTMLSelectElement)) ||
            (!(name2 instanceof HTMLInputElement) && !(name2 instanceof HTMLSelectElement)) ||
            (!(country1 instanceof HTMLInputElement) && !(country1 instanceof HTMLSelectElement)) ||
            (!(country2 instanceof HTMLInputElement) && !(country2 instanceof HTMLSelectElement))
        ) {
            return;
        }

        [name1.value, name2.value] = [name2.value, name1.value];
        [country1.value, country2.value] = [country2.value, country1.value];
        void submitScoreboardForm(context, playersForm);
    });

    if (!scoresForm) {
        return;
    }

    const scoreLabels = Array.from(scoresForm.querySelectorAll<HTMLElement>('[id^="score-"]'));
    const gameLabels = Array.from(scoresForm.querySelectorAll<HTMLElement>('[id^="games-"]'));
    const trackedLabels = [...gameLabels, ...scoreLabels];

    trackedLabels.forEach((label) => {
        const fieldName = label.id.startsWith('games-')
            ? label.id.replace('games-', 'games')
            : label.id.replace('score-', 'score');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = fieldName;
        input.value = label.textContent?.trim() ?? '0';
        scoresForm.append(input);
    });

    const updateScoreLabels = (event: Event): void => {
        const scoreboard = (event as CustomEvent<Record<string, unknown>>).detail;

        trackedLabels.forEach((label) => {
            const fieldName = label.id.startsWith('games-')
                ? label.id.replace('games-', 'games')
                : label.id.replace('score-', 'score');
            const value = scoreboard[fieldName];

            if (value !== undefined) {
                label.textContent = String(value);
            }
        });
    };

    const hydrateScoreForm = (scoreboard: Record<string, unknown>): void => {
        trackedLabels.forEach((label) => {
            const fieldName = label.id.startsWith('games-')
                ? label.id.replace('games-', 'games')
                : label.id.replace('score-', 'score');
            const value = scoreboard[fieldName];

            if (value === undefined) {
                return;
            }

            label.textContent = String(value);
            const input = scoresForm.elements.namedItem(fieldName);

            if (input instanceof HTMLInputElement) {
                input.value = String(value);
            }
        });
    };

    context.app.addEventListener('scoreboard:updated', updateScoreLabels);
    hydrateScoreForm(context.scoreboard);

    let scoreUpdateQueue = Promise.resolve();
    let latestScoreUpdate = 0;

    const queueScoreUpdate = (): void => {
        const body = new FormData(scoresForm);
        const updateNumber = ++latestScoreUpdate;

        scoreUpdateQueue = scoreUpdateQueue.then(() => submitScoreboardForm(
            context,
            scoresForm,
            body,
            () => updateNumber === latestScoreUpdate,
        ));
    };

    scoresForm.querySelectorAll<HTMLButtonElement>('[data-score-action]').forEach((button) => {
        button.addEventListener('click', () => {
            const action = button.dataset.scoreAction;

            const container = button.closest('.games, .score');
            const labels = container?.classList.contains('games') ? gameLabels : scoreLabels;

            if (action === 'reset') {
                labels.forEach((label) => {
                    label.textContent = '0';
                });
            } else {
                const itemContainer = button.closest('[class^="games-"], [class^="score-"]');
                const label = itemContainer?.querySelector<HTMLElement>('.label');

                if (!label) {
                    return;
                }

                const currentScore = Number.parseInt(label.textContent ?? '0', 10) || 0;
                const change = action === 'increment' ? 1 : -1;
                label.textContent = String(Math.max(0, currentScore + change));
            }

            labels.forEach((label) => {
                const fieldName = label.id.startsWith('games-')
                    ? label.id.replace('games-', 'games')
                    : label.id.replace('score-', 'score');
                const input = scoresForm.elements.namedItem(fieldName);

                if (input instanceof HTMLInputElement) {
                    input.value = label.textContent?.trim() ?? '0';
                }
            });

            queueScoreUpdate();
        });
    });
};
