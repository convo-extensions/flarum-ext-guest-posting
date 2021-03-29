import {extend} from 'flarum/common/extend';
import app from 'flarum/app';
import SignUpModal from 'flarum/forum/components/SignUpModal';

/* global m */

export default function () {
    extend(SignUpModal.prototype, 'oninit', function () {
        this.importGuestContent = !!app.forum.attribute('guestPostCount');
    });

    extend(SignUpModal.prototype, 'fields', function (items) {
        if (!app.forum.attribute('guestPostCount') && !app.forum.attribute('guestVoteCount')) {
            return;
        }

        items.add('guest-posting', m('.Form-group', m('div', m('label.checkbox', [
            m('input', {
                type: 'checkbox',
                checked: this.importGuestContent,
                onchange: () => {
                    this.importGuestContent = !this.importGuestContent;
                },
                disabled: this.loading,
            }),
            app.forum.attribute('guestVoteCount') ? app.translator.trans('guest-posting.forum.modal.import-votes', {
                postCount: app.forum.attribute('guestPostCount') || '0',
                voteCount: app.forum.attribute('guestVoteCount'),
            }) : app.translator.trans('guest-posting.forum.modal.import', {
                count: app.forum.attribute('guestPostCount'),
            }),
        ]))));
    });

    extend(SignUpModal.prototype, 'submitData', function (data) {
        if (this.importGuestContent) {
            data.importGuestContent = this.importGuestContent;
        }
    });
}
