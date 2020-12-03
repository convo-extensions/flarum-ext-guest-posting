import app from 'flarum/app';
import Component from 'flarum/Component';
import Button from 'flarum/components/Button';
import SignUpModal from 'flarum/components/SignUpModal';
import listItems from 'flarum/helpers/listItems';

/* global m */

/**
 * Renders similarly to Flarum's Alert, but with an additional .container inside
 */
export default class CreateAccountAlert extends Component {
    view() {
        let text = '';

        if (app.forum.attribute('guestPostCount') && app.forum.attribute('guestVoteCount')) {
            text = app.translator.trans('guest-posting.forum.alert.create-account-posts-and-votes', {
                postCount: app.forum.attribute('guestPostCount'),
                voteCount: app.forum.attribute('guestVoteCount'),
            });
        } else if (app.forum.attribute('guestVoteCount')) {
            text = app.translator.trans('guest-posting.forum.alert.create-account-votes-only', {
                count: app.forum.attribute('guestVoteCount'),
            });
        } else {
            text = app.translator.trans('guest-posting.forum.alert.create-account', {
                count: app.forum.attribute('guestPostCount'),
            });
        }

        return m('.Alert.Alert-info', m('.container', [
            m('span.Alert-body', text),
            m('ul.Alert-controls', listItems([
                Button.component({
                    className: 'Button Button--link',
                    onclick: () => {
                        app.modal.show(SignUpModal);
                    },
                }, app.translator.trans('guest-posting.forum.alert.signup')),
            ])),
        ]));
    }
}
