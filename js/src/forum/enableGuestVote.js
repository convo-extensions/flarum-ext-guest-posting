import {extend, override} from 'flarum/common/extend';
import app from 'flarum/forum/app';

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

        const optionId = this.poll.attribute('guestVoteOption');

        // Instead of poll.myVotes(), we will simulate votes from the guestVoteOption attribute
        // This is easier than trying to gake the relationship in the backend
        this.myVotes = optionId ? [app.store.createRecord('poll_votes', {
            id: '0', // Not actually used, this model is never used from the store
            type: 'poll_votes',
            relationships: {
                option: {
                    data: {
                        id: optionId,
                        type: 'poll_options',
                    },
                },
            },
        })] : [];
    });
}
