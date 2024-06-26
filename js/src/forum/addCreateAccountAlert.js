import {override} from 'flarum/common/extend';
import app from 'flarum/forum/app';
import IndexPage from 'flarum/forum/components/IndexPage';
import DiscussionPage from 'flarum/forum/components/DiscussionPage';
import CreateAccountAlert from './components/CreateAccountAlert';

/* global m */

// This single method will be used to inject the alert into existing components
// If the view is already an array, we add our content at the start
// If it isn't an array we wrap the content into a new array
// Copied from fof/terms
function addAlertToContent(original, ...originalArgs) {
    const existing = original(...originalArgs);

    if (!app.forum.attribute('guestPostCount') && !app.forum.attribute('guestVoteCount')) {
        return existing;
    }

    const additional = CreateAccountAlert.component();

    if (Array.isArray(existing)) {
        existing.unshift(additional);

        return existing;
    }

    return m('div', [
        additional,
        existing,
    ]);
}

export default function () {
    // We inject the banner on a few key pages
    override(IndexPage.prototype, 'hero', addAlertToContent);
    override(DiscussionPage.prototype, 'view', addAlertToContent);
}
