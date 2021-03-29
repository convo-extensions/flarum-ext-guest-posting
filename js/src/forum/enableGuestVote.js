import {extend, override} from 'flarum/common/extend';
import app from 'flarum/app';

/* global flarum */

export default function () {
    if (
        !flarum.extensions['fof-polls'] ||
        !flarum.extensions['fof-polls'].components ||
        !flarum.extensions['fof-polls'].components.DiscussionPoll
    ) {
        return;
    }

    /**
     * Override changeVote to allow guest to vote instead of a login modal opening
     */
    override(flarum.extensions['fof-polls'].components.DiscussionPoll.prototype, 'changeVote', function (original, ...args) {
        if (!app.session.user && this.poll.attribute('canGuestVote')) {
            app.session.user = true; // A truthy value to make the test pass in the original method
            original(...args);
            app.session.user = null; // Revert to null
        } else {
            original(...args);
        }
    });

    extend(flarum.extensions['fof-polls'].components.DiscussionPoll.prototype, 'updateData', function () {
        if (app.session.user) {
            return;
        }

        this.vote(this.votes.find(v => v.user() === false && parseInt(v.optionId()) === parseInt(this.poll.attribute('guestVoteOption'))));

        this.voted(!!this.vote());
    });
}
