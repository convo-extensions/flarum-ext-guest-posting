import {extend, override} from 'flarum/extend';
import app from 'flarum/app';
import Badge from 'flarum/components/Badge';
import CommentPost from 'flarum/components/CommentPost';
import IndexPage from 'flarum/components/IndexPage';
import DiscussionComposer from 'flarum/components/DiscussionComposer';
import ReplyComposer from 'flarum/components/ReplyComposer';
import DiscussionControls from 'flarum/utils/DiscussionControls';

/* global m */

export default function () {
    override(DiscussionControls, 'replyAction', function (original, goToLast, forceRefresh) {
        if (!app.session.user && this.canReply()) {
            // Code copied from DiscussionControls.replyAction
            let component = app.composer.component;
            if (!app.composingReplyTo(this) || forceRefresh) {
                component = new ReplyComposer({
                    user: app.session.user,
                    discussion: this,
                });
                app.composer.load(component);
            }
            app.composer.show();

            if (goToLast && app.viewingDiscussion(this) && !app.composer.isFullScreen()) {
                app.current.stream.goToNumber('reply');
            }

            const deferred = m.deferred();
            deferred.resolve(component);
            return deferred.promise;
        }

        return original(goToLast, forceRefresh);
    });

    override(IndexPage.prototype, 'newDiscussionAction', function (original) {
        if (!app.session.user && app.forum.attribute('canStartDiscussion')) {
            // Code copied from IndexPage.newDiscussionAction
            const component = new DiscussionComposer({user: app.session.user});

            app.composer.load(component);
            app.composer.show();

            const deferred = m.deferred();
            deferred.resolve(component);
            return deferred.promise;
        }

        return original();
    });

    extend(CommentPost.prototype, 'headerItems', function (items) {
        const guestUsername = this.props.post.attribute('guest_username');

        if (!guestUsername) {
            return;
        }

        if (items.has('user')) {
            items.remove('user');
        }

        items.add('guest-user', m('.PostUser', [
            m('h3', [
                m('span.Avatar.PostUser-avatar', guestUsername.charAt(0).toUpperCase()),
                m('span.username', guestUsername),
            ]),
            m('ul.PostUser-badges badges', m('li', Badge.component({
                type: 'guest',
                icon: 'fas fa-user-secret',
                label: app.translator.trans('guest-posting.forum.badge.guest'),
            }))),
        ]), 100);
    });
}
