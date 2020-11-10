import {extend} from 'flarum/extend';
import app from 'flarum/app';
import SignUpModal from 'flarum/components/SignUpModal';

/* global m */

export default function () {
    extend(SignUpModal.prototype, 'init', function () {
        this.importGuestContent = !!app.forum.attribute('guestPostCount');
    });

    extend(SignUpModal.prototype, 'fields', function (items) {
        if (!app.forum.attribute('guestPostCount')) {
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
            app.translator.trans('guest-posting.forum.modal.import', {
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
