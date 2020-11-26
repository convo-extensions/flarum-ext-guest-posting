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
        return m('.Alert.Alert-info', m('.container', [
            m('span.Alert-body', app.translator.trans('guest-posting.forum.alert.create-account', {
                count: app.forum.attribute('guestPostCount'),
            })),
            m('ul.Alert-controls', listItems([
                Button.component({
                    className: 'Button Button--link',
                    onclick: () => {
                        app.modal.show(new SignUpModal);
                    },
                }, app.translator.trans('guest-posting.forum.alert.signup')),
            ])),
        ]));
    }
}
