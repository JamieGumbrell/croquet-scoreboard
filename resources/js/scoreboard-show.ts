import { createScoreboardContext } from './scoreboard-common';
import { initializeScoresTab } from './scoreboard-scores';
import { initializeTabs } from './scoreboard-tabs';

const app = document.querySelector<HTMLElement>('#scoreboard-app');

if (app) {
    const scoreboardContext = createScoreboardContext(app);

    initializeScoresTab(scoreboardContext);
    initializeTabs(app);
}
