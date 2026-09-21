export type Scoreboard = Record<string, unknown>;

export interface ScoreboardContext {
    app: HTMLElement;
    channel: BroadcastChannel | null;
}

export const createScoreboardContext = (app: HTMLElement): ScoreboardContext => {
    const scoreboard = JSON.parse(app.dataset.scoreboard ?? '{}') as { id?: number };
    const channel = typeof BroadcastChannel === 'undefined'
        ? null
        : new BroadcastChannel(`scoreboard:${scoreboard.id ?? 'unknown'}`);

    const context: ScoreboardContext = { app, channel };

    channel?.addEventListener('message', (event: MessageEvent<Scoreboard>) => {
        if (event.data && typeof event.data === 'object') {
            applyScoreboard(context, event.data);
        }
    });

    return context;
};

export const applyScoreboard = (context: ScoreboardContext, scoreboard: Scoreboard): void => {
    context.app.dataset.scoreboard = JSON.stringify(scoreboard);

    document.querySelectorAll<HTMLFormElement>('form').forEach((form) => {
        Object.entries(scoreboard).forEach(([name, value]) => {
            const field = form.elements.namedItem(name);

            if (
                field instanceof HTMLInputElement ||
                field instanceof HTMLSelectElement ||
                field instanceof HTMLTextAreaElement
            ) {
                field.value = String(value ?? '');
            }
        });
    });

    context.app.dispatchEvent(new CustomEvent('scoreboard:updated', {
        detail: scoreboard,
    }));
};

export const submitScoreboardForm = async (
    context: ScoreboardContext,
    form: HTMLFormElement,
    body: FormData = new FormData(form),
    shouldApplyResponse: () => boolean = () => true,
): Promise<void> => {
    form.setAttribute('aria-busy', 'true');

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body,
        });

        if (!response.ok) {
            throw new Error(`Scoreboard update failed with status ${response.status}`);
        }

        const scoreboard = await response.json() as Scoreboard;

        if (shouldApplyResponse()) {
            applyScoreboard(context, scoreboard);
            context.channel?.postMessage(scoreboard);
        }
    } catch (error) {
        console.error(error);
    } finally {
        form.removeAttribute('aria-busy');
    }
};
