import {override} from 'flarum/extend';
import app from 'flarum/app';
import IndexPage from 'flarum/components/IndexPage';
import DiscussionPage from 'flarum/components/DiscussionPage';
import CreateAccountAlert from './components/CreateAccountAlert';

/* global m */

// This single method will be used to inject the alert into existing components
// If the view is already an array, we add our content at the start
// If it isn't an array we wrap the content into a new array
// Copied from fof/terms
function addAlertToContent(original, ...originalArgs) {
    const existing = original(...originalArgs);

    if (!app.forum.attribute('guestPostCount')) {
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
